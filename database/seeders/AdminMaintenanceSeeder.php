<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminMaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate([
            'email' => 'admin.maintenance@dsda.go.id',
        ], [
            'name' => 'Admin Maintenance',
            'unit_id' => null,
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        $role = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);
        $user->assignRole($role);
    }
} 