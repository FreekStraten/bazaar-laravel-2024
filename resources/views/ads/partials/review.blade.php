{{-- resources/views/review.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-900 leading-tight">
            {{ __('review.leave_a_review') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-slate-200 shadow-sm sm:rounded-lg p-6">
                @include('ads.partials.review-partial', [
                  'canLeaveReview' => true,
                  'reviewRoute' => route('user.reviews.store', $user->id),
                  'cannotLeaveReviewMessage' => '',
                  'reviews' => $reviews ?? collect(),
                ])
            </div>
        </div>
    </div>
</x-app-layout>
