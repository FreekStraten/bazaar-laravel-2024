<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ads') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex space-x-4">
                            <button
                                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                                data-tab="rental-ads">
                                {{ __('Rental Ads') }}
                            </button>
                            <button
                                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                                data-tab="normal-ads">
                                {{ __('Normal Ads') }}
                            </button>
                        </div>
                        <button
                            class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                            onclick="openCreateModal()">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('Create Ad') }}
                        </button>
                    </div>

                    <div id="rental-ads" class="tab-content">
                        @include('ads.rental-ads.partials.ad-list')
                    </div>

                    <div id="normal-ads" class="tab-content" style="display:none;">

                        {{-- add normal adds should be here --}}

                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('ads.rental-ads.create-modal')
    {{--    @include('normal-ads.create-modal')--}}

    <script>
        function openCreateModal() {
            document.getElementById('create-modal').classList.remove('hidden');
        }

        const rentalAdsTab = document.querySelector('[data-tab="rental-ads"]');
        const normalAdsTab = document.querySelector('[data-tab="normal-ads"]');


        //add on click event to rental ads tab
        rentalAdsTab.addEventListener('click', function () {
            document.getElementById('rental-ads').style.display = 'block';
            document.getElementById('normal-ads').style.display = 'none';
        });

        //add on click event to normal ads tab
        normalAdsTab.addEventListener('click', function () {
            document.getElementById('rental-ads').style.display = 'none';
            document.getElementById('normal-ads').style.display = 'block';
        });
    </script>

</x-app-layout>
