@props(['ad'])

@php
    $modalId = 'qr-modal-'.$ad->id;
    $adUrl   = route('ads.show', $ad->id);
    $isFavorite = auth()->check()
        ? auth()->user()->favorites()->whereKey($ad->id)->exists()
        : false;
@endphp

<div class="relative overflow-hidden rounded-2xl border border-slate-300 bg-white transition shadow-sm hover:shadow-md">

    {{-- ACTIE-KNOPPEN RECHTSBOVEN --}}
    <div class="absolute right-2 top-2 z-10 flex items-center gap-1">

        {{-- FAVORIET --}}
        @auth
            <div
                x-data="{ active: {{ $isFavorite ? 'true' : 'false' }} }"
                x-init="console.log('Alpine actief op ad {{ $ad->id }}')"
            >
                <button
                    type="button"
                    @click.prevent.stop="
                    console.log('Klik favoriet voor ad {{ $ad->id }}');
                    fetch('{{ route('favorites.toggle', $ad) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    })
                    .then(r => r.json())
                    .then(data => {
                        console.log('Response:', data);
                        if (data.success) {
                            active = data.favorite;
                            if ($store?.fav) $store.fav.count += (active ? 1 : -1);
                        }
                    })
                    .catch(e => console.error('Fetch error', e));
                "
                    class="p-2 rounded-full bg-white border border-slate-300 hover:bg-rose-50 transition"
                >
                    <template x-if="active">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                             class="h-5 w-5 text-rose-500 fill-rose-500">
                            <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 4 4 6.5 4c1.74 0 3.41 1.01 4.13 2.44h.74C13.09 5.01 14.76 4 16.5 4 19 4 21 6 21 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                        </svg>
                    </template>
                    <template x-if="!active">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-slate-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35l-.45-.41C6.4 15.36 3 12.28 3 8.5 3 6 5 4 7.5 4c1.74 0 3.41 1.01 4.13 2.44h.74C13.09 5.01 14.76 4 16.5 4 19 4 21 6 21 8.5z"/>
                        </svg>
                    </template>
                </button>
            </div>
        @endauth

    </div>

    {{-- INHOUD --}}
    <a href="{{ $adUrl }}" class="block">
        <div class="h-24 w-full bg-slate-100"></div>
        <div class="p-4">
            <h3 class="mb-1 text-[1.05rem] font-semibold leading-snug text-slate-900 line-clamp-2">
                {{ $ad->title }}
            </h3>
            <div class="mb-2 font-semibold text-emerald-600">
                €{{ number_format((float)$ad->price, 2, ',', '.') }}
            </div>
            <p class="text-sm text-slate-600 line-clamp-2">{{ $ad->description }}</p>
            <div class="mt-3 flex items-center gap-2 text-xs text-slate-500">
                <span>{{ $ad->address->city ?? '' }}</span>
                @if(!empty($ad->address?->city) && !empty($ad->user?->name))
                    <span>•</span>
                @endif
                <span>{{ $ad->user?->name ?? '' }}</span>
            </div>
        </div>
    </a>
</div>
