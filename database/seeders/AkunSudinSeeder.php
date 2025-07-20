<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UnitKerja;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AkunSudinSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin first
        $this->createSuperAdmin();

        // Create users for each Sudin
        $this->createSudinUsers();
    }

    private function createSuperAdmin()
    {
        $user = User::firstOrCreate([
            'email' => 'superadmin@dsda.go.id',
        ], [
            'name' => 'Super Admin Pusdatin',
            'unit_id' => null,
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        $role = Role::where('name', 'Super Admin')->first();
        if ($role) {
            $user->assignRole($role);
        }
    }

    private function createSudinUsers()
    {
        // Template data structure for each Sudin
        $sudinData = [
            'Jakarta Selatan' => [
                ['name' => 'Santo', 'nip' => '197302211996031001', 'role' => 'Kadis', 'position' => 'Kepala Dinas'],
                ['name' => 'Sekdis Jakarta Selatan', 'nip' => '198000000000000001', 'role' => 'Sekdis', 'position' => 'Sekretaris Dinas'],
                ['name' => 'Kasudin Jakarta Selatan', 'nip' => '197000000000000001', 'role' => 'Kasudin', 'position' => 'Kepala Suku Dinas (PPK)'],
                ['name' => 'Kasubag Umum Jakarta Selatan', 'nip' => '197100000000000001', 'role' => 'Kasubag Umum', 'position' => 'Kepala Sub Bagian Umum'],
                ['name' => 'Inge Sukma Yupicha', 'nip' => '198308112010012033', 'role' => 'Kasie Perencanaan', 'position' => 'Kepala Seksi Perencanaan'],
                ['name' => 'Siti Nurjannah', 'nip' => '196806251993032007', 'role' => 'Staff Perencanaan', 'position' => 'Staf Seksi Perencanaan'],
                ['name' => 'Siti Nurjannah', 'nip' => '196806251993032007', 'role' => 'Kasubag TU', 'position' => 'Kepala Sub Bagian Tata Usaha'],
                ['name' => 'Nasrudin Darmadi', 'nip' => '198210062014121003', 'role' => 'Pengurus Barang', 'position' => 'Pengurus Barang'],
                ['name' => 'Paulus Junjung', 'nip' => '198004142010011032', 'role' => 'Kasie Pemeliharaan', 'position' => 'Kepala Seksi Pemeliharaan'],
                ['name' => 'Horas Yosua', 'nip' => '198510262010011014', 'role' => 'Kasie Pembangunan', 'position' => 'Kepala Seksi Pembangunan'],
                ['name' => 'Heriyanto', 'nip' => '198102102010011023', 'role' => 'Kasie Pompa', 'position' => 'Kepala Seksi Pompa'],
                ['name' => 'Yansori', 'nip' => '196903132009041001', 'role' => 'Kasatpel', 'position' => 'Kepala Satuan Pelaksana Cilandak'],
                ['name' => 'Sartono', 'nip' => '197208281994031001', 'role' => 'Kasatpel', 'position' => 'Kepala Satuan Pelaksana Jagakarsa'],
            ],
            'Jakarta Utara' => [
                ['name' => 'Ahmad Saipul', 'nip' => '196709291996031001', 'role' => 'Kadis', 'position' => 'Kepala Dinas'],
                ['name' => 'Sekdis Jakarta Utara', 'nip' => '198000000000000002', 'role' => 'Sekdis', 'position' => 'Sekretaris Dinas'],
                ['name' => 'Kasudin Jakarta Utara', 'nip' => '197000000000000002', 'role' => 'Kasudin', 'position' => 'Kepala Suku Dinas (PPK)'],
                ['name' => 'Kasubag Umum Jakarta Utara', 'nip' => '197100000000000002', 'role' => 'Kasubag Umum', 'position' => 'Kepala Sub Bagian Umum'],
                ['name' => 'Apriyani Talalohu', 'nip' => '197604052008042001', 'role' => 'Kasie Perencanaan', 'position' => 'Kepala Seksi Perencanaan'],
                ['name' => 'Desni Citra Mumpuni', 'nip' => '199512120019032012', 'role' => 'Staff Perencanaan', 'position' => 'Staf Seksi Perencanaan'],
                ['name' => 'Deny Tri Hendarto', 'nip' => '196810202003011002', 'role' => 'Kasubag TU', 'position' => 'Kepala Sub Bagian Tata Usaha'],
                ['name' => 'Pengurus Barang Jakut', 'nip' => '198000000000000003', 'role' => 'Pengurus Barang', 'position' => 'Pengurus Barang'],
                ['name' => 'Kasie Pemeliharaan Jakut', 'nip' => '198000000000000004', 'role' => 'Kasie Pemeliharaan', 'position' => 'Kepala Seksi Pemeliharaan'],
                ['name' => 'PPK Tim Jakut', 'nip' => '198000000000000005', 'role' => 'Tim Pendukung PPK', 'position' => 'Tim Pendukung PPK'],
            ],
            'Jakarta Pusat' => [
                ['name' => 'Kadis Jakarta Pusat', 'nip' => '197503292006041015', 'role' => 'Kadis', 'position' => 'Kepala Dinas'],
                ['name' => 'Sekdis Jakarta Pusat', 'nip' => '198000000000000006', 'role' => 'Sekdis', 'position' => 'Sekretaris Dinas'],
                ['name' => 'Kasie Perencanaan Jakpus', 'nip' => '198501172010012002', 'role' => 'Kasie Perencanaan', 'position' => 'Kepala Seksi Perencanaan'],
                ['name' => 'Staff Perencanaan Jakpus', 'nip' => '199008242022122014', 'role' => 'Staff Perencanaan', 'position' => 'Staf Seksi Perencanaan'],
                ['name' => 'Kasubag TU Jakpus', 'nip' => '198104252009012001', 'role' => 'Kasubag TU', 'position' => 'Kepala Sub Bagian Tata Usaha'],
                ['name' => 'Pengurus Barang Jakpus', 'nip' => '197408272004101004', 'role' => 'Pengurus Barang', 'position' => 'Pengurus Barang'],
                ['name' => 'PPK Tim Jakpus', 'nip' => '197802162006041005', 'role' => 'Tim Pendukung PPK', 'position' => 'Tim Pendukung PPK'],
            ],
            'Jakarta Barat' => [
                ['name' => 'Purwanti Suryandari', 'nip' => '197501912001122001', 'role' => 'Kadis', 'position' => 'Kepala Dinas'],
                ['name' => 'Sekdis Jakarta Barat', 'nip' => '198000000000000007', 'role' => 'Sekdis', 'position' => 'Sekretaris Dinas'],
                ['name' => 'Islauni Juliana', 'nip' => '198707092010012022', 'role' => 'Kasie Perencanaan', 'position' => 'Kepala Seksi Perencanaan'],
                ['name' => 'Maria Alvina Angelica', 'nip' => '197603022012012017', 'role' => 'Staff Perencanaan', 'position' => 'Staf Seksi Perencanaan'],
                ['name' => 'Eko Wahyono', 'nip' => '197802031998031004', 'role' => 'Kasubag TU', 'position' => 'Kepala Sub Bagian Tata Usaha'],
                ['name' => 'Mohkamad Zahrani', 'nip' => '199711102021101004', 'role' => 'Pengurus Barang', 'position' => 'Pengurus Barang'],
                ['name' => 'Yopi Naidiza Siregar', 'nip' => '197905222010011016', 'role' => 'Kasie Pemeliharaan', 'position' => 'Kepala Seksi Pemeliharaan'],
                ['name' => 'Arief Chandra Pamungkas', 'nip' => '199009062006041006', 'role' => 'Tim Pendukung PPK', 'position' => 'Tim Pendukung PPK'],
            ],
            'Jakarta Timur' => [
                ['name' => 'Kadis Jakarta Timur', 'nip' => '197001011990031001', 'role' => 'Kadis', 'position' => 'Kepala Dinas'],
                ['name' => 'Sekdis Jakarta Timur', 'nip' => '198000000000000008', 'role' => 'Sekdis', 'position' => 'Sekretaris Dinas'],
                ['name' => 'Kasie Perencanaan Jaktim', 'nip' => '198001011990031002', 'role' => 'Kasie Perencanaan', 'position' => 'Kepala Seksi Perencanaan'],
                ['name' => 'Staff Perencanaan Jaktim', 'nip' => '199001011990031003', 'role' => 'Staff Perencanaan', 'position' => 'Staf Seksi Perencanaan'],
                ['name' => 'Kasubag TU Jaktim', 'nip' => '200001011990031004', 'role' => 'Kasubag TU', 'position' => 'Kepala Sub Bagian Tata Usaha'],
                ['name' => 'Pengurus Barang Jaktim', 'nip' => '201001011990031005', 'role' => 'Pengurus Barang', 'position' => 'Pengurus Barang'],
            ],
            'Kepulauan Seribu' => [
                ['name' => 'Mustajab', 'nip' => '197101181979031005', 'role' => 'Kadis', 'position' => 'Kepala Dinas'],
                ['name' => 'Sekdis Kepulauan Seribu', 'nip' => '198000000000000009', 'role' => 'Sekdis', 'position' => 'Sekretaris Dinas'],
                ['name' => 'Efit Wiyati', 'nip' => '1972022210012012', 'role' => 'Kasie Perencanaan', 'position' => 'Kepala Seksi Perencanaan'],
                ['name' => 'Sofar Wahyu Asmoroajit', 'nip' => '1974062514110202', 'role' => 'Staff Perencanaan', 'position' => 'Staf Seksi Perencanaan'],
                ['name' => 'Geoffrey Rejoice Novena', 'nip' => '198801122009041001', 'role' => 'Kasubag TU', 'position' => 'Kepala Sub Bagian Tata Usaha'],
                ['name' => 'Abdullah Syafii', 'nip' => '198104252009041002', 'role' => 'Tim Pendukung PPK', 'position' => 'Tim Pendukung PPK'],
                ['name' => 'Sri Handayani', 'nip' => '198012202003012002', 'role' => 'Tim Pendukung PPK', 'position' => 'Tim Pendukung PPK'],
            ],
        ];

        foreach ($sudinData as $sudinName => $users) {
            $unit = UnitKerja::where('nama', 'like', "%{$sudinName}%")->first();
            if (!$unit) {
                continue; // Skip if unit not found
            }

            foreach ($users as $userData) {
                $email = Str::of($userData['name'])
                    ->replace(['.', ','], '')
                    ->replace(' ', '.')
                    ->lower()
                    ->append('@' . Str::slug($sudinName) . '.dsda.go.id');

                $user = User::firstOrCreate([
                    'email' => $email,
                ], [
                    'name' => $userData['name'],
                    'unit_id' => $unit->id, // This is correct - UnitKerja model has id field
                    'nip' => $userData['nip'],
                    'password' => bcrypt('password123'),
                    'email_verified_at' => now(),
                ]);

                // Assign standardized role
                $role = Role::where('name', $userData['role'])->first();
                if ($role) {
                    $user->syncRoles([$role]); // Use sync to replace existing roles
                }
            }
        }
    }
}
