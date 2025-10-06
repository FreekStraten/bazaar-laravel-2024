<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('ads.title') ?? 'Ads' }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Top toolbar: Upload CSV + Create --}}
            <div class="mb-4 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    {{-- Upload CSV --}}
                    <form method="POST" action="{{ route('ads.upload-csv') }}" enctype="multipart/form-data" class="flex items-center gap-2">
                        @csrf
                        <label class="inline-flex items-center gap-2 rounded-md border border-slate-300 bg-white px-3 py-2 text-sm hover:bg-slate-50 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16V8m0 0l-3 3m3-3l3 3M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2" />
                            </svg>
                            <span>Upload CSV</span>
                            <input type="file" name="csv" accept=".csv" class="hidden" onchange="this.form.submit()">
                        </label>
                    </form>

                    {{-- Create Ad (opent gedeelde modal) --}}
                    <button type="button"
                            class="inline-flex items-center gap-2 rounded-md bg-emerald-600 px-3 py-2 text-sm text-white hover:bg-emerald-700"
                            @click="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'create-ad' }))">
                        {{ __('Create Ad') }}
                    </button>
                </div>

                <div class="hidden sm:block text-xs text-slate-500">
                    Bulk-create or update ads from a CSV file (title, price, description, image).
                </div>
            </div>

            {{-- FILTER PANEL (witte box met nette selects + chevron en Apply rechts) --}}
            <form method="GET" action="{{ route('ads.index') }}"
                  class="mb-4 rounded-2xl border border-slate-200 bg-white px-5 py-4">
                <div class="flex flex-wrap items-center justify-between gap-3">

                    {{-- Links: Filter + Sort met consistente dropdown-styling --}}
                    <div class="flex flex-wrap items-center gap-3">
                        <label class="text-sm text-slate-600">Filter</label>

                        {{-- FILTER select --}}
                        <div class="relative">
                            <select name="filter"
                                    class="appearance-none bg-white bg-none [background-image:none]
                                           rounded-md border border-slate-300 text-sm px-3 pr-8 py-1.5
                                           focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500">
                                <option value="all"    {{ request('filter','all')==='all' ? 'selected' : '' }}>All Ads</option>
                                <option value="rental" {{ request('filter')==='rental'    ? 'selected' : '' }}>Rental Ads</option>
                                <option value="sale"   {{ request('filter')==='sale'      ? 'selected' : '' }}>Sale Ads</option>
                            </select>
                            {{-- Custom chevron --}}
                            <span class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-slate-500">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </div>

                        {{-- SORT select (zelfde look) --}}
                        <div class="relative">
                            <select name="sort"
                                    class="appearance-none bg-white bg-none [background-image:none]
                                           rounded-md border border-slate-300 text-sm px-3 pr-8 py-1.5
                                           focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500">
                                <option value="date_desc" {{ request('sort','date_desc')==='date_desc' ? 'selected' : '' }}>Sort by Date Descending</option>
                                <option value="date_asc"  {{ request('sort')==='date_asc'              ? 'selected' : '' }}>Sort by Date Ascending</option>
                                <option value="price_asc" {{ request('sort')==='price_asc'             ? 'selected' : '' }}>Sort by Price Ascending</option>
                                <option value="price_desc"{{ request('sort')==='price_desc'            ? 'selected' : '' }}>Sort by Price Descending</option>
                            </select>
                            {{-- Custom chevron --}}
                            <span class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 text-slate-500">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        </div>
                    </div>

                    {{-- Rechts: Apply --}}
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-md border border-slate-300 bg-white px-3 py-1.5
                                   text-sm font-medium text-slate-700 hover:bg-slate-50">
                        Apply
                    </button>
                </div>
            </form>

            {{-- KAARTEN PANEL (zelfde look als homepage) --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                @include('ads.partials.ad-list', [
                    'ads' => $ads,
                    'shouldPaginate' => true
                ])
            </div>
        </div>
    </div>

    {{-- Create Ad Modal (gedeelde modal) --}}
    @include('ads.partials.create-modal')
</x-app-layout>
