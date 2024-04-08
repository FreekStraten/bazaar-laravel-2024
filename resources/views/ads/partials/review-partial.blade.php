<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                @if ($canLeaveReview)
                    <h3 class="text-lg font-medium mb-4">{{ __('ads.leave_review') }}</h3>
                    <form action="{{ $reviewRoute }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="review" class="block font-medium text-gray-700 dark:text-gray-300">{{ __('ads.review') }}</label>
                            <textarea id="review" name="review" rows="3" class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 sm:text-sm" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="rating" class="block font-medium text-gray-700 dark:text-gray-300">{{ __('ads.rating') }}</label>
                            <select id="rating" name="rating" class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 sm:text-sm" required>
                                <option value="">{{ __('ads.select_rating') }}</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                        <x-primary-button type="submit">{{ __('ads.submit_review') }}</x-primary-button>
                    </form>
                @else
                    <p>{{ $cannotLeaveReviewMessage }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="pb-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h3 class="text-lg font-medium mb-4">{{ __('ads.reviews') }}</h3>
                @if ($reviews->count() > 0)
                    <div class="space-y-4">
                        @foreach ($reviews as $review)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4">
                                <div class="flex items-center mb-2">
                                    <div class="flex-shrink-0 mr-3">
                                        @if($review->reviewer)
                                            {{ $review->reviewer->name }}
                                        @elseif($review->user)
                                            {{ $review->user->name }}
                                        @endif
                                    </div>
                                </div>
                                <p class="text-gray-700 dark:text-gray-300">{{ $review->review }}</p>
                                <div class="flex items-center mt-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p>{{ __('ads.no_reviews') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
