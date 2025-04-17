<!DOCTYPE html>
<html lang="fr" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>En-tête Administrateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        tailwind.config = {
            darkMode: 'class', // Enable dark mode via class instead of media query
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer components {
            .nav-link {
                @apply px-3 py-2 rounded-md text-sm font-medium transition-colors duration-200;
            }

            .nav-link-light {
                @apply text-gray-700 hover:text-primary-600 hover:bg-gray-100;
            }

            .nav-link-dark {
                @apply text-gray-200 hover:text-white hover:bg-gray-700;
            }
        }
    </style>
</head>

<body class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
    <!-- Header/Navbar -->
    <nav class="bg-white dark:bg-gray-800 shadow-md transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo and brand -->
                <div class="flex items-center">
                    <a href="./Admin.php" class="flex-shrink-0 flex items-center">
                        <img class="h-8 w-auto" src="{{ asset('image/ofppt_logo.png') }}" alt="Logo OFPPT">
                        <span class="ml-2 text-xl font-bold text-primary-600 dark:text-primary-400">OFPPT</span>
                    </a>
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center md:hidden">
                    <button id="mobile-menu-button" type="button"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Desktop navigation -->
                <div class="hidden md:flex md:items-center md:space-x-4">
                    <a href="{{ url('/admin') }}" class="nav-link nav-link-light dark:nav-link-dark">
                        <i class="fas fa-home mr-1"></i> Accueil
                    </a>
                    <a href="{{ url('/classes') }}" class="nav-link nav-link-light dark:nav-link-dark">
                        <i class="fas fa-user-plus mr-1"></i> Ajouter stagiaires
                    </a>
                    <a href="{{ url('/filires') }}" class="nav-link nav-link-light dark:nav-link-dark">
                        <i class="fas fa-users mr-1"></i> Classes
                    </a>
                    <a href="{{ route('validation.history') }}" class="nav-link nav-link-light dark:nav-link-dark">
                        <i class="fas fa-history mr-1"></i> Validations
                    </a>

                    <!-- Theme toggle -->
                    <button id="theme-toggle" type="button"
                        class="nav-link nav-link-light dark:nav-link-dark flex items-center">
                        <i id="theme-toggle-dark-icon" class="fas fa-moon"></i>
                        <i id="theme-toggle-light-icon" class="fas fa-sun hidden"></i>
                    </button>

                    <!-- Profile dropdown -->
                    <div class="relative">
                        <button id="user-menu-button"
                            class="nav-link nav-link-light dark:nav-link-dark flex items-center">
                            <i class="fas fa-user-circle mr-1"></i>
                            <i class="fas fa-caret-down ml-1 text-xs"></i>
                        </button>

                        <!-- Dropdown menu -->
                        <div id="user-dropdown"
                            class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-10"
                            role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                            tabindex="-1">
                            <a href="{{ url('/admin/profile') }}"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                                role="menuitem">
                                <i class="fas fa-user mr-2"></i> Mon Profile
                            </a>
                            <div class="border-t border-gray-200 dark:border-gray-700"></div>
                            <form action="{{ url('/login') }}" method="POST" class="block">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700"
                                    role="menuitem">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Se déconnecter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile menu, show/hide based on menu state -->
        <div id="mobile-menu" class="hidden md:hidden bg-white dark:bg-gray-800 transition-colors duration-200">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="{{ url('/admin') }}" class="nav-link nav-link-light dark:nav-link-dark block">
                    <i class="fas fa-home mr-1"></i> Accueil
                </a>
                <a href="{{ url('/classes') }}" class="nav-link nav-link-light dark:nav-link-dark block">
                    <i class="fas fa-user-plus mr-1"></i> Ajouter stagiaires
                </a>
                <a href="{{ url('/filires') }}" class="nav-link nav-link-light dark:nav-link-dark block">
                    <i class="fas fa-users mr-1"></i> Classes
                </a>
                <a href="{{ route('validation.history') }}" class="nav-link nav-link-light dark:nav-link-dark block">
                    <i class="fas fa-history mr-1"></i> Validations
                </a>

                <button id="mobile-theme-toggle"
                    class="nav-link nav-link-light dark:nav-link-dark flex items-center w-full">
                    <i class="fas fa-moon mr-1"></i> Changer de thème
                </button>

                <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>

                <a href="#" class="nav-link nav-link-light dark:nav-link-dark block">
                    <i class="fas fa-user mr-1"></i> Mon Profile
                </a>

                <form action="{{ url('/login') }}" method="POST" class="block">
                    @csrf
                    <button type="submit"
                        class="nav-link w-full text-left text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-sign-out-alt mr-1"></i> Se déconnecter
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div>
        @yield('content')
    </div>

    <script>
        // Theme toggle functionality
        function setupThemeToggle() {
            const themeToggleBtn = document.getElementById('theme-toggle');
            const mobileThemeToggleBtn = document.getElementById('mobile-theme-toggle');
            const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
            const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

            // Function to set theme
            function setTheme(theme) {
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                    themeToggleDarkIcon.classList.add('hidden');
                    themeToggleLightIcon.classList.remove('hidden');
                    localStorage.theme = 'dark';
                } else {
                    document.documentElement.classList.remove('dark');
                    themeToggleDarkIcon.classList.remove('hidden');
                    themeToggleLightIcon.classList.add('hidden');
                    localStorage.theme = 'light';
                }
            }

            // Check for saved theme preference or use device preference
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                    '(prefers-color-scheme: dark)').matches)) {
                setTheme('dark');
            } else {
                setTheme('light');
            }

            // Theme toggle button
            themeToggleBtn.addEventListener('click', function() {
                // Toggle theme
                if (document.documentElement.classList.contains('dark')) {
                    setTheme('light');
                } else {
                    setTheme('dark');
                }
            });

            // Mobile theme toggle
            if (mobileThemeToggleBtn) {
                mobileThemeToggleBtn.addEventListener('click', function() {
                    // Toggle theme
                    if (document.documentElement.classList.contains('dark')) {
                        setTheme('light');
                    } else {
                        setTheme('dark');
                    }
                });
            }
        }

        // Dropdown functionality
        function setupDropdown() {
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');

            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function() {
                    userDropdown.classList.toggle('hidden');
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(event) {
                    if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }
        }

        // Mobile menu functionality
        function setupMobileMenu() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
            }
        }

        // Initialize all functionality when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            setupThemeToggle();
            setupDropdown();
            setupMobileMenu();
        });
    </script>
</body>

</html>
