<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UnitKerja;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class SudinJakpusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil unit kerja Sudin SDA Jakarta Pusat
        $unit = UnitKerja::where('nama', 'like', '%Jakarta Pusat%')->first();
        if (!$unit) {
            throw new \Exception('Unit Kerja Sudin SDA Jakarta Pusat tidak ditemukan!');
        }

        // Data user Sudin SDA Jakarta Pusat
        $users = [
            [
                'role' => 'Kepala Suku Dinas',
                'name' => 'Adrian Mara Maulana',
                'nip' => '197503292006041015',
            ],
            [
                'role' => 'Kepala Seksi Perencanaan',
                'name' => 'Dwi Endah Aryaningrum',
                'nip' => '198501172010012002',
            ],
            [
                'role' => 'Staf Seksi Perencanaan',
                'name' => 'Rahmi Agustina',
                'nip' => '199008242022122014',
            ],
            [
                'role' => 'Kepala Sub Bagian Tata Usaha',
                'name' => 'Nila Sari',
                'nip' => '198104252009012001',
            ],
            [
                'role' => 'Pembantu Pengurus Barang I',
                'name' => 'Wawan Hadiyana',
                'nip' => '197408272004101004',
            ],
            [
                'role' => 'Pembantu Pengurus Barang II',
                'name' => 'Mulyanto',
                'nip' => '197802162006041005',
            ],
            [
                'role' => 'Kepala Seksi Pemeliharaan',
                'name' => 'Citrin Indriati',
                'nip' => '197510252009042006',
            ],
            [
                'role' => 'Tim Pendukung PPK',
                'name' => 'Mohammad Irfansyah',
                'nip' => '198410152009041007',
            ],
            [
                'role' => 'Tim Pendukung PPK',
                'name' => 'Heri Hermanto',
                'nip' => '197411252009041006',
            ],
            [
                'role' => 'Tim Pendukung PPK',
                'name' => 'Karsid',
                'nip' => '1975031420121002',
            ],
            [
                'role' => 'Tim Pendukung PPK',
                'name' => 'Rhefa Fauza Setiani',
                'nip' => '199601042022122002',
            ],
            [
                'role' => 'Ketua Satuan Pelaksana Kecamatan Gambir',
                'name' => 'Muhamad Imawan',
                'nip' => '196710191990031001',
            ],
            [
                'role' => 'Driver Kecamatan Gambir',
                'name' => 'Iwam Hanapi',
                'nip' => '8098189',
            ],
            [
                'role' => 'Ketua Satuan Pelaksana Kecamatan Sawah Besar',
                'name' => 'Yusuf Sumardi',
                'nip' => '197803032009041004',
            ],
            [
                'role' => 'Driver Kecamatan Sawah Besar',
                'name' => 'Agus Supriyanti',
                'nip' => '80099451',
            ],
            [
                'role' => 'Ketua Satuan Pelaksana Kecamatan Kemayoran',
                'name' => 'Supriyadi',
                'nip' => '198101052009041002',
            ],
            [
                'role' => 'Driver Kecamatan Kemayoran',
                'name' => 'Bambang Riyanto',
                'nip' => '80734228',
            ],
            [
                'role' => 'Ketua Satuan Pelaksana Kecamatan Senen',
                'name' => 'Zulfahmi',
                'nip' => '197501062009041004',
            ],
            [
                'role' => 'Driver Kecamatan Senen',
                'name' => 'Eko Sugianto',
                'nip' => '80333008',
            ],
            [
                'role' => 'Ketua Satuan Pelaksana Kecamatan Cempaka Putih',
                'name' => 'Muhammad Sahudi',
                'nip' => '197911272009041002',
            ],
            [
                'role' => 'Driver Kecamatan Cempaka Putih',
                'name' => 'Arga Seftiyan Wijaya',
                'nip' => '80049975',
            ],
            [
                'role' => 'Ketua Satuan Pelaksana Kecamatan Menteng',
                'name' => 'Nawan',
                'nip' => '196803081990061001',
            ],
            [
                'role' => 'Driver Kecamatan Menteng',
                'name' => 'Bambang Sunarto',
                'nip' => '80108282',
            ],
            [
                'role' => 'Ketua Satuan Pelaksana Kecamatan Tanah Abang',
                'name' => 'Eli Menawan Sari',
                'nip' => '197706172010012012',
            ],
            [
                'role' => 'Driver Kecamatan Tanah Abang',
                'name' => 'Suryanto',
                'nip' => '80028235',
            ],
            [
                'role' => 'Ketua Satuan Pelaksana Kecamatan Johar Baru',
                'name' => 'Rudy Prasetya',
                'nip' => '198010032006041009',
            ],
            [
                'role' => 'Driver Kecamatan Johar Baru',
                'name' => 'Bambang Riyanto',
                'nip' => '80734228',
            ],
            [
                'role' => 'Kepala Seksi Pembangunan',
                'name' => 'Martineet Felix',
                'nip' => '198506172010011025',
            ],
            [
                'role' => 'Tim Pendukung PPK',
                'name' => 'Devita Octamara',
                'nip' => '197103132022122021',
            ],
            [
                'role' => 'Kepala Seksi Pompa',
                'name' => 'Yusuf Saut Pangibulan',
                'nip' => '198505252010011023',
            ],
            [
                'role' => 'Tim Pendukung PPK',
                'name' => 'Dian Darmaji',
                'nip' => '198209252010011030',
            ],
        ];

        foreach ($users as $userData) {
            $email = Str::of($userData['name'])
                ->replace(['.', ','], '')
                ->replace(' ', '.')
                ->lower()
                ->append('@jakpus.go.id');

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