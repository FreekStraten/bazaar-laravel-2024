@php
    // Helpers per card
    $displayTitle = function($title) {
        return preg_replace('/^\s*Te\s*huur:\s*/i', '', $title);
    };
@endphp

@if ($ads->count() === 0)
    <div class="rounded-xl border border-slate-200 bg-slate-50 p-6 text-slate-700">
        {{ __('ads.no_ads_found') }}
    </div>
@else
    <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach ($ads as $ad)
            @php
                $isRental = (bool) $ad->is_rental;
                $badgeText = $isRental ? __('Te huur') : __('Te koop');
                $badgeClasses = $isRental
                    ? 'bg-amber-500 text-white'
                    : 'bg-emerald-600 text-white';
            @endphp

            <li
                class="group relative bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition"
                onclick="window.location='{{ route('ads.show', $ad->id) }}'">

                {{-- Image + BADGE --}}
                <div class="relative">
                    <div class="aspect-[4/3] bg-slate-100 rounded-xl overflow-hidden">
                        <img
                            src="{{ $ad->cover_url }}"
                            alt="{{ $ad->title }}"
                            class="h-full w-full object-cover"
                            width="640" height="480"
                            loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                            decoding="async"
                            fetchpriority="{{ $loop->first ? 'high' : 'auto' }}"
                        >
                    </div>

                    {{-- Top-left badge --}}
                    <div class="absolute left-2 top-2">
                        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium shadow-sm {{ $badgeClasses }}">
                            @if($isRental)
                                {{-- calendar/clock-ish icon for rental --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2z"/>
                              </svg>
                            @else
                                {{-- cart icon for sale --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l1.6-8H6.4M7 13L6 6M7 13l-2 9m12-9l-2 9M9 22a1 1 0 1 0 0-2 1 1 0 0 0 0 2zm8 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2z"/>
                              </svg>
                            @endif
                            {{ $badgeText }}
                        </span>
                    </div>

                    {{-- Quick actions --}}
                    <div class="absolute top-2 right-2 flex gap-2">
                        <a href="{{ route('ads.qr', $ad->id) }}"
                           onclick="event.stopPropagation();"
                           class="inline-flex items-center justify-center rounded-full bg-white/95 border border-slate-300 p-2 text-slate-700 hover:bg-slate-50"
                           title="{{ __('ads.qr') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.5" class="h-5 w-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M3.75 4.5h4.5v4.5h-4.5V4.5zM15.75 4.5h4.5v4.5h-4.5V4.5zM3.75 15h4.5v4.5h-4.5V15zM15.75 15H18M19.5 15H21M15.75 18v1.5M18 19.5H21"/>
                            </svg>
                        </a>

                        <form method="POST" action="{{ route('favorites.toggle', $ad->id) }}"
                              onclick="event.stopPropagation();" class="inline-block">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center justify-center rounded-full bg-white/95 border border-slate-300 p-2 text-slate-700 hover:bg-slate-50"
                                    title="{{ __('ads.favorite') }}">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 20.727S3.273 16.7 3.273 9.45A5.45 5.45 0 0112 5.273 5.45 5.45 0 0120.727 9.45C20.727 16.7 12 20.727 12 20.727z"
                                          stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Body --}}
                <div class="p-4 space-y-2">
                    <h3 class="font-semibold text-slate-900 line-clamp-1">
                        {{ $displayTitle($ad->title) }}
                    </h3>

                    <div class="text-sm">
                        <span class="font-medium text-slate-900">
                          €{{ number_format((float)$ad->price, 2) }}
                        </span>
                    </div>

                    <p class="text-sm text-slate-600 line-clamp-2">
                        {{ $ad->description }}
                    </p>

                    <p class="text-xs text-slate-500 line-clamp-1">
                        @php $addr = $ad->address; @endphp
                        {{ $addr?->city ? $addr->city : '' }}
                        @if(($addr?->city) && $ad->user?->name) • @endif
                        {{ $ad->user?->name ?? '' }}
                    </p>

                    <a href="{{ route('ads.show', $ad->id) }}"
                       onclick="event.stopPropagation();"
                       class="mt-2 inline-flex items-center gap-2 text-sm font-medium text-emerald-700 hover:text-emerald-800">
                        {{ __('ads.view') }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                             viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </li>
        @endforeach
    </ul>

    @if(!empty($shouldPaginate))
        <div class="mt-6">
            {!! $ads->appends(['filter'=>request('filter'), 'sort'=>request('sort')])->render() !!}
        </div>
    @endif
@endif
