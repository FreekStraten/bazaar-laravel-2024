<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    public function toggle(Request $request, int $adId)
    {
        $userId = $request->user()->id;

        // bestaat er al een favorite?
        $exists = DB::table('favorites')
            ->where('user_id', $userId)
            ->where('ad_id', $adId)
            ->exists();

        if ($exists) {
            DB::table('favorites')
                ->where('user_id', $userId)
                ->where('ad_id', $adId)
                ->delete();
            $state = 'removed';
        } else {
            DB::table('favorites')->insert([
                'user_id' => $userId,
                'ad_id'   => $adId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $state = 'added';
        }

        // terug naar vorige pagina (preserve querystring) met een kleine flash
        return back()->with('favorite_state', $state)->with('favorite_ad_id', $adId);
    }
}
