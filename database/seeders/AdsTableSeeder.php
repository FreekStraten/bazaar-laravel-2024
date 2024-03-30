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
        $images = File::files(public_path('ads-images'));
        $usedImages = [];

        $this->DemoAd();

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
        $ad = new Ad([
            'title' => $faker->sentence(3, true),
            'description' => $faker->paragraph(3),
            'price' => $faker->randomFloat(2, 500, 2000),
            'is_rental' => $isRental,
            'user_id' => User::inRandomOrder()->first()->id,
        ]);
        $ad->address()->associate($address);

        do {
            $image = $images[array_rand($images)];
        } while (in_array(basename($image), $usedImages));

        $ad->image = basename($image);
        $usedImages[] = basename($image);
        $ad->save();
    }

    /**
     * @return void
     */
    public function DemoAd(): void
    {
        $user = User::where('name', 'Seller')->first();
        $image = public_path('ads-images/ad_11.png');

        $ad = new Ad([
            'title' => 'Camera',
            'description' => 'A camera for rent',
            'price' => 500,
            'is_rental' => true,
            'user_id' => $user->id,
        ]);
        $ad->address()->associate($user->address);

        $ad->image = basename($image);
        $ad->save();
    }
}
