<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\File;

class AdsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $images = File::files(public_path('ads'));
        $usedImages = [];

        $this->createRentalAds($faker, $images, $usedImages);
        $this->createNormalAds($faker, $images, $usedImages);
    }

    private function createRentalAds($faker, $images, &$usedImages)
    {
        for ($i = 1; $i <= 5; $i++) {
            $this->createAd($faker, $images, $usedImages, true);
        }
    }

    private function createNormalAds($faker, $images, &$usedImages)
    {
        for ($i = 1; $i <= 5; $i++) {
            $this->createAd($faker, $images, $usedImages, false);
        }
    }

    private function createAd($faker, $images, &$usedImages, $isRental)
    {
        $address = Address::inRandomOrder()->first();
        $ad = new Ad();
        $ad->title = $faker->sentence(3, true);
        $ad->description = $faker->paragraph(3);
        $ad->price = $faker->randomFloat(2, 500, 2000);
        $ad->is_rental = $isRental;
        $ad->user_id = User::inRandomOrder()->first()->id;
        $ad->address()->associate($address);

        do {
            $image = $images[array_rand($images)];
        } while (in_array(basename($image), $usedImages));

        $ad->image = basename($image);
        $usedImages[] = basename($image);
        $ad->save();
    }
}
