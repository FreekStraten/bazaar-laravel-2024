<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Ad;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdController extends Controller
{
    public function index()
    {
        $rentalAds = Ad::with('user')->where('is_rental', true)->paginate(10);
        $normalAds = Ad::with('user')->where('is_rental', false)->paginate(10);
        return view('ads.index', compact('rentalAds', 'normalAds'));
    }

    public function create()
    {
        return view('ads.create');
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

        $ad = new Ad([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'user_id' => auth()->id(),
            'is_rental' => $request->input('is_rental'),
        ]);
        $ad->address()->associate($address);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('ads-images'), $imageName);
            $ad->image = $imageName;
        }

        $ad->save();
        return redirect()->route('ads.index')->with('success', 'Rental ad created successfully.');
    }

    public function toggleFavorite(Ad $ad)
    {
        $user = auth()->user();
        $userFavorite = $user->AdFavorites()->where('ad_id', $ad->id)->first();

        if ($userFavorite) {
            $userFavorite->toggleFavorite();
        } else {
            $user->AdFavorites()->create([
                'ad_id' => $ad->id,
            ]);
        }

        return redirect()->back();
    }

    public function uploadCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $this->processCsvFile($request->file('csv_file'));
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

            $ad = new Ad([
                'title' => $row[0],
                'description' => $row[1],
                'price' => $row[2],
                'user_id' => auth()->id(),
                'is_rental' => (bool)$row[7],
            ]);

            $ad->address()->associate($address);
            $ad->save();
        }
    }

    public function show(Ad $ad)
    {
        return view('ads.show', compact('ad'));
    }
}
