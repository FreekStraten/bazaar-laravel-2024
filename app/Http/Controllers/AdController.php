<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Ad;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdController extends Controller
{
    public function rental_ads()
    {
        $rentalAds = Ad::with('user')->where('is_rental', true)->paginate(10);
        $normalAds = Ad::with('user')->where('is_rental', false)->paginate(10);
        return view('ads.index', compact('rentalAds', 'normalAds'));
    }

    public function create()
    {
        return view('rental-ads.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'street' => 'required|string|max:255',
            'house_number' => 'required|string|max:50',
            'city' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'image' => 'image|max:5120',
            'is_rental' => 'required|boolean',
        ]);

        $userAdCount = auth()->user()->rentalAds()->count();
        if ($userAdCount >= 4) {
            return redirect()->back()->withErrors(['message' => __('ads.max_ads_reached')]);
        }

        $address = Address::firstOrCreate([
            'street' => $request->input('street'),
            'house_number' => $request->input('house_number'),
            'city' => $request->input('city'),
            'zip_code' => $request->input('zip_code'),
        ]);

        $ad = new Ad();
        $ad->title = $request->input('title');
        $ad->description = $request->input('description');
        $ad->price = $request->input('price');
        $ad->user_id = auth()->id();
        $ad->address()->associate($address);
        $ad->is_rental = $request->input('is_rental');

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('ads'), $imageName);
            $ad->image = $imageName;
        }

        $ad->save();
        return redirect()->route('rental-ads.index')->with('success', 'Rental ad created successfully.');
    }

    public function toggleFavorite(Ad $rentalAd)
    {
        $user = auth()->user();
        $userFavorite = $user->AdFavorites()->where('ad_id', $rentalAd->id)->first();

        if ($userFavorite) {
            $userFavorite->toggleFavorite();
        } else {
            $user->AdFavorites()->create([
                'ad_id' => $rentalAd->id,
            ]);
        }

        return redirect()->back();
    }

    public function homepage() {
        $ads = Ad::with('user')->where('is_rental', false)->orderBy('created_at', 'desc')->take(5)->paginate(5);
        return view('homepage', compact('ads'));
    }

    public function uploadCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $this->processCsvFile($request->file('csv_file'));

        // Redirect back
        return redirect()->back();

    }

    protected function processCsvFile($csvFile)
    {
        $rows = array_map('str_getcsv', file($csvFile->getRealPath()));

        array_shift($rows);

        foreach ($rows as $row) {
            $address = Address::firstOrCreate([
                'street' => $row[3],
                'house_number' => $row[4],
                'city' => $row[5],
                'zip_code' => $row[6],
            ]);

            $ad = new Ad();
            $ad->title = $row[0];
            $ad->description = $row[1];
            $ad->price = $row[2];
            $ad->user_id = auth()->id();
            $ad->address()->associate($address);
            $ad->is_rental = (bool)$row[7];
            $ad->save();
        }
    }


    //bid should properly be placed in a new controller


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
