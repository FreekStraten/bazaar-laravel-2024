<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ $ad->title }}
        </h2>
    </x-slot>

    @php
        // Gebruik direct de accessor; geen lokale fallback meer nodig
        $hero = $ad->cover_url;

        $bids          = $bids    ?? collect();
        $reviews       = $reviews ?? collect();
        $highestBid    = $bids->max('amount');
        $suggestedBid  = $highestBid ? round($highestBid + 1, 2) : round((float)$ad->price, 2);
    @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-12 gap-6">

                {{-- LEFT: media + details (8/12) --}}
                <div class="col-span-12 lg:col-span-8 space-y-6">

                    {{-- Media --}}
                    <section class="relative bg-white border border-slate-200 shadow-sm sm:rounded-lg overflow-hidden">
                        <div class="w-full bg-slate-100 flex items-center justify-center">
                            <img
                                src="{{ $hero }}"
                                alt="{{ $ad->title }}"
                                class="block w-auto max-w-full max-h-[280px] sm:max-h-[320px] lg:max-h-[380px] object-contain"
                                width="800" height="600"
                                loading="eager" fetchpriority="high" decoding="async"
                            >
                        </div>

                        {{-- Overlay action icons --}}
                        <div class="absolute top-2 right-2 flex gap-2">
                            <a href="{{ route('ads.qr', $ad->id) }}"
                               class="inline-flex items-center justify-center rounded-full bg-white/95 border border-slate-300 p-2 text-slate-700 hover:bg-slate-50"
                               title="{{ __('ads.qr') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3.75 4.5h4.5v4.5h-4.5V4.5zM15.75 4.5h4.5v4.5h-4.5V4.5zM3.75 15h4.5v4.5h-4.5V15zM15.75 15H18M19.5 15H21M15.75 18v1.5M18 19.5H21"/>
                                </svg>
                                <span class="sr-only">{{ __('ads.qr') }}</span>
                            </a>

                            <button type="button"
                                    class="inline-flex items-center justify-center rounded-full bg-white/95 border border-slate-300 p-2 text-slate-700 hover:bg-slate-50"
                                    title="{{ __('ads.share') ?? 'Share' }}"
                                    onclick="if(navigator.share){navigator.share({title: '{{ addslashes($ad->title) }}', url: '{{ route('ads.show',$ad->id) }}'})}else{navigator.clipboard.writeText('{{ route('ads.show',$ad->id) }}'); alert('Link copied');}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                                    <path d="M7 12v7a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                    <path d="M12 3v13M12 3l-3 3M12 3l3 3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <span class="sr-only">{{ __('ads.share') ?? 'Share' }}</span>
                            </button>
                        </div>
                    </section>

                    {{-- Details --}}
                    <section class="bg-white border border-slate-200 shadow-sm sm:rounded-lg">
                        <div class="p-6 text-slate-900 space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="text-2xl font-semibold">
                                    €{{ number_format((float)$ad->price, 2, ',', '.') }}
                                </div>
                                @if($highestBid)
                                    <span class="inline-flex items-center rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-800">
                                        Highest bid: €{{ number_format((float)$highestBid, 2, ',', '.') }}
                                    </span>
                                @endif
                            </div>

                            @if($ad->description)
                                <p class="text-slate-700">{{ $ad->description }}</p>
                            @endif

                            <div class="grid sm:grid-cols-2 gap-4">
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
                    </section>

                </div>

                {{-- RIGHT: bids -> bid form -> reviews (4/12) --}}
                <aside class="col-span-12 lg:col-span-4 space-y-6">

                    {{-- Bids --}}
                    <section class="bg-white border border-slate-200 shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('ads.bids') ?? 'Bids' }}</h3>
                            @if($bids->count() > 0)
                                <ul class="divide-y divide-slate-200">
                                    @foreach($bids->sortByDesc('amount')->take(6) as $bid)
                                        <li class="py-3 flex items-center justify-between">
                                            <div class="text-sm text-slate-700">
                                                <span class="font-medium">{{ $bid->user->name ?? 'User' }}</span>
                                                <span class="text-slate-500">• {{ optional($bid->created_at)->diffForHumans() }}</span>
                                            </div>
                                            <div class="text-sm font-semibold text-slate-900">
                                                €{{ number_format((float)$bid->amount, 2, ',', '.') }}
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm text-slate-600">{{ __('ads.no_bids') ?? 'No bids' }}</p>
                            @endif
                        </div>
                    </section>

                    {{-- Place Bid --}}
                    <section class="bg-white border border-slate-200 shadow-sm sm:rounded-lg">
                        <div class="p-6 space-y-4">
                            @auth
                                @if (Route::has('ads.place-bid'))
                                    <form method="POST" action="{{ route('ads.place-bid', $ad->id) }}" class="space-y-3">
                                        @csrf
                                        <div>
                                            <label for="amount" class="block text-sm text-slate-700 mb-1">
                                                {{ __('ads.bid_amount') ?? 'Bid Amount' }}
                                            </label>
                                            <input
                                                id="amount"
                                                name="amount"
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                value="{{ old('amount', number_format($suggestedBid, 2, '.', '')) }}"
                                                class="w-full rounded-md border-slate-300"
                                                required
                                            >
                                            @error('amount') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                        </div>

                                        <button
                                            class="w-full inline-flex justify-center items-center rounded-md bg-emerald-600 px-4 py-2 text-white font-medium hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
                                            {{ __('ads.place_bid') }}
                                        </button>
                                    </form>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                   class="w-full inline-flex justify-center items-center rounded-md bg-emerald-600 px-4 py-2 text-white font-medium hover:bg-emerald-700">
                                    {{ __('auth.Login') }}
                                </a>
                            @endauth
                        </div>
                    </section>

                    {{-- Reviews: alleen tonen bij huuradvertenties --}}
                    @if($ad->is_rental)
                        <section class="bg-white border border-slate-200 shadow-sm sm:rounded-lg">
                            <div class="p-2 sm:p-4">
                                <details class="group" open>
                                    <summary class="cursor-pointer list-none flex items-center justify-between px-4 py-2 rounded-md hover:bg-slate-50">
                                        <span class="text-sm font-medium text-slate-900">
                                            {{ __('ads.reviews') }} / {{ __('ads.leave_review') }}
                                        </span>
                                        <svg class="h-4 w-4 text-slate-500 transition-transform duration-200 group-open:rotate-180"
                                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
                                        </svg>
                                    </summary>
                                    <div class="px-4 pb-4 pt-2">
                                        @include('ads.partials.review-partial', [
                                            'canLeaveReview' => auth()->check(),
                                            'reviewRoute' => route('ads.reviews.store', $ad->id),
                                            'cannotLeaveReviewMessage' => __('auth.Login') . ' ' . (__('to continue') ?? ''),
                                            'reviews' => $reviews,
                                        ])
                                    </div>
                                </details>
                            </div>
                        </section>
                    @endif

                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
