<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $ads = $user->favorites()
            ->with(['user','address'])
            ->latest('ads.created_at')
            ->paginate(12);

        return view('favorites.index', compact('ads'));
    }

    public function toggle(Request $request, Ad $ad)
    {
        $user = $request->user();

        // pivot is UNIQUE(user_id, ad_id); toggle is betrouwbaar
        $user->favorites()->toggle($ad->id);

        // status + count fris uit DB
        $isNowFavorite = $user->favorites()->whereKey($ad->id)->exists();
        $newCount      = $user->favorites()->count();

        if ($request->expectsJson()) {
            return response()->json([
                'success'  => true,
                'favorite' => $isNowFavorite,
                'count'    => $newCount,
            ]);
        }

        return back();
    }
}
