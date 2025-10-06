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
        $images = $this->loadProductImages(); // laad alle images uit storage/app/public/products

        if (count($images) < 3) {
            throw new \RuntimeException('Voeg minstens 3 productafbeeldingen toe in storage/app/public/products');
        }

        // --- verdeling 1/3 huur, 2/3 verkoop ---
        $splitIndex = (int) floor(count($images) * 0.33);
        $rentalImages = array_slice($images, 0, $splitIndex);
        $saleImages   = array_slice($images, $splitIndex);

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
        $user = User::inRandomOrder()->first();
        if (!$user || !$address) return;

        $baseTitle = $image['name'];
        $title = $isRental ? "Te huur: {$baseTitle}" : $baseTitle;

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
            'image_path'  => $image['path'], // e.g. products/bike.jpg
        ]);
    }

    private function loadProductImages(): array
    {
        $files = Storage::disk('public')->files('products');
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $out = [];

        foreach ($files as $relPath) {
            $ext = strtolower(pathinfo($relPath, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed, true)) continue;

            $filename = pathinfo($relPath, PATHINFO_FILENAME);
            $name = Str::title(preg_replace('/[_\-]+/', ' ', $filename));

            $out[] = [
                'path' => $relPath,
                'name' => $name,
            ];
        }

        shuffle($out);

        return $out;
    }
}
