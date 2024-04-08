<?php

// app/Http/Controllers/BidController.php
namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Bid;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function placeBid(Request $request)
    {
        $validatedData = $request->validate([
            'bid-amount' => 'required|numeric|min:1',
            'ad_id' => 'required|exists:ads,id',
        ]);

        $ad = Ad::findOrFail($validatedData['ad_id']);

        if ($ad->user_id == auth()->id()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => __('ads.cannot_bid_on_own_ad')], 400);
            } else {
                return redirect()->back()->withErrors(['bid-error' => __('ads.cannot_bid_on_own_ad')]);
            }
        }

        $userBidCount = auth()->user()->bid()->count();
        if ($userBidCount >= 4) {
            if ($request->wantsJson()) {
                return response()->json(['error' => __('ads.max_bid_reached')], 400);
            } else {
                return redirect()->back()->withErrors(['bid-error' => __('ads.max_bid_reached')]);
            }
        }

        $bid = $ad->bids()->create([
            'amount' => $validatedData['bid-amount'],
            'user_id' => auth()->id(),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['bid' => $bid]);
        } else {
            return redirect()->route('ads.show', $ad);
        }
    }

    public function acceptBid($ad_id, $bid_id)
    {

        $ad = Ad::findOrFail($ad_id);
        $bid = Bid::findOrFail($bid_id);

        if ($ad->user_id != auth()->id()) {
            return redirect()->back()->withErrors(['bid-error' => __('ads.error')]);
        }

        $ad->bids()->update(['is_accepted' => false]);
        $bid->update(['is_accepted' => true]);

        return redirect()->route('ads.show', $ad);
    }
}
