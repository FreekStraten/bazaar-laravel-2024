<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('ads.ads-i-rented') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Ads I Rented (wat jij gehuurd hebt) --}}
            <div class="bg-white border border-slate-200 shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium mb-4">{{ __('ads.ads-i-rented') }}</h3>
                    @if(isset($adsIRenting) && $adsIRenting->count() > 0)
                        @include('ads.partials.ad-list', ['ads' => $adsIRenting, 'shouldPaginate' => true])
                    @else
                        <p class="text-slate-600">{{ __('ads.no-ads-rented') }}</p>
                    @endif
                </div>
            </div>

            {{-- Kalender (optioneel) --}}
            @if(isset($events))
                <div class="bg-white border border-slate-200 shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium mb-4">{{ __('ads.calendar') ?? 'Calendar' }}</h3>
                        <div id="calendar" class="w-full"></div>
                    </div>
                </div>

                <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>
                <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet"/>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var el = document.getElementById('calendar');
                        if (el) {
                            var calendar = new FullCalendar.Calendar(el, {
                                initialView: 'dayGridMonth',
                                events: @json($events ?? []),
                            });
                            calendar.render();
                        }
                    });
                </script>
            @endif
        </div>
    </div>
</x-app-layout>
