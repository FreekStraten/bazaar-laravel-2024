<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('ads.recent_ads') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex items-center justify-between">
                <div class="text-sm text-slate-500">
                    {{ __('ads.results') }}: {{ $ads->total() ?? count($ads) }}
                </div>

                <form action="{{ route('ads.index') }}" method="GET" class="flex items-center gap-2">
                    <label for="sort" class="sr-only">{{ __('ads.sort') }}</label>
                    <select id="sort" name="sort"
                            class="rounded-md border border-slate-300 bg-white text-slate-800 text-sm px-2 py-1"
                            onchange="this.form.submit()">
                        <option value="date_desc" {{ request('sort')==='date_desc' ? 'selected' : '' }}>
                            {{ __('ads.sort_by_date_desc') }}
                        </option>
                        <option value="date_asc" {{ request('sort')==='date_asc' ? 'selected' : '' }}>
                            {{ __('ads.sort_by_date_asc') }}
                        </option>
                        <option value="price_asc" {{ request('sort')==='price_asc' ? 'selected' : '' }}>
                            {{ __('ads.sort_by_price_asc') }}
                        </option>
                        <option value="price_desc" {{ request('sort')==='price_desc' ? 'selected' : '' }}>
                            {{ __('ads.sort_by_price_desc') }}
                        </option>
                    </select>
                </form>
            </div>

            @include('ads.partials.ad-list', ['ads' => $ads, 'shouldPaginate' => true])
        </div>
    </div>
</x-app-layout>
