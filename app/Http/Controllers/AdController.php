<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdController extends Controller
{
    public function index()
    {
        $rentalAds = Ad::with('user')->where('is_rental', true)->paginate(10);
//        dd($rentalAds);

        return view('ads.index', compact('rentalAds'));
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

    public function homepage() {
        $ads = Ad::with('user')->where('is_rental', false)->orderBy('created_at', 'desc')->take(5)->paginate(5);
        return view('homepage', compact('ads'));
    }
}
