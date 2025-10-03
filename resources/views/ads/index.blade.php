<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('ads.ads') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Toolbar: CSV upload + Create Ad --}}
            <div class="bg-white border border-slate-200 shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                        {{-- Upload CSV --}}
                        <div class="flex items-center gap-3">
                            <form method="POST" action="{{ route('ads.upload-csv') }}" enctype="multipart/form-data" id="csv-upload-form">
                                @csrf
                                <input type="file" name="csv_file" class="hidden" id="csv-file-input" onchange="submitCsvForm()">
                                <label for="csv-file-input"
                                       class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm text-slate-800 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    {{ __('ads.upload_csv') }}
                                </label>
                            </form>
                        </div>

                        {{-- Create Ad --}}
                        <button
                            class="create-button inline-flex items-center gap-2 rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-300"
                            type="button"
                            onclick="openCreateModal()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('ads.create_ad') }}
                        </button>
                    </div>
                </div>
            </div>

            {{-- Filters & Sort --}}
            <div class="bg-white border border-slate-200 shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form id="filter-sort-form" action="{{ route('ads.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 sm:items-center">
                        <div class="flex items-center gap-3">
                            <label for="filter" class="text-sm text-slate-700">{{ __('ads.filter') }}</label>
                            <select
                                name="filter" id="filter"
                                class="rounded-lg border-slate-300 bg-white text-slate-900 text-sm focus:border-slate-400 focus:ring-slate-400">
                                <option value="2" {{ request('filter') == '2' ? 'selected' : '' }}>{{ __('ads.all_ads') }}</option>
                                <option value="0" {{ request('filter') == '0' ? 'selected' : '' }}>{{ __('ads.normal_ads') }}</option>
                                <option value="1" {{ request('filter') == '1' ? 'selected' : '' }}>{{ __('ads.rental_ads') }}</option>
                            </select>
                        </div>

                        <div class="flex items-center gap-3">
                            <label for="sort" class="text-sm text-slate-700">{{ __('ads.sort') }}</label>
                            <select
                                name="sort" id="sort"
                                class="rounded-lg border-slate-300 bg-white text-slate-900 text-sm focus:border-slate-400 focus:ring-slate-400">
                                <option value="price_asc"  {{ request('sort') == 'price_asc'  ? 'selected' : '' }}>{{ __('ads.sort_by_price_asc') }}</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>{{ __('ads.sort_by_price_desc') }}</option>
                                <option value="date_desc"  {{ request('sort') == 'date_desc'  ? 'selected' : '' }}>{{ __('ads.sort_by_date_desc') }}</option>
                                <option value="date_asc"   {{ request('sort') == 'date_asc'   ? 'selected' : '' }}>{{ __('ads.sort_by_date_asc') }}</option>
                            </select>
                        </div>

                        <button type="submit"
                                class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm text-slate-800 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-300">
                            {{ __('ads.apply') }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- Ad list --}}
            <div class="bg-white border border-slate-200 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @include('ads.partials.ad-list', ['ads' => $ads, 'shouldPaginate' => true])
                </div>
            </div>
        </div>
    </div>

    @include('ads.partials.create-modal')

    <script>
        function submitCsvForm() {
            document.getElementById('csv-upload-form').submit();
        }
        document.querySelector('.create-button')?.addEventListener('click', openCreateModal);
    </script>
</x-app-layout>
