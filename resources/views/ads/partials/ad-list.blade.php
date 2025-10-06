@if ($ads->isEmpty())
    <div class="rounded-xl border border-slate-200 bg-slate-50 p-6 text-slate-700">
        {{ __('ads.no_ads_found') }}
    </div>
@else
    <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach ($ads as $ad)
            <li>
                <x-ad-card :ad="$ad" />
            </li>
        @endforeach
    </ul>

    @if(!empty($shouldPaginate))
        <div class="mt-6">
            {!! $ads->appends(['filter'=>request('filter'), 'sort'=>request('sort')])->render() !!}
        </div>
    @endif
@endif
