<table class="table-auto w-full">
    <thead>
    <tr>
        <th class="border px-4 py-2">{{ __('ads.title') }}</th>
        <th class="border px-4 py-2">{{ __('ads.image') }}</th>
        <th class="border px-4 py-2">{{ __('ads.description') }}</th>
        <th class="border px-4 py-2">{{ __('ads.price') }}</th>
        <th class="border px-4 py-2">{{ __('ads.address') }}</th>
        <th class="border px-4 py-2">{{ __('ads.posted_by') }}</th>
        <th class="border px-4 py-2">{{ __('ads.favorite') }}</th>
        <th class="border px-4 py-2">{{ __('ads.bid') }}</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($ads as $ad)
        <tr>
            <td class="border px-4 py-2">{{ $ad->title }}</td>
            <td class="border px-4 py-2">
                @if ($ad->image)
                    <button class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150" onclick="openImageModal('{{ asset('ads-images/' . $ad->image) }}')">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </button>
                @else
                    <span class="text-gray-500">{{ __('ads.no_image') }}</span>
                @endif
            </td>
            <td class="border px-4 py-2">{{ $ad->description }}</td>
            <td class="border px-4 py-2">€{{ $ad->price }}</td>
            <td class="border px-4 py-2">
                {{ $ad->address->street }} {{ $ad->address->house_number }}
                , {{ $ad->address->city }} {{ $ad->address->zip_code }}
            </td>
            <td class="border px-4 py-2">{{ $ad->user->name }}</td>
            <td class="border px-4 py-2">
                <form action="{{ route('ads.toggle-favorite', $ad) }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                        @if (auth()->user()->AdFavorites()->where('ad_id', $ad->id)->exists())
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                        @else
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        @endif
                        {{ __('ads.favorite') }}
                    </button>
                </form>
            </td>
            <td class="border px-4 py-2">
                <x-primary-button onclick="openBidModal({{ $ad->id }})">
                    {{ __('ads.place_bid') }}
                </x-primary-button>
            </td>
    @endforeach
    </tbody>
</table>

@if ($ads->count() > 10)
    <div class="mt-4">
        {{ $ads->links() }}
    </div>
@endif

@include('ads.partials.image-modal')
@include('ads.partials.bid-modal')


<script>
    function openImageModal(imageUrl) {
        const imageModal = document.getElementById('image-modal');
        const modalImage = document.getElementById('modal-image');
        modalImage.src = imageUrl;
        imageModal.classList.remove('hidden');
    }

    function openBidModal(adId) {
        const modal = document.getElementById('bid-modal');
        modal.style.display = 'flex';
        modal.classList.remove('hidden');
    }
</script>
