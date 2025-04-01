<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        // Tailwind configuration for dark mode
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {}
            }
        }

        // Dark mode toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Check for saved theme preference or use device preference
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia(
                    '(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.add('light');
            }

            // Update icon visibility based on current theme
            updateThemeIcons();

            // Theme toggle button
            const themeToggleBtn = document.getElementById('theme-toggle');
            themeToggleBtn.addEventListener('click', function() {
                // Toggle dark mode
                document.documentElement.classList.toggle('dark');
                document.documentElement.classList.toggle('light');

                // Save preference to localStorage
                if (document.documentElement.classList.contains('dark')) {
                    localStorage.setItem('theme', 'dark');
                } else {
                    localStorage.setItem('theme', 'light');
                }

                // Update icons
                updateThemeIcons();
            });
        });

        function updateThemeIcons() {
            const darkIcon = document.getElementById('theme-toggle-dark-icon');
            const lightIcon = document.getElementById('theme-toggle-light-icon');

            if (document.documentElement.classList.contains('dark')) {
                darkIcon.classList.add('hidden');
                lightIcon.classList.remove('hidden');
            } else {
                darkIcon.classList.remove('hidden');
                lightIcon.classList.add('hidden');
            }
        }
    </script>
</head>

<body class="bg-gray-100 dark:bg-gray-900 min-h-screen transition-colors duration-300">
    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-indigo-600 dark:text-indigo-400">Admin Portal</h1>
                    </div>
                </div>
                <div class="flex items-center">
                    <button id="theme-toggle" type="button"
                        class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5 mr-2">
                        <svg id="theme-toggle-dark-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                        <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <form action="{{ route('logout') }}" method="post" class="ml-4">
                        @csrf
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md flex items-center transition-colors duration-300">
                            <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Tableau de Bord Administrateur</h2>
            <p class="mt-4 text-lg text-gray-600 dark:text-gray-300">Bienvenue dans votre espace d'administration</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Card 1: Ajouter Stagiaires -->
            <a href="{{ url('/classes') }}" class="block group">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg transition-all duration-300 transform group-hover:scale-105 h-full">
                    <div class="p-6 flex flex-col items-center">
                        <div
                            class="w-16 h-16 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-user-plus text-2xl text-indigo-600 dark:text-indigo-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Ajouter Stagiaires</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-center">Ajouter et gérer les informations des
                            nouveaux stagiaires.</p>
                        <div
                            class="mt-4 bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md transition-colors duration-300 inline-block">
                            Accéder
                        </div>
                    </div>
                </div>
            </a>

            <!-- Card 2: Gestion des Classes -->
            <a href="{{ url('/filires') }}" class="block group">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg transition-all duration-300 transform group-hover:scale-105 h-full">
                    <div class="p-6 flex flex-col items-center">
                        <div
                            class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-chalkboard-teacher text-2xl text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Gestion des Classes</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-center">Gérer les classes, les cours et les
                            attributions des stagiaires.</p>
                        <div
                            class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition-colors duration-300 inline-block">
                            Accéder
                        </div>
                    </div>
                </div>
            </a>

            <!-- Card 3: Mon Profil -->
            <a href="{{ url('/admin/profile') }}" class="block group">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-lg transition-all duration-300 transform group-hover:scale-105 h-full">
                    <div class="p-6 flex flex-col items-center">
                        <div
                            class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-user-cog text-2xl text-green-600 dark:text-green-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Mon Profil</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-center">Gérer vos informations personnelles et
                            paramètres de compte.</p>
                        <div
                            class="mt-4 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-md transition-colors duration-300 inline-block">
                            Accéder
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 shadow-inner mt-12">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 dark:text-gray-400">© 2025 Admin Portal. Tous droits réservés.</p>
        </div>
    </footer>
</body>

</html>
