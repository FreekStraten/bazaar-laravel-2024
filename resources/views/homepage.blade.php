<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-slate-900">{{ __('ads.homepage') }}</h1>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <section class="bg-white border border-slate-200 shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-lg font-semibold text-slate-900">{{ __('ads.recent_ads') }}</h2>
                        <a href="{{ route('ads.index') }}" class="text-sm font-medium text-emerald-700 hover:text-emerald-800">
                            {{ __('ads.view_all') }}
                        </a>
                    </div>

                    @include('ads.partials.ad-list', [
                        'ads' => $latestAds ?? collect(),
                        'shouldPaginate' => false
                    ])
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
