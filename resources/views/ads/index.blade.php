<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-slate-900">{{ __('ads.ads') }}</h1>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Actions --}}
            <div class="mb-4">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <form action="{{ route('ads.upload-csv') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                            @csrf
                            <label class="inline-flex items-center gap-2 rounded-md border border-slate-300 bg-white px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-50 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M7.5 12L12 16.5m0 0L16.5 12M12 16.5V3"/>
                                </svg>
                                <span>{{ __('ads.upload_csv') }}</span>
                                <input type="file" name="csv" class="hidden" accept=".csv" />
                            </label>

                            <button type="button"
                                    onclick="openCreateModal()"
                                    class="inline-flex items-center rounded-md bg-emerald-600 px-3 py-2 text-sm font-medium text-white hover:bg-emerald-700">
                                {{ __('ads.create_ad') }}
                            </button>
                        </form>
                    </div>

                    <p class="text-sm text-slate-500">
                        {{ __('ads.upload_csv_help') }}
                    </p>
                </div>
            </div>

            {{-- Filter / Sort / Apply --}}
            <div class="mb-6">
                <div class="rounded-xl border border-slate-200 bg-white p-3 sm:p-4">
                    <form method="GET" action="{{ route('ads.index') }}" class="flex flex-wrap items-center gap-3">
                        <label class="text-sm text-slate-600">{{ __('ads.filter') }}</label>
                        <select name="filter" class="rounded-md border-slate-300 text-sm">
                            <option value="all" @selected(request('filter')==='all' || request('filter')===null)>{{ __('ads.all_ads') }}</option>
                            <option value="rentals" @selected(request('filter')==='rentals')>{{ __('ads.rental_ads') }}</option>
                            <option value="sales" @selected(request('filter')==='sales')>{{ __('ads.normal_ads') }}</option>
                        </select>

                        <label class="ml-2 text-sm text-slate-600">{{ __('ads.sort') }}</label>
                        <select name="sort" class="rounded-md border-slate-300 text-sm">
                            <option value="date_desc"  @selected(request('sort')==='date_desc' || request('sort')===null)>{{ __('ads.sort_by_date_desc') }}</option>
                            <option value="date_asc"   @selected(request('sort')==='date_asc')>{{ __('ads.sort_by_date_asc') }}</option>
                            <option value="price_asc"  @selected(request('sort')==='price_asc')>{{ __('ads.sort_by_price_asc') }}</option>
                            <option value="price_desc" @selected(request('sort')==='price_desc')>{{ __('ads.sort_by_price_desc') }}</option>
                        </select>

                        <button class="ml-auto inline-flex items-center rounded-md bg-slate-900 px-3 py-2 text-sm font-medium text-white hover:bg-slate-800">
                            {{ __('ads.apply') }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- Grid met cards --}}
            <div class="bg-white border border-slate-200 shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6">
                    @include('ads.partials.ad-list', [
                        'ads' => $ads,
                        'shouldPaginate' => true
                    ])
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
