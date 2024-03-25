<div class="fixed z-10 inset-0 overflow-y-auto hidden items-center justify-center" id="bid-modal">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full relative">
            <button type="button" class="absolute top-4 right-4 bg-red-500 hover:bg-red-600 text-white rounded-full w-8 h-8 flex items-center justify-center focus:outline-none" onclick="closeBidModal()">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <div class="mt-3 text-center sm:mt-0 sm:my-4 sm:mx-4 sm:text-left">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">{{ $ad->title }}</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">{{ $ad->description }}</p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Price: €{{ $ad->price }}</p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Address: {{ $ad->address->street }} {{ $ad->address->house_number }}
                    , {{ $ad->address->city }} {{ $ad->address->zip_code }}</p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Posted by: {{ $ad->user->name }}</p>
                <form action="{{ route('ads.place-bid', ['ad' => $ad->id]) }}" method="POST" class="mt-4"
                      id="bid-forum">
                    @csrf
                    <div class="mb-4">
                        <label for="bid-amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bid Amount</label>
                        <input type="number" id="bid-amount" name="bid-amount" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 rounded-md" required>
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Place Bid
                    </button>
                </form>
                <div class="mt-4">
                    <h4 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">Bids</h4>

                    @if($ad->bids->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">No bids placed yet.</p>
                    @else
                        @foreach ($ad->bids as $bid)
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded mb-2">
                                <p class="text-gray-900 dark:text-gray-100 font-medium">Bid Amount:
                                    €{{ $bid->amount }}</p>
                                <p class="text-gray-500 dark:text-gray-400">Placed by: {{ $bid->user->name }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    function openModal() {
        const modal = document.getElementById('bid-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeBidModal() {
        const bidModal = document.getElementById('bid-modal');
        bidModal.classList.remove('flex');
        bidModal.classList.add('hidden');
    }
</script>
