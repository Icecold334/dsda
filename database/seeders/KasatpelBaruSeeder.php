<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UnitKerja;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Model;

class KasatpelBaruSeeder extends Seeder
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


        // Unit Kerja Utama secara dinamis
        $unitUtara = UnitKerja::where('nama', 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Utara')->first();
        $unitSelatan = UnitKerja::where('nama', 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Selatan')->first();


        // Data Users No. 1 sampai 6
        $usersData =
            [
                // [
                //     'match' => ['email' => 'tatang'],
                //     'data' => [
                //         'name' => 'Tatang Sunjaya',
                //         'username' => 'tatang.sunjaya',
                //         'nip' => '197605152009041003',
                //         'unit_id' => $seksiSaranaUtara?->id,
                //         'password' => bcrypt('!nventory@Utara2025'),
                //         'email_verified_at' => now(),
                //     ],
                //     'role_id' => $roleStaff->id,
                // ],
                [
                    'match' => ['email' => 'kasatpel.utara.pademangan'],
                    'name' => 'Novita Kesnanda',
                    'username' => 'kasatpel.utara.pademangan',
                    'nip' => '198311122010012030',
                    'kecamatan_id' => 10,
                    'unit_id' => $unitUtara?->id,
                    'password' => bcrypt('!nventory@Utara2025'),
                    'role_id' => $roleKasatpel->id,
                ],
                [
                    'match' => ['email' => 'kasatpel.selatan.setiabudi'],
                    'name' => 'Hastyanti Hidayat',
                    'username' => 'kasatpel.selatan.setiabudi',
                    'nip' => '198508072010012034',
                    'kecamatan_id' => 32,
                    'unit_id' => $unitSelatan?->id,
                    'password' => bcrypt('!nventory@Selatan2025'),
                    'role_id' => $roleKasatpel->id,
                ],
                [
                    'match' => ['email' => 'kasatpel.selatan.pasarminggu'],
                    'name' => 'Retno Wulandari',
                    'username' => 'kasatpel.selatan.pasarminggu',
                    'nip' => '197904072010012025',
                    'kecamatan_id' => 27,
                    'unit_id' => $unitSelatan?->id,
                    'password' => bcrypt('!nventory@Selatan2025'),
                    'role_id' => $roleKasatpel->id,
                ],
                [
                    'match' => ['email' => 'kasatpel.selatan.tebet'],
                    'name' => 'Rosi Surya Indah',
                    'username' => 'kasatpel.selatan.tebet',
                    'nip' => '197404222014122001',
                    'kecamatan_id' => 31,
                    'unit_id' => $unitSelatan?->id,
                    'password' => bcrypt('!nventory@Selatan2025'),
                    'role_id' => $roleKasatpel->id,
                ],
                [
                    'match' => ['email' => 'kasatpel.selatan.pesanggrahan'],
                    'name' => 'Zulfah Huzaifah',
                    'username' => 'kasatpel.selatan.pesanggrahan',
                    'nip' => '198404272015042002',
                    'kecamatan_id' => 26,
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
