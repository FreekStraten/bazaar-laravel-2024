<?php

// app/Http/Controllers/BidController.php
namespace App\Http\Controllers;

use App\Models\Ad;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function placeBid(Request $request, Ad $ad)
    {
        $validatedData = $request->validate([
            'bid-amount' => 'required|numeric|min:1',
        ]);

        $ad->bids()->create([
            'amount' => $validatedData['bid-amount'],
            'user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Bid placed successfully!');
    }

    public function getBids(Ad $ad)
    {
        $bids = $ad->bids()->with('user')->get();
        return redirect()->back()->with('bids', $bids);
    }
}
