<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function homepage()
    {
        $ads = Ad::with('user')
            ->where('is_rental', false)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('homepage', compact('ads'));
    }
}
