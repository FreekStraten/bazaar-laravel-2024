{{-- resources/views/homepage.blade.php --}}
<x-app-layout>
    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Homepage
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- CHANGED: bovenbalk met result count + directe sortering (geen Apply-knop) --}}
            <div class="mb-5 flex items-center justify-between gap-4">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    {{ isset($ads) && method_exists($ads, 'total') ? $ads->total() : (isset($ads) ? count($ads) : 0) }} resultaten
                </p>

                <form action="{{ url()->current() }}" method="GET" class="ml-auto">
                    <label for="sort" class="sr-only">Sorteer</label>
                    <select id="sort" name="sort"
                            class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 text-sm"
                            onchange="this.form.submit()">
                        <option value="date_desc" {{ request('sort')==='date_desc' ? 'selected' : '' }}>Nieuwste eerst</option>
                        <option value="date_asc"  {{ request('sort')==='date_asc'  ? 'selected' : '' }}>Oudste eerst</option>
                        <option value="price_asc" {{ request('sort')==='price_asc' ? 'selected' : '' }}>Prijs ↑</option>
                        <option value="price_desc"{{ request('sort')==='price_desc'? 'selected' : '' }}>Prijs ↓</option>
                    </select>
                </form>
            </div>

            <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse ($ads as $ad)
                    {{-- SIMPLE CARD (inline) – je kunt dit later vervangen door @include('ads.partials.ad-card') --}}
                    <div class="relative bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow hover:shadow-lg transition">
                        <a href="{{ route('ads.show', $ad->id) }}" class="block">
                            @php
                                $imgPath = !empty($ad->image)
                                    ? asset('ads-images/' . $ad->image)
                                    : asset('images/placeholder.webp');
                            @endphp

                            <img
                                src="{{ $imgPath }}"
                                alt="{{ $ad->title }}"
                                class="w-full object-cover"
                                style="aspect-ratio: 4/3;"
                                loading="lazy"
                            >

                            <div class="p-4">
                                <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-1 line-clamp-2">
                                    {{ $ad->title }}
                                </h3>

                                <div class="text-lg font-bold text-emerald-600 dark:text-emerald-400 mb-2">
                                    €{{ number_format((float)$ad->price, 2, ',', '.') }}
                                </div>

                                <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                                    {{ $ad->description }}
                                </p>

                                <div class="mt-3 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                    <span>{{ $ad->address->city ?? '' }}</span>
                                    @if(!empty($ad->address?->city) && !empty($ad->user?->name))
                                        <span>•</span>
                                    @endif
                                    <span>{{ $ad->user?->name ?? '' }}</span>
                                </div>
                            </div>
                        </a>

                        {{-- FAVORIET komt later als toggle rechtsboven --}}
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="rounded-lg border border-dashed border-gray-300 dark:border-gray-700 p-8 text-center text-gray-600 dark:text-gray-300">
                            Geen advertenties gevonden.
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            @if(isset($ads) && method_exists($ads, 'links'))
                <div class="mt-8">
                    {{ $ads->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
