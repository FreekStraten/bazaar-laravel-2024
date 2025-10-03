{{-- resources/views/ads/partials/ad-list.blade.php --}}
@php
    $isFavorited = fn($ad) => auth()->user()
        ? auth()->user()->AdFavorites()->where('ad_id', $ad->id)->exists()
        : false;
@endphp

@if ($ads->count() === 0)
    <div class="rounded-xl border border-slate-200 bg-slate-50 p-6 text-slate-700">
        {{ __('ads.no_results') ?? 'No ads found.' }}
    </div>
@else
    <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach ($ads as $ad)
            <li
                class="group relative bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition"
                onclick="window.location.href='{{ route('ads.show', $ad->id) }}'"
                style="cursor: pointer"
            >
                {{-- Image --}}
                <div class="relative">
                    @php
                        $img = $ad->image ? asset('ads-images/'.$ad->image) : null;
                    @endphp
                    <div class="aspect-[4/3] bg-slate-100">
                        <img src="{{ $ad->cover_url }}" alt="{{ $ad->title }}" class="h-full w-full object-cover" loading="lazy">
                    </div>

                    {{-- Quick actions: QR + Favorite --}}
                    <div class="absolute top-2 right-2 flex gap-2">
                        {{-- QR link --}}
                        <a
                            href="{{ route('ads.qr', $ad->id) }}"
                            onclick="event.stopPropagation();"
                            class="inline-flex items-center justify-center h-9 w-9 rounded-lg bg-white/90 border border-slate-200 shadow-sm hover:bg-white"
                            title="QR"
                            aria-label="QR"
                        >
                            {{-- QR icon (inline SVG) --}}
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                <path d="M4 4h6v6H4V4zM14 4h6v6h-6V4zM4 14h6v6H4v-6zM14 14h2v2h-2v-2zM18 14h2v2h-2v-2zM16 18h2v2h-2v-2zM20 18h2v2h-2v-2z" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                        </a>

                        {{-- Favorite toggle --}}
                        <form action="{{ route('ads.toggle-favorite', $ad->id) }}" method="POST" onclick="event.stopPropagation();">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center justify-center h-9 w-9 rounded-lg bg-white/90 border border-slate-200 shadow-sm hover:bg-white"
                                    title="{{ __('ads.favorite') }}"
                                    aria-label="{{ __('ads.favorite') }}">
                                @if ($isFavorited($ad))
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 21s-6.716-4.43-9.193-7.233C.64 11.371 1.21 8.4 3.514 6.91c1.92-1.27 4.38-.86 5.99.69L12 9.06l2.496-1.46c1.61-1.55 4.07-1.96 5.99-.69 2.304 1.49 2.874 4.46.707 6.857C18.716 16.57 12 21 12 21z"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                        <path d="M12 20.727S3.273 14.182 3.273 9.818A4.545 4.545 0 0 1 12 8.091a4.545 4.545 0 0 1 8.727 1.727c0 4.364-8.727 10.909-8.727 10.909Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                @endif
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Body --}}
                <div class="p-4 space-y-2">
                    <h3 class="font-semibold text-slate-900 line-clamp-1">
                        {{ $ad->title }}
                    </h3>

                    {{-- Price --}}
                    <div class="text-sm">
                        <span class="font-medium text-slate-900">€{{ number_format((float)$ad->price, 2) }}</span>
                    </div>

                    {{-- Description --}}
                    <p class="text-sm text-slate-600 line-clamp-2">
                        {{ $ad->description }}
                    </p>

                    {{-- Address --}}
                    <p class="text-xs text-slate-500 line-clamp-1">
                        @php $addr = $ad->address; @endphp
                        @if($addr)
                            {{ $addr->street }} {{ $addr->house_number }}, {{ $addr->city }} {{ $addr->zip_code }}
                        @endif
                    </p>
                </div>

                {{-- Footer --}}
                <div class="px-4 pb-4">
                    @if($ad->user && $ad->user->id === auth()->id())
                        <span class="inline-flex items-center rounded-md bg-slate-100 px-2 py-1 text-xs font-medium text-slate-800">
                            {{ __('ads.me') }}
                        </span>
                    @else
                        <a
                            href="{{ route('user.reviews.show', $ad->id) }}"
                            onclick="event.stopPropagation();"
                            class="inline-flex items-center rounded-md border border-slate-300 bg-white px-2 py-1 text-xs font-medium text-slate-800 hover:bg-slate-50"
                        >
                            {{ $ad->user->name ?? 'User' }}
                        </a>
                    @endif
                </div>
            </li>
        @endforeach
    </ul>

    {{-- Pagination --}}
    @if(!empty($shouldPaginate))
        <div class="mt-6">
            {!! $ads->appends(['filter'=>request('filter'), 'sort'=>request('sort')])->render() !!}
        </div>
    @endif
@endif
