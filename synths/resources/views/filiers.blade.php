@extends('layout.header')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Filiers</title>
        <!-- Alpine.js for interactivity -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <style>
            /* Ensure consistent color scheme */
            :root {
                --primary-light: rgb(14, 165, 233);
                /* primary-500 from header */
                --primary-dark: rgb(3, 105, 161);
                /* primary-700 from header */
            }

            .border-primary-light {
                border-color: var(--primary-light);
            }

            .border-primary-dark {
                border-color: var(--primary-dark);
            }

            .bg-primary-light {
                background-color: var(--primary-light);
            }

            .bg-primary-dark {
                background-color: var(--primary-dark);
            }

            .text-primary-light {
                color: var(--primary-light);
            }

            .text-primary-dark {
                color: var(--primary-dark);
            }

            /* New styles for enhanced filiers */
            .page-header {
                text-align: center;
                margin-bottom: 2.5rem;
                position: relative;
                padding-bottom: 1.5rem;
            }

            .page-title {
                font-size: 2.5rem;
                font-weight: 700;
                color: var(--primary-dark);
                margin-bottom: 0.5rem;
                position: relative;
                display: inline-block;
            }

            .page-title::after {
                content: '';
                position: absolute;
                bottom: -10px;
                left: 50%;
                transform: translateX(-50%);
                width: 80px;
                height: 4px;
                background: var(--primary-light);
                border-radius: 2px;
            }

            .page-subtitle {
                font-size: 1.2rem;
                color: #6b7280;
                max-width: 600px;
                margin: 1rem auto 0;
            }

            .filier-card {
                border-radius: 1rem;
                border: 1px solid #e5e7eb;
                overflow: hidden;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                transition: all 0.3s ease;
                position: relative;
                z-index: 1;
            }

            .filier-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            }

            .filier-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-dark) 100%);
                opacity: 0;
                z-index: -1;
                transition: opacity 0.3s ease;
            }

            .filier-card:hover::before {
                opacity: 0.05;
            }

            .filier-card-header {
                padding: 1.5rem;
                background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-dark) 100%);
                color: white;
            }

            .filier-card-body {
                padding: 1.5rem;
                background: white;
            }

            .dark .filier-card-body {
                background: #1f2937;
            }

            .filier-icon {
                width: 48px;
                height: 48px;
                background-color: rgba(255, 255, 255, 0.2);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1rem;
            }

            .dark .page-title {
                color: white;
            }

            .dark .page-subtitle {
                color: #d1d5db;
            }
        </style>
    </head>

    <body x-data="{
        activeFilier: null,
        showPopup: false,
        classesData: JSON.parse('{{ $classesData }}'),
        selectedClasses: [],
        uniqueCodeClasses: [],
    
        showClasses(filierName) {
            this.activeFilier = filierName;
    
            // Get classes for the selected filier
            if (this.classesData[filierName]) {
                this.selectedClasses = this.classesData[filierName];
    
                // Extract unique code_class values
                const uniqueCodes = new Set();
                this.selectedClasses.forEach(classItem => {
                    uniqueCodes.add(classItem.code_class);
                });
    
                // Convert Set to Array of objects with code_class
                this.uniqueCodeClasses = Array.from(uniqueCodes).map(code => {
                    return { code_class: code };
                });
            } else {
                this.selectedClasses = [];
                this.uniqueCodeClasses = [];
            }
    
            this.showPopup = true;
        },
    
        goToClassDetails(codeClass) {
            // Navigate to student list page for the selected class
            window.location.href = '{{ url('/class-details') }}/' + this.activeFilier + '/' + codeClass;
        }
    }" class="bg-gray-100 dark:bg-gray-900 min-h-screen">

        <!-- Main Content -->
        <main class="container mx-auto px-6 py-8">
            <!-- New Header Section -->
            <div class="page-header">
                <h1 class="page-title">Gestion des Filières</h1>
                <p class="page-subtitle">Consultez et gérez les classes par filière</p>
            </div>

            <!-- Enhanced Filiers Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                @foreach ($filiers as $filier)
                    <div @click="showClasses('{{ $filier }}')" class="filier-card cursor-pointer">
                        <div class="filier-card-header">
                            <div class="filier-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold">{{ $filier }}</h3>
                        </div>
                        <div class="filier-card-body dark:bg-gray-800">
                            <p class="text-gray-600 dark:text-gray-300 mb-4">Découvrez toutes les classes associées à cette
                                filière et accédez à leurs informations détaillées.</p>
                            <div class="flex justify-between items-center">

                                <span
                                    class="inline-flex items-center text-primary-light dark:text-primary-light font-medium">
                                    Voir les classes
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </main>

        <!-- Classes Popup -->
        <div x-show="showPopup" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
            @click.self="showPopup = false">

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-4 overflow-hidden">
                <div class="bg-primary-light dark:bg-primary-dark text-white px-6 py-4 flex justify-between items-center">
                    <h3 class="text-xl font-semibold" x-text="'Classes pour ' + activeFilier"></h3>
                    <button @click="showPopup = false" class="focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-4 max-h-80 overflow-y-auto">
                    <template x-if="uniqueCodeClasses && uniqueCodeClasses.length > 0">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            <template x-for="(classItem, index) in uniqueCodeClasses" :key="index">
                                <li @click="goToClassDetails(classItem.code_class)"
                                    class="py-3 flex items-center hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer rounded px-2">
                                    <div
                                        class="bg-gray-200 dark:bg-gray-700 rounded-full w-8 h-8 flex items-center justify-center mr-3">
                                        <span class="text-primary-light dark:text-primary-dark font-medium"
                                            x-text="index + 1"></span>
                                    </div>
                                    <span class="dark:text-white flex-grow" x-text="classItem.code_class"></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </li>
                            </template>
                        </ul>
                    </template>

                    <template x-if="!uniqueCodeClasses || uniqueCodeClasses.length === 0">
                        <div class="py-4 text-center text-gray-500 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p>No classes found for this filier</p>
                        </div>
                    </template>
                </div>

                <div class="bg-gray-100 dark:bg-gray-700 px-6 py-3 text-right">
                    <button @click="showPopup = false"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-white rounded hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none">
                        Close
                    </button>
                </div>
            </div>
        </div>

    </body>

    </html>
@endsection
