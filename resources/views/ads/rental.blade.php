<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            Placeholder (Rental and Calendar)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Ads I Rented (jij verhuurt) --}}
            <div class="bg-white border border-slate-200 shadow-sm sm:rounded-lg">
                <div class="p-6 text-slate-900">
                    <h3 class="text-lg font-medium mb-4">{{ __('ads.ads-i-rented') }}</h3>
                    @if(isset($adsIRentedOut) && $adsIRentedOut->count() > 0)
                        @include('ads.partials.ad-list', ['ads' => $adsIRentedOut, 'shouldPaginate' => true])
                    @else
                        <p>{{ __('ads.no-ads-rented') }}</p>
                    @endif
                </div>
            </div>

            {{-- Ads I Am Renting (jij huurt) --}}
            <div class="bg-white border border-slate-200 shadow-sm sm:rounded-lg mt-6">
                <div class="p-6 text-slate-900">
                    <h3 class="text-lg font-medium mb-4">{{ __('ads.ads-i-am-renting') }}</h3>
                    @if(isset($adsIRenting) && $adsIRenting->count() > 0)
                        @include('ads.partials.ad-list', ['ads' => $adsIRenting, 'shouldPaginate' => true])
                    @else
                        <p>{{ __('ads.no-ads-i-am-renting') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Calendar --}}
    <div class="pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div id="calendar" class="rounded-xl border border-slate-200 p-3"></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- FullCalendar --}}
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet"/>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: @json($events ?? []),
        });
        calendar.render();
    });
</script>
