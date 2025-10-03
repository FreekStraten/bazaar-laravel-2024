<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Idempotent: maak of update rollen zonder duplicates
        $roles = ['Company', 'Admin', 'Private Advertiser'];

        foreach ($roles as $name) {
            Role::updateOrCreate(['name' => $name], []);
        }

        // Admin krijgt alle permissies
        $adminRole   = Role::where('name', 'Admin')->firstOrFail();
        $permissions = Permission::pluck('id');

        // Idempotent: sync zet exact deze set (geen duplicates)
        $adminRole->permissions()->sync($permissions);
    }
}
