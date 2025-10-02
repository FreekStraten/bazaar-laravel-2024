@props(['ad'])

<div class="relative bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow hover:shadow-lg transition">
    <a href="{{ route('ads.show', $ad->id) }}" class="block">
        @if ($ad->image)
            <img
                src="{{ asset('ads-images/' . $ad->image) }}"
                alt="{{ $ad->title }}"
                class="w-full object-cover"
                style="aspect-ratio: 4/3;"
            >
        @else
            <div class="w-full flex items-center justify-center text-sm text-gray-500 dark:text-gray-400"
                 style="aspect-ratio: 4/3;">
                {{ __('ads.no_image') }}
            </div>
        @endif

        <div class="p-4">
            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-1">
                {{ \Illuminate\Support\Str::limit($ad->title, 80) }}
            </h3>

            <div class="text-lg font-bold text-emerald-600 dark:text-emerald-400 mb-2">
                €{{ number_format($ad->price, 2, ',', '.') }}
            </div>

            <p class="text-sm text-gray-600 dark:text-gray-300">
                {{ \Illuminate\Support\Str::limit($ad->description, 120) }}
            </p>

            <div class="mt-3 text-xs text-gray-500 dark:text-gray-400 flex items-center gap-2">
                <span>{{ $ad->address->city ?? '' }}</span>
                <span>•</span>
                <span>{{ $ad->user?->name ?? '' }}</span>
            </div>
        </div>
    </a>
</div>
