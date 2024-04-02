<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ads.homepage') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{ __('ads.recent_ads') }}</h3>

                    <form id="filter-sort-form" action="{{ route('ads.index') }}" method="GET">
                        <div class="my-4">
                            <select name="sort" id="sort" class="form-control mr-2 inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-25 transition ease-in-out duration-150">
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

                    @include('ads.partials.ad-list')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

