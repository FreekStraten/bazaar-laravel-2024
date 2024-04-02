<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Ad;
use App\Models\AdReview;
use App\Models\Bid;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdController extends Controller
{
    public function index(Request $request)
    {
        $query = Ad::with('user');

        // Filter ads based on the 'filter' parameter
        if ($request->has('filter')) {
            $filter = $request->input('filter');
            if ($filter == '0') {
                $query->where('is_rental', false);
            } elseif ($filter == '1') {
                $query->where('is_rental', true);
            }
        }

        // Sort ads based on the 'sort' parameter
        if ($request->has('sort')) {
            $sort = $request->input('sort');
            switch ($sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'date_desc':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'date_asc':
                    $query->orderBy('created_at', 'asc');
                    break;
            }
        }

        $ads = $query->paginate(10);
        return view('ads.index', compact('ads'));
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



    public function showQrCode(Ad $ad)
    {
        $url = route('ads.show', $ad);
        $qrCode = QrCode::size(250)->generate($url);
        $ad->qr_code = $qrCode;

        return view('ads.qr-code', compact('ad'));
    }

    public function getUserRentedAds()
    {
        $user_id = auth()->id();

        $ads = Ad::whereHas('bids', function ($query) use ($user_id) {
            $query->where('user_id', $user_id)
                ->where('is_accepted', true);
        })->with('bids')->paginate(10);

        $AdsIAmRentingOut = Ad::where('user_id', $user_id)
            ->whereHas('bids', function ($query) {
                $query->where('is_accepted', true);
            })->with('bids')->paginate(10);

        return view('ads.rental', compact('ads', 'AdsIAmRentingOut'));
    }

    public function setDates(Request $request, Ad $ad)
    {
        $request->validate([
            'pickup-date' => 'required|date',
            'return-date' => 'required|date',
        ]);

        $bid = $ad->bids()->where('is_accepted', true)->first();
        $bid->pickup_date = $request->input('pickup-date');
        $bid->return_date = $request->input('return-date');
        $bid->save();

        return redirect()->back()->with('success', 'Dates updated successfully.');
    }

    public function show(Ad $ad)
    {
        $reviews = $ad->reviews()->with('user')->get();
        $user = auth()->user();
        $hasBid = $ad->bids()->where('user_id', $user->id)->where('is_accepted', true)->exists();

        return view('ads.show', compact('ad', 'reviews', 'hasBid'));
    }

    public function storeReview(Request $request, Ad $ad)
    {
        $request->validate([
            'review' => 'required|string',
            'rating' => 'required|integer|between:1,5',
        ]);

        $user = auth()->user();

        $hasBid = $ad->bids()->where('user_id', $user->id)->where('is_accepted', true)->exists();
        if (!$hasBid) {
            return redirect()->back()->withErrors(['message' => 'You can only leave a review for an ad you have rented.']);
        }

        $review = AdReview::create([
            'ad_id' => $ad->id,
            'user_id' => $user->id,
            'review' => $request->input('review'),
            'rating' => $request->input('rating'),
        ]);

        return redirect()->back()->with('success', 'Review created successfully.');
    }

    public function setReturn(Request $request, $id)
    {
        $bid = Bid::find($id);

        $request->validate([
            'return_image' => 'required|image|max:2048',
            'is_rental' => 'required|in:0,1,2',
        ]);

        if ($request->hasFile('return_image')) {
            $image = $request->file('return_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('return-images'), $imageName);
            $bid->return_image = $imageName;
        }

        $bid->damage = $request->input('is_rental');

        $bid->save();
        $ad = Ad::find($bid->ad_id);

        return redirect()->route('ads.show', $ad->id)->with('success', 'Dates and return photo updated successfully.');
    }
}
