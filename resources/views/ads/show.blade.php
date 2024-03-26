<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $ad->title }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto">
        <div class="flex">
            <div class="py-12 px-4 w-2/3">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6 text-gray-900 dark:text-gray-100 grid grid-cols-1 md:grid-cols-2 gap-6 h-full">
                            <div class="mb-4 h-full">
                                <h3 class="text-lg font-bold">{{ __('ads.ad_details') }}</h3>
                                <div class="mt-2">
                                    <p>{{ __('ads.title') }}: {{ $ad->title }}</p>
                                    <p>{{ __('ads.price') }}: €{{ $ad->price }}</p>
                                    <p>{{ __('ads.address') }}
                                        : {{ $ad->address->street }} {{ $ad->address->house_number }}
                                        , {{ $ad->address->city }} {{ $ad->address->zip_code }}</p>
                                    <p>{{ __('ads.posted_by') }}: {{ $ad->user->name }}</p>
                                </div>
                                @if ($ad->image)
                                    <div class="mb-4">
                                        <div class="mt-2">
                                            <img src="{{ asset('ads-images/' . $ad->image) }}" alt="{{ $ad->title }}"
                                                 class="max-w-full h-auto border rounded">
                                        </div>
                                    </div>
                                @endif
                                <p class="italic">{{ $ad->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="py-12 px-4 w-1/3">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6 text-gray-900 dark:text-gray-100 grid grid-cols-1 md:grid-cols-2 gap-6 h-full">
                            <div class="h-full">
                                <h3 class="text-lg font-bold mb-4">{{ __('ads.bids') }}</h3>
                                @if ($ad->bids->count() > 0)
                                    <table class="table-auto w-full mb-6">
                                        <thead>
                                        <tr class="bg-gray-200 dark:bg-gray-700">
                                            <th class="px-4 py-2">{{ __('ads.bidder') }}</th>
                                            <th class="px-4 py-2">{{ __('ads.amount') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($ad->bids as $bid)
                                            <tr class="odd:bg-gray-100 dark:odd:bg-gray-600">
                                                <td class="border px-4 py-2">{{ $bid->user->name }}</td>
                                                <td class="border px-4 py-2">€{{ $bid->amount }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>{{ __('ads.no_bids') }}</p>
                                @endif

                                <form action="{{ route('ads.place-bid', $ad->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="bid-amount"
                                               class="block font-medium text-gray-700 dark:text-gray-300">{{ __('ads.bid_amount') }}</label>
                                        <input type="number" id="bid-amount" name="bid-amount" min="1" step="0.01"
                                               class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 rounded-md shadow-sm block w-full"
                                               required>
                                    </div>
                                    <input type="hidden" name="ad_id" value="{{ $ad->id }}">
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        {{ __('ads.place_bid') }}
                                    </button>
                                </form>
                                @if ($errors->has('bid-max-error'))
                                    <div class="text-red-500 mt-2">
                                        {{ $errors->first('bid-max-error') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
