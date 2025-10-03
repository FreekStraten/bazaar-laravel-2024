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
        $actions = [
            'view'     => 'View',
            'approve'  => 'Approve',
            'reject'   => 'Reject',
            'export'   => 'Export',
            'download' => 'Download',
            'store'    => 'Store',
        ];

        $permissions = collect($actions)
            ->map(fn ($label, $key) => [
                'name'        => "{$key}_contracts",
                'description' => "{$label} contracts",
            ])
            ->values()
            ->all();


        foreach ($permissions as $p) {
            Permission::updateOrCreate(
                ['name' => $p['name']],
                ['description' => $p['description']]
            );
        }
    }
}
