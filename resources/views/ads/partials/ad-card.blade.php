@props(['ad'])

@php
    $modalId = 'qr-modal-'.$ad->id;
    $adUrl   = route('ads.show', $ad->id);
    $imgPath = !empty($ad->image) ? asset('ads-images/' . $ad->image) : asset('images/placeholder.webp');

    $isFavorite = method_exists($ad, 'isFavoritedBy')
        ? $ad->isFavoritedBy(auth()->user())
        : ($ad->favorite ?? false);
@endphp

<div class="relative overflow-hidden rounded-2xl border border-slate-300 bg-white transition shadow-sm hover:shadow-md">
    {{-- IMAGE --}}
    <a href="{{ $adUrl }}" class="block">
        <img src="{{ $imgPath }}" alt="{{ $ad->title }}"
             class="w-full object-cover" style="aspect-ratio: 4/3;" loading="lazy" />
    </a>

    {{-- TOP-RIGHT ACTIONS --}}
    <div class="absolute right-2 top-2 flex items-center gap-1">
        {{-- Favorite --}}
        <form method="POST" action="{{ route('favorites.toggle', $ad->id) }}"
              onsubmit="event.stopPropagation();" class="inline-block">
            @csrf
            <button type="submit"
                    title="{{ $isFavorite ? __('Unfavorite') : __('Favorite') }}"
                    class="p-2 rounded-full bg-white border border-slate-300 text-slate-700 hover:bg-slate-50"
                    onclick="event.preventDefault(); this.closest('form').submit();">
                @if($isFavorite)
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         fill="currentColor" class="h-5 w-5 text-emerald-600">
                        <path d="M11.645 20.91l-.007-.003-.022-.011a15.247 15.247 0 01-.383-.214 25.18 25.18 0 01-4.244-3.17C4.688 15.203 3 12.829 3 10.25 3 7.322 5.272 5 8.2 5c1.676 0 3.174.785 4.175 2.02C13.374 5.785 14.872 5 16.548 5 19.476 5 21.75 7.322 21.75 10.25c0 2.58-1.688 4.954-3.989 7.262a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.214l-.022.011-.007.003a.75.75 0 01-.66 0z"/>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 11.25 9 11.25s9-4.03 9-11.25z"/>
                    </svg>
                @endif
            </button>
        </form>

        {{-- QR --}}
        <button type="button" title="Deel / QR"
                class="p-2 rounded-full bg-white border border-slate-300 text-slate-700 hover:bg-slate-50"
                onclick="event.stopPropagation(); document.getElementById('{{ $modalId }}').classList.remove('hidden')">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="1.5" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3.75 4.5h4.5v4.5h-4.5V4.5zM15.75 4.5h4.5v4.5h-4.5V4.5zM3.75 15h4.5v4.5h-4.5V15zM15.75 15H18M19.5 15H21M15.75 18v1.5M18 19.5H21"/>
            </svg>
        </button>
    </div>

    {{-- CONTENT --}}
    <a href="{{ $adUrl }}" class="block">
        <div class="p-4">
            <h3 class="mb-1 line-clamp-2 text-[1.05rem] font-semibold leading-snug text-slate-900">
                {{ $ad->title }}
            </h3>
            <div class="mb-2 font-semibold text-emerald-600">
                €{{ number_format((float)$ad->price, 2, ',', '.') }}
            </div>
            <p class="line-clamp-2 text-sm text-slate-600">
                {{ $ad->description }}
            </p>
            <div class="mt-3 flex items-center gap-2 text-xs text-slate-500">
                <span>{{ $ad->address->city ?? '' }}</span>
                @if(!empty($ad->address?->city) && !empty($ad->user?->name))
                    <span>•</span>
                @endif
                <span>{{ $ad->user?->name ?? '' }}</span>
            </div>
        </div>
    </a>

    {{-- QR MODAL (img, geen iframe) --}}
    <div id="{{ $modalId }}" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40" onclick="this.parentElement.classList.add('hidden')"></div>

        <div class="relative mx-auto mt-24 w-full max-w-md rounded-2xl border border-slate-300 bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <h3 class="text-sm font-semibold text-slate-900">Deel advertentie</h3>
                <button class="rounded p-1 hover:bg-slate-100"
                        onclick="document.getElementById('{{ $modalId }}').classList.add('hidden')">
                    <span class="sr-only">Close</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="space-y-4 px-5 py-4">
                <div class="aspect-square w-full rounded-lg bg-white p-4 ring-1 ring-slate-200">
                    <img src="{{ route('ads.qr', $ad->id) }}"
                         alt="QR"
                         class="h-full w-full object-contain"
                         style="image-rendering: pixelated;">
                </div>

                <div>
                    <label class="mb-1 block text-xs text-slate-500">Link</label>
                    <div class="flex gap-2">
                        <input type="text" value="{{ $adUrl }}" readonly
                               class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder:text-slate-500">
                        <button type="button"
                                class="rounded-md bg-emerald-600 px-3 py-2 text-sm text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/40"
                                onclick="navigator.clipboard.writeText('{{ $adUrl }}')">
                            Kopieer
                        </button>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="button"
                            class="rounded-md border border-slate-300 px-3 py-2 text-sm hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/40"
                            onclick="if(navigator.share){navigator.share({title: '{{ addslashes($ad->title) }}', url: '{{ $adUrl }}'})}">
                        Delen…
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
