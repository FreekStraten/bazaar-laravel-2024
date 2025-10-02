{{-- resources/views/homepage.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-900 leading-tight">
                Homepage
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-5 flex items-center justify-between gap-4">
                <p class="text-sm text-subtle">
                    {{ isset($ads) && method_exists($ads, 'total') ? $ads->total() : (isset($ads) ? count($ads) : 0) }} resultaten
                </p>

                <form action="{{ url()->current() }}" method="GET" class="ml-auto">
                    <label for="sort" class="sr-only">Sorteer</label>
                    <select id="sort" name="sort"
                            class="rounded-md border border-slate-300 bg-white text-slate-900 text-sm focus:ring-emerald-500"
                            onchange="this.form.submit()">
                        <option value="date_desc" {{ request('sort')==='date_desc' ? 'selected' : '' }}>Nieuwste eerst</option>
                        <option value="date_asc"  {{ request('sort')==='date_asc'  ? 'selected' : '' }}>Oudste eerst</option>
                        <option value="price_asc" {{ request('sort')==='price_asc' ? 'selected' : '' }}>Prijs ↑</option>
                        <option value="price_desc"{{ request('sort')==='price_desc'? 'selected' : '' }}>Prijs ↓</option>
                    </select>
                </form>
            </div>

            <div class="grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse ($ads as $ad)
                    @include('ads.partials.ad-card', ['ad' => $ad])
                @empty
                    <div class="col-span-full">
                        <div class="rounded-2xl border border-dashed border-slate-300 p-8 text-center text-muted">
                            Geen advertenties gevonden.
                        </div>
                    </div>
                @endforelse
            </div>

            @if(isset($ads) && method_exists($ads, 'links'))
                <div class="mt-8">
                    {{ $ads->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
