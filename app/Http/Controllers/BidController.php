<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Bid;
use Illuminate\Http\Request;

class BidController extends Controller
{
    public function placeBid(Request $request, $id)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        $ad = Ad::findOrFail($id);

        // Niet op eigen advertentie bieden
        if ($ad->user_id == auth()->id()) {
            return back()->withErrors(['bid-error' => __('ads.cannot_bid_on_own_ad')]);
        }

        // Max 4 biedingen per gebruiker
        $userBidCount = auth()->user()->bids()->count();
        if ($userBidCount >= 4) {
            return back()->withErrors(['bid-error' => __('ads.max_bid_reached')]);
        }

        // Bieding opslaan via relatie
        $ad->bids()->create([
            'amount'  => $validated['amount'],
            'user_id' => auth()->id(),
        ]);

        // Succes: terug naar de ad met status
        return redirect()
            ->route('ads.show', $ad->id)
            ->with('status', __('ads.bid_placed') ?? 'Bid placed');
    }

    public function acceptBid($ad_id, $bid_id)
    {
        $ad = Ad::findOrFail($ad_id);
        $bid = Bid::findOrFail($bid_id);

        // Alleen eigenaar van de advertentie mag accepteren
        if ($ad->user_id != auth()->id()) {
            return redirect()->back()->withErrors(['bid-error' => __('ads.error')]);
        }

        // Eerst alles resetten, dan 1 accepteren
        $ad->bids()->update(['is_accepted' => false]);
        $bid->update(['is_accepted' => true]);

        return redirect()->route('ads.show', $ad->id)->with('status', __('ads.bid_accepted') ?? 'Bid accepted');
    }
}
