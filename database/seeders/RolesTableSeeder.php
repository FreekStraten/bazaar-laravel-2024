<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'Company'],
            ['name' => 'Admin'],
            ['name' => 'Private Advertiser'],
        ]);

        $adminRole = Role::where('name', 'Admin')->first();
        $permissions = Permission::all();
        $adminRole->permissions()->sync($permissions->pluck('id'));
    }
}
