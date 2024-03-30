<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('review.leave_a_review') }}
        </h2>
    </x-slot>

    @include('ads.partials.review-partial', [
    'canLeaveReview' => true,
    'reviewRoute' => route('user.reviews.store', $user),
    'cannotLeaveReviewMessage' => '',
    'reviews' => $reviews
])

</x-app-layout>
