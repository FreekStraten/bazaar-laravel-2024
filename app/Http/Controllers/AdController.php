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
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

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
        'title'         => 'required|string|max:255',
        'description'   => 'required|string',
        'price'         => 'required|numeric',
        'street'        => 'required|string|max:255',
        'house_number'  => 'required|string|max:50',
        'city'          => 'required|string|max:255',
        'zip_code'      => 'required|string|max:20',
        'image'         => 'required|image|mimes:jpg,jpeg,png,webp|max:8192',
        'is_rental'     => 'required|boolean',
    ]);

    // Max 4 rentals (jouw bestaande check)
    $userAdCount = auth()->user()->rentalAds()->count();
    if ($userAdCount >= 4 && (int)$request->input('is_rental') === 1) {
        return $request->expectsJson()
            ? response()->json(['message' => __('ads.max_ads_reached')], 422)
            : back()->withErrors(['message' => __('ads.max_ads_reached')]);
    }

    // Adres aanmaken/zoeken
    $address = Address::firstOrCreate([
        'street'        => $request->input('street'),
        'house_number'  => $request->input('house_number'),
        'city'          => $request->input('city'),
        'zip_code'      => $request->input('zip_code'),
    ]);

    // Ad opbouwen (nog zonder image_path)
    $ad = new Ad([
        'title'       => $request->input('title'),
        'description' => $request->input('description'),
        'price'       => $request->input('price'),
        'user_id'     => auth()->id(),
        'is_rental'   => (int) $request->input('is_rental') === 1,
    ]);
    $ad->address()->associate($address);

    // Belangrijk: juiste volgorde file/ad meegeven
    if ($request->hasFile('image')) {
        $this->handleImage($request->file('image'), $ad);
    }

    $ad->save();

    return $request->expectsJson()
        ? response()->json(['message' => 'Ad created successfully.', 'data' => $ad], 201)
        : redirect()->route('ads.index')->with('success', 'Advertentie geplaatst.');
}


    private function handleImage(UploadedFile $file, Ad $ad): void
    {
        // Zorg dat de mappen bestaan
        Storage::disk('public')->makeDirectory('products/orig');
        Storage::disk('public')->makeDirectory('products/thumbs');

        // Bestandsnaam
        $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        if (!in_array($ext, ['jpg','jpeg','png','webp'], true)) {
            $ext = 'jpg';
        }
        $name = Str::random(40) . '.' . $ext;

        // Relatieve paden
        $relOriginal = "products/orig/{$name}";
        $relThumb    = "products/thumbs/{$name}";

        // Opslaan origineel op public disk
        Storage::disk('public')->putFileAs('products/orig', $file, $name);

        // Absolute paden
        $absOriginal = Storage::disk('public')->path($relOriginal);
        $absThumb    = Storage::disk('public')->path($relThumb);

        // Thumb maken
        $this->makeThumb($absOriginal, $absThumb, 800);

        // In het model bewaren (ORIG-pad)
        $ad->image_path = $relOriginal;
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

    public function destroy($id, Request $request)
    {
        $ad = Ad::findOrFail($id);

        // Alleen eigenaar mag verwijderen
        if (!$request->user() || $request->user()->id !== $ad->user_id) {
            abort(403);
        }

        // Probeer gekoppelde afbeeldingen op te ruimen
        try {
            $orig = $ad->image_path;
            if ($orig) {
                $thumb = str_replace('products/orig/', 'products/thumbs/', $orig);
                Storage::disk('public')->delete([$orig, $thumb]);
            }
        } catch (\Throwable $e) {
            // stil: media opruimen mag niet blokkeren
        }

        $ad->delete();

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('ads.index')->with('success', 'Advertentie verwijderd.');
    }

    public function update($id, Request $request)
    {
        $ad = Ad::findOrFail($id);

        if (!$request->user() || $request->user()->id !== $ad->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
        ]);

        $ad->fill($validated);
        $ad->save();

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['success' => true, 'ad' => $ad]);
        }

        return redirect()->route('ads.show', $ad->id)->with('success', 'Advertentie bijgewerkt.');
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
        @mkdir(dirname($dstAbs), 0755, true);

        [$w, $h, $type] = @getimagesize($srcAbs);
        if (!$w || !$h) return;

        $create = [
            IMAGETYPE_JPEG => 'imagecreatefromjpeg',
            IMAGETYPE_PNG  => 'imagecreatefrompng',
            IMAGETYPE_WEBP => 'imagecreatefromwebp',
        ][$type] ?? null;
        if (!$create || !function_exists($create)) return;

        $src = @$create($srcAbs);
        if (!$src) return;

        $scale = min($max / $w, $max / $h, 1);
        $nw = (int) round($w * $scale);
        $nh = (int) round($h * $scale);
        $dst = imagecreatetruecolor($nw, $nh);

        if (in_array($type, [IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            imagealphablending($dst, false);
            imagesavealpha($dst, true);
            $transparent = imagecolorallocatealpha($dst, 0, 0, 0, 127);
            imagefilledrectangle($dst, 0, 0, $nw, $nh, $transparent);
        }

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

        if ($type === IMAGETYPE_PNG)      imagepng($dst, $dstAbs, 6);
        elseif ($type === IMAGETYPE_WEBP) imagewebp($dst, $dstAbs, 80);
        else                              imagejpeg($dst, $dstAbs, 80);

        imagedestroy($src);
        imagedestroy($dst);
    }


}
