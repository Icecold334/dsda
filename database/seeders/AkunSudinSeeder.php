<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AkunSudinSeeder extends Seeder
{
    public function run(): void
    {
        // Role mapping dari nama asli ke permission role
        $roleMapping = [
            'Kepala Suku Dinas' => 'Kepala Suku Dinas',
            'Kepala Seksi Perencanaan' => 'Kepala Seksi',
            'Staf Seksi Perencanaan' => 'Perencanaan',
            'Kepala Sub Bagian Tata Usaha' => 'Kepala Subbagian Tata Usaha',
            'Pembantu Pengurus Barang I' => 'Pengurus Barang',
            'Pembantu Pengurus Barang II' => 'Pengurus Barang',
            'Kepala Seksi Pemeliharaan' => 'Kepala Seksi',
            'Tim Pendukung PPK' => 'P3K',
            'Ketua Satuan Pelaksana Kecamatan Gambir' => 'Kepala Satuan Pelaksana',
            'Ketua Satuan Pelaksana Kecamatan Sawah Besar' => 'Kepala Satuan Pelaksana',
            'Ketua Satuan Pelaksana Kecamatan Kemayoran' => 'Kepala Satuan Pelaksana',
            'Ketua Satuan Pelaksana Kecamatan Senen' => 'Kepala Satuan Pelaksana',
            'Ketua Satuan Pelaksana Kecamatan Cempaka Putih' => 'Kepala Satuan Pelaksana',
            'Ketua Satuan Pelaksana Kecamatan Menteng' => 'Kepala Satuan Pelaksana',
            'Ketua Satuan Pelaksana Kecamatan Tanah Abang' => 'Kepala Satuan Pelaksana',
            'Ketua Satuan Pelaksana Kecamatan Johar Baru' => 'Kepala Satuan Pelaksana',
            'Kepala Seksi Pembangunan' => 'Kepala Seksi',
            'Kepala Seksi Pompa' => 'Kepala Seksi',
        ];

        // Data user dari JSON
        $userData = [
            ['role' => 'Kepala Suku Dinas', 'nama' => 'Adrian Mara Maulana, ST. M.Si', 'nip' => '197503292006041015'],
            ['role' => 'Kepala Seksi Perencanaan', 'nama' => 'Dwi Endah Aryaningrum, ST', 'nip' => '198501172010012022'],
            ['role' => 'Staf Seksi Perencanaan', 'nama' => 'Rahmi Agustina, ST', 'nip' => '199008192020122014'],
            ['role' => 'Kepala Sub Bagian Tata Usaha', 'nama' => 'Nila Sari, ST', 'nip' => '198404072010012034'],
            ['role' => 'Pembantu Pengurus Barang I', 'nama' => 'Wawan Hadiyana, ST', 'nip' => '197008272009041001'],
            ['role' => 'Pembantu Pengurus Barang II', 'nama' => 'Mulyanto, ST', 'nip' => '197802162009041005'],
            ['role' => 'Kepala Seksi Pemeliharaan', 'nama' => 'Citrin Indriati, ST', 'nip' => '198110202010012016'],
            ['role' => 'Tim Pendukung PPK', 'nama' => 'Mohammad Irfansyah, ST', 'nip' => '198004152009041007'],
            ['role' => 'Tim Pendukung PPK', 'nama' => 'Herri Hermawan, ST', 'nip' => '198105192009041006'],
            ['role' => 'Tim Pendukung PPK', 'nama' => 'Karsid', 'nip' => '197503182014121002'],
            ['role' => 'Tim Pendukung PPK', 'nama' => 'Rhefa Fauza Setiani, ST', 'nip' => '199609142022032009'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Gambir', 'nama' => 'Muhamad Imawan, ST', 'nip' => '196710191990081001'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Sawah Besar', 'nama' => 'Yusuf Sumardani, ST', 'nip' => '197803032009041004'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Kemayoran', 'nama' => 'Supriyadi, ST', 'nip' => '198101052009041002'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Senen', 'nama' => 'Zulfahmi, ST', 'nip' => '197501062009041004'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Cempaka Putih', 'nama' => 'Muhammad Sahudi, ST', 'nip' => '197911272009041002'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Menteng', 'nama' => 'Nawan, SAP', 'nip' => '196803081990061001'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Tanah Abang', 'nama' => 'Eli Menawan Sari, ST', 'nip' => '197706172010012012'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Johar Baru', 'nama' => 'Rudy Prasetya, ST', 'nip' => '198010032006041009'],
            ['role' => 'Kepala Seksi Pembangunan', 'nama' => 'Martineet Felix, ST', 'nip' => '198506172010011025'],
            ['role' => 'Tim Pendukung PPK', 'nama' => 'Devita Octamara', 'nip' => '199710312020122021'],
            ['role' => 'Kepala Seksi Pompa', 'nama' => 'Yusuf Saut Pangibulan, ST, MPSDA', 'nip' => '198505252010011023'],
            ['role' => 'Tim Pendukung PPK', 'nama' => 'Dian Darmaji, ST', 'nip' => '198209252010011030'],
        ];

        foreach ($userData as $data) {
            if (empty($data['nama']) || empty($data['nip'])) {
                continue; // Skip empty entries
            }

            $permissionRole = $roleMapping[$data['role']] ?? null;
            if (!$permissionRole) {
                continue; // Skip if no mapping found
            }

            $user = User::firstOrCreate(
                ['email' => strtolower(str_replace(' ', '.', $data['nama'])) . '@jakarta.go.id'],
                [
                    'name' => $data['nama'],
                    'nip' => $data['nip'],
                    'password' => Hash::make('password123'),
                    'email_verified_at' => now(),
                ]
            );

            // Assign role berdasarkan mapping
            $role = Role::firstOrCreate(['name' => $permissionRole]);
            if (!$user->hasRole($role)) {
                $user->assignRole($role);
            }
        }
    }
}
