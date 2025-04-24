@extends('layout.header')
@section('content')
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Manage Classes</h1>

        <!-- File Upload Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-8">
            <div class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                <h5 class="text-xl font-semibold text-gray-900 dark:text-white">Import Students from Excel</h5>
            </div>
            <div class="p-6">
                <form action="{{ route('classes.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="excel_file"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload Excel
                            File</label>
                        <input type="file"
                            class="block w-full px-3 py-2 text-gray-700 bg-white dark:bg-gray-800 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                            name="excel_file" id="excel_file" accept=".xlsx,.xls" required>
                    </div>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-300 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Import
                    </button>
                </form>
            </div>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <!-- Classes Table Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div
                class="bg-gray-50 dark:bg-gray-700 px-6 py-4 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                <h5 class="text-xl font-semibold text-gray-900 dark:text-white">List of Classes</h5>
                <div class="flex items-center">
                    <label for="tableSearch" class="mr-2 text-gray-700 dark:text-gray-300 font-medium">Search:</label>
                    <input type="text" id="tableSearch"
                        class="rounded-md border border-gray-400 dark:border-gray-600 bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
                </div>
            </div>
            <div class="p-6 overflow-x-auto">
                <table id="classesTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                    <thead class="bg-gray-700 text-white">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider cursor-pointer">
                                CIN <span class="sort-icon">↕</span></th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider cursor-pointer">
                                NOM <span class="sort-icon">↕</span></th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider cursor-pointer">
                                PRÉNOM <span class="sort-icon">↕</span></th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider cursor-pointer">
                                FILIÈRE <span class="sort-icon">↕</span></th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider cursor-pointer">
                                CLASSE <span class="sort-icon">↕</span></th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider cursor-pointer">
                                AGE <span class="sort-icon">↕</span></th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                                ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($classes as $classe)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $classe->cin }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $classe->s_lname }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $classe->s_fname }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $classe->filier_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $classe->code_class }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ $classe->age }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button data-cin="{{ $classe->cin }}"
                                        class="delete-student-btn bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded text-sm inline-flex items-center transition-colors duration-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-center mt-6">
            @if ($classes->hasPages())
                <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
                    <div class="flex justify-between flex-1 sm:hidden">
                        @if ($classes->onFirstPage())
                            <span
                                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 cursor-default rounded-md">
                                Previous
                            </span>
                        @else
                            <a href="{{ $classes->previousPageUrl() }}"
                                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                                Previous
                            </a>
                        @endif

                        @if ($classes->hasMorePages())
                            <a href="{{ $classes->nextPageUrl() }}"
                                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                                Next
                            </a>
                        @else
                            <span
                                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-500 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 cursor-default rounded-md">
                                Next
                            </span>
                        @endif
                    </div>

                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-center">
                        <div>
                            <span class="relative z-0 inline-flex rounded-md shadow-sm">
                                {{-- Previous Page Link --}}
                                @if ($classes->onFirstPage())
                                    <span aria-disabled="true" aria-label="Previous">
                                        <span
                                            class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 cursor-default rounded-l-md"
                                            aria-hidden="true">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </span>
                                @else
                                    <a href="{{ $classes->previousPageUrl() }}" rel="prev"
                                        class="relative inline-flex items-center px-2 py-2 text-sm font-medium text-gray-500 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-l-md hover:bg-gray-50 dark:hover:bg-gray-700"
                                        aria-label="Previous">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($classes->getUrlRange(max(1, $classes->currentPage() - 2), min($classes->lastPage(), $classes->currentPage() + 2)) as $page => $url)
                                    @if ($page == $classes->currentPage())
                                        <span aria-current="page">
                                            <span
                                                class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900 border border-indigo-300 dark:border-indigo-700 cursor-default">{{ $page }}</span>
                                        </span>
                                    @else
                                        <a href="{{ $url }}"
                                            class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700">{{ $page }}</a>
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($classes->hasMorePages())
                                    <a href="{{ $classes->nextPageUrl() }}" rel="next"
                                        class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-r-md hover:bg-gray-50 dark:hover:bg-gray-700"
                                        aria-label="Next">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                @else
                                    <span aria-disabled="true" aria-label="Next">
                                        <span
                                            class="relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-gray-500 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 cursor-default rounded-r-md"
                                            aria-hidden="true">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </span>
                                @endif
                            </span>
                        </div>
                    </div>
                </nav>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('tableSearch');
            const table = document.getElementById('classesTable');
            const rows = table.querySelectorAll('tbody tr');
            const headers = table.querySelectorAll('thead th');

            // Enhanced search functionality 
            searchInput.addEventListener('keyup', function() {
                const searchTerm = searchInput.value.toLowerCase().trim();

                rows.forEach(row => {
                    let found = false;
                    const cells = row.querySelectorAll('td');

                    // Check each cell in the row
                    cells.forEach((cell, index) => {
                        if (index < 6) { // Skip the actions column
                            const text = cell.textContent.toLowerCase().trim();
                            if (text.includes(searchTerm)) {
                                found = true;
                            }
                        }
                    });

                    // Show or hide the row based on search results
                    row.style.display = found ? '' : 'none';
                });
            });

            // Sorting functionality
            headers.forEach((header, index) => {
                if (index < 6) { // Only make the first 6 columns sortable (exclude actions)
                    header.addEventListener('click', function() {
                        sortTable(index);
                    });
                }
            });

            function sortTable(columnIndex) {
                let switching = true;
                let shouldSwitch = false;
                let direction = 'asc';
                let switchcount = 0;
                let i, x, y;

                // Get current direction from the clicked header
                const headerClicked = headers[columnIndex];
                const icon = headerClicked.querySelector('.sort-icon');

                // Determine or change sort direction
                if (icon.textContent === '↑') {
                    direction = 'desc';
                    icon.textContent = '↓';
                } else {
                    direction = 'asc';
                    icon.textContent = '↑';
                }

                // Reset all other headers
                headers.forEach((h, idx) => {
                    if (idx !== columnIndex && idx < 6) {
                        h.querySelector('.sort-icon').textContent = '↕';
                    }
                });

                while (switching) {
                    switching = false;
                    const rowsArray = Array.from(table.querySelectorAll('tbody tr'));

                    for (i = 0; i < rowsArray.length - 1; i++) {
                        shouldSwitch = false;
                        x = rowsArray[i].getElementsByTagName('td')[columnIndex];
                        y = rowsArray[i + 1].getElementsByTagName('td')[columnIndex];

                        // Check if column is numeric (e.g., age)
                        if (columnIndex === 5) { // Age column
                            const xValue = parseInt(x.textContent.trim());
                            const yValue = parseInt(y.textContent.trim());

                            if (direction === 'asc' && xValue > yValue) {
                                shouldSwitch = true;
                                break;
                            } else if (direction === 'desc' && xValue < yValue) {
                                shouldSwitch = true;
                                break;
                            }
                        } else { // Text columns
                            const xValue = x.textContent.trim().toLowerCase();
                            const yValue = y.textContent.trim().toLowerCase();

                            if (direction === 'asc' && xValue > yValue) {
                                shouldSwitch = true;
                                break;
                            } else if (direction === 'desc' && xValue < yValue) {
                                shouldSwitch = true;
                                break;
                            }
                        }
                    }

                    if (shouldSwitch) {
                        rowsArray[i].parentNode.insertBefore(rowsArray[i + 1], rowsArray[i]);
                        switching = true;
                        switchcount++;
                    } else {
                        if (switchcount === 0 && direction === 'asc') {
                            direction = 'desc';
                            icon.textContent = '↓';
                            switching = true;
                        }
                    }
                }
            }

            // Handle student deletion with AJAX
            const deleteButtons = document.querySelectorAll('.delete-student-btn');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const cin = this.getAttribute('data-cin');

                    if (confirm('Are you sure you want to delete this student?')) {
                        // Create a loading indicator
                        const originalContent = this.innerHTML;
                        this.innerHTML =
                            '<svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                        this.disabled = true;

                        // Create form data
                        const formData = new FormData();
                        formData.append('_token', '{{ csrf_token() }}');
                        formData.append('_method', 'DELETE');

                        // Send AJAX request
                        fetch('{{ url('classes') }}/' + cin, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Remove the row from the table
                                    const row = this.closest('tr');
                                    row.style.backgroundColor = '#FEE2E2';
                                    setTimeout(() => {
                                        row.remove();
                                    }, 500);

                                    // Show success message
                                    const successAlert = document.createElement('div');
                                    successAlert.className =
                                        'bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded';
                                    successAlert.innerHTML = '<p>' + data.message + '</p>';

                                    const container = document.querySelector('.container');
                                    container.insertBefore(successAlert, container.firstChild);

                                    // Auto-dismiss the alert after 3 seconds
                                    setTimeout(() => {
                                        successAlert.remove();
                                    }, 3000);
                                } else {
                                    // Show error message and restore button
                                    alert(data.message || 'Error deleting student');
                                    this.innerHTML = originalContent;
                                    this.disabled = false;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert(
                                    'An error occurred while deleting the student. Please try again.'
                                );
                                this.innerHTML = originalContent;
                                this.disabled = false;
                            });
                    }
                });
            });
        });
    </script>
@endsection
