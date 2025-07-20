<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UnitKerja;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class SudinJaktimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil unit kerja Sudin SDA Jakarta Timur
        $unit = UnitKerja::where('nama', 'like', '%Jakarta Timur%')->first();
        if (!$unit) {
            throw new \Exception('Unit Kerja Sudin SDA Jakarta Timur tidak ditemukan!');
        }

        // Data user Sudin SDA Jakarta Timur
        $users = [
            [
                'role' => 'Kepala Suku Dinas',
                'name' => 'Kepala Suku Dinas Jaktim',
                'nip' => '197001011990031001',
            ],
            [
                'role' => 'Admin',
                'name' => 'Admin Jaktim',
                'nip' => '198001011990031002',
            ],
            [
                'role' => 'Kasatpel',
                'name' => 'Kasatpel Jaktim',
                'nip' => '199001011990031003',
            ],
            [
                'role' => 'Operator',
                'name' => 'Operator Jaktim',
                'nip' => '200001011990031004',
            ],
            [
                'role' => 'Staf',
                'name' => 'Staf Jaktim',
                'nip' => '201001011990031005',
            ],
        ];

        foreach ($users as $userData) {
            $email = Str::of($userData['name'])
                ->replace(['.', ',', '  '], '')
                ->replace(' ', '.')
                ->lower()
                ->append('@jaktim.go.id');

            $user = User::firstOrCreate([
                'email' => $email,
            ], [
                'name' => $userData['name'],
                'unit_id' => $unit->id,
                'nip' => $userData['nip'],
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
            ]);

            // Assign role
            $role = Role::firstOrCreate([
                'name' => $userData['role'],
                'guard_name' => 'web',
            ]);
            $user->assignRole($role);
        }
    }
} 