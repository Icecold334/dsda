<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UnitKerja;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class SudinJakselSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil unit kerja Sudin SDA Jakarta Selatan
        $unit = UnitKerja::where('nama', 'like', '%Jakarta Selatan%')->first();
        if (!$unit) {
            throw new \Exception('Unit Kerja Sudin SDA Jakarta Selatan tidak ditemukan!');
        }

        // Data user Sudin SDA Jakarta Selatan
        $users = [
            ['role' => 'Kepala Suku Dinas', 'name' => 'Santo', 'nip' => '197302211996031001'],
            ['role' => 'Kepala Seksi Perencanaan', 'name' => 'Inge Sukma Yupicha', 'nip' => '198308112010012033'],
            ['role' => 'Staf Seksi Perencanaan', 'name' => 'Siti Nurjannah', 'nip' => '196806251993032007'],
            ['role' => 'Kepala Sub Bagian Tata Usaha', 'name' => 'Siti Nurjannah', 'nip' => '196806251993032007'],
            ['role' => 'Pembantu Pengurus Barang II', 'name' => 'Nasrudin Darmadi', 'nip' => '198210062014121003'],
            ['role' => 'Kepala Seksi Pemeliharaan', 'name' => 'Paulus Junjung', 'nip' => '198004142010011032'],
            // Tim Pendukung PPK, Satpel, Driver, dst (isi sesuai gambar)
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Cilandak', 'name' => 'Yansori', 'nip' => '196903132009041001'],
            ['role' => 'Driver DT Alkal Kecamatan Cilandak', 'name' => 'Supriyadi', 'nip' => '80450132'],
            ['role' => 'Driver Kecamatan Cilandak', 'name' => 'Hari Triatna', 'nip' => '80126320'],
            ['role' => 'Driver Cilandak', 'name' => 'Dedi Andriansah', 'nip' => '80126401'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Jagakarsa', 'name' => 'Sartono', 'nip' => '197208281994031001'],
            ['role' => 'Driver Kecamatan Jagakarsa', 'name' => 'Dimas Aditya Putra', 'nip' => ''],
            ['role' => 'Driver Alkal Kecamatan Jagakarsa', 'name' => 'Ferry Somirin', 'nip' => ''],
            ['role' => 'Driver Kecamatan Jagakarsa', 'name' => 'Sidik', 'nip' => '80516951'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Kebayoran Baru', 'name' => 'Budi Sutopo', 'nip' => '197809291994031003'],
            ['role' => 'Driver Kecamatan Kebayoran Baru', 'name' => 'Jasn', 'nip' => '80123644'],
            ['role' => 'Driver Alkal Kecamatan Kebayoran Baru', 'name' => 'Deni Haryanto', 'nip' => '80123634'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Kebayoran Lama', 'name' => 'Andriansyah', 'nip' => '197008081994031001'],
            ['role' => 'Driver Kecamatan Kebayoran Lama', 'name' => 'Diah Agus', 'nip' => '80123841'],
            ['role' => 'Driver Alkal Kecamatan Kebayoran Lama', 'name' => 'Maulan Malik', 'nip' => '80123844'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Mampang Prapatan', 'name' => 'Dicky Wildan Prakoso', 'nip' => '199210272010121001'],
            ['role' => 'Driver Kecamatan Mampang Prapatan', 'name' => 'M. Fadillah Tigana', 'nip' => '80123847'],
            ['role' => 'Driver Alkal Kecamatan Mampang Prapatan', 'name' => 'Cosmas Silen', 'nip' => '80123851'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Pancoran', 'name' => 'Agus Bowo Leksono', 'nip' => '198808092010111007'],
            ['role' => 'Driver Kecamatan Pancoran', 'name' => 'Wawan', 'nip' => '80123751'],
            ['role' => 'Driver Alkal Kecamatan Pancoran', 'name' => 'Marhali', 'nip' => '80579697'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Pasar Minggu', 'name' => 'Rousi Surya Indah', 'nip' => '197404221994031001'],
            ['role' => 'Driver Kecamatan Pasar Minggu', 'name' => 'Muhamad Topik', 'nip' => '80240112'],
            ['role' => 'Driver DT Alkal Kecamatan Pasar Minggu', 'name' => 'Didi Bastian', 'nip' => '80240122'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Pesanggrahan', 'name' => 'Chairul Anwar', 'nip' => '196712131993081003'],
            ['role' => 'Driver Kecamatan Pesanggrahan', 'name' => 'Andri Sentosa', 'nip' => '80242549'],
            ['role' => 'Driver Alkal Kecamatan Pesanggrahan', 'name' => 'Jatmiko Nugroho', 'nip' => '80242549'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Setiabudi', 'name' => 'Retno Wulandari', 'nip' => '197904072010012025'],
            ['role' => 'Driver Kecamatan Setiabudi', 'name' => 'Hairudin', 'nip' => '80317722'],
            ['role' => 'Driver DT Alkal Kecamatan Setiabudi', 'name' => 'Juliathro Endro', 'nip' => '80317724'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Tebet', 'name' => 'Hastyanti Hidayat', 'nip' => '198508072010012034'],
            ['role' => 'Driver Kecamatan Tebet', 'name' => 'Abdol Rouf', 'nip' => '80202279'],
            ['role' => 'Driver Alkal Kecamatan Tebet', 'name' => 'Aji Sekar Bawono', 'nip' => '80202279'],
            ['role' => 'Kepala Seksi Pembangunan', 'name' => 'Horas Yosua', 'nip' => '198510262010011014'],
            ['role' => 'Kepala Seksi Pompa', 'name' => 'Heriyanto', 'nip' => '198102102010011023'],
            // Security Gudang
            ['role' => 'Security Gudang Material Rawa Minyak', 'name' => 'Jekson Riwu', 'nip' => '80031103'],
            ['role' => 'Security Gudang Material Rawa Minyak', 'name' => 'Dimas Sapta Alamsyah', 'nip' => '80594634'],
            ['role' => 'Security Gudang Material Rawa Minyak', 'name' => 'Rizki Kurnia Adinata', 'nip' => '80031104'],
            ['role' => 'Security Gudang Material Rawa Minyak', 'name' => 'Budi Alvino', 'nip' => '80594635'],
            ['role' => 'Security Gudang Material Rawa Minyak', 'name' => 'Rojih', 'nip' => '80594636'],
            ['role' => 'Security Gudang Material Rawa Minyak', 'name' => 'Arif Maulana', 'nip' => '80754330'],
            ['role' => 'Security Gudang Material Rawa Minyak', 'name' => 'Maryadi', 'nip' => '80213798'],
            ['role' => 'Security Gudang Material Rawa Minyak', 'name' => 'Riki', 'nip' => '80213799'],
            ['role' => 'Security Gudang Material Rawa Minyak', 'name' => 'K. Kamaluddin Zuhdi', 'nip' => '80213798'],
        ];

        foreach ($users as $userData) {
            $email = Str::of($userData['name'])
                ->replace(['.', ','], '')
                ->replace(' ', '.')
                ->lower()
                ->append('@jaksel.go.id');

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