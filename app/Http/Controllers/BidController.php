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
            return back()->with('error', __('ads.cannot_bid_on_own_ad'));
        }

        // Geen biedingen meer als er al een geaccepteerd bod is (zowel sale als rental)
        if ($ad->bids()->where('is_accepted', true)->exists()) {
            $msg = $ad->is_rental
                ? __('ads.already_booked') ?? 'Deze advertentie is al geboekt.'
                : __('ads.already_sold')   ?? 'Dit product is al verkocht.';
            return back()->with('error', $msg);
        }

        // Max 4 biedingen per gebruiker
        $userBidCount = auth()->user()->bids()->count();
        if ($userBidCount >= 4) {
            return back()->with('error', __('ads.max_bid_reached'));
        }

        // Bieding opslaan via relatie
        $ad->bids()->create([
            'amount'  => $validated['amount'],
            'user_id' => auth()->id(),
        ]);

        // Succes: terug naar de ad met status
        return redirect()
            ->route('ads.show', $ad->id)
            ->with('success', __('ads.bid_placed') ?? 'Bid placed');
    }

    public function acceptBid($ad_id, $bid_id)
    {
        $ad = Ad::findOrFail($ad_id);
        $bid = Bid::findOrFail($bid_id);

        if ($bid->ad_id !== $ad->id) {
            return redirect()->back()->with('error', __('ads.error'));
        }

        // Alleen eigenaar van de advertentie mag accepteren
        if ($ad->user_id != auth()->id()) {
            return redirect()->back()->with('error', __('ads.error'));
        }

        // Eerst alles resetten, dan 1 accepteren
        $ad->bids()->update(['is_accepted' => false]);
        $bid->update(['is_accepted' => true, 'dates_confirmed' => false]);

        return redirect()->route('ads.show', $ad->id)->with('success', __('ads.bid_accepted') ?? 'Bid accepted');
}

    public function confirmDates($ad_id, $bid_id)
    {
        $ad  = Ad::findOrFail($ad_id);
        $bid = Bid::findOrFail($bid_id);

        // Moet bij deze ad horen
        if ($bid->ad_id !== $ad->id) {
            return redirect()->back()->with('error', __('ads.error'));
        }

        // Alleen de bieder (huurder) van het geaccepteerde bod kan bevestigen
        if (!$bid->is_accepted || auth()->id() !== $bid->user_id) {
            abort(403);
        }

        // Vereist voorgestelde datums
        if (empty($bid->pickup_date) || empty($bid->return_date)) {
            return redirect()->back()->with('error', __('ads.no_dates_set_yet'));
        }

        $bid->dates_confirmed = true;
        $bid->save();

        return redirect()->route('ads.show', $ad->id)->with('success', __('ads.dates_confirmed_success') ?? 'Dates confirmed');
    }
}
