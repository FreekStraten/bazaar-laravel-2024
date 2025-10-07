<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class AdsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Zorg dat mappen bestaan
        Storage::disk('public')->makeDirectory('products/orig');
        Storage::disk('public')->makeDirectory('products/thumbs');

        // Zorg dat er i.i.g. 1 user en wat adressen zijn
        $user = User::first() ?? User::factory()->create([
            'name' => 'Demo User',
            'email' => 'demo@example.com',
            'password' => bcrypt('password'),
        ]);

        if (Address::count() < 5) {
            for ($i = 0; $i < 8; $i++) {
                Address::create([
                    'street'       => $faker->streetName(),
                    'house_number' => (string) $faker->buildingNumber(),
                    'city'         => $faker->city(),
                    'zip_code'     => strtoupper($faker->bothify('#### ??')),
                ]);
            }
        }

        // --- laad ALLE images uit products/orig (zoals origineel, maar nu met submap) ---
        $images = $this->loadProductImages(); // ['path' => 'products/orig/tent.jpg', 'name' => 'Tent']

        if (count($images) < 3) {
            throw new \RuntimeException(
                "Voeg minstens 3 productafbeeldingen toe in storage/app/public/products/orig"
            );
        }

        // --- 1/3 huur, 2/3 verkoop ---
        $splitIndex   = (int) floor(count($images) * 0.33);
        $rentalImages = array_slice($images, 0, $splitIndex);
        $saleImages   = array_slice($images, $splitIndex);

        // --- thumbs genereren voor alle afbeeldingen (1x veilig) ---
        foreach ($images as $img) {
            $this->ensureThumbFor($img['path']);
        }

        // --- advertenties aanmaken ---
        $this->createAds($faker, $rentalImages, true);   // huur
        $this->createAds($faker, $saleImages, false);    // verkoop
    }

    private function createAds($faker, array $images, bool $isRental): void
    {
        foreach ($images as $img) {
            $this->createAd($faker, $isRental, $img);
        }
    }

    private function createAd($faker, bool $isRental, array $image): void
    {
        $address = Address::inRandomOrder()->first();
        $user    = User::inRandomOrder()->first();
        if (!$user || !$address) return;

        $baseTitle = $image['name'];                         // <-- leesbare titel uit filename
        $title     = $isRental ? "Te huur: {$baseTitle}" : $baseTitle;

        // Realistische prijsverdeling
        $price = $isRental
            ? $faker->randomFloat(2, 5, 50)      // huur: €5–€50
            : $faker->randomFloat(2, 50, 800);   // koop: €50–€800

        Ad::create([
            'title'       => $title,
            'description' => "{$baseTitle} in nette staat.",
            'price'       => $price,
            'is_rental'   => $isRental,
            'user_id'     => $user->id,
            'address_id'  => $address->id,
            // Belangrijk: we bewaren het ORIG-pad, jouw accessor kan daar een thumb van afleiden
            'image_path'  => $image['path'],      // bv. products/orig/tent.jpg
        ]);
    }

    /**
     * Leest alle seed-afbeeldingen uit products/orig en maakt nette namen.
     * Retourneert lijst met ['path' => 'products/orig/naam.jpg', 'name' => 'Naam'].
     */
    private function loadProductImages(): array
    {
        $files    = Storage::disk('public')->files('products/orig');
        $allowed  = ['jpg', 'jpeg', 'png', 'webp'];
        $out      = [];

        foreach ($files as $relPath) {
            $ext = strtolower(pathinfo($relPath, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed, true)) continue;

            $filename = pathinfo($relPath, PATHINFO_FILENAME);

            // Sla geüploade user-bestanden met random bestandsnaam (Str::random(40)) over,
            // zodat migrate:fresh --seed geen user-content oppakt.
            if (preg_match('/^[A-Za-z0-9]{40}$/', $filename)) {
                continue;
            }

            // Verwijder eventuele random suffixen → toonbaar maken
            // (maar in principe: kies gewoon nette bestandsnamen in orig/)
            $pretty = Str::title(preg_replace('/[_\-]+/', ' ', $filename));

            $out[] = [
                'path' => $relPath,  // bv. products/orig/tent.jpg
                'name' => $pretty,   // bv. Tent
            ];
        }

        shuffle($out);
        return $out;
    }

    /**
     * Zorgt dat er een thumb naast het orig-bestand staat.
     */
    private function ensureThumbFor(string $origRel, int $max = 800): void
    {
        $disk = Storage::disk('public');

        $thumbRel = str_replace('products/orig/', 'products/thumbs/', $origRel);
        if ($disk->exists($thumbRel)) {
            return;
        }

        $origAbs  = $disk->path($origRel);
        $thumbAbs = $disk->path($thumbRel);

        @mkdir(dirname($thumbAbs), 0755, true);

        [$w, $h, $type] = @getimagesize($origAbs);
        if (!$w || !$h) return;

        $create = [
            IMAGETYPE_JPEG => 'imagecreatefromjpeg',
            IMAGETYPE_PNG  => 'imagecreatefrompng',
            IMAGETYPE_WEBP => 'imagecreatefromwebp',
        ][$type] ?? null;
        if (!$create || !function_exists($create)) return;

        $src = @$create($origAbs);
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

        if ($type === IMAGETYPE_PNG) {
            imagepng($dst, $thumbAbs, 6);
        } elseif ($type === IMAGETYPE_WEBP) {
            imagewebp($dst, $thumbAbs, 80);
        } else {
            imagejpeg($dst, $thumbAbs, 80);
        }

        imagedestroy($src);
        imagedestroy($dst);
    }
}
