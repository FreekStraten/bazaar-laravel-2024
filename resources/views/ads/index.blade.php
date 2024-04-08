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

                            <button class="create-button inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150" onclick="openCreateModal()">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ __('ads.create_ad') }}
                            </button>
                        </div>
                    </div>

                    <div>
                        <form id="filter-sort-form" action="{{ route('ads.index') }}" method="GET">
                            <div class="my-4">
                                <select name="filter" id="filter" class="control mr-2 inline-flex items-center pr-6 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-25 transition ease-in-out duration-150 w-max">
                                    <option value="2" {{ request()->input('filter') == '2' ? 'selected' : '' }}>{{ __('ads.all_ads') }}</option>
                                    <option value="0" {{ request()->input('filter') == '0' ? 'selected' : '' }}>{{ __('ads.normal_ads') }}</option>
                                    <option value="1" {{ request()->input('filter') == '1' ? 'selected' : '' }}>{{ __('ads.rental_ads') }}</option>
                                </select>

                                <select name="sort" id="sort" class="form-control mr-2 inline-flex items-center pr-6 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-25 transition ease-in-out duration-150 w-max">
                                    <option value="price_asc" {{ request()->input('sort') == 'price_asc' ? 'selected' : '' }}>{{ __('ads.sort_by_price_asc') }}</option>
                                    <option value="price_desc" {{ request()->input('sort') == 'price_desc' ? 'selected' : '' }}>{{ __('ads.sort_by_price_desc') }}</option>
                                    <option value="date_desc" {{ request()->input('sort') == 'date_desc' ? 'selected' : '' }}>{{ __('ads.sort_by_date_desc') }}</option>
                                    <option value="date_asc" {{ request()->input('sort') == 'date_asc' ? 'selected' : '' }}>{{ __('ads.sort_by_date_asc') }}</option>
                                </select>

                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                    {{ __('ads.apply') }}
                                </button>
                            </div>
                        </form>


                        @include('ads.partials.ad-list', ['ads' => $ads, 'shouldPaginate' => true])
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
    </script>
</x-app-layout>
