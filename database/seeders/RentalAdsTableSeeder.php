<?php

namespace Database\Seeders;

use App\Models\RentalAd;
use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class RentalAdsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $images = File::files(public_path('ads'));


        $usedImages = [];

        // Create 10 rental ads
        for ($i = 1; $i <= 10; $i++) {
            $address = Address::inRandomOrder()->first();

            $rentalAd = new RentalAd();
            $rentalAd->title = $faker->sentence(3, true);
            $rentalAd->description = $faker->paragraph(3);
            $rentalAd->price = $faker->randomFloat(2, 500, 2000);
            $rentalAd->user_id = User::inRandomOrder()->first()->id;
            $rentalAd->address()->associate($address);

            // Randomly select an image from the list, but avoid selecting the same image twice
            do {
                $image = $images[array_rand($images)];
            } while (in_array(basename($image), $usedImages));

            $rentalAd->image = basename($image);
            $usedImages[] = basename($image);
            $rentalAd->save();
        }
    }
}
