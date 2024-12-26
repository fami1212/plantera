<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Créer des permissions
        $permissions = [
            'manage crops',
            'view dashboard',
            'manage users',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Créer des rôles et leur attribuer des permissions
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $farmerRole = Role::create(['name' => 'farmer']);
        $farmerRole->givePermissionTo(['manage crops', 'view dashboard']);
    }
}
