<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Toon de favorietenlijst van de ingelogde gebruiker.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // via belongsToMany('ads') op User model (pivot: user_ad_favorites)
        $ads = $user->favorites()
            ->with(['user','address'])
            ->latest('ads.created_at')
            ->paginate(12);

        return view('favorites.index', compact('ads'));
    }

    /**
     * Toggle favorite voor een ad via route-model binding.
     * POST /ads/{ad}/favorite
     */
    public function toggle(Request $request, Ad $ad)
    {
        $user = $request->user();
        $user->favorites()->toggle($ad->id);

        if ($request->expectsJson()) {
            $isNowFavorite = $user->favorites()->whereKey($ad->id)->exists();
            return response()->json(['success' => true, 'favorite' => $isNowFavorite]);
        }

        return back();
    }


}
