<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ---- PERMISSIONS ----
        $permissions = [
            'view users',
            'create users',
            'edit users',
            'delete users',
            'assign roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ---- ROLES ----
        $roles = [
            'Super Admin' => $permissions, // all permissions
            'Admin'       => ['view users', 'create users', 'edit users', 'delete users'],
            'Moderator'   => ['view users'],
            'User'        => [],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);

            // Assign permissions
            $role->syncPermissions($rolePermissions);
        }

        $this->command->info('Roles and permissions seeded successfully!');
    }
}
