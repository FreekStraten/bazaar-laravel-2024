<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $availableAddresses = Address::pluck('id')->toArray();
        $roles = Role::pluck('id')->toArray();

        // Create an admin user
        $user = new User();
        $user->name = 'Freek';
        $user->email = 'freekstraten@gmail.com';
        $user->email_verified_at = now();
        $user->password = Hash::make('12345678');
        $user->role_id = $roles[1]; // Admin role
        $user->remember_token = null;
        $user->address_id = array_shift($availableAddresses); // Use the first available address
        $user->save();

        // Create a seller user
        $user = new User();
        $user->name = 'Seller';
        $user->email = 'seller@gmail.com';
        $user->email_verified_at = now();
        $user->password = Hash::make('12345678');
        $user->role_id = $roles[2]; // Private role
        $user->remember_token = null;
        $user->address_id = array_shift($availableAddresses); // Use the first available address
        $user->save();

        // Create 5 business users
        for ($i = 1; $i <= 5; $i++) {
            $businessUser = new User();
            $businessUser->name = $faker->name;
            $businessUser->email = $faker->unique()->email;
            $businessUser->email_verified_at = now();
            $businessUser->password = Hash::make($faker->password);
            $businessUser->role_id = $roles[0]; // Company role
            $businessUser->remember_token = null;
            $businessUser->address_id = array_shift($availableAddresses); // Use the next available address
            $businessUser->save();
        }

        // Create 5 private users
        for ($i = 1; $i <= 5; $i++) {
            $privateUser = new User();
            $privateUser->name = $faker->name;
            $privateUser->email = $faker->unique()->email;
            $privateUser->email_verified_at = now();
            $privateUser->password = Hash::make($faker->password);
            $privateUser->role_id = $roles[2]; // Private Advertiser role
            $privateUser->remember_token = null;
            $privateUser->address_id = array_shift($availableAddresses); // Use the next available address
            $privateUser->save();
        }
    }
}
