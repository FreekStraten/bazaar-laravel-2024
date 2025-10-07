<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Address;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Haal rol-ids op een robuuste manier op
        $adminRoleId   = Role::where('name', 'Admin')->value('id');
        $companyRoleId = Role::where('name', 'Company')->value('id');
        $privateRoleId = Role::where('name', 'Private Advertiser')->value('id');

        // Adres helper: pak volgende uit lijst, anders random
        $availableAddresses = Address::pluck('id')->all();
        $nextAddressId = function () use (&$availableAddresses) {
            if (empty($availableAddresses)) {
                return Address::inRandomOrder()->value('id');
            }
            return array_shift($availableAddresses);
        };

        // --- Fixed accounts (idempotent) ---
        User::updateOrCreate(
            ['email' => 'freekstraten@gmail.com'],
            [
                'name' => 'Freek',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role_id' => $adminRoleId,
                'address_id' => $nextAddressId(),
                'remember_token' => null,
            ]
        );

        // Extra gewenste account
        User::updateOrCreate(
            ['email' => '123@gmail.com'],
            [
                'name' => 'User 123',
                'email_verified_at' => now(),
                'password' => Hash::make('123456789'),
                'role_id' => $privateRoleId,
                'address_id' => $nextAddressId(),
                'remember_token' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'seller@gmail.com'],
            [
                'name' => 'Seller',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'role_id' => $privateRoleId,
                'address_id' => $nextAddressId(),
                'remember_token' => null,
            ]
        );

        // --- Business users (deterministisch, idempotent) ---
        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => "company{$i}@example.test"],
                [
                    'name' => $faker->company . ' User',
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'role_id' => $companyRoleId,
                    'address_id' => $nextAddressId(),
                    'remember_token' => null,
                ]
            );
        }

        // --- Private users (deterministisch, idempotent) ---
        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => "private{$i}@example.test"],
                [
                    'name' => $faker->name,
                    'email_verified_at' => now(),
                    'password' => Hash::make('password'),
                    'role_id' => $privateRoleId,
                    'address_id' => $nextAddressId(),
                    'remember_token' => null,
                ]
            );
        }
    }
}
