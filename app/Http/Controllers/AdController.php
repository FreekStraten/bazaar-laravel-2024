<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Ad;
use App\Models\AdReview;
use App\Models\Bid;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdController extends Controller
{
    public function index()
    {
        $query = Ad::query()->with(['address','user']);

        $filter = request('filter');
        if ($filter === 'rentals') {
            $query->where('is_rental', 1);
        } elseif ($filter === 'sales') {
            $query->where('is_rental', 0);
        }

        $sort = request('sort');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'date_asc':
                $query->orderBy('created_at', 'asc');
                break;
            case 'date_desc':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $ads = $query->paginate(24)->withQueryString();

        $ads->setCollection(
            $ads->getCollection()->map(function ($ad) {
                $ad->image_url = $this->resolveImageUrl($ad);
                return $ad;
            })
        );

        return view('ads.index', compact('ads'));
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
            'image' => 'required',
            'is_rental' => 'required|boolean',
        ]);

        $userAdCount = auth()->user()->rentalAds()->count();
        if ($userAdCount >= 4) {
            if ($request->expectsJson()) {
                return response()->json(['message' => __('ads.max_ads_reached')], 422);
            } else {
                return redirect()->back()->withErrors(['message' => __('ads.max_ads_reached')]);
            }
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

        $this->handleImage($ad, $request);

        $ad->save();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Rental ad created successfully.',
                'data' => $ad,
            ], 201);
        } else {
            return redirect()->route('ads.index')->with('success', 'Rental ad created successfully.');
        }
    }

    protected function handleImage(\App\Models\Ad $ad, \Illuminate\Http\Request $request): void
    {
        // 1) multipart upload
        if ($request->hasFile('image')) {
            // Sla origineel op
            $origRel = $request->file('image')->store('products/orig', 'public'); // bv. products/orig/abc.jpg
            $origAbs = storage_path('app/public/' . $origRel);

            // Maak thumb pad
            $thumbRel = 'products/thumbs/' . pathinfo($origRel, PATHINFO_FILENAME) . '.jpg';
            $thumbAbs = storage_path('app/public/' . $thumbRel);

            // Genereer thumb (800px langste zijde)
            if ($this->makeThumb($origAbs, $thumbAbs, 800, 80)) {
                // Gebruik thumb in lijstweergaven
                $ad->image_path = $thumbRel; // => /storage/products/thumbs/abc.jpg via Storage::url()
            } else {
                // fallback: gebruik origineel als het echt moet
                $ad->image_path = $origRel;
            }
            return;
        }

        // 2) base64 upload
        if ($request->filled('image')) {
            $raw = base64_decode($request->input('image'));

            // paden bepalen
            $name = 'products/orig/' . uniqid('ad_') . '.jpg';
            Storage::disk('public')->put($name, $raw);
            $origAbs  = storage_path('app/public/' . $name);

            $thumbRel = 'products/thumbs/' . pathinfo($name, PATHINFO_FILENAME) . '.jpg';
            $thumbAbs = storage_path('app/public/' . $thumbRel);

            if ($this->makeThumb($origAbs, $thumbAbs, 800, 80)) {
                $ad->image_path = $thumbRel;
            } else {
                $ad->image_path = $name;
            }
            return;
        }
    }

    private function resolveImageUrl(Ad $ad): string
    {
        if (!empty($ad->image_path)) {
            if (Storage::disk('public')->exists($ad->image_path)) {
                return Storage::url($ad->image_path); // -> "/storage/products/bike.jpg"
            }
        }
        return 'https://placehold.co/640x480?text=No+Image';
    }


    public function toggleFavorite($id, Request $request)
    {
        $ad = Ad::findOrFail($id);
        $user = auth()->user();
        $userFavorite = $user->AdFavorites()->where('ad_id', $ad->id)->first();

        if ($userFavorite) {
            $userFavorite->toggleFavorite();
        } else {
            $user->AdFavorites()->create([
                'ad_id' => $ad->id,
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Favorite status updated successfully.',
            ]);
        } else {
            return redirect()->back();
        }
    }

    public function uploadCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'nullable|file|mimes:csv,txt',
            'csv'      => 'nullable|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file') ?? $request->file('csv');
        if (!$file) {
            return back()->withErrors(['csv' => 'No CSV file uploaded.']);
        }

        $this->processCsvFile($file);

        return redirect()->back()->with('success', 'CSV processed.');
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


    public function showQrCode(int $id)
    {
        $url = route('ads.show', $id);

        // Geef raw SVG terug, zonder Blade/layout
        $svg = QrCode::format('svg')
            ->size(320)      // pixel
            ->margin(1)      // witte rand
            ->generate($url);

        return response($svg)->header('Content-Type', 'image/svg+xml');
    }

    public function getUserRentedAds()
    {
        $userId = auth()->id();

        $adsIRenting = Ad::whereHas('bids', fn($q) => $q->where('user_id', $userId)->where('is_accepted', true))
            ->with('bids')
            ->paginate(10);

        $adsIRentedOut = Ad::where('user_id', $userId)
            ->whereHas('bids', fn($q) => $q->where('is_accepted', true))
            ->with('bids')
            ->paginate(10);

        // Bouw events hier
        $events = [];
        foreach ($adsIRenting as $ad) {
            foreach ($ad->bids as $bid) {
                $events[] = [
                    'title' => $ad->title,
                    'start' => $bid->pickup_date,
                    'end'   => $bid->return_date,
                    'color' => $bid->user_id == $userId ? '#4CAF50' : '#F44336',
                ];
            }
        }
        foreach ($adsIRentedOut as $ad) {
            foreach ($ad->bids as $bid) {
                $events[] = [
                    'title' => $ad->title,
                    'start' => $bid->pickup_date,
                    'end'   => $bid->return_date,
                    'color' => $bid->user_id == $userId ? '#4CAF50' : '#F44336',
                ];
            }
        }

        return view('ads.rental', compact('adsIRenting', 'adsIRentedOut', 'events'));
    }


    public function setDates(Request $request, $id)
    {
        $ad = Ad::findOrFail($id);

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

    public function show($id, Request $request)
    {
        $ad = Ad::with([
            'user',
            'address',
            'bids.user',
            'reviews.user',
        ])->findOrFail($id);

        $reviews = $ad->reviews;
        $bids    = $ad->bids->sortByDesc('amount');

        $user    = auth()->user();
        $hasBid  = $user
            ? $ad->bids()->where('user_id', $user->id)->where('is_accepted', true)->exists()
            : false;

        if ($request->wantsJson()) {
            $imageData = $this->getEncodedImageData($ad);

            return response()->json([
                'ad'                 => $ad,
                'reviews'            => $reviews,
                'bids'               => $bids->values(),
                'hasBid'             => $hasBid,
                'base64_encoded_image' => $imageData,
            ]);
        }

        return view('ads.show', compact('ad', 'reviews', 'hasBid', 'bids'));
    }

    private function getEncodedImageData($ad)
    {
        if ($ad->image) {
            $imagePath = public_path('ads-images/' . $ad->image);
            return base64_encode(file_get_contents($imagePath));
        }

        return null;
    }

    public function storeReview(Request $request, $id)
    {
        $ad = Ad::findOrFail($id);

        // ✅ Alleen voor verhuur
        if (!$ad->is_rental) {
            return redirect()
                ->back()
                ->withErrors(['message' => 'Reviews zijn alleen beschikbaar voor huuradvertenties.']);
        }

        $request->validate([
            'review' => 'required|string',
            'rating' => 'required|integer|between:1,5',
        ]);

        $user = auth()->user();

        // Alleen als deze gebruiker een geaccepteerde huur heeft
        $hasBid = $ad->bids()
            ->where('user_id', $user->id)
            ->where('is_accepted', true)
            ->exists();

        if (!$hasBid) {
            return redirect()
                ->back()
                ->withErrors(['message' => 'Je kunt alleen een review achterlaten voor een advertentie die je gehuurd hebt.']);
        }

        AdReview::create([
            'ad_id'   => $ad->id,
            'user_id' => $user->id,
            'review'  => $request->input('review'),
            'rating'  => $request->input('rating'),
        ]);

        return redirect()->back()->with('success', 'Review geplaatst.');
    }


    public function setReturn(Request $request, $id)
    {
        $bid = Bid::findOrFail($id);

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

    private function makeThumb(string $srcAbs, string $dstAbs, int $max = 800): void
    {
        [$w, $h, $type] = getimagesize($srcAbs);
        $fn = [
            IMAGETYPE_JPEG => 'imagecreatefromjpeg',
            IMAGETYPE_PNG  => 'imagecreatefrompng',
            IMAGETYPE_WEBP => 'imagecreatefromwebp',
        ][$type] ?? null;
        if (!$fn || !function_exists($fn)) return;

        $src = $fn($srcAbs);
        $scale = min($max / $w, $max / $h, 1);
        $nw = (int)($w * $scale);
        $nh = (int)($h * $scale);
        $dst = imagecreatetruecolor($nw, $nh);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
        imagejpeg($dst, $dstAbs, 80);
        imagedestroy($src);
        imagedestroy($dst);
    }
}
