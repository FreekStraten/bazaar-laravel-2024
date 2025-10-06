<x-app-layout>


    {{-- HERO (clean, no grid) --}}
    <section class="relative overflow-hidden -mt-6"> {{-- cancels <main class="pt-6"> --}}
        {{-- green wash that starts right under header and fades down --}}
        <div class="pointer-events-none absolute inset-x-0 -z-10">
            <div class="h-[240px] sm:h-[280px] bg-gradient-to-b from-emerald-100/65 to-transparent"></div>
            <div class="absolute inset-0 bg-[radial-gradient(60%_40%_at_75%_28%,rgba(16,185,129,0.16),transparent_70%)]"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 pb-8 sm:pb-10">
            <div class="max-w-2xl">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/80 px-3 py-1 text-xs font-medium text-emerald-700 ring-1 ring-emerald-200">
                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2z"/>
                </svg>
                {{ __('hero.badge') }}
            </span>

                <h2 class="mt-3 text-3xl sm:text-4xl font-bold tracking-tight text-slate-900">
                    {{ __('hero.title') }}
                </h2>
                <p class="mt-3 text-slate-600 max-w-prose">
                    {{ __('hero.subtitle') }}
                </p>

                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="{{ route('ads.index') }}"
                       class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-2.5 text-white font-medium shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
                        {{ __('hero.cta_browse') }}
                    </a>
                    <a href="{{ route('ads.index', ['filter' => 'rentals']) }}"
                       class="inline-flex items-center rounded-lg bg-white px-4 py-2.5 text-slate-800 font-medium ring-1 ring-slate-200 hover:bg-slate-50">
                        {{ __('hero.cta_rentals') }}
                    </a>
                    <a href="{{ route('ads.index', ['filter' => 'sales']) }}"
                       class="inline-flex items-center rounded-lg bg-white px-4 py-2.5 text-slate-800 font-medium ring-1 ring-slate-200 hover:bg-slate-50">
                        {{ __('hero.cta_sales') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- smooth blend into content below --}}
        <div class="h-6 bg-gradient-to-b from-transparent to-white/90"></div>
    </section>

    {{-- RECENT ADS --}}
    <div class="pb-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <section class="bg-white border border-slate-200 shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6">

                    {{-- Header met "Just in" pill + klok-icoon --}}
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 ring-1 ring-emerald-200">
                                <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                                </svg>
                                Just in
                            </span>
                            <h2 class="text-lg font-semibold text-slate-900">{{ __('ads.recent_ads') }}</h2>
                        </div>
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
