@extends('layout.header')
@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mb-6">
                <div class="bg-primary-500 dark:bg-primary-700 text-white px-6 py-4 flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold">Validation Details</h1>
                        <p class="text-sm mt-1 text-primary-100">{{ $filierName }} / {{ $className }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if ($validations->count() > 0)
                            <button onclick="confirmDeleteAll('{{ $filierName }}', '{{ $className }}')"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md text-sm flex items-center transition-colors">
                                <i class="fas fa-trash-alt mr-2"></i> Delete All
                            </button>
                        @endif
                        <a href="{{ route('validation.history') }}"
                            class="text-white hover:text-gray-200 flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i> Back to History
                        </a>
                    </div>
                </div>

                <!-- Update info -->
                <div class="px-6 py-3 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 text-sm">
                    <i class="fas fa-info-circle mr-2"></i>
                    <span>When validating a class again, existing records will be updated instead of creating
                        duplicates.</span>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Validations -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                            <i class="fas fa-file-alt text-blue-500 dark:text-blue-300 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Validations</h3>
                            <p class="text-2xl font-semibold text-gray-800 dark:text-white">{{ $validations->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Correct Documents -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                            <i class="fas fa-check-circle text-green-500 dark:text-green-300 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Correct Documents</h3>
                            <p class="text-2xl font-semibold text-gray-800 dark:text-white">
                                {{ $validations->where('is_correct', true)->count() }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Incorrect Documents -->
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full">
                            <i class="fas fa-times-circle text-red-500 dark:text-red-300 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Incorrect Documents</h3>
                            <p class="text-2xl font-semibold text-gray-800 dark:text-white">
                                {{ $validations->where('is_correct', false)->count() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Validation Results Table -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-white">Validation Results</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white dark:bg-gray-800">
                        <thead>
                            <tr
                                class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs leading-normal">
                                <th class="py-3 px-6 text-left">CIN</th>
                                <th class="py-3 px-6 text-left">Student Name</th>
                                <th class="py-3 px-6 text-center">Status</th>
                                <th class="py-3 px-6 text-left">Verified Name</th>
                                <th class="py-3 px-6 text-center">Date</th>
                                <th class="py-3 px-6 text-left">Details</th>
                                <th class="py-3 px-6 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 dark:text-gray-200">
                            @foreach ($validations as $validation)
                                @php
                                    $statusClass = $validation->is_correct
                                        ? 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100'
                                        : 'bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-100';
                                    $statusText = $validation->is_correct ? '✅ Correct' : '❌ Incorrect';
                                @endphp
                                <tr
                                    class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="py-3 px-6 text-left">{{ $validation->cin }}</td>
                                    <td class="py-3 px-6 text-left">{{ $validation->student_name }}</td>
                                    <td class="py-3 px-6 text-center">
                                        <span class="py-1 px-3 rounded-full text-xs {{ $statusClass }}">
                                            {{ $statusText }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 text-left">{{ $validation->verified_name ?? 'N/A' }}</td>
                                    <td class="py-3 px-6 text-center">{{ $validation->created_at->format('M d, Y - H:i') }}
                                    </td>
                                    <td class="py-3 px-6 text-left">
                                        @if (!empty($validation->file_details))
                                            <div class="text-sm">
                                                <button onclick="toggleDetails('files-{{ $validation->id }}')"
                                                    class="text-primary-500 dark:text-primary-400 hover:underline mb-1">
                                                    Show file details
                                                </button>
                                                <ul id="files-{{ $validation->id }}" class="hidden mt-2 space-y-1">
                                                    @foreach ($validation->file_details as $detail)
                                                        <li class="pl-2 border-l-2 border-gray-300 dark:border-gray-600">
                                                            <strong>{{ $detail['file'] }}</strong>:
                                                            {{ $detail['extracted_name'] ?? 'No name extracted' }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        @if (!empty($validation->errors))
                                            <div class="text-sm mt-2">
                                                <button onclick="toggleDetails('errors-{{ $validation->id }}')"
                                                    class="text-red-600 dark:text-red-400 hover:underline">
                                                    Show errors
                                                </button>
                                                <ul id="errors-{{ $validation->id }}" class="hidden mt-2 space-y-1">
                                                    @foreach ($validation->errors as $error)
                                                        <li
                                                            class="pl-2 border-l-2 border-red-300 dark:border-red-600 text-red-600 dark:text-red-400">
                                                            <strong>{{ $error['file'] }}</strong>: {{ $error['error'] }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <button
                                            onclick="confirmDelete({{ $validation->id }}, '{{ $validation->student_name }}')"
                                            class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs transition-colors">
                                            <i class="fas fa-trash mr-1"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md mx-auto p-6">
            <div class="mb-4 text-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-5xl mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Confirm Deletion</h3>
                <p id="deleteMessage" class="text-gray-600 dark:text-gray-300"></p>
            </div>
            <div class="flex justify-center space-x-4">
                <button id="cancelDelete"
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400 transition-colors">
                    Cancel
                </button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                        Delete
                    </button>
                </form>
            </div>
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

        // Delete confirmation functions
        const deleteModal = document.getElementById('deleteModal');
        const deleteMessage = document.getElementById('deleteMessage');
        const deleteForm = document.getElementById('deleteForm');
        const cancelDelete = document.getElementById('cancelDelete');

        // Close modal when clicking cancel
        cancelDelete.addEventListener('click', function() {
            deleteModal.classList.add('hidden');
        });

        // Close modal when clicking outside
        deleteModal.addEventListener('click', function(event) {
            if (event.target === deleteModal) {
                deleteModal.classList.add('hidden');
            }
        });

        // Confirm single validation deletion
        function confirmDelete(id, name) {
            deleteMessage.textContent = `Are you sure you want to delete the validation record for "${name}"?`;
            deleteForm.action = "{{ url('/validation') }}/" + id;
            deleteModal.classList.remove('hidden');
        }

        // Confirm deletion of all validations for a class
        function confirmDeleteAll(filierName, className) {
            deleteMessage.textContent =
                `Are you sure you want to delete ALL validation records for ${filierName} / ${className}?`;
            deleteForm.action = "{{ url('/validation-details') }}/" + filierName + "/" + className;
            deleteModal.classList.remove('hidden');
        }
    </script>
@endsection
