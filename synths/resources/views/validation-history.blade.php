@extends('layout.header')
@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mb-6">
                <div class="bg-primary-500 dark:bg-primary-700 text-white px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold">Document Validation History</h1>
                            <p class="text-sm mt-1 text-primary-100">View all document validation results by filier and class
                            </p>
                        </div>
                    </div>
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

            <!-- Export Button Section -->
            <div class="flex justify-end mb-6">
                <div class="relative group">
                    <button
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition-colors flex items-center">
                        <i class="fas fa-download mr-2"></i>Export Data
                        <i class="fas fa-chevron-down ml-2"></i>
                    </button>
                    <div
                        class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg overflow-hidden z-20 hidden group-hover:block">
                        <a href="{{ route('validation.export', ['format' => 'excel']) }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <i class="fas fa-file-excel mr-2 text-green-600"></i>Export as Excel
                        </a>
                        <a href="{{ route('validation.export', ['format' => 'csv']) }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <i class="fas fa-file-csv mr-2 text-blue-600"></i>Export as CSV
                        </a>
                        <a href="{{ route('validation.export', ['format' => 'text']) }}"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <i class="fas fa-file-alt mr-2 text-gray-600"></i>Export as Text
                        </a>
                    </div>
                </div>
            </div>

            @if ($validations->isEmpty())
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-8 text-center">
                    <i class="fas fa-history text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                    <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No Validation History</h2>
                    <p class="text-gray-500 dark:text-gray-400">No document validations have been performed yet.</p>
                </div>
            @else
                <!-- Filier Groups -->
                @foreach ($validations as $filierName => $filierGroup)
                    <div class="mb-8">
                        <h2
                            class="text-xl font-semibold text-gray-800 dark:text-white mb-4 border-b pb-2 border-gray-200 dark:border-gray-700">
                            <i class="fas fa-folder-open mr-2 text-primary-500"></i>{{ $filierName }}
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($filierGroup as $className => $classValidations)
                                <div
                                    class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden transition transform hover:scale-105 hover:shadow-lg">
                                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ $className }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ count($classValidations) }} validation records
                                        </p>
                                    </div>
                                    <div class="px-6 py-4">
                                        <div class="flex justify-between items-center mb-4">
                                            <div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">Latest validation:</p>
                                                <p class="text-gray-700 dark:text-gray-300">
                                                    {{ $classValidations->first()->created_at->format('M d, Y - H:i') }}
                                                </p>
                                            </div>
                                            <div>
                                                <span
                                                    class="px-2 py-1 rounded-full text-xs
                                                    {{ $classValidations->where('is_correct', true)->count() >
                                                    $classValidations->where('is_correct', false)->count()
                                                        ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100'
                                                        : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                                    {{ $classValidations->where('is_correct', true)->count() }} correct /
                                                    {{ $classValidations->where('is_correct', false)->count() }} incorrect
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex space-x-2 mt-4">
                                            <a href="{{ route('validation.details', ['filierName' => $filierName, 'className' => $className]) }}"
                                                class="flex-1 bg-primary-500 hover:bg-primary-600 text-white text-center py-2 rounded-md transition-colors">
                                                View Details
                                            </a>
                                            <button onclick="confirmDelete('{{ $filierName }}', '{{ $className }}')"
                                                class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-md transition-colors">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @endif
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

        // Confirm deletion of all validations for a class
        function confirmDelete(filierName, className) {
            deleteMessage.textContent =
                `Are you sure you want to delete ALL validation records for ${filierName} / ${className}?`;
            deleteForm.action = "{{ url('/validation-details') }}/" + filierName + "/" + className;
            deleteModal.classList.remove('hidden');
        }
    </script>
@endsection
