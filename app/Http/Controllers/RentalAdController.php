<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\RentalAd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'street' => 'required|string|max:255',
            'house_number' => 'required|string|max:50',
            'city' => 'required|string|max:255',
            'zip_code' => 'required|string|max:20',
            'image' => 'nullable|image|max:5120',
        ]);

        // Annoying error: "Unknown: file created in the system's temporary directory in Unknown on line 0" thus
        // Create a temporary file to test if the server can create files
        $tempFile = tempnam(sys_get_temp_dir(), 'prefix_');
        if ($tempFile === false) {
            Log::error('Failed to create temporary file');
            return redirect()->back()->withErrors(['message' => 'An error occurred while processing the file.']);
        } else {
            file_put_contents($tempFile, 'This is some temporary content.');
            unlink($tempFile);
        }

        $address = Address::firstOrCreate([
            'street' => $request->input('street'),
            'house_number' => $request->input('house_number'),
            'city' => $request->input('city'),
            'zip_code' => $request->input('zip_code'),
        ]);

        // Create a new rental ad
        $rentalAd = new RentalAd();
        $rentalAd->title = $request->input('title');
        $rentalAd->description = $request->input('description');
        $rentalAd->price = $request->input('price');
        $rentalAd->user_id = auth()->id();
        $rentalAd->address()->associate($address);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('ads'), $imageName);
            $rentalAd->image = $imageName;
        }

        $rentalAd->save();

        return redirect()->route('rental-ads.index')->with('success', 'Rental ad created successfully.');
    }


    public function toggleFavorite(RentalAd $rentalAd)
    {
        $user = auth()->user();
        $userFavorite = $user->rentalAdFavorites()->where('rental_ad_id', $rentalAd->id)->first();

        if ($userFavorite) {
            $userFavorite->toggleFavorite();
        } else {
            $user->rentalAdFavorites()->create([
                'rental_ad_id' => $rentalAd->id,
            ]);
        }

        return redirect()->back();
    }
}
