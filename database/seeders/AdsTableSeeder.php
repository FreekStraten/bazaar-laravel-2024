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
        $images = $this->loadProductImages(); // MUST exist

        // Fail fast if no images
        if (count($images) === 0) {
            throw new \RuntimeException('Put images in storage/app/public/products first.');
        }

        $this->createAds($faker, $images, 10, true);   // rentals
        $this->createAds($faker, $images, 10, false);  // normal
    }

    private function createAds($faker, array $images, int $count, bool $isRental): void
    {
        $n = count($images);
        for ($i = 0; $i < $count; $i++) {
            $img = $images[$i % $n]; // cycle images
            $this->createAd($faker, $isRental, $img);
        }
    }

    private function createAd($faker, bool $isRental, array $image): void
    {
        $address = Address::inRandomOrder()->first();
        $user = User::inRandomOrder()->first();
        if (!$user || !$address) return;

        $baseTitle = $image['name'];                 // derived from filename
        $title = $isRental ? "Te huur: {$baseTitle}" : $baseTitle;

        $ad = new Ad([
            'title' => $title,
            'description' => "{$baseTitle} in nette staat.",
            'price' => $faker->randomFloat(2, 10, 200),
            'is_rental' => $isRental,
            'user_id' => $user->id,
            'image_path' => $image['path'],        // e.g. products/bike.jpg
        ]);

        $ad->address()->associate($address);
        $ad->save();
    }

    private function loadProductImages(): array
    {
        $files = Storage::disk('public')->files('products'); // storage/app/public/products
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $out = [];

        foreach ($files as $relPath) {
            $ext = strtolower(pathinfo($relPath, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed, true)) continue;

            $filename = pathinfo($relPath, PATHINFO_FILENAME);           // coffee-maker_pro-2
            $name = Str::title(preg_replace('/[_\-]+/', ' ', $filename)); // Coffee Maker Pro 2

            $out[] = ['path' => $relPath, 'name' => $name];
        }

        shuffle($out);

        return $out;
    }
}
