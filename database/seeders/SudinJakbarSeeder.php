<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UnitKerja;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class SudinJakbarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil unit kerja Sudin SDA Jakarta Barat
        $unit = UnitKerja::where('nama', 'like', '%Jakarta Barat%')->first();
        if (!$unit) {
            throw new \Exception('Unit Kerja Sudin SDA Jakarta Barat tidak ditemukan!');
        }

        // Data user Sudin SDA Jakarta Barat
        $users = [
            ['role' => 'Kepala Suku Dinas', 'name' => 'Purwanti Suryandari', 'nip' => '197501912001122001'],
            ['role' => 'Kepala Seksi Perencanaan', 'name' => 'Islauni Juliana', 'nip' => '198707092010012022'],
            ['role' => 'Staf Seksi Perencanaan', 'name' => 'Maria Alvina Angelica', 'nip' => '197603022012012017'],
            ['role' => 'Kepala Sub Bagian Tata Usaha', 'name' => 'Eko Wahyono', 'nip' => '197802031998031004'],
            ['role' => 'Pembantu Pengurus Barang I', 'name' => 'Mohkamad Zahrani', 'nip' => '199711102021101004'],
            ['role' => 'Pembantu Pengurus Barang II', 'name' => 'Yopi Naidiza Siregar', 'nip' => '197905222010011016'],
            ['role' => 'Kepala Seksi Pemeliharaan', 'name' => 'Yopi Naidiza Siregar', 'nip' => '197905222010011016'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Arief Chandra Pamungkas', 'nip' => '199009062006041006'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Jani Kurniawan', 'nip' => '199010262021101016'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Muji Pranoto', 'nip' => '197612262004101004'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Sumitra', 'nip' => ''],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Maria Alvina Angelica', 'nip' => '197603022012012017'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Cengkareng', 'name' => 'Mulyadi', 'nip' => ''],
            ['role' => 'Driver Kecamatan Cengkareng', 'name' => 'Abdul Mutholib', 'nip' => '8001691'],
            ['role' => 'Driver Kecamatan Cengkareng', 'name' => 'M Agus Saleh', 'nip' => '80243769'],
            ['role' => 'Driver Kecamatan Cengkareng', 'name' => 'Muhadi', 'nip' => '80051639'],
            ['role' => 'Driver Kecamatan Cengkareng', 'name' => 'Hadi Sumarno', 'nip' => '80135444'],
            ['role' => 'Driver Kecamatan Cengkareng', 'name' => 'Yusri Andani', 'nip' => '80051647'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Grogol Petamburan', 'name' => 'Ibnu Affandi', 'nip' => '198112042010031003'],
            ['role' => 'Driver Kecamatan Grogol Petamburan', 'name' => 'Damar Rini', 'nip' => '80156691'],
            ['role' => 'Driver Kecamatan Grogol Petamburan', 'name' => 'Feri Irawan', 'nip' => '80382414'],
            ['role' => 'Driver Kecamatan Grogol Petamburan', 'name' => 'Guntur', 'nip' => '80156692'],
            ['role' => 'Driver Kecamatan Grogol Petamburan', 'name' => 'Hadi Sumarno', 'nip' => '80135444'],
            ['role' => 'Driver Kecamatan Grogol Petamburan', 'name' => 'Yusri Andani', 'nip' => '80051647'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Taman Sari', 'name' => 'Aria Raksa Kusumah', 'nip' => '198004272014121003'],
            ['role' => 'Driver Kecamatan Taman Sari', 'name' => 'Edy Johani', 'nip' => '80243744'],
            ['role' => 'Driver Kecamatan Taman Sari', 'name' => 'Edi Siswanto', 'nip' => '80146566'],
            ['role' => 'Driver Kecamatan Taman Sari', 'name' => 'Usep Matius', 'nip' => '80146566'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Tambora', 'name' => 'Yopi Naidiza Siregar', 'nip' => '197905222010011016'],
            ['role' => 'Driver Kecamatan Tambora', 'name' => 'Adi Sukarjo', 'nip' => ''],
            ['role' => 'Driver Kecamatan Tambora', 'name' => 'Syaifullah', 'nip' => '80156383'],
            ['role' => 'Driver Kecamatan Tambora', 'name' => 'Nuri Hendriansyah', 'nip' => '80156384'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Kebon Jeruk', 'name' => 'Suprapto', 'nip' => '197804152009041002'],
            ['role' => 'Driver Kecamatan Kebon Jeruk', 'name' => 'Satria Bahari', 'nip' => '80154991'],
            ['role' => 'Driver Kecamatan Kebon Jeruk', 'name' => 'Curyinato', 'nip' => '80243797'],
            ['role' => 'Driver Kecamatan Kebon Jeruk', 'name' => 'Enci', 'nip' => '80236597'],
            ['role' => 'Driver Kecamatan Kebon Jeruk', 'name' => 'Saripudin', 'nip' => '80045254'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Kalideres', 'name' => 'A. Iskandar Zulkarnain', 'nip' => '198507212010112023'],
            ['role' => 'Driver Kecamatan Kalideres', 'name' => 'Ahmad Hasyim Baihaqi Y', 'nip' => '20578845'],
            ['role' => 'Driver Kecamatan Kalideres', 'name' => 'Jhon Edward G', 'nip' => '80421825'],
            ['role' => 'Driver Kecamatan Kalideres', 'name' => 'M. Irhim Hadi', 'nip' => '80156629'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Palmerah', 'name' => 'Arif Juniadi', 'nip' => '198007122009041007'],
            ['role' => 'Driver Kecamatan Palmerah', 'name' => 'Budi Sugiarto', 'nip' => '80157237'],
            ['role' => 'Driver Kecamatan Palmerah', 'name' => 'Endang Supriatna', 'nip' => '80380616'],
            ['role' => 'Driver Kecamatan Palmerah', 'name' => 'M Firmansyah', 'nip' => '80090546'],
            ['role' => 'Driver Kecamatan Palmerah', 'name' => 'Johan Wahyudin', 'nip' => '80090546'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Kembangan', 'name' => 'Husnapri Jakhtinihari', 'nip' => '198004242010121029'],
            ['role' => 'Driver Kecamatan Kembangan', 'name' => 'Fahru Salam', 'nip' => '80579641'],
            ['role' => 'Driver Kecamatan Kembangan', 'name' => 'Jonatan Sembiring', 'nip' => '80307664'],
            ['role' => 'Driver Kecamatan Kembangan', 'name' => 'Suryadi', 'nip' => '80244478'],
            ['role' => 'Kepala Seksi Pembangunan', 'name' => 'Imam Prasetyo', 'nip' => '198203062010121029'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Ria Magdalena Simbolon', 'nip' => '198711082012012014'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Ika Nurhafni', 'nip' => '198504262010012032'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Iman Ramadhan', 'nip' => '197509052004101004'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Jeri Vidian', 'nip' => ''],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Hesti Pratiwi', 'nip' => '199508232021102024'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Muhammad Aqsho', 'nip' => ''],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Muhammad Tri Edi Saputra', 'nip' => '199507202023013007'],
            ['role' => 'Kepala Seksi Pompa', 'name' => 'Wira Yudha Bhakti', 'nip' => '198510262010011014'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Thomas Lisdiyantoko', 'nip' => '199608292021101007'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Hesti Pratiwi', 'nip' => '199508232021102024'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Sulaiman', 'nip' => '197810042009041002'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'M usman Harun', 'nip' => '196101021990031001'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Mujibul Anwar', 'nip' => '198301052010101011'],
            ['role' => 'Security Gudang Material Kembangan', 'name' => 'Rojali', 'nip' => '80275650'],
            ['role' => 'Security Gudang Material Kembangan', 'name' => 'Suri Adam', 'nip' => '80248966'],
            ['role' => 'Security Gudang Material Kembangan', 'name' => 'Khoirul', 'nip' => '80050896'],
            ['role' => 'Security Gudang Material Kembangan', 'name' => 'Bambang Suharjo', 'nip' => '80243867'],
            ['role' => 'Security Gudang Material Kembangan', 'name' => 'Kukuh Kuncoro', 'nip' => '80243867'],
            ['role' => 'Security Gudang Material Mercu Buana', 'name' => 'Johny Satria Mirza', 'nip' => '80275017'],
            ['role' => 'Security Gudang Material Mercu Buana', 'name' => 'Umar Suamtri', 'nip' => '80243867'],
            ['role' => 'Security Gudang Material Pos Pengumben', 'name' => 'Miskar', 'nip' => '80548434'],
            ['role' => 'Security Gudang Material Pos Pengumben', 'name' => 'Agus Mawardin', 'nip' => '80548435'],
            ['role' => 'Security Gudang Material Pos Pengumben', 'name' => 'Arfan Faisal', 'nip' => '80431567'],
            ['role' => 'Security Gudang Material Pos Pengumben', 'name' => 'FIR Ramadhan', 'nip' => '80431567'],
            ['role' => 'Security Gudang Material Perumnas', 'name' => 'Adilyas', 'nip' => '80030577'],
            ['role' => 'Security Gudang Material Perumnas', 'name' => 'Ubaydillah', 'nip' => '80244111'],
            ['role' => 'Security Gudang Material Perumnas', 'name' => 'Toton Fhatoni', 'nip' => '80244112'],
        ];

        foreach ($users as $userData) {
            $email = Str::of($userData['name'])
                ->replace(['.', ','], '')
                ->replace(' ', '.')
                ->lower()
                ->append('@jakbar.go.id');

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