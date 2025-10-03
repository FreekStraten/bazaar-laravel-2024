<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AdsTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // 10 rental + 10 normal ads
        $this->createRentalAds($faker, 10);
        $this->createNormalAds($faker, 10);
    }

    private function createRentalAds($faker, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $this->createAd($faker, true);
        }
    }

    private function createNormalAds($faker, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $this->createAd($faker, false);
        }
    }

    private function createAd($faker, bool $isRental): void
    {
        $address = Address::inRandomOrder()->first();
        $user    = User::inRandomOrder()->first();

        if (!$user || !$address) return;

        $title = $this->simpleTitle($isRental);

        $ad = new Ad([
            'title'       => $title,
            'description' => $this->simpleDescription($title), // kort & clean
            'price'       => $faker->randomFloat(2, 10, 200),  // compact bereik
            'is_rental'   => $isRental,
            'user_id'     => $user->id,
            // 'image' => null, // leeg laten → placeholder pakt het op
        ]);

        $ad->address()->associate($address);
        $ad->save();
    }


    private function simpleTitle(bool $isRental): string
    {
        $items = [
            'Fiets', 'Boormachine', 'Camera', 'Projector',
            'Laptop', 'Smartphone', 'Nintendo Switch',
            'PlayStation 5', 'Xbox Series S', 'Bureaustoel',
            'Scooter', 'Dakkoffer',
        ];

        $item = $items[array_rand($items)];
        return $isRental ? "Te huur: {$item}" : $item;
    }

    private function simpleDescription(string $title): string
    {
        // Heel kort en veilig; geen lorem
        return "{$title} in nette staat.";
    }
}
