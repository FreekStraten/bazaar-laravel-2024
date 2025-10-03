<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ $ad->title }}
        </h2>
    </x-slot>

    @php
        $candidate = $ad->cover_url ?? ($ad->image ? asset('ads-images/'.$ad->image) : null);
        $localPlaceholder = asset('images/placeholder-ad.svg');
        $hero = $candidate ?: $localPlaceholder;
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Hero image --}}
            <div class="bg-white border border-slate-200 shadow-sm sm:rounded-lg overflow-hidden">
                <div class="aspect-[16/9] bg-slate-100">
                    <img
                        src="{{ $hero }}"
                        alt="{{ $ad->title }}"
                        class="w-full h-full object-cover"
                        onerror="this.onerror=null;this.src='https://placehold.co/1280x720?text=No+Image';"
                    >
                </div>
            </div>

            {{-- Info + Actions --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Left: details --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white border border-slate-200 shadow-sm sm:rounded-lg">
                        <div class="p-6 text-slate-900 space-y-3">
                            <div class="text-2xl font-semibold">€{{ number_format((float)$ad->price, 2, ',', '.') }}</div>
                            <p class="text-slate-700">{{ $ad->description }}</p>

                            <div class="grid sm:grid-cols-2 gap-4 pt-4">
                                <div class="rounded-lg border border-slate-200 p-4">
                                    <div class="text-xs text-slate-500 mb-1">{{ __('ads.address') }}</div>
                                    <div class="text-sm text-slate-900">
                                        {{ $ad->address->street ?? '' }} {{ $ad->address->house_number ?? '' }}<br>
                                        {{ $ad->address->postal_code ?? '' }} {{ $ad->address->city ?? '' }}
                                    </div>
                                </div>
                                <div class="rounded-lg border border-slate-200 p-4">
                                    <div class="text-xs text-slate-500 mb-1">{{ __('ads.seller') ?? 'Seller' }}</div>
                                    <div class="text-sm text-slate-900">{{ $ad->user->name ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Reviews (bestaande partial behouden) --}}
                    <div class="bg-white border border-slate-200 shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            @include('ads.partials.review')
                        </div>
                    </div>
                </div>

                {{-- Right: acties --}}
                <aside class="space-y-6">
                    {{-- Huren / Contact / QR --}}
                    <div class="bg-white border border-slate-200 shadow-sm sm:rounded-lg">
                        <div class="p-6 space-y-4">
                            @auth
                                <a href="{{ route('ads.rent', $ad->id) }}"
                                   class="w-full inline-flex justify-center items-center rounded-md bg-emerald-600 px-4 py-2 text-white font-medium hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
                                    {{ __('ads.rent_now') ?? 'Rent now' }}
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                   class="w-full inline-flex justify-center items-center rounded-md bg-emerald-600 px-4 py-2 text-white font-medium hover:bg-emerald-700">
                                    {{ __('auth.Login') }}
                                </a>
                            @endauth

                            <div class="flex gap-2">
                                <a href="{{ route('ads.qr', $ad->id) }}"
                                   class="flex-1 inline-flex justify-center items-center rounded-md border border-slate-300 bg-white px-4 py-2 text-slate-700 hover:bg-slate-50"
                                   title="QR / Share">
                                    QR
                                </a>
                                <button type="button"
                                        class="flex-1 inline-flex justify-center items-center rounded-md border border-slate-300 bg-white px-4 py-2 text-slate-700 hover:bg-slate-50"
                                        onclick="if(navigator.share){navigator.share({title: '{{ addslashes($ad->title) }}', url: '{{ route('ads.show',$ad->id) }}'})}">
                                    {{ __('ads.share') ?? 'Share' }}
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Kalender (indien gebruikt) --}}
                    @if(isset($events))
                        <div class="bg-white border border-slate-200 shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div id="calendar" class="w-full"></div>
                            </div>
                        </div>

                        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>
                        <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet"/>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                var calendarEl = document.getElementById('calendar');
                                if (calendarEl) {
                                    var calendar = new FullCalendar.Calendar(calendarEl, {
                                        initialView: 'dayGridMonth',
                                        events: @json($events ?? []),
                                    });
                                    calendar.render();
                                }
                            });
                        </script>
                    @endif
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
