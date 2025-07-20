<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UnitKerja;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class SudinKepulauanSeribuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil unit kerja Sudin SDA Kepulauan Seribu
        $unit = UnitKerja::where('nama', 'like', '%Kepulauan Seribu%')->first();
        if (!$unit) {
            throw new \Exception('Unit Kerja Sudin SDA Kepulauan Seribu tidak ditemukan!');
        }

        // Data user Sudin SDA Kepulauan Seribu
        $users = [
            ['role' => 'Kepala Suku Dinas', 'name' => 'Mustajab', 'nip' => '197101181979031005'],
            ['role' => 'Kepala Seksi Perencanaan', 'name' => 'Efit Wiyati', 'nip' => '1972022210012012'],
            ['role' => 'Staf Seksi Perencanaan', 'name' => 'Sofar Wahyu Asmoroajit', 'nip' => '1974062514110202'],
            ['role' => 'Staf Seksi Perencanaan', 'name' => 'Yuda Dwi Yulan', 'nip' => '199607121903031003'],
            ['role' => 'Staf Seksi Perencanaan', 'name' => 'Muhammad Feydyh Nizom', 'nip' => '1977032021012113'],
            ['role' => 'Kepala Sub Bagian Tata Usaha', 'name' => 'Geoffrey Rejoice Novena', 'nip' => '198801122009041001'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Abdullah Syafii', 'nip' => '198104252009041002'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Sri Handayani', 'nip' => '198012202003012002'],
            ['role' => 'Pengurus Barang Pembantu', 'name' => 'Anton Fauzi Firmansyah', 'nip' => '197803032004112002'],
            ['role' => 'Pengurus Barang Pembantu', 'name' => 'Ahmad Farhan Adhitya', 'nip' => ''],
            ['role' => 'Pembantu Pengurus Barang II', 'name' => 'Nandya Efria Saputri', 'nip' => '80297113'],
            ['role' => 'Kepala Seksi Pesisir Pantai', 'name' => 'Dediana Rosadi', 'nip' => '1986111401012001'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Dedi Rosadi', 'nip' => '198410041009041006'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Ferdinan Tongam', 'nip' => '198804102009041016'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Putra Sinaga', 'nip' => ''],
            ['role' => 'Petugas Administrasi Seksi Pengamanan dan Pengembangan Pesisir Pantai', 'name' => 'Saut Tobing', 'nip' => '80210693'],
            ['role' => 'Petugas Administrasi Gudang Pantai Pulau Lancang', 'name' => 'Putra Pinangga', 'nip' => '80210694'],
            ['role' => 'PJU Pantai Untung Jawa', 'name' => 'Mahiar', 'nip' => ''],
            ['role' => 'PJU Pantai Untung Jawa', 'name' => 'Siti Alyaeni', 'nip' => ''],
            ['role' => 'PJU Pantai Untung Jawa', 'name' => 'Zul Kobar', 'nip' => ''],
            ['role' => 'PJU Pantai Untung Jawa', 'name' => 'Muhammad Ardi Wijaya', 'nip' => '80346995'],
            ['role' => 'PJU Pantai Untung Jawa', 'name' => 'Jonathan J Koa', 'nip' => '80124291'],
            ['role' => 'PJU Pantai Untung Jawa', 'name' => 'Egison', 'nip' => '80044991'],
            ['role' => 'PJU Pantai Untung Jawa', 'name' => 'M. Saman', 'nip' => ''],
            ['role' => 'PJU Pantai Tidung', 'name' => 'Ahmad Sofyan', 'nip' => '80121721'],
            ['role' => 'PJU Pantai Tidung', 'name' => 'Ali Nurhadi', 'nip' => '80121722'],
            ['role' => 'PJU Pantai Tidung', 'name' => 'Suryadi', 'nip' => '80121723'],
            ['role' => 'PJU Pantai Tidung', 'name' => 'Andri', 'nip' => '80121724'],
            ['role' => 'PJU Pantai Tidung', 'name' => 'Sopian', 'nip' => '80121725'],
            ['role' => 'PJU Pantai Tidung', 'name' => 'Nanang Kosim', 'nip' => '80121754'],
            ['role' => 'PJU Pantai Pramuka', 'name' => 'Indra Zulkifli', 'nip' => '80210695'],
            ['role' => 'PJU Pantai Pramuka', 'name' => 'Defri Ibnu Fajar', 'nip' => '80296786'],
            ['role' => 'PJU Pantai Pramuka', 'name' => 'Abdul Rahman', 'nip' => '80131826'],
            ['role' => 'PJU Pantai Pramuka', 'name' => 'Abdi Mampa', 'nip' => '80133003'],
            ['role' => 'PJU Pantai Pramuka', 'name' => 'Takwiming', 'nip' => '80210868'],
            ['role' => 'PJU Pantai Pramuka', 'name' => 'M. Hafiz', 'nip' => ''],
            ['role' => 'Petugas Administrasi Gudang Pantai Pulau Harapan', 'name' => 'Khosafi Aufa Yulianto', 'nip' => '80156580'],
            ['role' => 'Petugas Administrasi Gudang Pantai Pulau Harapan', 'name' => 'Suganda', 'nip' => '80156581'],
            ['role' => 'Petugas Administrasi Gudang Pantai Pulau Harapan', 'name' => 'Ahmad Syarif', 'nip' => '80128421'],
            ['role' => 'Petugas Administrasi Gudang Pantai Pulau Harapan', 'name' => 'Hamdan', 'nip' => '80128421'],
            ['role' => 'Petugas Administrasi Gudang Pantai Pulau Harapan', 'name' => 'Abdullah', 'nip' => '80128421'],
            ['role' => 'Petugas Administrasi Gudang Pantai Pulau Harapan', 'name' => 'Rezky Arie Pranata', 'nip' => '19870325001011005'],
            ['role' => 'Petugas Administrasi Gudang Pantai Pulau Harapan', 'name' => 'Fahmy Arry Sagita', 'nip' => '1971122200941003'],
            ['role' => 'Petugas Administrasi Gudang Pantai Pulau Harapan', 'name' => 'Agus Setiawan', 'nip' => '198209062009011029'],
            ['role' => 'Petugas Administrasi Gudang Pantai Pulau Harapan', 'name' => 'Martua Luthal Natanael', 'nip' => '198412082009011002'],
            ['role' => 'Petugas Administrasi Gudang Pantai Pulau Harapan', 'name' => 'Yohannes Andre D.S', 'nip' => '1997032000121002'],
            ['role' => 'Kepala Seksi Air Bersih dan Air Limbah', 'name' => 'Rezky Arie Pranata', 'nip' => '19870325001011005'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Agus Setiawan', 'nip' => '198209062009011029'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Martua Luthal Natanael', 'nip' => '198412082009011002'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Yohannes Andre D.S', 'nip' => '1997032000121002'],
            ['role' => 'Petugas Administrasi Seksi Air Bersih dan Air Limbah', 'name' => 'Vowel Kamlio', 'nip' => '80734720'],
            ['role' => 'Petugas Administrasi Seksi Air Bersih dan Air Limbah', 'name' => 'Khairul Rahmadur', 'nip' => ''],
            ['role' => 'PJU ABAL Pulau Untung Jawa', 'name' => 'Rahmat Alyuh', 'nip' => '80042627'],
            ['role' => 'PJU ABAL Pulau Untung Jawa', 'name' => 'Rahmat Ilhudi', 'nip' => '80042629'],
            ['role' => 'PJU ABAL Pulau Untung Jawa', 'name' => 'Awan Wardana', 'nip' => '80218120'],
            ['role' => 'PJU ABAL Pulau Untung Jawa', 'name' => 'Azaddin', 'nip' => '80218359'],
            ['role' => 'PJU ABAL Pulau Untung Jawa', 'name' => 'Rismawi Hudaya', 'nip' => '80218236'],
            ['role' => 'PJU ABAL Pulau Untung Jawa', 'name' => 'Rendy Andhika Wibowo', 'nip' => '80211255'],
            ['role' => 'PJU ABAL Pulau Tidung', 'name' => 'Fadlich', 'nip' => ''],
            ['role' => 'PJU ABAL Pulau Tidung', 'name' => 'M. Saif Hidayat', 'nip' => '80096066'],
            ['role' => 'PJU ABAL Pulau Tidung', 'name' => 'Haji Julhudi', 'nip' => '80096065'],
            ['role' => 'PJU ABAL Pulau Tidung', 'name' => 'Syahril Prihata', 'nip' => '80096068'],
            ['role' => 'PJU ABAL Pulau Sabira', 'name' => 'Akbar', 'nip' => '80542116'],
            ['role' => 'PJU ABAL Pulau Sabira', 'name' => 'Sopian', 'nip' => '80542116'],
            ['role' => 'PJU ABAL Pulau Sabira', 'name' => 'Ahmad Fajri Yasser', 'nip' => '80542116'],
            ['role' => 'PJU ABAL Pulau Pramuka', 'name' => 'Hasbullah', 'nip' => '80611924'],
            ['role' => 'PJU ABAL Pulau Pramuka', 'name' => 'Echa Bagas Wera Widianto', 'nip' => '80542658'],
            ['role' => 'PJU ABAL Pulau Pramuka', 'name' => 'Muh. Abd. Rahim Yusup', 'nip' => '80542580'],
            ['role' => 'PJU ABAL Pulau Pramuka', 'name' => 'Fiky Suryadi', 'nip' => '80542580'],
            ['role' => 'PJU ABAL Pulau Harapan', 'name' => 'Sahruni', 'nip' => '80371304'],
            ['role' => 'PJU ABAL Pulau Harapan', 'name' => 'Syahrul Basri', 'nip' => '80371102'],
        ];

        foreach ($users as $userData) {
            $email = Str::of($userData['name'])
                ->replace(['.', ','], '')
                ->replace(' ', '.')
                ->lower()
                ->append('@kepulauanseribu.go.id');

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