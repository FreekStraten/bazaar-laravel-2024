<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReviewController extends Controller
{
    public function show($id, Request $request)
    {
        $user = User::findOrFail($id);
        $reviews = UserReview::with('reviewer', 'reviewedUser')->where('reviewed_user_id', $user->id)->get();

        if ($request->wantsJson()) {
            return $reviews->isEmpty() ? response()->json(['message' => 'No reviews']) : response()->json($reviews);
        }

        return view('ads.partials.review', compact('user', 'reviews'));
    }

    public function store(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'required|string',
        ]);

        $review = new UserReview([
            'rating' => $request->input('rating'),
            'review' => $request->input('review'),
            'reviewer_id' => auth()->id(),
            'reviewed_user_id' => $user->id,
        ]);

        $review->save();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => "Review added successfully",
            ], 201);
        } else {
            return redirect()->back()->with('success', __('reviews.review_added'))->with('user', $user);
        }
    }
}
