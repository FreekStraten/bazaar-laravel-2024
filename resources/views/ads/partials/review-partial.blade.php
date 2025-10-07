{{-- resources/views/ads/partials/review-partial.blade.php --}}
@php
    // Verwachte props:
    // $canLeaveReview (bool), $reviewRoute (string), $cannotLeaveReviewMessage (string), $reviews (Collection)
    $reviews = $reviews ?? collect();
@endphp

<div class="space-y-4">
    {{-- Reviews lijst bovenaan --}}
    <div>
        @if ($reviews->count() > 0)
            <ul class="divide-y divide-slate-200">
                @foreach ($reviews as $review)
                    <li class="p-4">
                        <div class="flex items-center justify-between mb-1.5">
                            <div class="text-sm font-medium text-slate-900">
                                @if($review->reviewer) {{ $review->reviewer->name }}
                                @elseif($review->user)  {{ $review->user->name }}
                                @else                   {{ __('User') }}
                                @endif
                            </div>
                            <div class="text-sm text-slate-700">{{ $review->rating }}/5</div>
                        </div>
                        <p class="text-sm text-slate-700">{{ $review->review }}</p>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-sm text-slate-600">{{ __('ads.no_reviews') }}</p>
        @endif
    </div>

    <div class="border-t border-slate-200"></div>

    {{-- Formulier onderaan (zelfde volgorde als bij bids) --}}
    <div>
        @if ($canLeaveReview)
            <form action="{{ $reviewRoute }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="review" class="block text-sm text-slate-700 mb-1">{{ __('ads.review') }}</label>
                    <textarea id="review" name="review" rows="4" class="w-full rounded-md border-slate-300" required></textarea>
                    @error('review') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm text-slate-700 mb-1">{{ __('ads.rating') }}</label>
                    <div class="flex items-center gap-2" data-rating>
                        <input id="rating" type="number" name="rating" min="1" max="5" required class="sr-only" value="{{ old('rating') }}" />
                        <button type="button" class="text-2xl leading-none text-slate-300 hover:scale-105 transition" aria-label="1 ster" data-value="1">★</button>
                        <button type="button" class="text-2xl leading-none text-slate-300 hover:scale-105 transition" aria-label="2 sterren" data-value="2">★</button>
                        <button type="button" class="text-2xl leading-none text-slate-300 hover:scale-105 transition" aria-label="3 sterren" data-value="3">★</button>
                        <button type="button" class="text-2xl leading-none text-slate-300 hover:scale-105 transition" aria-label="4 sterren" data-value="4">★</button>
                        <button type="button" class="text-2xl leading-none text-slate-300 hover:scale-105 transition" aria-label="5 sterren" data-value="5">★</button>
                        <span class="ml-2 text-sm text-slate-600" data-rating-text></span>
                    </div>
                    @error('rating') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <button class="w-full inline-flex justify-center items-center rounded-md bg-slate-900 px-4 py-2 text-white font-medium hover:bg-slate-800 focus:outline-none">
                    {{ __('ads.submit_review') }}
                </button>
            </form>
        @else
            <p class="text-sm text-slate-600">{{ $cannotLeaveReviewMessage }}</p>
        @endif
    </div>
</div>
