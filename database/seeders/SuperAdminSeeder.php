<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        // Create master data permissions
        $this->createMasterDataPermissions();

        // Create Super Admin user
        $superAdmin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('data!nformas!'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );

        // Create or get superadmin role
        $superAdminRole = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);

        // Assign all permissions to Super Admin role
        $permissions = Permission::all();
        $superAdminRole->syncPermissions($permissions);

        // Assign superadmin role to the user
        $superAdmin->assignRole('superadmin');
    }

    /**
     * Create permissions for master data CRUD operations
     */
    private function createMasterDataPermissions()
    {
        // Master data entities
        $entities = [
            'unit_kerja',
            'kecamatan',
            'kelurahan',
        ];

        // CRUD operations
        $operations = ['create', 'read', 'update', 'delete'];

        // Create permissions for each entity and operation
        foreach ($entities as $entity) {
            foreach ($operations as $operation) {
                Permission::firstOrCreate([
                    'name' => "{$entity}.{$operation}",
                    'guard_name' => 'web',
                ]);
            }
        }
    }
}
