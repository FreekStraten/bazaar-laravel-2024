<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $ad->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-start mb-4">
                        <div class="mb-4 w-full md:w-3/4">
                            <div class="mt-2">
                                <p>{{ __('ads.price') }}: €{{ $ad->price }}</p>
                                <p>{{ __('ads.address') }} : {{ $ad->address->street }} {{ $ad->address->house_number }}, {{ $ad->address->city }} {{ $ad->address->zip_code }}</p>
                                <p>{{ __('ads.posted_by') }}: {{ $ad->user->name }}</p>
                                <p class="mb-4">{{__('ads.description') }} {{ $ad->description }}</p>
                            </div>

                            <h3 class="text-lg font-bold mb-2">{{ __('ads.bids') }}</h3>
                            @if ($ad->bids->count() > 0)
                                <table class="table-auto w-full mb-6 rounded-lg overflow-hidden">
                                    <thead>
                                    <tr class="bg-gray-200 dark:bg-gray-700 rounded-t-lg">
                                        <th class="border px-4 py-2">{{ __('ads.bidder') }}</th>
                                        <th class="border px-4 py-2">{{ __('ads.amount') }}</th>
                                        @if(auth()->id() == $ad->user_id)
                                            <th class="border px-4 py-2">{{ __('action.Action') }}</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($ad->bids as $bid)
                                        <tr class="odd:bg-gray-100 dark:odd:bg-gray-600 even:bg-white dark:even:bg-gray-800 rounded-b-lg">
                                            <td class="@if($bid->is_accepted) underline @endif border px-4 py-2">{{ $bid->user->name }}</td>
                                            <td class="@if($bid->is_accepted) underline @endif border px-4 py-2">€{{ $bid->amount }}</td>
                                            @if(auth()->id() == $ad->user_id)
                                                <th class="border px-4 py-2">
                                                    @if ($bid->is_accepted)
                                                        <x-secondary-button disabled>{{ __('ads.accept_bid') }}</x-secondary-button>
                                                    @else
                                                        <form action="{{ route('ads.accept-bid', [$ad->id, $bid->id]) }}" method="POST">
                                                            @csrf
                                                            <x-primary-button type="submit">{{ __('ads.accept_bid') }}</x-primary-button>
                                                        </form>
                                                    @endif
                                                </th>
                                            @endif
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>{{ __('ads.no_bids') }}</p>
                            @endif

                            <div>
                                @if(auth()->id() != $ad->user_id && !$ad->bids->where('is_accepted', true)->first())
                                    <form action="{{ route('ads.place-bid', $ad->id) }}" method="POST" class="mt-2">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="bid-amount" class="block font-medium text-gray-700 dark:text-gray-300">{{ __('ads.bid_amount') }}</label>
                                            <input type="number" id="bid-amount" name="bid-amount" min="1" step="0.01" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                                        </div>
                                        <input type="hidden" name="ad_id" value="{{ $ad->id }}">
                                        <x-primary-button type="submit">
                                            {{ __('ads.place_bid') }}
                                        </x-primary-button>
                                    </form>
                                @endif
                                @if ($errors->has('bid-error'))
                                    @foreach ($errors->get('bid-error') as $error)
                                        <p class="text-red-500 mt-2">{{ $error }}</p>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        @if ($ad->image)
                            <div class="mb-4 w-full md:w-1/2 mx-5 my-5">
                                <img src="{{ asset('ads-images/' . $ad->image) }}" alt="{{ $ad->title }}" class="max-w-full h-auto border rounded">
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    @if($ad->bids->where('is_accepted', true)->first())
        @if($ad->bids->where('is_accepted', true)->first()->user_id == auth()->id() || $ad->user_id == auth()->id())
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        @if($ad->bids->where('is_accepted', true)->first()->pickup_date && $ad->bids->where('is_accepted', true)->first()->return_date)
                            <h3 class="text-lg font-medium mb-4">{{ __('ads.pickup-return-dates') }}</h3>
                            @if(app()->getLocale() == 'nl')
                                <p>{{ __('ads.pickup_date') }}: {{ \Carbon\Carbon::parse($ad->bids->where('is_accepted', true)->first()->pickup_date)->format('d-m-Y') }}</p>
                                <p>{{ __('ads.return_date') }}: {{ \Carbon\Carbon::parse($ad->bids->where('is_accepted', true)->first()->return_date)->format('d-m-Y') }}</p>
                            @else
                                <p>{{ __('ads.pickup_date') }}: {{ \Carbon\Carbon::parse($ad->bids->where('is_accepted', true)->first()->pickup_date)->format('d-m-Y') }}</p>
                                <p>{{ __('ads.return_date') }}: {{ \Carbon\Carbon::parse($ad->bids->where('is_accepted', true)->first()->return_date)->format('d-m-Y') }}</p>
                            @endif

                        @elseif($ad->user_id == auth()->id())
                            <h3 class="text-lg font-medium mb-4">{{ __('ads.set-dates') }}</h3>
                            <form action="{{ route('ads.set-dates', $ad->id) }}" method="POST">
                                @csrf
                                <div class="mb-4">
                                    <label for="pickup-date" class="block font-medium text-gray-700 dark:text-gray-300">{{ __('ads.pickup_date') }}</label>
                                    <input type="date" id="pickup-date" name="pickup-date" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                                </div>
                                <div class="mb-4">
                                    <label for="return-date" class="block font-medium text-gray-700 dark:text-gray-300">{{ __('ads.return_date') }}</label>
                                    <input type="date" id="return-date" name="return-date" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 rounded-md shadow-sm block w-full" required>
                                </div>
                                <x-primary-button type="submit">{{ __('ads.save_dates') }}</x-primary-button>
                            </form>
                        @endif

                        @if($ad->bids->where('is_accepted', true)->first()->user_id == auth()->id())
                            <h3 class="text-lg font-medium my-4">{{ __('ads.set-return-image') }}</h3>
                            <form action="{{ route('ads.set-return-date', $ad->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mt-4">
                                    <label for="image" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('ads.image') }}</label>
                                    <div class="relative">
                                        <x-secondary-button type="button" onclick="document.getElementById('image').click()">
                                            <span id="file-name">{{ __('Action.ChooseFile') }}</span>
                                        </x-secondary-button>
                                        <input type="file" id="return_image" name="return_image" class="sr-only" onchange="updateFileName(this)">
                                    </div>
                                    @error('image')
                                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <x-primary-button type="submit" class="mt-2">{{ __('ads.save-return-file') }}</x-primary-button>
                            </form>
                        @endif

                    </div>
                </div>
            </div>
        @endif
    @endif

    @include('ads.partials.review-partial', [
    'canLeaveReview' => $hasBid,
    'reviewRoute' => route('ads.reviews.store', $ad),
    'cannotLeaveReviewMessage' => __('ads.can_only_review_rented'),
    'reviews' => $reviews
])

</x-app-layout>




