<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        success: {
                            100: '#dcfce7',
                            500: '#22c55e',
                            700: '#15803d',
                        },
                        danger: {
                            100: '#fee2e2',
                            500: '#ef4444',
                            700: '#b91c1c',
                        }
                    }
                }
            }
        }
    </script>
    <script>
        // Check for saved theme preference or use the system preference
        document.addEventListener('DOMContentLoaded', function() {
            // Check for saved theme preference
            const theme = localStorage.getItem('theme');

            // If we have a theme preference saved
            if (theme) {
                document.documentElement.classList.remove('light', 'dark');
                document.documentElement.classList.add(theme);
            } else {
                // If no preference, check system preference
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.add('light');
                    localStorage.setItem('theme', 'light');
                }
            }
        });

        // Toggle theme function
        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.classList.remove('light');
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            }
        }

        // Function to update file input labels with selected filename
        function updateFileName(inputId, labelId) {
            const input = document.getElementById(inputId);
            const label = document.getElementById(labelId);

            input.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    label.textContent = this.files[0].name;
                    label.classList.remove('text-gray-600', 'dark:text-gray-400');
                    label.classList.add('text-success-500', 'dark:text-success-400');
                } else {
                    label.textContent = input.getAttribute('data-default-text');
                    label.classList.remove('text-success-500', 'dark:text-success-400');
                    label.classList.add('text-gray-600', 'dark:text-gray-400');
                }
            });
        }

        // Initialize file inputs after DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            updateFileName('id_card_img', 'id-card-file-name');
            updateFileName('bac_img', 'bac-file-name');
            updateFileName('birth_img', 'birth-file-name');
        });
    </script>
    <style>
        /* Custom file input styling */
        .custom-file-input::-webkit-file-upload-button {
            display: none;
        }

        .custom-file-input::file-selector-button {
            display: none;
        }
    </style>
</head>

<body class="bg-gray-100 dark:bg-gray-900 min-h-screen py-8 px-4 transition-colors duration-200">
    <div class="container mx-auto max-w-3xl">
        @if (Session::has('student'))
            @php
                $student = Session::get('student');
            @endphp
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 md:p-8 transition-colors duration-200">
                <!-- Success Message -->
                @if (session('success'))
                    <div
                        class="mb-6 p-4 bg-success-100 border-l-4 border-success-500 text-success-700 dark:bg-success-900 dark:border-success-700 dark:text-success-100">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Error Message -->
                @if ($errors->any())
                    <div
                        class="mb-6 p-4 bg-danger-100 border-l-4 border-danger-500 text-danger-700 dark:bg-danger-900 dark:border-danger-700 dark:text-danger-100">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 dark:text-white">Welcome,
                        {{ $student->s_fname }} {{ $student->s_lname }}</h1>
                    <button onclick="toggleDarkMode()"
                        class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200"
                        aria-label="Toggle dark mode">
                        <!-- Sun icon for dark mode -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800 dark:hidden" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <!-- Moon icon for light mode -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white hidden dark:block"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>
                </div>

                <div
                    class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg mb-6 border-l-4 border-blue-500 dark:border-blue-400">
                    <p class="text-blue-700 dark:text-blue-300">Please complete your registration by uploading the
                        required documents below.</p>
                </div>

                <form action="{{ url('/student') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <input type="hidden" name="cin" value="{{ $student->cin }}">
                    <input type="hidden" name="s_fname" value="{{ $student->s_fname }}">
                    <input type="hidden" name="s_lname" value="{{ $student->s_lname }}">

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CIN</label>
                            <input type="text" value="{{ $student->cin }}" readonly
                                class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 transition-colors duration-200">
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Group</label>
                            <input type="text" name="code_class" value="{{ $student->code_class }}" readonly
                                class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 transition-colors duration-200">
                        </div>

                        <div class="form-group">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Filière</label>
                            <input type="text" name="filier_name" value="{{ $student->filier_name }}" readonly
                                class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 transition-colors duration-200">
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">ID
                                Card</label>
                            <div class="flex items-center mt-1 relative">
                                <label for="id_card_img"
                                    class="w-full flex items-center justify-center px-4 py-3 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-md cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <svg class="h-6 w-6 text-gray-500 dark:text-gray-400 mr-2"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400" id="id-card-file-name">Upload
                                        ID Card</span>
                                    <input type="file" id="id_card_img" name="id_card_img" required
                                        class="custom-file-input sr-only" data-default-text="Upload ID Card">
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Baccalaureate
                                Certificate</label>
                            <div class="flex items-center mt-1 relative">
                                <label for="bac_img"
                                    class="w-full flex items-center justify-center px-4 py-3 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-md cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <svg class="h-6 w-6 text-gray-500 dark:text-gray-400 mr-2"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400" id="bac-file-name">Upload
                                        Baccalaureate Certificate</span>
                                    <input type="file" id="bac_img" name="bac_img" required
                                        class="custom-file-input sr-only"
                                        data-default-text="Upload Baccalaureate Certificate">
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Birth
                                Certificate</label>
                            <div class="flex items-center mt-1 relative">
                                <label for="birth_img"
                                    class="w-full flex items-center justify-center px-4 py-3 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-md cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <svg class="h-6 w-6 text-gray-500 dark:text-gray-400 mr-2"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                    </svg>
                                    <span class="text-sm text-gray-600 dark:text-gray-400" id="birth-file-name">Upload
                                        Birth Certificate</span>
                                    <input type="file" id="birth_img" name="birth_img" required
                                        class="custom-file-input sr-only"
                                        data-default-text="Upload Birth Certificate">
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button (Inside Form) -->
                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Envoyer
                        </button>
                    </div>
                </form>

                <!-- Logout Button (Outside Form) -->
                <div class="flex justify-start pt-4">
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md flex items-center transition-colors duration-300">
                            <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center">
                <h2 class="text-xl font-bold text-red-600 dark:text-red-400">No student session found</h2>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Please log in to access your registration form.</p>
                <a href="{{ url('/login') }}"
                    class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors duration-200">Go
                    to Login</a>
            </div>
        @endif
    </div>
</body>

</html>
