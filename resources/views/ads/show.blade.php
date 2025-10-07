<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight flex items-center gap-2">
            <span>{{ $ad->title }}</span>
            @php $ownerOfAd = auth()->check() && auth()->id() === ($ad->user_id ?? null); @endphp
            @if($ownerOfAd)
                <span class="inline-flex items-center rounded-full bg-indigo-600 text-white text-[11px] px-2 py-0.5">
                    {{ __('ads.my_ad') ?? 'My Ad' }}
                </span>
            @endif
        </h2>
    </x-slot>

    @php
        $hero          = $ad->cover_url ?: asset('images/placeholder-product.jpg');
        $bids          = $bids    ?? collect();
        $reviews       = $reviews ?? collect();
        $highestBid    = $bids->max('amount');
        $suggestedBid  = $highestBid ? round($highestBid + 1, 2) : round((float)$ad->price, 2);
        $isSold        = (!$ad->is_rental) && (bool) ($ad->is_sold ?? false);
    @endphp

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div x-data="{ open:false, bidId:null, bidAmount:null, bidder:null }" class="grid grid-cols-12 gap-6">

                {{-- LEFT: media + details (8/12) --}}
                <div class="col-span-12 lg:col-span-8 space-y-6">

                    {{-- Media --}}
                    <section class="relative bg-white border border-slate-200 shadow-sm sm:rounded-lg overflow-hidden">
                        <div class="w-full bg-slate-100 flex items-center justify-center">
                            <img
                                src="{{ $hero }}"
                                alt="{{ $ad->title }}"
                                class="block w-auto max-w-full max-h-[280px] sm:max-h-[320px] lg:max-h-[380px] object-contain {{ $isSold ? 'opacity-70' : '' }}"
                                width="800" height="600"
                                loading="eager" fetchpriority="high" decoding="async"
                            >
                        </div>

                        @if($isSold)
                            <div class="absolute left-2 top-2">
                                <span class="rounded-md bg-rose-600/95 px-2.5 py-1 text-xs font-semibold uppercase tracking-wide text-white shadow">
                                    {{ __('ads.sold') }}
                                </span>
                            </div>
                        @endif

                        {{-- Overlay action icons --}}
                        <div x-data="{ qr:false }" class="absolute top-2 right-2 flex gap-2">
                            {{-- QR trigger --}}
                            <button type="button"
                                    class="inline-flex items-center justify-center rounded-full bg-white/95 border border-slate-300 p-2 text-slate-700 hover:bg-slate-50"
                                    title="{{ __('ads.qr') }}"
                                    @click.stop="qr = true">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M3.75 4.5h4.5v4.5h-4.5V4.5zM15.75 4.5h4.5v4.5h-4.5V4.5zM3.75 15h4.5v4.5h-4.5V15zM15.75 15H18M19.5 15H21M15.75 18v1.5M18 19.5H21"/>
                                </svg>
                                <span class="sr-only">{{ __('ads.qr') }}</span>
                            </button>

                            {{-- QR modal --}}
                            <template x-teleport="body">
                                <div x-show="qr"
                                     x-transition
                                     class="fixed inset-0 z-[999] flex items-center justify-center bg-black/40 backdrop-blur-sm"
                                     @click.self="qr=false">
                                    <div class="bg-white rounded-2xl p-6 shadow-xl w-[90%] max-w-md relative">
                                        <button @click="qr=false"
                                                class="absolute top-3 right-3 p-1 rounded hover:bg-slate-100"
                                                type="button">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500"
                                                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                        <h3 class="text-sm font-semibold text-slate-900 mb-3">{{ __('ads.qr') }}</h3>
                                        <div class="flex flex-col items-center gap-3">
                                            <img src="{{ route('ads.qr', $ad->id) }}" alt="QR code"
                                                 class="w-48 h-48 object-contain">
                                            <p class="text-xs text-slate-600 text-center break-all">
                                                {{ route('ads.show',$ad->id) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </section>

                    {{-- Details --}}
                    <section class="bg-white border border-slate-200 shadow-sm sm:rounded-lg">
                        <div class="p-6 text-slate-900 space-y-4">

                            @if(auth()->check() && auth()->id() === $ad->user_id)
                                <form method="POST" action="{{ route('ads.update', $ad->id) }}"
                                      class="space-y-3 p-4 mb-4 rounded-md border border-slate-200 bg-slate-50">
                                    @csrf
                                    @method('PATCH')
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="title" class="block text-sm text-slate-700 mb-1">{{ __('Titel') }}</label>
                                            <input id="title" name="title" type="text"
                                                   class="w-full rounded-md border-slate-300"
                                                   value="{{ old('title', $ad->title) }}" required>
                                            @error('title') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                        </div>
                                        <div>
                                            <label for="price" class="block text-sm text-slate-700 mb-1">{{ __('Prijs') }}</label>
                                            <input id="price" name="price" type="number" step="0.01" min="0"
                                                   class="w-full rounded-md border-slate-300"
                                                   value="{{ old('price', number_format((float)$ad->price, 2, '.', '')) }}"
                                                   required>
                                            @error('price') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                    <div>
                                        <label for="description" class="block text-sm text-slate-700 mb-1">{{ __('Omschrijving') }}</label>
                                        <textarea id="description" name="description" rows="4"
                                                  class="w-full rounded-md border-slate-300" required>{{ old('description', $ad->description) }}</textarea>
                                        @error('description') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="submit"
                                                class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-white font-medium hover:bg-slate-800">
                                            {{ __('Opslaan') }}
                                        </button>
                                    </div>
                                </form>
                            @endif

                            <div class="flex items-center justify-between">
                                <div class="text-2xl font-semibold">
                                    &euro;{{ number_format((float)$ad->price, 2, ',', '.') }}
                                </div>
                            </div>

                            @if($ad->description)
                                <p class="text-slate-700">{{ $ad->description }}</p>
                            @endif

                            {{-- Accept-bid modal (alleen voor eigenaar) --}}
                            @if(auth()->check() && auth()->id() === $ad->user_id)
                                <template x-teleport="body">
                                    <div x-show="open" x-transition class="fixed inset-0 z-[1000]">
                                        <div class="absolute inset-0 bg-black/40"></div>
                                        <div class="absolute inset-0 flex items-center justify-center p-4">
                                            <div class="w-full max-w-md rounded-2xl bg-white shadow-xl border border-slate-200">
                                                <div class="p-5 space-y-3">
                                                    <h4 class="text-base font-semibold text-slate-900">
                                                        {{ __('ads.accept_bid') ?? 'Bod accepteren' }}
                                                    </h4>
                                                    <p class="text-sm text-slate-600" x-text="bidder + ' - \u20AC' + bidAmount"></p>
                                                    <p class="text-sm text-slate-600">
                                                        @if($ad->is_rental)
                                                            {{ __('ads.accept_bid_confirm_rental') ?? 'Are you sure you want to accept this bid? You can set rental dates afterwards.' }}
                                                        @else
                                                            {{ __('ads.accept_bid_confirm') ?? 'Weet je zeker dat je dit bod wilt accepteren? Dit markeert het product als verkocht.' }}
                                                        @endif
                                                    </p>
                                                    <div class="flex items-center justify-end gap-2 pt-2">
                                                        <button type="button"
                                                                class="px-3 py-1.5 text-sm rounded-md border border-slate-300 text-slate-700 hover:bg-slate-50"
                                                                @click="open=false">{{ __('Cancel') }}</button>
                                                        <form x-ref="acceptForm" method="POST"
                                                              :action="'{{ route('ads.accept-bid', [$ad->id, 0]) }}'.replace('/0/accept','/' + bidId + '/accept')">
                                                            @csrf
                                                            <button type="submit"
                                                                    class="px-3 py-1.5 text-sm rounded-md bg-emerald-600 text-white hover:bg-emerald-700">
                                                                {{ __('ads.accept') ?? 'Accepteren' }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
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
                                    <div class="text-sm text-slate-900">
                                        {{ $ad->user->name ?? '-' }}
                                        @if($ownerOfAd)
                                            <span class="ml-2 text-xs text-indigo-700 bg-indigo-50 rounded px-1.5 py-0.5">{{ __('ads.me') ?? 'Me' }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </section>
                </div>

                {{-- RIGHT: bids -> bid form -> reviews (4/12) --}}
                <aside class="col-span-12 lg:col-span-4 space-y-6">

                    {{-- Bids --}}
                    <section class="bg-white border border-slate-200 shadow-sm sm:rounded-lg">
                        <div class="p-6 space-y-4">
                            <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('ads.bids') ?? 'Bids' }}</h3>

                            @if($bids->count() > 0)
                                <ul class="divide-y divide-slate-200">
                                    @foreach($bids->sortByDesc('amount')->take(6) as $bid)
                                        @php $bidOwnerCanAct = auth()->check() && auth()->id() === $ad->user_id && !$isSold && !$bid->is_accepted; @endphp
                                        <li
                                            class="group relative rounded-md px-2 py-2 flex items-center justify-between gap-3 transition
                                                {{ $bid->is_accepted ? 'bg-emerald-50 border border-emerald-200' : 'border border-transparent' }}
                                                {{ $bidOwnerCanAct ? 'cursor-pointer hover:bg-slate-50 hover:ring-1 hover:ring-emerald-200' : '' }}"
                                            @if($bidOwnerCanAct)
                                                @click="open=true; bidId={{ $bid->id }}; bidAmount='{{ number_format((float)$bid->amount, 2, ',', '.') }}'; bidder='{{ addslashes($bid->user->name ?? 'User') }}'"
                                            @endif
                                            @if($bidOwnerCanAct)
                                                title="{{ __('ads.accept_bid') ?? 'Accept bid' }}"
                                            @endif
                                        >
                                            <div class="min-w-0 text-sm text-slate-700">
                                                <span class="font-medium truncate">{{ $bid->user->name ?? 'User' }}</span>
                                                @if($bid->is_accepted)
                                                    <span class="ml-2 inline-flex items-center rounded px-1.5 py-0.5 text-[11px] font-medium bg-emerald-600/10 text-emerald-700 ring-1 ring-emerald-600/20">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 5.29a1 1 0 010 1.414l-7.071 7.071a1 1 0 01-1.414 0L3.296 9.853a1 1 0 111.414-1.414l3.096 3.096 6.364-6.364a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                        {{ __('Accepted') }}
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <div class="text-sm font-semibold text-slate-900">&euro;{{ number_format((float)$bid->amount, 2, ',', '.') }}</div>
                                                @if($bidOwnerCanAct)
                                                    <span class="opacity-0 group-hover:opacity-100 transition text-emerald-600 text-xs inline-flex items-center gap-1">
                                                        {{ __('ads.accept') ?? 'Accept' }}
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                                                    </span>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-sm text-slate-600">{{ __('ads.no_bids') ?? 'No bids' }}</p>
                            @endif

                            <div class="border-t border-slate-200"></div>

                            @php $hasAccepted = (bool) ($bids->firstWhere('is_accepted', true)); @endphp
                            @auth
                                {{-- Validation feedback --}}
                                @if ($errors->any())
                                    <div class="rounded-md border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-800">
                                        {{ $errors->first() }}
                                    </div>
                                @endif

                                @php $isOwner = auth()->id() === ($ad->user_id ?? null); @endphp

                                @if($isOwner)
                                    <div class="rounded-md border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700">
                                        {{ __('ads.cannot_bid_on_own_ad') }}
                                    </div>
                                @elseif(!$isSold && !$hasAccepted)
                                    <form method="POST" action="{{ route('ads.place-bid', $ad->id) }}" class="space-y-3">
                                        @csrf
                                        @php
                                            $min = max(0.01, ($highestBid ? $highestBid + 0.01 : (float)$ad->price));
                                        @endphp

                                        <div>
                                            <label for="amount" class="block text-sm text-slate-700 mb-1">
                                                {{ __('ads.bid_amount') ?? 'Bid Amount' }}
                                            </label>
                                            <input
                                                id="amount"
                                                name="amount"
                                                type="number"
                                                step="0.01"
                                                min="{{ number_format($min, 2, '.', '') }}"
                                                value="{{ old('amount', number_format($suggestedBid, 2, '.', '')) }}"
                                                class="w-full rounded-md border-slate-300"
                                                required
                                            >
                                            @error('amount')
                                            <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <button
                                            type="submit"
                                            class="w-full inline-flex justify-center items-center rounded-md bg-emerald-600 px-4 py-2 text-white font-medium hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/40">
                                            {{ __('ads.place_bid') }}
                                        </button>
                                    </form>
                                @else
                                    <div class="rounded-md border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-800">
                                        @if($ad->is_rental || $hasAccepted)
                                            {{ __('ads.no_more_bids_rental') ?? 'Geboekt: er kunnen geen biedingen meer worden geplaatst.' }}
                                        @else
                                            {{ __('ads.no_more_bids_sale') ?? 'Verkocht: er kunnen geen biedingen meer worden geplaatst.' }}
                                        @endif
                                    </div>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                   class="w-full inline-flex justify-center items-center rounded-md bg-emerald-600 px-4 py-2 text-white font-medium hover:bg-emerald-700">
                                    {{ __('auth.Login') }}
                                </a>
                            @endauth
                        </div>
                    </section>

                    {{-- Rental dates (owner propose; renter confirm) --}}
                    @if($ad->is_rental && auth()->check())
                        @php $acceptedBid = $bids->firstWhere('is_accepted', true); @endphp
                        @if($acceptedBid)
                            @php $isOwner = auth()->id() === ($ad->user_id ?? null); $isRenter = auth()->id() === ($acceptedBid->user_id ?? null); @endphp
                            <section class="bg-white border border-slate-200 shadow-sm sm:rounded-lg">
                                <div class="p-6 space-y-4">
                                    <h3 class="text-lg font-semibold text-slate-900 mb-2">{{ __('ads.pickup-return-dates') }}</h3>

                                    @if($isOwner)
                                        <form method="POST" action="{{ route('ads.set-dates', $ad->id) }}" class="grid gap-4 sm:grid-cols-2">
                                            @csrf
                                            <div>
                                                <label for="pickup-date" class="block text-sm text-slate-700 mb-1">{{ __('ads.pickup_date') }}</label>
                                                <input id="pickup-date" name="pickup-date" type="datetime-local" class="w-full rounded-md border-slate-300"
                                                       value="{{ $acceptedBid->pickup_date ? \Carbon\Carbon::parse($acceptedBid->pickup_date)->format('Y-m-d\TH:i') : '' }}" required>
                                            </div>
                                            <div>
                                                <label for="return-date" class="block text-sm text-slate-700 mb-1">{{ __('ads.return_date') }}</label>
                                                <input id="return-date" name="return-date" type="datetime-local" class="w-full rounded-md border-slate-300"
                                                       value="{{ $acceptedBid->return_date ? \Carbon\Carbon::parse($acceptedBid->return_date)->format('Y-m-d\TH:i') : '' }}" required>
                                            </div>
                                            <div class="sm:col-span-2 flex items-center gap-3">
                                                <button type="submit" class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-white text-sm font-medium hover:bg-emerald-700">
                                                    {{ __('ads.save_dates') }}
                                                </button>
                                                @if(!($acceptedBid->dates_confirmed ?? false))
                                                    <span class="text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded px-2 py-1">{{ __('ads.waiting_renter_confirmation') ?? 'Waiting for renter confirmation' }}</span>
                                                @else
                                                    <span class="text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded px-2 py-1">{{ __('ads.dates_confirmed') ?? 'Dates confirmed' }}</span>
                                                @endif
                                            </div>
                                        </form>
                                    @elseif($isRenter)
                                        <div class="grid gap-4 sm:grid-cols-2">
                                            <div>
                                                <div class="text-xs text-slate-500 mb-1">{{ __('ads.pickup_date') }}</div>
                                                <div class="text-sm text-slate-900">{{ $acceptedBid->pickup_date ? \Carbon\Carbon::parse($acceptedBid->pickup_date)->format('d-m-Y H:i') : '—' }}</div>
                                            </div>
                                            <div>
                                                <div class="text-xs text-slate-500 mb-1">{{ __('ads.return_date') }}</div>
                                                <div class="text-sm text-slate-900">{{ $acceptedBid->return_date ? \Carbon\Carbon::parse($acceptedBid->return_date)->format('d-m-Y H:i') : '—' }}</div>
                                            </div>
                                        </div>
                                        @if(!$acceptedBid->pickup_date || !$acceptedBid->return_date)
                                            <div class="text-sm text-slate-600">{{ __('ads.owner_has_not_proposed') ?? 'The owner has not proposed dates yet.' }}</div>
                                        @elseif(!($acceptedBid->dates_confirmed ?? false))
                                            <form method="POST" action="{{ route('ads.confirm-dates', [$ad->id, $acceptedBid->id]) }}" class="pt-2">
                                                @csrf
                                                <button class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2 text-white text-sm font-medium hover:bg-emerald-700">
                                                    {{ __('ads.confirm_dates') ?? 'Confirm dates' }}
                                                </button>
                                            </form>
                                        @else
                                            <div class="text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded px-2 py-1 inline-flex">{{ __('ads.dates_confirmed') ?? 'Dates confirmed' }}</div>
                                        @endif
                                    @endif
                                </div>
                            </section>
                        @endif
                    @endif

                    {{-- Reviews: alleen bij huuradvertenties --}}
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
                                            <path fill-rule="evenodd"
                                                  d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </summary>
                                    <div class="px-4 pb-4 pt-2">
                                        @include('ads.partials.review-partial', [
                                            'canLeaveReview' => ($canReview ?? false),
                                            'reviewRoute' => route('ads.reviews.store', $ad->id),
                                            'cannotLeaveReviewMessage' => $cannotReviewMessage ?? '',
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
