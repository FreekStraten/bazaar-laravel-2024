<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\RentalAd;
use Illuminate\Http\Request;

class RentalAdController extends Controller
{
    public function index()
    {
        $rentalAds = RentalAd::with('user')->paginate(10);
        return view('rental-ads.index', compact('rentalAds'));
    }

    public function create()
    {
        return view('rental-ads.create');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric',
                'street' => 'required|string|max:255',
                'house_number' => 'required|string|max:50',
                'city' => 'required|string|max:255',
                'zip_code' => 'required|string|max:20',
                'image' => 'nullable|image|max:5120',
            ]);

            $address = Address::firstOrCreate([
                'street' => $request->input('street'),
                'house_number' => $request->input('house_number'),
                'city' => $request->input('city'),
                'zip_code' => $request->input('zip_code'),
            ]);

            $rentalAd = new RentalAd($validatedData);
            $rentalAd->user_id = auth()->id();
            $rentalAd->address()->associate($address);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('rental-ads/ads', $imageName, 'public');
                $rentalAd->image = $imageName;
            }

            $rentalAd->save();

            return response()->json(['success' => true, 'message' => 'Rental ad created successfully.']);
        } catch (\Exception $e) {
            logger()->error('Error creating rental ad: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'An error occurred while creating the rental ad.'], 500);
        }
    }
}
