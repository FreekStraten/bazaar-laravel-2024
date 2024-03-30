<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ads.ads') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex space-x-4">
                            <button
                                class="underline tab-button inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                                data-tab="normal-ads">
                                {{ __('ads.normal_ads') }}
                            </button>
                            <button
                                class="tab-button inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                                data-tab="rental-ads">
                                {{ __('ads.rental_ads') }}
                            </button>
                        </div>


                        <div class="flex space-x-4">
                            @if(auth()->user()->user_type === 'business' || auth()->user()->user_type === 'admin')
                                <div class="flex items-center space-x-4">
                                    <form method="POST" action="{{ route('ads.upload-csv') }}"
                                          enctype="multipart/form-data" id="csv-upload-form">
                                        @csrf
                                        <input type="file" name="csv_file" class="hidden" id="csv-file-input"
                                               onchange="submitCsvForm()">
                                        <label for="csv-file-input"
                                               class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                            {{ __('ads.upload_csv') }}
                                        </label>
                                    </form>
                                </div>
                            @endif

                            <button
                                class="create-button inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                                onclick="openCreateModal()">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ __('ads.create_ad') }}
                            </button>
                        </div>

                    </div>

                    <div id="normal-ads" class="tab-content">
                        @include('ads.partials.ad-list', ['ads' => $normalAds])
                    </div>

                    <div id="rental-ads" class="tab-content" style="display:none;">
                        @include('ads.partials.ad-list', ['ads' => $rentalAds])
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('ads.partials.create-modal')

    <script>
        function submitCsvForm() {
            document.getElementById('csv-upload-form').submit();
        }



        document.querySelector('.create-button').addEventListener('click', openCreateModal);

        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach((button, index) => {
            button.addEventListener('click', () => {
                const tabId = button.dataset.tab;
                tabContents.forEach(content => content.style.display = 'none');
                document.getElementById(tabId).style.display = 'block';
                tabButtons.forEach(button => button.classList.remove('underline'));
                button.classList.add('underline');
            });
        });
    </script>
</x-app-layout>
