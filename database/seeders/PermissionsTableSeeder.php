<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $permissions = [
            [
                'name' => 'view_contracts',
                'description' => 'View contracts',
            ],
            [
                'name' => 'approve_contracts',
                'description' => 'Approve contracts',
            ],
            [
                'name' => 'reject_contracts',
                'description' => 'Reject contracts',
            ],
            [
                'name' => 'export_contracts',
                'description' => 'Export contracts',
            ],
            [
                'name' => 'download_contracts',
                'description' => 'Download contracts',
            ],
            [
                'name' => 'store_contracts',
                'description' => 'Store contracts',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
