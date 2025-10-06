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
                @include('ads.partials.ad-list', ['ads' => $ads, 'shouldPaginate' => true])
            @endif
        </div>
    </div>
</x-app-layout>
