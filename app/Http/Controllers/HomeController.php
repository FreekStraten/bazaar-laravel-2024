<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function homepage()
    {
        $latestAdsQuery = Ad::with(['address','user']);
        if (auth()->check()) {
            $latestAdsQuery->orderByRaw('CASE WHEN user_id = ? THEN 0 ELSE 1 END', [auth()->id()]);
        }
        $latestAds = $latestAdsQuery
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('homepage', compact('latestAds'));
    }
}
