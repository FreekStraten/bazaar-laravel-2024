@props(['ad'])

@php
    $adUrl   = route('ads.show', $ad->id);
    $isFavorite = auth()->check()
        ? auth()->user()->favorites()->whereKey($ad->id)->exists()
        : false;

    $isRental = (bool) $ad->is_rental;
    $badgeText = $isRental ? __('Te huur') : __('Te koop');
    $badgeClasses = $isRental ? 'bg-amber-500 text-white' : 'bg-emerald-600 text-white';
@endphp

<div
    x-data="{ active: {{ $isFavorite ? 'true' : 'false' }} }"
    class="relative overflow-hidden rounded-2xl border border-slate-300 bg-white transition shadow-sm hover:shadow-md"
>
    {{-- ACTIES RECHTSBOVEN --}}
    <div class="absolute right-2 top-2 z-10 flex items-center gap-1">
        @auth
            {{-- FAVORIET --}}
            <button
                type="button"
                class="p-2 rounded-full bg-white border border-slate-300 hover:bg-rose-50 transition"
                :title="active ? 'Verwijder uit favorieten' : 'Voeg toe aan favorieten'"
                @click.stop.prevent="
                fetch('{{ route('favorites.toggle', $ad) }}', {
                    method:'POST',
                    headers:{
                        'X-CSRF-TOKEN':'{{ csrf_token() }}',
                        'X-Requested-With':'XMLHttpRequest',
                        'Accept':'application/json'
                    }
                })
                .then(r=>r.json())
                .then(d=>{
                    if(d?.success){ active = !!d.favorite; if($store.fav) $store.fav.count = +d.count; }
                })
            ">
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

            @if(auth()->id() === ($ad->user_id ?? null))
            {{-- VERWIJDEREN (alleen eigenaar) --}}
            <button
                type="button"
                class="p-2 rounded-full bg-white border border-slate-300 text-slate-700 hover:bg-red-50 transition"
                title="Verwijder advertentie"
                @click.stop.prevent="
                    if(confirm('{{ __('Weet je zeker dat je deze advertentie wilt verwijderen?') }}')){
                        fetch('{{ route('ads.destroy', $ad->id) }}', {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN':'{{ csrf_token() }}',
                                'X-Requested-With':'XMLHttpRequest',
                                'Accept':'application/json'
                            }
                        })
                        .then(r => r.ok ? r.json() : Promise.reject(r))
                        .then(() => { window.location.reload(); })
                        .catch(() => { alert('{{ __('Verwijderen is mislukt.') }}'); });
                    }
                "
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m-8 0l1 12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-12M10 11v6M14 11v6"/>
                </svg>
            </button>
            @endif
        @endauth

        {{-- QR (1 plek) --}}
        <button type="button"
                class="p-2 rounded-full bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 transition"
                title="Toon QR-code"
                @click.stop="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'qr-{{ $ad->id }}' }))">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="1.5" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M3.75 4.5h4.5v4.5h-4.5V4.5zM15.75 4.5h4.5v4.5h-4.5V4.5zM3.75 15h4.5v4.5h-4.5V15zM15.75 15H18M19.5 15H21M15.75 18v1.5M18 19.5H21"/>
            </svg>
        </button>
    </div>

    {{-- MEDIA + BADGE --}}
    <div class="relative">
        <div class="aspect-[4/3] bg-slate-100 overflow-hidden">
            <img src="{{ $ad->cover_url }}" alt="{{ $ad->title }}"
                 class="h-full w-full object-cover" width="640" height="480"
                 loading="lazy" decoding="async">
        </div>
        <div class="absolute left-2 top-2">
            <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-medium shadow-sm {{ $badgeClasses }}">
                {{ $badgeText }}
            </span>
        </div>
    </div>

    {{-- CONTENT --}}
    <a href="{{ $adUrl }}" class="block">
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

    {{-- QR MODAL via gedeelde component --}}
    <x-modal id="qr-{{ $ad->id }}" :title="__('QR-code')" maxWidth="md" z="z-[90]">
        <div class="flex flex-col items-center gap-3">
            <img src="{{ route('ads.qr', $ad->id) }}" alt="QR code" class="w-48 h-48 object-contain">
            <p class="text-xs text-slate-600 text-center break-all">{{ route('ads.show', $ad->id) }}</p>
        </div>
    </x-modal>
</div>
