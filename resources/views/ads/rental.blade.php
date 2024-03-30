<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Placeholder (Rental and Calendar)
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{__('ads.ads-i-rented')}}</h3>
                    @if($ads->count() > 0)
                        @include('ads.partials.ad-list', ['ads' => $ads])
                    @else
                        <p>{{__('ads.no-ads-rented')}}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">{{__('ads.ads-i-am-renting')}}</h3>
                    @if($AdsIAmRentingOut->count() > 0)
                        @include('ads.partials.ad-list', ['ads' => $AdsIAmRentingOut])
                    @else
                        <p>{{__('ads.no-ads-i-am-renting')}}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="pb-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.2/main.min.css" rel="stylesheet" />

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: [
                    @foreach($ads as $ad)
                    @foreach($ad->bids as $bid)
                {
                    title: '{{ $ad->title }}',
                    start: '{{ $bid->pickup_date }}',
                    end: '{{ $bid->return_date }}',
                    color: '{{ $bid->user_id == auth()->id() ? '#4CAF50' : '#F44336' }}'
                },
                    @endforeach
                    @endforeach
                    @foreach($AdsIAmRentingOut as $ad)
                    @foreach($ad->bids as $bid)
                {
                    title: '{{ $ad->title }}',
                    start: '{{ $bid->pickup_date }}',
                    end: '{{ $bid->return_date }}',
                    color: '{{ $bid->user_id == auth()->id() ? '#4CAF50' : '#F44336' }}'
                },
                @endforeach
                @endforeach
            ]
        });
        calendar.render();
    });
</script>
