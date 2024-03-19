<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Freek';
        $user->email = 'freekstraten@gmail.com';
        $user->email_verified_at = now();
        $user->password = Hash::make('12345678');
        $user->user_type = 'admin';
        $user->remember_token = null;
        $user->address_id = 1; // Assuming you have already created an address record
        $user->save();
    }
}
