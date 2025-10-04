{{-- resources/views/ads/partials/review-partial.blade.php --}}
@php
    // Verwachte props:
    // $canLeaveReview (bool), $reviewRoute (string), $cannotLeaveReviewMessage (string), $reviews (Collection)
    $reviews = $reviews ?? collect();
@endphp

<div class="space-y-6">
    {{-- Formulier --}}
    <section>
        <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('ads.leave_review') }}</h3>

        @if ($canLeaveReview)
            <form action="{{ $reviewRoute }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="review" class="block text-sm text-slate-700 mb-1">{{ __('ads.review') }}</label>
                    <textarea id="review" name="review" rows="4" class="w-full rounded-md border-slate-300" required></textarea>
                    @error('review') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="rating" class="block text-sm text-slate-700 mb-1">{{ __('ads.rating') }}</label>
                    <select id="rating" name="rating" class="w-full rounded-md border-slate-300" required>
                        <option value="">{{ __('ads.select_rating') }}</option>
                        @for($i=1;$i<=5;$i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    @error('rating') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <button class="inline-flex items-center rounded-md bg-slate-900 px-4 py-2 text-white font-medium hover:bg-slate-800">
                    {{ __('ads.submit_review') }}
                </button>
            </form>
        @else
            <p class="text-sm text-slate-600">{{ $cannotLeaveReviewMessage }}</p>
        @endif
    </section>

    {{-- Lijst met reviews --}}
    <section>
        <h3 class="text-lg font-semibold text-slate-900 mb-4">{{ __('ads.reviews') }}</h3>

        @if ($reviews->count() > 0)
            <div class="space-y-4">
                @foreach ($reviews as $review)
                    <article class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                        <div class="flex items-center justify-between mb-2">
                            <div class="text-sm font-medium text-slate-900">
                                @if($review->reviewer) {{ $review->reviewer->name }}
                                @elseif($review->user)  {{ $review->user->name }}
                                @else                   {{ __('User') }}
                                @endif
                            </div>
                            <div class="text-sm text-slate-700">{{ $review->rating }}/5</div>
                        </div>
                        <p class="text-sm text-slate-700">{{ $review->review }}</p>
                    </article>
                @endforeach
            </div>
        @else
            <p class="text-sm text-slate-600">{{ __('ads.no_reviews') }}</p>
        @endif
    </section>
</div>
