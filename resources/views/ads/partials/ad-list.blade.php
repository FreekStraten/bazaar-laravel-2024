@php
    $isFavorited = fn($ad) => auth()->user()
        ? auth()->user()->AdFavorites()->where('ad_id', $ad->id)->exists()
        : false;

    $cover = function($ad) {
        $candidate = $ad->cover_url
            ?? ($ad->image ? asset('ads-images/'.$ad->image) : null);
        $localPlaceholder = asset('images/placeholder-ad.svg');
        return $candidate ?: $localPlaceholder;
    };
@endphp

@if ($ads->count() === 0)
    <div class="rounded-xl border border-slate-200 bg-slate-50 p-6 text-slate-700">
        {{ __('ads.no_ads_found') }}
    </div>
@else
    <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach ($ads as $ad)
            <li
                class="group relative bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition"
                onclick="window.location='{{ route('ads.show', $ad->id) }}'">

                {{-- Image --}}
                <div class="relative">
                    @php $img = $cover($ad); @endphp
                    <div class="aspect-[4/3] bg-slate-100 rounded-xl overflow-hidden">
                        <img
                            src="{{ $ad->image_url }}"
                            alt="{{ $ad->title }}"
                            class="h-full w-full object-cover"
                            loading="lazy"
                            decoding="async"
                        >
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
                        {{ $ad->title }}
                    </h3>

                    <div class="text-sm">
                        <span class="font-medium text-slate-900">€{{ number_format((float)$ad->price, 2) }}</span>
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
