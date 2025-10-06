<div class="fixed inset-0 z-[70] overflow-y-auto hidden" id="create-modal" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="flex min-h-screen items-end justify-center pt-4 px-4 pb-20 text-center sm:block sm:p-0">

        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 transition-opacity" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Panel -->
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <div class="mt-3 text-center sm:mt-0 sm:my-4 sm:mx-4 sm:text-left">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">{{ __('ads.create_new_ad') }}</h3>

                    <div class="mt-2">
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                                <strong class="font-semibold">{{ __('ads.error') }}:</strong>
                                <ul class="list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form id="ad-create-form" method="POST" action="{{ route('ads.store') }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <!-- Type (maps to is_rental boolean) -->
                            <div>
                                <label for="is_rental" class="block text-sm font-medium text-gray-700">{{ __('ads.type') }}</label>
                                <select id="is_rental" name="is_rental" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                    <option value="0">{{ __('ads.normal_ad') }}</option>
                                    <option value="1">{{ __('ads.rental_ad') }}</option>
                                </select>
                            </div>

                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700">{{ __('ads.title') }}</label>
                                <input id="title" name="title" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">{{ __('ads.description') }}</label>
                                <textarea id="description" name="description" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">{{ __('ads.price') }}</label>
                                <input id="price" name="price" type="number" step="0.01" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                            </div>

                            <!-- Address -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="street" class="block text-sm font-medium text-gray-700">{{ __('ads.street') }}</label>
                                    <input id="street" name="street" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label for="house_number" class="block text-sm font-medium text-gray-700">{{ __('ads.house_number') }}</label>
                                    <input id="house_number" name="house_number" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700">{{ __('ads.city') }}</label>
                                    <input id="city" name="city" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label for="zip_code" class="block text-sm font-medium text-gray-700">{{ __('ads.zip_code') }}</label>
                                    <input id="zip_code" name="zip_code" type="text" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                            </div>

                            <!-- Image -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">{{ __('ads.image') }}</label>
                                <div class="mt-1 flex items-center gap-3">
                                    <label class="inline-flex items-center gap-2 rounded-md border border-gray-300 px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.5v15m7.5-7.5h-15"/>
                                        </svg>
                                        <span>{{ __('ads.choose_file') }}</span>
                                        <input type="file" name="image" class="hidden" accept="image/*" onchange="updateFileName(this)" />
                                    </label>
                                    <span id="file-name" class="text-sm text-slate-500"></span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="mt-5 sm:mt-6 flex justify-end gap-2">
                                <button type="button" onclick="closeModal()" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    {{ __('ads.cancel') }}
                                </button>
                                <button type="submit" class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">
                                    {{ __('ads.submit_review') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Close (X) -->
            <button type="button" class="absolute right-4 top-4 rounded-md p-1 text-slate-500 hover:text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500" onclick="closeModal()" aria-label="Close">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeModal();
        });
        document.getElementById('create-modal').addEventListener('click', function (e) {
            if (e.target === this) closeModal();
        });
        function closeModal() {
            document.getElementById('create-modal').classList.add('hidden');
            document.documentElement.classList.remove('overflow-hidden');
        }
        function openCreateModal() {
            document.getElementById('create-modal').classList.remove('hidden');
            document.documentElement.classList.add('overflow-hidden');
        }
        function updateFileName(input) {
            if (input.files && input.files[0]) {
                document.getElementById('file-name').innerText = input.files[0].name;
            }
        }
    </script>
</div>
