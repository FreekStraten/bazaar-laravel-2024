<div class="fixed z-10 inset-0 overflow-y-auto hidden" id="image-modal">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full relative">
            <img id="modal-image" src="" alt="Rental Ad Image" class="max-h-[80vh] object-contain">
            <button type="button" class="absolute top-4 right-4 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center focus:outline-none" onclick="closeImageModal()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
    function closeImageModal() {
        const imageModal = document.getElementById('image-modal');
        imageModal.classList.add('hidden');
    }

    function openImageModal(imageUrl) {
        const imageModal = document.getElementById('image-modal');
        const modalImage = document.getElementById('modal-image');

        //log the imageUrl to the console
        console.log(imageUrl);

        modalImage.src = imageUrl;
        imageModal.classList.remove('hidden');
    }
</script>

