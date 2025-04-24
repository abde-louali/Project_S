@extends('layout.header')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Class Details</title>
        <!-- Alpine.js for interactivity -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <style>
            :root {
                --primary-light: rgb(14, 165, 233);
                --primary-dark: rgb(3, 105, 161);
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

            /* Document status indicators */
            .document-status {
                display: inline-flex;
                align-items: center;
                margin-right: 10px;
            }

            .document-status-icon {
                margin-right: 5px;
            }

            .document-present {
                color: #10B981;
            }

            .document-missing {
                color: #EF4444;
            }

            /* Image modal styles */
            .image-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.9);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1000;
            }

            .image-modal-content {
                position: relative;
                max-width: 90%;
                max-height: 90%;
            }

            .image-modal-content img {
                max-width: 100%;
                max-height: 90vh;
                object-fit: contain;
                border-radius: 4px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
            }

            .close-modal {
                position: absolute;
                top: -40px;
                right: 0;
                color: white;
                font-size: 30px;
                cursor: pointer;
                background: rgba(0, 0, 0, 0.5);
                width: 40px;
                height: 40px;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .close-modal:hover {
                background: rgba(0, 0, 0, 0.8);
            }

            /* Thumbnail styles */
            .thumbnail-container {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                margin-top: 4px;
            }

            .thumbnail {
                width: 60px;
                height: 60px;
                object-fit: cover;
                cursor: pointer;
                border: 2px solid #e2e8f0;
                border-radius: 4px;
                transition: all 0.2s ease;
            }

            .thumbnail:hover {
                transform: scale(1.1);
                border-color: var(--primary-light);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            }

            .dark .thumbnail {
                border-color: #4b5563;
            }

            /* Loading spinner */
            .spinner {
                animation: spin 1s linear infinite;
            }

            @keyframes spin {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }
        </style>
    </head>

    <body x-data="{
        showProfileModal: false,
        selectedStudent: null,
        showImageModal: false,
        currentImage: '',
        currentImageTitle: '',
        isLoading: false,
    
        viewProfile(student) {
            this.selectedStudent = student;
            this.showProfileModal = true;
        },
        openImage(imageUrl, title = '') {
            this.isLoading = true;
            this.showImageModal = true;
            this.currentImage = imageUrl;
            this.currentImageTitle = title;
    
            // Preload image
            const img = new Image();
            img.src = imageUrl;
            img.onload = () => {
                this.isLoading = false;
            };
            img.onerror = () => {
                this.isLoading = false;
            };
        },
        closeImageModal() {
            this.showImageModal = false;
            this.currentImage = '';
            this.currentImageTitle = '';
        }
    }" class="bg-gray-100 dark:bg-gray-900 min-h-screen">

        <!-- Image Modal -->
        <template x-if="showImageModal">
            <div class="image-modal" @click.self="closeImageModal">
                <div class="image-modal-content">
                    <span class="close-modal" @click="closeImageModal">&times;</span>
                    <template x-if="isLoading">
                        <div class="flex justify-center items-center h-64">
                            <svg class="spinner h-12 w-12 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                    </template>
                    <template x-if="!isLoading">
                        <div>
                            <img :src="currentImage" :alt="currentImageTitle" @click.stop>
                            <p x-text="currentImageTitle" class="text-white text-center mt-2"></p>
                        </div>
                    </template>
                </div>
            </div>
        </template>

        <!-- Main Content -->
        <main class="container mx-auto px-4 py-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
                <!-- Header -->
                <div class="bg-primary-light dark:bg-primary-dark text-white px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-semibold">{{ $filierName }} - {{ $className }}</h2>
                        <a href="{{ url('/filires') }}" class="flex items-center text-white hover:text-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                            Back to Filiers
                        </a>
                    </div>
                </div>

                <!-- Student List -->
                <div class="mb-4 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white">Student List</h3>
                    <div class="flex space-x-3">
                        <button id="createFoldersBtn"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                    clip-rule="evenodd" />
                            </svg>
                            Create Folders
                        </button>
                        <button id="verifyDocumentsBtn"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            Verify Documents
                        </button>
                    </div>
                    <div class="relative">
                        <input type="text" placeholder="Search students..."
                            class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary-light dark:focus:ring-primary-dark">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table
                        class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                        <thead>
                            <tr
                                class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 uppercase text-xs leading-normal">
                                <th class="py-3 px-6 text-left">CIN</th>
                                <th class="py-3 px-6 text-left">First Name</th>
                                <th class="py-3 px-6 text-left">Last Name</th>
                                <th class="py-3 px-6 text-left">Documents</th>
                                <th class="py-3 px-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 dark:text-gray-200">
                            @foreach ($students as $student)
                                @php
                                    $folderName = $student->cin . '_' . $student->s_lname;
                                    $studentFolder = public_path("uploads/{$filierName}/{$className}/{$folderName}");

                                    $hasFolder = is_dir($studentFolder);

                                    // Check multiple possible extensions for each document type
                                    $bacImg = null;
                                    $idCard = null;
                                    $birthImg = null;

                                    // Image extensions to check
                                    $extensions = ['png', 'jpg', 'jpeg', 'JPG', 'JPEG', 'PNG'];

                                    // Find bac_img with any extension
                                    foreach ($extensions as $ext) {
                                        if ($hasFolder && file_exists("{$studentFolder}/bac_img.{$ext}")) {
                                            $bacImg = asset(
                                                "uploads/{$filierName}/{$className}/{$folderName}/bac_img.{$ext}",
                                            );
                                            break;
                                        }
                                    }

                                    // Find id_card_img with any extension
                                    foreach ($extensions as $ext) {
                                        if ($hasFolder && file_exists("{$studentFolder}/id_card_img.{$ext}")) {
                                            $idCard = asset(
                                                "uploads/{$filierName}/{$className}/{$folderName}/id_card_img.{$ext}",
                                            );
                                            break;
                                        }
                                    }

                                    // Find birth_img with any extension
                                    foreach ($extensions as $ext) {
                                        if ($hasFolder && file_exists("{$studentFolder}/birth_img.{$ext}")) {
                                            $birthImg = asset(
                                                "uploads/{$filierName}/{$className}/{$folderName}/birth_img.{$ext}",
                                            );
                                            break;
                                        }
                                    }
                                @endphp

                                <tr
                                    class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="py-3 px-6 text-left">{{ $student->cin }}</td>
                                    <td class="py-3 px-6 text-left">{{ $student->s_fname }}</td>
                                    <td class="py-3 px-6 text-left">{{ $student->s_lname }}</td>
                                    <td class="py-3 px-6 text-left">
                                        @if ($hasFolder)
                                            <div class="thumbnail-container">
                                                @if ($bacImg)
                                                    <img src="{{ $bacImg }}"
                                                        @click="openImage('{{ $bacImg }}', 'Bac Image - {{ $student->s_fname }} {{ $student->s_lname }}')"
                                                        class="thumbnail" title="Bac Image">
                                                @endif
                                                @if ($idCard)
                                                    <img src="{{ $idCard }}"
                                                        @click="openImage('{{ $idCard }}', 'ID Card - {{ $student->s_fname }} {{ $student->s_lname }}')"
                                                        class="thumbnail" title="ID Card">
                                                @endif
                                                @if ($birthImg)
                                                    <img src="{{ $birthImg }}"
                                                        @click="openImage('{{ $birthImg }}', 'Birth Certificate - {{ $student->s_fname }} {{ $student->s_lname }}')"
                                                        class="thumbnail" title="Birth Certificate">
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400">No folder created</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <button
                                            @click="viewProfile({
                                            cin: '{{ $student->cin }}',
                                            first_name: '{{ $student->s_fname }}',
                                            last_name: '{{ $student->s_lname }}',
                                            folder_name: '{{ $folderName }}',
                                            has_folder: {{ $hasFolder ? 'true' : 'false' }},
                                            bac_img: {{ $bacImg ? "'$bacImg'" : 'null' }},
                                            id_card: {{ $idCard ? "'$idCard'" : 'null' }},
                                            birth_img: {{ $birthImg ? "'$birthImg'" : 'null' }}
                                        })"
                                            class="px-4 py-1.5 bg-primary-light dark:bg-primary-dark text-white rounded hover:bg-blue-600 dark:hover:bg-blue-800 transition duration-200 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                            See Profile
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4 flex justify-between items-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span
                            class="font-medium">{{ count($students) }}</span> results
                    </p>
                    <div class="flex space-x-1">
                        <button
                            class="px-3 py-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            Previous
                        </button>
                        <button
                            class="px-3 py-1 bg-primary-light dark:bg-primary-dark text-white rounded-md hover:bg-blue-600 dark:hover:bg-blue-800">
                            1
                        </button>
                        <button
                            class="px-3 py-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            2
                        </button>
                        <button
                            class="px-3 py-1 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </main>

        <!-- Student Profile Modal -->
        <div x-show="showProfileModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
            @click.self="showProfileModal = false">

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-4 overflow-hidden">
                <div class="bg-primary-light dark:bg-primary-dark text-white px-6 py-4 flex justify-between items-center">
                    <h3 class="text-xl font-semibold">Student Documents</h3>
                    <button @click="showProfileModal = false" class="focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-4">
                    <div class="flex flex-col items-center mb-4">
                        <div class="w-24 h-24 bg-gray-300 dark:bg-gray-600 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-600 dark:text-gray-300"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="mt-2 text-xl font-semibold text-gray-800 dark:text-white">
                            <span x-text="selectedStudent?.first_name"></span> <span
                                x-text="selectedStudent?.last_name"></span>
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400" x-text="'CIN: ' + selectedStudent?.cin"></p>
                    </div>

                    <div class="space-y-3">
                        <template x-if="!selectedStudent?.has_folder">
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative"
                                role="alert">
                                <span class="block sm:inline">No folder exists for this student</span>
                            </div>
                        </template>

                        <div class="flex items-center border-b border-gray-200 dark:border-gray-700 py-2">
                            <span class="w-1/3 font-medium text-gray-600 dark:text-gray-300">Documents:</span>
                            <span class="w-2/3">
                                <template
                                    x-if="selectedStudent?.bac_img || selectedStudent?.id_card || selectedStudent?.birth_img">
                                    <div class="thumbnail-container">
                                        <template x-if="selectedStudent?.bac_img">
                                            <img :src="selectedStudent?.bac_img"
                                                @click="openImage(selectedStudent?.bac_img, 'Bac Image - ' + selectedStudent?.first_name + ' ' + selectedStudent?.last_name)"
                                                class="thumbnail" title="Bac Image">
                                        </template>
                                        <template x-if="selectedStudent?.id_card">
                                            <img :src="selectedStudent?.id_card"
                                                @click="openImage(selectedStudent?.id_card, 'ID Card - ' + selectedStudent?.first_name + ' ' + selectedStudent?.last_name)"
                                                class="thumbnail" title="ID Card">
                                        </template>
                                        <template x-if="selectedStudent?.birth_img">
                                            <img :src="selectedStudent?.birth_img"
                                                @click="openImage(selectedStudent?.birth_img, 'Birth Certificate - ' + selectedStudent?.first_name + ' ' + selectedStudent?.last_name)"
                                                class="thumbnail" title="Birth Certificate">
                                        </template>
                                    </div>
                                </template>
                                <template
                                    x-if="!selectedStudent?.bac_img && !selectedStudent?.id_card && !selectedStudent?.birth_img && selectedStudent?.has_folder">
                                    <span class="text-gray-500 dark:text-gray-400">No documents uploaded</span>
                                </template>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-100 dark:bg-gray-700 px-6 py-3 text-right">
                    <button @click="showProfileModal = false"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-white rounded hover:bg-gray-400 dark:hover:bg-gray-500 focus:outline-none">
                        Close
                    </button>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('createFoldersBtn').addEventListener('click', function() {
                this.innerHTML =
                    '<svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Creating folders...';

                fetch('{{ route('create.folders', ['filierName' => $filierName, 'className' => $className]) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.innerHTML =
                                '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg> Create Folders';
                            alert('Folders created successfully!');
                            location.reload();
                        } else {
                            this.innerHTML =
                                '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg> Create Folders';
                            alert('Error creating folders: ' + data.message);
                        }
                    })
                    .catch(error => {
                        this.innerHTML =
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg> Create Folders';
                        alert('Error creating folders: ' + error);
                    });
            });

            document.getElementById('verifyDocumentsBtn').addEventListener('click', function() {
                this.innerHTML =
                    '<svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Verifying Documents...';
                this.disabled = true;

                fetch('{{ route('verify.documents', ['filierName' => $filierName, 'className' => $className]) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            window.location.href =
                                '{{ route('document.verification.results', ['filierName' => $filierName, 'className' => $className]) }}';
                        } else {
                            this.innerHTML =
                                '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg> Verify Documents';
                            this.disabled = false;
                            alert('Error verifying documents: ' + data.message);
                        }
                    })
                    .catch(error => {
                        this.innerHTML =
                            '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg> Verify Documents';
                        this.disabled = false;
                        alert('Error verifying documents: ' + error);
                    });
            });
        </script>

    </body>

    </html>
@endsection
