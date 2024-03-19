<div class="fixed z-10 inset-0 overflow-y-auto hidden" id="create-modal">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div>
                <div class="mt-3 text-center sm:mt-0 sm:my-4 sm:mx-4 sm:text-left">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100" id="modal-title">{{ __('rental-ads.create_new_ad') }}</h3>
                    <div class="mt-2">
                        <form id="create-form" action="{{ route('rental-ads.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label for="title" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('rental-ads.title') }}</label>
                                <input type="text" id="title" name="title" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full" required>
                            </div>
                            <div class="mb-4">
                                <label for="description" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('rental-ads.description') }}</label>
                                <textarea id="description" name="description" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full" required></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="price" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('rental-ads.price') }}</label>
                                <input type="number" id="price" name="price" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full" required>
                            </div>


                            <div class="mb-4 grid grid-cols-2 gap-4">
                                <div>
                                    <label for="street" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('auth.Street') }}</label>
                                    <input type="text" id="street" name="street" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full" required>
                                </div>
                                <div>
                                    <label for="house_number" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('auth.HouseNumber') }}</label>
                                    <input type="text" id="house_number" name="house_number" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full" required>
                                </div>
                            </div>

                            <div class="mb-4 grid grid-cols-2 gap-4">
                                <div>
                                    <label for="city" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('auth.City') }}</label>
                                    <input type="text" id="city" name="city" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full" required>
                                </div>
                                <div>
                                    <label for="zip_code" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('auth.ZipCode') }}</label>
                                    <input type="text" id="zip_code" name="zip_code" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm block w-full" required>
                                </div>
                            </div>


                            <div class="mb-4">
                                <label for="image" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('rental-ads.image') }}</label>
                                <div class="relative">
                                    <x-secondary-button type="button" onclick="document.getElementById('image').click()">
                                        <span id="file-name">{{ __('Action.ChooseFile') }}</span>
                                    </x-secondary-button>
                                    <input type="file" id="image" name="image" class="sr-only" onchange="updateFileName(this)">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                <button type="button" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto" onclick="createRentalAd()">
                    {{ __('action.Create') }}
                </button>
                <button type="button" class="mt-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto" onclick="closeCreateModal()">
                    {{ __('action.Cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>
