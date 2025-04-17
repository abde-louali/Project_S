@extends('layout.header')

@section('content')
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-200">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                {{-- Status Message --}}
                @if (session('status') && session('message'))
                    <div class="
                    {{ session('status') == 'success'
                        ? 'bg-green-100 border-green-400 text-green-700'
                        : 'bg-red-100 border-red-400 text-red-700' }} 
                    px-4 py-3 rounded relative mb-4"
                        role="alert">
                        <span class="block sm:inline">{{ session('message') }}</span>
                    </div>
                @endif

                {{-- Profile Header --}}
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <i class="fas fa-user-circle text-4xl text-primary-600 dark:text-primary-400"></i>
                        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Profile Administrateur</h1>
                    </div>
                    <button id="edit-toggle-btn"
                        class="
                        px-4 py-2 
                        bg-primary-500 
                        text-white 
                        rounded-md 
                        hover:bg-primary-600 
                        transition-colors 
                        flex items-center space-x-2
                    ">
                        <i class="fas fa-edit"></i>
                        <span>Modifier</span>
                    </button>
                </div>

                {{-- Profile Information --}}
                <div id="info-display" class="grid grid-cols-2 gap-4 p-6">
                    @php
                        $infoItems = [
                            ['label' => 'Nom d\'utilisateur', 'value' => $adminModel->username],
                            ['label' => 'CIN', 'value' => $adminModel->cin],
                            ['label' => 'Nom', 'value' => $adminModel->last_name],
                            ['label' => 'Prénom', 'value' => $adminModel->first_name],
                        ];
                    @endphp

                    @foreach ($infoItems as $item)
                        <div
                            class="
                        bg-gray-50 
                        dark:bg-gray-700 
                        p-4 
                        rounded-lg 
                        border 
                        border-gray-200 
                        dark:border-gray-600
                    ">
                            <div
                                class="
                            text-xs 
                            uppercase 
                            tracking-wide 
                            text-gray-500 
                            dark:text-gray-400 
                            mb-1
                        ">
                                {{ $item['label'] }}
                            </div>
                            <div
                                class="
                            text-lg 
                            font-semibold 
                            text-gray-800 
                            dark:text-gray-200
                        ">
                                {{ $item['value'] }}
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Edit Form --}}
                <form id="edit-form" action="{{ route('admin.update.profile') }}" method="POST" class="hidden p-6">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        @php
                            $formFields = [
                                ['name' => 'username', 'label' => 'Nom d\'utilisateur'],
                                ['name' => 'cin', 'label' => 'CIN'],
                                ['name' => 'last_name', 'label' => 'Nom'],
                                ['name' => 'first_name', 'label' => 'Prénom'],
                            ];
                        @endphp

                        @foreach ($formFields as $field)
                            <div>
                                <label for="{{ $field['name'] }}"
                                    class="
                                    block 
                                    text-sm 
                                    font-medium 
                                    text-gray-700 
                                    dark:text-gray-300 
                                    mb-2
                                ">
                                    {{ $field['label'] }}
                                </label>
                                <input type="text" name="{{ $field['name'] }}" id="{{ $field['name'] }}"
                                    value="{{ $adminModel->{$field['name']} }}"
                                    class="
                                    w-full 
                                    px-3 
                                    py-2 
                                    border 
                                    border-gray-300 
                                    dark:border-gray-600 
                                    rounded-md 
                                    shadow-sm 
                                    focus:outline-none 
                                    focus:ring-2 
                                    focus:ring-primary-500 
                                    dark:bg-gray-700 
                                    dark:text-gray-200
                                "
                                    required>
                            </div>
                        @endforeach

                        <div class="col-span-2 flex space-x-4 mt-4">
                            <button type="submit"
                                class="
                                w-full 
                                bg-primary-500 
                                text-white 
                                py-2 
                                rounded-md 
                                hover:bg-primary-600 
                                transition-colors
                                flex 
                                items-center 
                                justify-center 
                                space-x-2
                            ">
                                <i class="fas fa-check"></i>
                                <span>Enregistrer</span>
                            </button>
                            <button type="button" id="cancel-edit-btn"
                                class="
                                w-full 
                                bg-gray-200 
                                dark:bg-gray-600 
                                text-gray-700 
                                dark:text-gray-300 
                                py-2 
                                rounded-md 
                                hover:bg-gray-300 
                                dark:hover:bg-gray-500 
                                transition-colors
                                flex 
                                items-center 
                                justify-center 
                                space-x-2
                            ">
                                <i class="fas fa-times"></i>
                                <span>Annuler</span>
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Password Change Section --}}
                <div
                    class="
                border-t 
                border-gray-200 
                dark:border-gray-700 
                p-6
            ">
                    <h2
                        class="
                    text-xl 
                    font-semibold 
                    text-gray-800 
                    dark:text-gray-200 
                    mb-4 
                    flex 
                    items-center 
                    space-x-2
                ">
                        <i class="fas fa-key"></i>
                        <span>Changer le mot de passe</span>
                    </h2>

                    <form action="{{ route('admin.change.password') }}" method="POST" class="space-y-4">
                        @csrf
                        @php
                            $passwordFields = [
                                ['name' => 'currentPassword', 'label' => 'Mot de passe actuel'],
                                ['name' => 'newPassword', 'label' => 'Nouveau mot de passe'],
                                ['name' => 'confirmPassword', 'label' => 'Confirmer le nouveau mot de passe'],
                            ];
                        @endphp

                        @foreach ($passwordFields as $field)
                            <div class="relative">
                                <label for="{{ $field['name'] }}"
                                    class="
                                    block 
                                    text-sm 
                                    font-medium 
                                    text-gray-700 
                                    dark:text-gray-300 
                                    mb-2
                                ">
                                    {{ $field['label'] }}
                                </label>
                                <input type="password" name="{{ $field['name'] }}" id="{{ $field['name'] }}"
                                    class="
                                    w-full 
                                    px-3 
                                    py-2 
                                    border 
                                    border-gray-300 
                                    dark:border-gray-600 
                                    rounded-md 
                                    shadow-sm 
                                    focus:outline-none 
                                    focus:ring-2 
                                    focus:ring-primary-500 
                                    dark:bg-gray-700 
                                    dark:text-gray-200
                                "
                                    required>
                                <button type="button" class="password-toggle-btn" data-target="{{ $field['name'] }}"
                                    class="
                                    absolute 
                                    right-3 
                                    top-10 
                                    text-gray-500 
                                    dark:text-gray-400
                                ">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        @endforeach

                        <button type="submit"
                            class="
                            w-full 
                            bg-primary-500 
                            text-white 
                            py-2 
                            rounded-md 
                            hover:bg-primary-600 
                            transition-colors
                            flex 
                            items-center 
                            justify-center 
                            space-x-2
                        ">
                            <i class="fas fa-key"></i>
                            <span>Modifier le mot de passe</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Execute when the DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Get references to elements
            const editToggleBtn = document.getElementById('edit-toggle-btn');
            const cancelEditBtn = document.getElementById('cancel-edit-btn');
            const infoDisplay = document.getElementById('info-display');
            const editForm = document.getElementById('edit-form');
            const passwordToggleBtns = document.querySelectorAll('.password-toggle-btn');

            // Toggle between view and edit modes
            function toggleEdit() {
                infoDisplay.classList.toggle('hidden');
                editForm.classList.toggle('hidden');

                // Scroll to the form if it's now visible
                if (!editForm.classList.contains('hidden')) {
                    editForm.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }

            // Add click event listeners
            if (editToggleBtn) {
                editToggleBtn.addEventListener('click', toggleEdit);
            }

            if (cancelEditBtn) {
                cancelEditBtn.addEventListener('click', toggleEdit);
            }

            // Toggle password visibility
            passwordToggleBtns.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const inputId = this.getAttribute('data-target');
                    const input = document.getElementById(inputId);
                    const eyeIcon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        eyeIcon.classList.remove('fa-eye');
                        eyeIcon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        eyeIcon.classList.remove('fa-eye-slash');
                        eyeIcon.classList.add('fa-eye');
                    }
                });
            });
        });
    </script>
@endsection
