<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\UserAdFavorite;
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

        try {
            // Toggle via pivot-relatie; handelt attach/detach voor je af
            $result = $user->favorites()->toggle($ad->id);

            // Als er iets "attached" is, dan is het nu favoriet; anders is het verwijderd
            $isFavorite = !empty($result['attached']);

            return response()->json([
                'success'  => true,
                'favorite' => $isFavorite,
                'count'    => $user->favorites()->count(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kon favoriet niet bijwerken',
            ], 422);
        }
    }

}
