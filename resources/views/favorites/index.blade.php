<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('My Favorites') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($ads->count() === 0)
                <div class="bg-white border border-slate-200 sm:rounded-lg p-6 text-slate-700">
                    {{ __('You have no favorites yet.') }}
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($ads as $ad)
                        <a href="{{ route('ads.show', $ad->id) }}"
                           class="block bg-white border border-slate-200 sm:rounded-lg overflow-hidden hover:shadow">
                            <div class="bg-slate-100 flex items-center justify-center">
                                <img src="{{ $ad->cover_url }}" alt="{{ $ad->title }}"
                                     class="w-auto max-w-full max-h-48 object-contain">
                            </div>
                            <div class="p-4">
                                <div class="text-sm text-slate-500 mb-1">{{ $ad->user->name ?? '' }}</div>
                                <div class="font-semibold text-slate-900 truncate">{{ $ad->title }}</div>
                                <div class="text-slate-700">€{{ number_format((float)$ad->price, 2, ',', '.') }}</div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $ads->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
