<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class AddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $address = new Address();
        $address->street = 'Elm Street';
        $address->house_number = '123';
        $address->city = 'Anytown';
        $address->zip_code = '12345';
        $address->save();

        for ($i = 1; $i <= 10; $i++) {
            $newAddress = new Address();
            $newAddress->street = $faker->streetName;
            $newAddress->house_number = $faker->buildingNumber;
            $newAddress->city = $faker->city;
            $newAddress->zip_code = $faker->postcode;
            $newAddress->save();
        }
    }
}
