<!-- Delete Confirmation Modal -->
<div id="deleteConfirmationModal"
    class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-6">
            <div class="text-center">
                <svg class="mx-auto mb-4 w-14 h-14 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-2">Delete Student</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Are you sure you want to delete this student? This
                    action cannot be undone.</p>
            </div>
            <div class="flex justify-center space-x-4">
                <button id="cancelDeleteBtn"
                    class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-md transition-colors">
                    Cancel
                </button>
                <button id="confirmDeleteBtn"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Success Toast Notification -->
<div id="successToast" class="hidden fixed top-4 right-4 z-50 transform transition-all duration-300 translate-x-full">
    <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center">
        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span id="successToastMessage"></span>
    </div>
</div>

<script>
    // ... existing code ...

    // Handle student deletion with AJAX
    const deleteButtons = document.querySelectorAll('.delete-student-btn');
    const deleteModal = document.getElementById('deleteConfirmationModal');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const successToast = document.getElementById('successToast');
    let studentToDelete = null;

    function showSuccessToast(message) {
        const toast = document.getElementById('successToast');
        const toastMessage = document.getElementById('successToastMessage');
        toastMessage.textContent = message;
        toast.classList.remove('hidden', 'translate-x-full');
        toast.classList.add('translate-x-0');

        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 300);
        }, 3000);
    }

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            studentToDelete = this;
            deleteModal.classList.remove('hidden');
        });
    });

    cancelDeleteBtn.addEventListener('click', () => {
        deleteModal.classList.add('hidden');
        studentToDelete = null;
    });

    deleteModal.addEventListener('click', (e) => {
        if (e.target === deleteModal) {
            deleteModal.classList.add('hidden');
            studentToDelete = null;
        }
    });

    confirmDeleteBtn.addEventListener('click', function() {
        if (!studentToDelete) return;

        const cin = studentToDelete.getAttribute('data-cin');
        const originalContent = studentToDelete.innerHTML;

        // Show loading state
        studentToDelete.innerHTML =
            '<svg class="animate-spin h-4 w-4 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
        studentToDelete.disabled = true;

        // Create form data
        const formData = new FormData();
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'DELETE');

        // Hide the modal
        deleteModal.classList.add('hidden');

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
                    // Remove the row with animation
                    const row = studentToDelete.closest('tr');
                    row.style.transition = 'all 0.5s';
                    row.style.backgroundColor = '#FEE2E2';
                    row.style.opacity = '0';
                    setTimeout(() => {
                        row.remove();
                    }, 500);

                    // Show success toast
                    showSuccessToast(data.message);
                } else {
                    // Show error message and restore button
                    alert(data.message || 'Error deleting student');
                    studentToDelete.innerHTML = originalContent;
                    studentToDelete.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the student. Please try again.');
                studentToDelete.innerHTML = originalContent;
                studentToDelete.disabled = false;
            })
            .finally(() => {
                studentToDelete = null;
            });
    });
    // ... existing code ...
</script>
@endsection
