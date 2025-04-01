@extends('layout.header')
@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-primary-light dark:bg-primary-dark text-white px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold">Document Verification Results</h2>
                    <a href="{{ url()->previous() }}" class="flex items-center text-white hover:text-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                clip-rule="evenodd" />
                        </svg>
                        Back
                    </a>
                </div>
            </div>

            <div id="loading" class="py-12 flex flex-col items-center justify-center hidden">
                <div
                    class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary-light dark:border-primary-dark">
                </div>
                <p class="mt-4 text-gray-600 dark:text-gray-300">Processing documents, please wait...</p>
            </div>

            @if (empty($results))
                <div class="p-6 text-center">
                    <p class="text-gray-600 dark:text-gray-300">No verification results available.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white dark:bg-gray-800">
                        <thead>
                            <tr
                                class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs leading-normal">
                                <th class="py-3 px-6 text-left">CIN</th>
                                <th class="py-3 px-6 text-left">Student Name</th>
                                <th class="py-3 px-6 text-center">Status</th>
                                <th class="py-3 px-6 text-left">Verified Name</th>
                                <th class="py-3 px-6 text-left">Details</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 dark:text-gray-200">
                            @foreach ($results as $result)
                                @php
                                    $student = $students->where('cin', $result['cin'])->first();
                                    $studentName = $student ? $student->s_fname . ' ' . $student->s_lname : 'Unknown';
                                    $statusClass = $result['is_correct']
                                        ? 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100'
                                        : 'bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-100';
                                    $statusText = $result['is_correct'] ? '✅ Correct' : '❌ Incorrect';
                                @endphp
                                <tr
                                    class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="py-3 px-6 text-left">{{ $result['cin'] }}</td>
                                    <td class="py-3 px-6 text-left">{{ $studentName }}</td>
                                    <td class="py-3 px-6 text-center">
                                        <span class="py-1 px-3 rounded-full text-xs {{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 text-left">{{ $result['verified_name'] ?? 'N/A' }}</td>
                                    <td class="py-3 px-6 text-left">
                                        @if (!empty($result['file_details']))
                                            <div class="text-sm">
                                                <button onclick="toggleDetails('files-{{ $result['cin'] }}')"
                                                    class="text-primary-light dark:text-primary-dark hover:underline mb-1">
                                                    Show file details
                                                </button>
                                                <ul id="files-{{ $result['cin'] }}" class="hidden mt-2 space-y-1">
                                                    @foreach ($result['file_details'] as $detail)
                                                        <li class="pl-2 border-l-2 border-gray-300 dark:border-gray-600">
                                                            <strong>{{ $detail['file'] }}</strong>:
                                                            {{ $detail['extracted_name'] ?? 'No name extracted' }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        @if (!empty($result['errors']))
                                            <div class="text-sm mt-2">
                                                <button onclick="toggleDetails('errors-{{ $result['cin'] }}')"
                                                    class="text-red-600 dark:text-red-400 hover:underline">
                                                    Show errors
                                                </button>
                                                <ul id="errors-{{ $result['cin'] }}" class="hidden mt-2 space-y-1">
                                                    @foreach ($result['errors'] as $error)
                                                        <li
                                                            class="pl-2 border-l-2 border-red-300 dark:border-red-600 text-red-600 dark:text-red-400">
                                                            <strong>{{ $error['file'] }}</strong>: {{ $error['error'] }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <script>
        function toggleDetails(id) {
            const element = document.getElementById(id);
            if (element.classList.contains('hidden')) {
                element.classList.remove('hidden');
            } else {
                element.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // This script would handle loading state if needed
        });
    </script>
@endsection
