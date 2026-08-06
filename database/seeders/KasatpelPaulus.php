<?php

namespace Database\Seeders;

use App\Models\UnitKerja;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class KasatpelPaulus extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Model::unguard();


        // $user->roles()->syncWithoutDetaching([$role->id]);

        // $this->command->info("User [{$user->email}] seeded successfully.");


        // Bersihkan duplikasi data seeder sebelumnya
        // User::where('email', 'kasatpel.selatan.setiabudi@test.com')->delete();

        $roleKasatpel = Role::firstOrCreate([
            'name' => 'Kepala Satuan Pelaksana',
            'guard_name' => 'web',
        ]);

        $unitSelatan = UnitKerja::where('nama', 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Selatan')->first();

        $usersData =
            [
                [
                    'match' => ['email' => 'kasatpel.selatan.kebayoranlama'],
                    'name' => 'Paulus Junjung, ST',
                    'username' => 'kasatpel.selatan.kebayoranlama',
                    'nip' => '198004142010011032',
                    'kecamatan_id' => 24,
                    'unit_id' => $unitSelatan?->id,
                    'password' => bcrypt('!nventory@Selatan2025'),
                    'role_id' => $roleKasatpel->id,
                ],
            ];

        foreach ($usersData as $uData) {
            $count = User::where('email', $uData['match']['email'] . '@test.com')->count();
            $user = User::firstOrCreate(
                ['email' => $uData['match']['email'] . $count + 1 . '@test.com'],
                [
                    'name' => $uData['name'],
                    'username' => $uData['username'] . $count + 1,
                    'nip' => $uData['nip'],
                    'kecamatan_id' => $uData['kecamatan_id'],
                    'unit_id' => $uData['unit_id'],
                    'password' => $uData['password'],
                    'email_verified_at' => now(),
                ]
            );
            $user->roles()->syncWithoutDetaching([$uData['role_id']]);
            $this->command->info("User [{$user->email}] berhasil di-seed.");
        }

        Model::reguard();
    }
}
