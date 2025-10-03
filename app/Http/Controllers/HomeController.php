<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function homepage()
    {
        $latestAds = Ad::with(['address','user'])
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('homepage', compact('latestAds'));
    }
}
