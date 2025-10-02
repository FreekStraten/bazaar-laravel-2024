<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ads.recent_ads') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex items-center justify-between">
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('ads.results') }}: {{ $ads->total() ?? count($ads) }}
                </div>

                <form action="{{ route('ads.index') }}" method="GET">
                    <label for="sort" class="sr-only">{{ __('ads.sort') }}</label>
                    <select id="sort" name="sort"
                            class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 text-sm"
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

            <div class="grid gap-6
                        grid-cols-1
                        sm:grid-cols-2
                        lg:grid-cols-3
                        xl:grid-cols-4">
                @foreach ($ads as $ad)
                    <x-ads.ad-card :ad="$ad" />
                @endforeach
            </div>

            @if(method_exists($ads, 'links'))
                <div class="mt-8">
                    {{ $ads->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
