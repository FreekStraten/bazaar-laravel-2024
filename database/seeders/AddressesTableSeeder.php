<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $address = new Address();
        $address->street = 'Elm Street';
        $address->house_number = '123';
        $address->city = 'Anytown';
        $address->zip_code = '12345';
        $address->save();
    }
}
