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
        // Bersihkan duplikasi data seeder sebelumnya
        User::where('email', 'kasatpel.selatan.setiabudi@test.com')->delete();

        $roleKasatpel = Role::firstOrCreate([
            'name' => 'Kepala Satuan Pelaksana',
            'guard_name' => 'web',
        ]);

        $roleStaff = Role::firstOrCreate([
            'name' => 'Pengurus Barang',
            'guard_name' => 'web',
        ]);

        // Unit Kerja Utama secara dinamis
        $unitUtara = UnitKerja::where('nama', 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Utara')->first();
        $unitSelatan = UnitKerja::where('nama', 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Selatan')->first();

        // Sub-unit untuk Tatang Sunjaya (Seksi Sarana Jakarta Utara)
        $seksiSaranaUtara = UnitKerja::where('nama', 'Seksi Pengelolaan Sarana Pengendali Banjir, Air Bersih dan Air Limbah')
            ->where('parent_id', $unitUtara?->id)
            ->first();

        // Data Users No. 1 sampai 6
        $usersData = [
            // 1. Tatang Sunjaya
            [
                'match' => ['email' => 'tatang.sunjaya@test.com'],
                'data' => [
                    'name' => 'Tatang Sunjaya',
                    'username' => 'tatang.sunjaya',
                    'nip' => '197605152009041003',
                    'unit_id' => $seksiSaranaUtara?->id,
                    'password' => bcrypt('!nventory@Utara2025'),
                    'email_verified_at' => now(),
                ],
                'role_id' => $roleStaff->id,
            ],
            // 2. Novita Kesnanda
            [
                'match' => ['email' => 'kasatpel.utara.pademangan@test.com'],
                'data' => [
                    'name' => 'Novita Kesnanda',
                    'username' => 'kasatpel.utara.pademangan',
                    'nip' => '198311122010012030',
                    'kecamatan_id' => 10,
                    'unit_id' => $unitUtara?->id,
                    'password' => bcrypt('!nventory@Utara2025'),
                    'email_verified_at' => now(),
                ],
                'role_id' => $roleKasatpel->id,
            ],
            // 3. Hastyanti Hidayat (Kasatpel Setiabudi)
            [
                'match' => ['email' => 'sda.setiabudijaksel@gmail.com'], // Menggunakan email Gmail aktif
                'data' => [
                    'name' => 'Hastyanti Hidayat',
                    'username' => 'kasatpel.selatan.setiabudi',
                    'nip' => '198508072010012034',
                    'kecamatan_id' => 32,
                    'unit_id' => $unitSelatan?->id,
                    'password' => bcrypt('!nventory@Selatan2025'),
                    'email_verified_at' => now(),
                ],
                'role_id' => $roleKasatpel->id,
            ],
            // 4. Retno Wulandari (Kasatpel Pasar Minggu)
            [
                'match' => ['email' => 'kasatpel.selatan.pasarminggu@test.com'],
                'data' => [
                    'name' => 'Retno Wulandari',
                    'username' => 'kasatpel.selatan.pasarminggu',
                    'nip' => '197904072010012025',
                    'kecamatan_id' => 27,
                    'unit_id' => $unitSelatan?->id,
                    'password' => bcrypt('!nventory@Selatan2025'),
                    'email_verified_at' => now(),
                ],
                'role_id' => $roleKasatpel->id,
            ],
            // 5. Rosi Surya Indah (Kasatpel Tebet)
            [
                'match' => ['email' => 'kasatpel.selatan.tebet@test.com'],
                'data' => [
                    'name' => 'Rosi Surya Indah',
                    'username' => 'kasatpel.selatan.tebet',
                    'nip' => '197404222014122001',
                    'kecamatan_id' => 31,
                    'unit_id' => $unitSelatan?->id,
                    'password' => bcrypt('!nventory@Selatan2025'),
                    'email_verified_at' => now(),
                ],
                'role_id' => $roleKasatpel->id,
            ],
            // 6. Zulfah Huzaifah (Kasatpel Pesanggrahan)
            [
                'match' => ['email' => 'kasatpel.selatan.pesanggrahan@test.com'],
                'data' => [
                    'name' => 'Zulfah Huzaifah',
                    'username' => 'kasatpel.selatan.pesanggrahan',
                    'nip' => '198404272015042002',
                    'kecamatan_id' => 26,
                    'unit_id' => $unitSelatan?->id,
                    'password' => bcrypt('!nventory@Selatan2025'),
                    'email_verified_at' => now(),
                ],
                'role_id' => $roleKasatpel->id,
            ],
        ];

        foreach ($usersData as $uData) {
            $user = User::updateOrCreate($uData['match'], $uData['data']);
            $user->roles()->syncWithoutDetaching([$uData['role_id']]);
            $this->command->info("User [{$user->email}] berhasil di-seed.");
        }

        Model::reguard();
    }
}
