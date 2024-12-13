<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UnitKerja;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $faker = Faker::create('id_ID');

        $units = [
            'Sekretaris Dinas' => [
                'kepala' => 'Hendri, ST, MT',
                'sub_units' => [
                    ['nama' => 'Subbagian Umum', 'kepala' => 'Putu Riska Komala Putri, ST'],
                    ['nama' => 'Subkelompok Kepegawaian', 'kepala' => 'Ratna Pertiwi, ST'],
                    ['nama' => 'Subkelompok Program dan Pelaporan', 'kepala' => 'Astrid Marzia Damayanti, ST'],
                    ['nama' => 'Subbagian Keuangan', 'kepala' => 'Indra Prabowo, SE'],
                ],
            ],
            'Bidang Pengendalian Banjir dan Drainase' => [
                'kepala' => 'Ika Agustin Ningrum, ST, MPSDA',
                'sub_units' => [
                    ['nama' => 'Subkelompok Perencanaan', 'kepala' => 'Vega Fitria Mutiara Sari, ST, M.T'],
                    ['nama' => 'Subkelompok Pengendalian Banjir', 'kepala' => 'Ericson Indra Pulungan, ST, MT'],
                    ['nama' => 'Subkelompok Drainase', 'kepala' => 'Firmansyah Saputra, ST'],
                ],
            ],
            'Bidang Geologi, Konservasi Air Baku dan Penyediaan Air Bersih' => [
                'kepala' => 'Nelson, ST, MT',
                'sub_units' => [
                    ['nama' => 'Subkelompok Perencanaan', 'kepala' => 'Elisabeth Tarigan, ST, M.IWM'],
                    ['nama' => 'Subkelompok Geologi dan Konservasi Air Baku', 'kepala' => 'Ikhwan Maulani, ST, MT'],
                    ['nama' => 'Subkelompok Pengendalian dan Penyediaan Air Bersih', 'kepala' => 'Maman Supratman, ST, M.Sc'],
                ],
            ],
            'Bidang Pengendalian Rob dan Pengembangan Pesisir Pantai' => [
                'kepala' => 'Ciko Tricanescoro, ST, M.Sc',
                'sub_units' => [
                    ['nama' => 'Subkelompok Perencanaan', 'kepala' => 'Alfan Widyastanto, ST'],
                    ['nama' => 'Subkelompok Pengendalian Rob dan Pengamanan Pesisir Pantai', 'kepala' => 'Achmad Daeroby, ST'],
                    ['nama' => 'Subkelompok Pengembangan Pesisir Pantai', 'kepala' => 'Yursid Suryanegara, ST'],
                ],
            ],
            'Bidang Pengelolaan Air Limbah' => [
                'kepala' => 'Robby Dwi Mariansyah, ST',
                'sub_units' => [
                    ['nama' => 'Subkelompok Perencanaan', 'kepala' => 'Sarah Dewi Yani, ST, MT'],
                    ['nama' => 'Subkelompok Pembangunan Sarana dan Prasarana Pengelolaan Air Limbah', 'kepala' => 'Heria Suwandi, ST'],
                    ['nama' => 'Subkelompok Peningkatan dan Pengendalian Air Limbah', 'kepala' => 'Glenn Santista, ST'],
                ],
            ],
            'Unit Pengelola Penyelidikan, Pengujian dan Pengukuran Sumber Daya Air' => [
                'kepala' => 'Nur Aprileny, ST, MT',
                'sub_units' => [
                    ['nama' => 'Subbagian Tata Usaha', 'kepala' => 'Niswatin Farika, ST, MT'],
                ],
            ],
            'Unit Peralatan dan Perbekalan Sumber Daya Air' => [
                'kepala' => 'Yose Rizal, ST, MT',
                'sub_units' => [
                    ['nama' => 'Subbagian Tata Usaha', 'kepala' => 'Sofia Ismiati, ST'],
                ],
            ],
            'Pusat Data dan Informasi Sumber Daya Air' => [
                'kepala' => 'Drs. Nugraharyadi',
                'sub_units' => [
                    ['nama' => 'Subbagian Tata Usaha', 'kepala' => 'Gde Made Panji Diarsa, S.Kom, M.T.I'],
                ],
            ],
            'Unit Pengadaan Tanah Sumber Daya Air' => [
                'kepala' => 'Roedito Setiawan, SH',
                'sub_units' => [
                    ['nama' => 'Subbagian Tata Usaha', 'kepala' => 'Ibnu Affan, ST'],
                ],
            ],
            'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Pusat' => [
                'kepala' => 'Adrian Mara Maulana, ST, M.Si',
                'sub_units' => [
                    ['nama' => 'Subbagian Tata Usaha', 'kepala' => 'Evy Af Ida, S.Sos, M.Si'],
                    ['nama' => 'Seksi Perencanaan', 'kepala' => 'Dwi Endah Aryaningrum, ST'],
                    ['nama' => 'Seksi Pemeliharaan Drainase', 'kepala' => 'Citrin Indriati, ST'],
                    ['nama' => 'Seksi Pembangunan dan Peningkatan Drainase', 'kepala' => 'Martineet Felix, ST'],
                    ['nama' => 'Seksi Pengelolaan Sarana Pengendali Banjir, Air Bersih dan Air Limbah', 'kepala' => 'Yusuf Saut Pangibulan, ST, MPSDA'],
                ],
            ],
            'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Utara' => [
                'kepala' => 'Ir. Ahmad Saipul, MM',
                'sub_units' => [
                    ['nama' => 'Subbagian Tata Usaha', 'kepala' => 'Deny Tri Hendarto, SE'],
                    ['nama' => 'Seksi Perencanaan', 'kepala' => 'Apriyani Talaohu, ST, MT'],
                    ['nama' => 'Seksi Pemeliharaan Drainase', 'kepala' => 'Yudo Widiatmoko, ST'],
                    ['nama' => 'Seksi Pembangunan dan Peningkatan Drainase', 'kepala' => 'Boris Karlop Lumbangaol, ST, MT'],
                    ['nama' => 'Seksi Pengelolaan Sarana Pengendali Banjir, Air Bersih dan Air Limbah', 'kepala' => 'Frans Agustinus Siahaan, ST, MT'],
                ],
            ],
            'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Barat' => [
                'kepala' => 'Purwanti Suryandari, ST, MM',
                'sub_units' => [
                    ['nama' => 'Subbagian Tata Usaha', 'kepala' => 'Eko Wahyono, SE'],
                    ['nama' => 'Seksi Perencanaan', 'kepala' => 'Islauni Juliana, ST'],
                    ['nama' => 'Seksi Pemeliharaan Drainase', 'kepala' => 'Yopi Maidiza Siregar, ST'],
                    ['nama' => 'Seksi Pembangunan dan Peningkatan Drainase', 'kepala' => 'Imam Prasetyo, ST, MT'],
                    ['nama' => 'Seksi Pengelolaan Sarana Pengendali Banjir, Air Bersih dan Air Limbah', 'kepala' => 'Wira Yudha Bhakti, ST, MT'],
                ],
            ],
            'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Selatan' => [
                'kepala' => 'Santo, SST, M.Si',
                'sub_units' => [
                    ['nama' => 'Subbagian Tata Usaha', 'kepala' => 'Siti Nurjannah, M.Si'],
                    ['nama' => 'Seksi Perencanaan', 'kepala' => 'Inge Sukma Yupicha, ST, MT'],
                    ['nama' => 'Seksi Pemeliharaan Drainase', 'kepala' => 'Paulus Junjung, ST'],
                    ['nama' => 'Seksi Pembangunan dan Peningkatan Drainase', 'kepala' => 'Horas Yosua, ST, MT'],
                    ['nama' => 'Seksi Pengelolaan Sarana Pengendali Banjir, Air Bersih dan Air Limbah', 'kepala' => 'Heriyanto, ST'],
                ],
            ],
            'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Timur' => [
                'kepala' => 'Ir. Abdul Rauf Gaffar, MT',
                'sub_units' => [
                    ['nama' => 'Subbagian Tata Usaha', 'kepala' => 'Herawan, SE, MM'],
                    ['nama' => 'Seksi Perencanaan', 'kepala' => 'Fajar Avisena, ST'],
                    ['nama' => 'Seksi Pemeliharaan Drainase', 'kepala' => 'Puryanto Palebangan, ST'],
                    ['nama' => 'Seksi Pembangunan dan Peningkatan Drainase', 'kepala' => 'Tengku Saugi Zikri, ST'],
                    ['nama' => 'Seksi Pengelolaan Sarana Pengendali Banjir, Air Bersih dan Air Limbah', 'kepala' => 'John Christian Tarigan, ST'],
                ],
            ],
            'Suku Dinas Sumber Daya Air Kabupaten Administrasi Kepulauan Seribu' => [
                'kepala' => 'Mustajab, ST',
                'sub_units' => [
                    ['nama' => 'Subbagian Tata Usaha', 'kepala' => 'Geofrey Rejoice Novena, S.Kom'],
                    ['nama' => 'Seksi Perencanaan', 'kepala' => 'Fitri Wiyati, ST, MT'],
                    ['nama' => 'Seksi Pengembangan dan Pengamanan Pesisir Pantai', 'kepala' => 'Wahyu Maulana, ST'],
                    ['nama' => 'Seksi Air Bersih dan Air Limbah', 'kepala' => 'Rezky Arie Pranata, ST'],
                ],
            ],
        ];
        $roles = ['Penanggung Jawab', 'Pejabat Pembuat Komitmen', 'Pejabat Pelaksana Teknis Kegiatan', 'Penerima Barang', 'Pemeriksa Barang', 'Pengurus Barang', 'Penjaga Gudang'];



        $superRole = Role::firstOrCreate([
            'name' => 'superadmin',
            'guard_name' => 'web',
        ]);
        $guestRole = Role::firstOrCreate([
            'name' => 'guest',
            'guard_name' => 'web',
        ]);
        $superUser = User::create([
            'name' => 'superadmin',
            'email' => 'superadmin@email.com',
            'unit_id' => null,
            'lokasi_id' => null,
            'password' => bcrypt('123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $guestUser = User::create([
            'name' => 'guest',
            'email' => 'guest@email.com',
            'unit_id' => null,
            'lokasi_id' => null,
            'password' => bcrypt('123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $superUser->roles()->attach($superRole->id);
        $guestUser->roles()->attach($guestRole->id);

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }
        foreach ($units as $unitName => $unitData) {
            // Simpan unit
            $unit = UnitKerja::create([
                'nama' => $unitName,
                'kode' => strtoupper(substr(str_replace('Bidang', '', $unitName), 0, 3)), // Membuat kode dari 3 huruf pertama nama unit
                'parent_id' => null, // Unit utama
                'keterangan' => "Unit $unitName.",
            ]);
            if (Str::contains($unitName, 'Suku Dinas Sumber Daya Air')) {
                // Buat kepala unit utama
                $unitRole = Role::firstOrCreate([
                    'name' => 'Kepala Suku Dinas Sumber Daya Air',
                    'guard_name' => 'web',
                ]);
            } else {
                // Buat kepala unit utama
                $unitRole = Role::firstOrCreate([
                    'name' => 'Kepala ' . $unitName,
                    'guard_name' => 'web',
                ]);
            }
            $unitUser = User::create([
                'name' => $unitData['kepala'],
                'unit_id' => $unit->id,
                'email' => strtolower($faker->freeEmail()),
                'password' => bcrypt('123'), // Password default
            ]);
            $unitUser->roles()->attach($unitRole->id);
            $roleMulti = ['Pejabat Pelaksana Teknis Kegiatan'];
            foreach ($roleMulti as $role) {
                for ($i = 1; $i <= 3; $i++) {
                    User::create([
                        'name' => $faker->name(),
                        'unit_id' => $unit->id,
                        'email' => $faker->freeEmail(),
                        'password' => bcrypt('123'), // Password default
                    ])->roles()->attach(Role::where('name', $role)->first()->id);
                }
            }
            $roleOnce = ['Pejabat Pembuat Komitmen', 'Penanggung Jawab', 'Pemeriksa Barang', 'Pengurus Barang'];
            foreach ($roleOnce as $item) {
                User::create([
                    'name' => $faker->name(),
                    'unit_id' => $unit->id,
                    'email' => $faker->freeEmail(),
                    'password' => bcrypt('123'), // Password default
                ])->roles()->attach(Role::where('name', $item)->first()->id);
            }
            // Simpan sub-unit
            foreach ($unitData['sub_units'] as $subUnit) {
                $subUnitEntry =  UnitKerja::create([
                    'nama' => $subUnit['nama'],
                    'kode' => strtoupper(substr(str_replace('Subkelompok', '', $subUnit['nama']), 0, 3)),
                    'parent_id' => $unit->id, // Sub-unit terkait dengan unit
                    'keterangan' => "Sub-unit $subUnit[nama].",
                ]);

                // Buat Jabatan
                $role = Role::firstOrCreate([
                    'name' => 'Kepala ' . $subUnit['nama'],
                    'guard_name' => 'web',
                ]);

                // Buat User
                $user = User::create([
                    'name' => $subUnit['kepala'],
                    'unit_id' => $subUnitEntry->id,
                    'email' => strtolower($faker->freeEmail()),
                    'password' => bcrypt('123'), // Password default
                ]);
                $user->roles()->attach($role->id);
            }
        }
    }
}
