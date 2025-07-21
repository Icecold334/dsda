<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UnitKerja;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AkunSudinSeeder extends Seeder
{
    public function run(): void
    {
        // Cari unit Jakarta Pusat
        $unitJakpus = UnitKerja::where('nama', 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Pusat')->first();

        if (!$unitJakpus) {
            throw new \Exception('Unit Jakarta Pusat tidak ditemukan. Pastikan UnitSeeder sudah dijalankan terlebih dahulu.');
        }

        // Role mapping dari nama asli ke permission role dan email format
        $roleMapping = [
            'Kepala Suku Dinas' => ['role' => 'Kepala Suku Dinas', 'email' => 'kasudin.pusat@test.com'],
            'Kepala Seksi Perencanaan' => ['role' => 'Kepala Seksi', 'email' => 'kasie_perencanaan.pusat@test.com'],
            'Staf Seksi Perencanaan' => ['role' => 'Perencanaan', 'email' => 'perencanaan.pusat@test.com'],
            'Kepala Sub Bagian Tata Usaha' => ['role' => 'Kepala Subbagian Tata Usaha', 'email' => 'kasubagtu.pusat@test.com'],
            'Pembantu Pengurus Barang I' => ['role' => 'Pengurus Barang', 'email' => 'pb.pusat@test.com'],
            'Pembantu Pengurus Barang II' => ['role' => 'Pengurus Barang', 'email' => null], // Skip duplicate
            'Kepala Seksi Pemeliharaan' => ['role' => 'Kepala Seksi', 'email' => 'kasipemel.pusat@test.com'],
            'Tim Pendukung PPK' => ['role' => 'P3K', 'email' => 'p3k.pusat@test.com'],
            'Ketua Satuan Pelaksana Kecamatan Gambir' => ['role' => 'Kepala Satuan Pelaksana', 'email' => 'kasatpel.pusat.gambir@test.com'],
            'Ketua Satuan Pelaksana Kecamatan Sawah Besar' => ['role' => 'Kepala Satuan Pelaksana', 'email' => null], // Skip duplicate
            'Ketua Satuan Pelaksana Kecamatan Kemayoran' => ['role' => 'Kepala Satuan Pelaksana', 'email' => null], // Skip duplicate
            'Ketua Satuan Pelaksana Kecamatan Senen' => ['role' => 'Kepala Satuan Pelaksana', 'email' => null], // Skip duplicate
            'Ketua Satuan Pelaksana Kecamatan Cempaka Putih' => ['role' => 'Kepala Satuan Pelaksana', 'email' => null], // Skip duplicate
            'Ketua Satuan Pelaksana Kecamatan Menteng' => ['role' => 'Kepala Satuan Pelaksana', 'email' => null], // Skip duplicate
            'Ketua Satuan Pelaksana Kecamatan Tanah Abang' => ['role' => 'Kepala Satuan Pelaksana', 'email' => null], // Skip duplicate
            'Ketua Satuan Pelaksana Kecamatan Johar Baru' => ['role' => 'Kepala Satuan Pelaksana', 'email' => null], // Skip duplicate
            'Kepala Seksi Pembangunan' => ['role' => 'Kepala Seksi', 'email' => 'kasie_pembangunan.pusat@test.com'],
            'Kepala Seksi Pompa' => ['role' => 'Kepala Seksi', 'email' => 'kasie_pompa@test.com'],
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

            $mapping = $roleMapping[$data['role']] ?? null;
            if (!$mapping || !$mapping['email']) {
                continue; // Skip if no mapping found or email is null (duplicate role)
            }

            $permissionRole = $mapping['role'];
            $email = $mapping['email'];

            // Tentukan unit berdasarkan role
            $targetUnit = $unitJakpus; // Default ke unit utama

            // Untuk Kepala Suku Dinas, gunakan unit utama
            if ($data['role'] === 'Kepala Suku Dinas') {
                $targetUnit = $unitJakpus;
            } else {
                // Untuk role lainnya, cari sub unit yang sesuai
                if (strpos($data['role'], 'Perencanaan') !== false) {
                    $targetUnit = $unitJakpus->children()->where('nama', 'LIKE', '%Perencanaan%')->first() ?? $unitJakpus;
                } elseif (strpos($data['role'], 'Tata Usaha') !== false) {
                    $targetUnit = $unitJakpus->children()->where('nama', 'LIKE', '%Tata Usaha%')->first() ?? $unitJakpus;
                } elseif (strpos($data['role'], 'Pemeliharaan') !== false) {
                    $targetUnit = $unitJakpus->children()->where('nama', 'LIKE', '%Pemeliharaan%')->first() ?? $unitJakpus;
                } elseif (strpos($data['role'], 'Pembangunan') !== false) {
                    $targetUnit = $unitJakpus->children()->where('nama', 'LIKE', '%Pembangunan%')->first() ?? $unitJakpus;
                } elseif (strpos($data['role'], 'Pompa') !== false) {
                    $targetUnit = $unitJakpus->children()->where('nama', 'LIKE', '%Pompa%')->first() ?? $unitJakpus;
                }
            }

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $data['nama'],
                    'nip' => $data['nip'],
                    'unit_id' => $targetUnit ? $targetUnit->getKey() : null,
                    'password' => Hash::make('test@123'),
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
