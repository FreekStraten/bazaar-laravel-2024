<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('rental-ads.all_ads') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">{{ __('rental-ads.all_ads') }}</h3>
                        <button
                            class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                            onclick="openCreateModal()">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('rental-ads.create_ad') }}
                        </button>
                    </div>

                    @if($rentalAds->isEmpty())
                        <p>{{ __('rental-ads.no_ads_found') }}</p>
                    @else
                        <table class="table-auto w-full">
                            <thead>
                            <tr>
                                <th class="border px-4 py-2">{{ __('rental-ads.title') }}</th>
                                <th class="border px-4 py-2">{{ __('rental-ads.image') }}</th>
                                <th class="border px-4 py-2">{{ __('rental-ads.description') }}</th>
                                <th class="border px-4 py-2">{{ __('rental-ads.price') }}</th>
                                <th class="border px-4 py-2">{{ __('rental-ads.address') }}</th>
                                <th class="border px-4 py-2">{{ __('rental-ads.posted_by') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($rentalAds as $ad)
                                <tr>
                                    <td class="border px-4 py-2">{{ $ad->title }}</td>
                                    <td class="border px-4 py-2">
                                        @if ($ad->image)
                                            <button
                                                class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150"
                                                onclick="openImageModal('{{ asset('storage/rental-ads/ads/' . $ad->image) }}')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </button>
                                        @else
                                            <span class="text-gray-500">{{ __('rental-ads.no_image') }}</span>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">{{ $ad->description }}</td>
                                    <td class="border px-4 py-2">€{{ $ad->price }}</td>
                                    <td class="border px-4 py-2">
                                        {{ $ad->address->street }} {{ $ad->address->house_number }}
                                        , {{ $ad->address->city }} {{ $ad->address->zip_code }}
                                    </td>
                                    <td class="border px-4 py-2">{{ $ad->user->name }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $rentalAds->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('rental-ads.create-modal')
    @include('rental-ads.image-modal')


    <script>
        function openCreateModal() {
            document.getElementById('create-modal').classList.remove('hidden');
        }

        function updateFileName(input) {
            const fileNameElement = document.getElementById('file-name');
            if (input.files.length > 0) {
                fileNameElement.textContent = input.files[0].name;
            } else {
                fileNameElement.textContent = '{{ __("rental-ads.choose_file") }}';
            }
        }
    </script>
</x-app-layout>



