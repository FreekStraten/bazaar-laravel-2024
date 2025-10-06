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

        // Toggle met unieke pivot is nu veilig
        $user->favorites()->toggle($ad->id);

        // Status en teller ALTIJD opnieuw uit DB halen (geen cache/race)
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
