<div class="fixed z-40 inset-0 overflow-y-auto hidden" id="create-modal">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div>
                <div class="mt-3 text-center sm:mt-0 sm:my-4 sm:mx-4 sm:text-left">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100" id="modal-title">{{ __('rental-ads.create_new_ad') }}</h3>
                    <div class="mt-2">
                        <form id="create-form" enctype="multipart/form-data" method="POST" action="{{ route('rental-ads.store') }}">
                            @csrf
                            <div class="mt-4">
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('rental-ads.title') }}</label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md @error('title') border-red-500 @enderror">
                                @error('title')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('rental-ads.description') }}</label>
                                <textarea name="description" id="description" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('rental-ads.price') }}</label>
                                <input type="number" name="price" id="price" value="{{ old('price') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md @error('price') border-red-500 @enderror">
                                @error('price')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="street" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('auth.Street') }}</label>
                                    <input type="text" name="street" id="street" value="{{ old('street') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md @error('street') border-red-500 @enderror">
                                    @error('street')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label for="house_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('auth.HouseNumber') }}</label>
                                    <input type="text" name="house_number" id="house_number" value="{{ old('house_number') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md @error('house_number') border-red-500 @enderror">
                                    @error('house_number')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('auth.City') }}</label>
                                    <input type="text" name="city" id="city" value="{{ old('city') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md @error('city') border-red-500 @enderror">
                                    @error('city')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <label for="zip_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('auth.ZipCode') }}</label>
                                    <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code') }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md @error('zip_code') border-red-500 @enderror">
                                    @error('zip_code')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <label for="image" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('rental-ads.image') }}</label>
                                <div class="relative">
                                    <x-secondary-button type="button" onclick="document.getElementById('image').click()">
                                        <span id="file-name">{{ __('Action.ChooseFile') }}</span>
                                    </x-secondary-button>
                                    <input type="file" id="image" name="image" class="sr-only" onchange="updateFileName(this)">
                                </div>
                                @error('image')
                                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                                <button type="submit" id="submit-btn" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto">
                                    {{ __('action.Create') }}
                                </button>

                                <button type="button" id="close-btn" class="mt-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto">
                                    {{ __('action.Cancel') }}
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    //if there are any errors show the modal
    @if($errors->any())
        document.getElementById('create-modal').classList.remove('hidden');
    @endif


    document.getElementById('close-btn').addEventListener('click', function () {
        closeModal();
    });

    function closeModal() {
        document.getElementById('create-modal').classList.add('hidden');
    }
</script>

