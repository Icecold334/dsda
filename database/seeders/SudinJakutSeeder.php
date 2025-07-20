<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UnitKerja;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class SudinJakutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil unit kerja Sudin SDA Jakarta Utara
        $unit = UnitKerja::where('nama', 'like', '%Jakarta Utara%')->first();
        if (!$unit) {
            throw new \Exception('Unit Kerja Sudin SDA Jakarta Utara tidak ditemukan!');
        }

        // Data user Sudin SDA Jakarta Utara
        $users = [
            // 1-5
            ['role' => 'Kepala Suku Dinas', 'name' => 'Ahmad Saipul', 'nip' => '196709291996031001'],
            ['role' => 'Kepala Seksi Perencanaan', 'name' => 'Apriyani Talalohu', 'nip' => '197604052008042001'],
            ['role' => 'Staf Seksi Perencanaan', 'name' => 'Desni Citra Mumpuni', 'nip' => '199512120019032012'],
            ['role' => 'Staf Seksi Perencanaan', 'name' => 'Dadang Darmawan', 'nip' => '197007262000041001'],
            ['role' => 'Staf Seksi Perencanaan', 'name' => 'Sony Marsono', 'nip' => '197709262000041001'],
            ['role' => 'Staf Seksi Perencanaan', 'name' => 'Ririan Safiadi Wahid', 'nip' => '198012202003012002'],
            ['role' => 'Staf Seksi Perencanaan', 'name' => 'Muhammad Fachri Maulvi', 'nip' => '19800272002301009'],
            ['role' => 'Kepala Sub Bagian Tata Usaha', 'name' => 'Deny Tri Hendarto', 'nip' => '196810202003011002'],
            ['role' => 'Pembantu Pengurus Barang I', 'name' => 'Mohamad Suherman Eka Putra', 'nip' => '197710042004101005'],
            ['role' => 'Pembantu Pengelola Gudang Material', 'name' => 'Sanjaya', 'nip' => '198008220009041005'],
            ['role' => 'Administrasi', 'name' => 'Evian Nazar Qutbu', 'nip' => '80548553'],
            ['role' => 'Administrasi', 'name' => 'Makhuss Iskandar', 'nip' => '80210421'],
            ['role' => 'Dwi Kurnia Sandi', 'name' => 'Dwi Kurnia Sandi', 'nip' => ''],
            // 6 - Seksi Pemeliharaan dan Tim Pendukung PPK
            ['role' => 'Kepala Seksi Pemeliharaan', 'name' => 'Yudo Widiatmoko', 'nip' => '198608302010011010'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Supiayn', 'nip' => '197907192009041001'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Sidiq', 'nip' => '196905072009041002'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Muhammad Fachri Maulvi', 'nip' => '19800272002301009'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Sanjaya', 'nip' => '198008220009041005'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Dewi Marlina', 'nip' => '197603092010012008'],
            // Ketua Satpel & Driver Kecamatan
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Cilincing', 'name' => 'Ichsan Nasution', 'nip' => '197106111996031000'],
            ['role' => 'Driver Kecamatan Cilincing', 'name' => 'Hisyam Anshori', 'nip' => '80243096'],
            ['role' => 'Driver Kecamatan Cilincing', 'name' => 'Absalon Wahyudin Tarihoran', 'nip' => '80124593'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Kelapa Gading', 'name' => 'Jhoni Sariyanto Situmorang', 'nip' => '197706092010011021'],
            ['role' => 'Driver Kecamatan Kelapa Gading', 'name' => 'Saripudin', 'nip' => '80156921'],
            ['role' => 'Driver Kecamatan Kelapa Gading', 'name' => 'Dadang', 'nip' => '80124313'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Koja', 'name' => 'Slamet Riyanto', 'nip' => '197410220009041003'],
            ['role' => 'Driver Kecamatan Koja', 'name' => 'Syafriudin', 'nip' => '80125522'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Pademangan', 'name' => 'Dewi Marlina', 'nip' => '197603092010012008'],
            ['role' => 'Driver Kecamatan Pademangan', 'name' => 'Diding', 'nip' => '80295140'],
            ['role' => 'Driver Kecamatan Pademangan', 'name' => 'Satria', 'nip' => '80124724'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Penjaringan', 'name' => 'Pendi', 'nip' => '197305302009041001'],
            ['role' => 'Ketua Satuan Pelaksana Kecamatan Tanjung Priok', 'name' => 'Neti Heriati', 'nip' => '197901102014122004'],
            ['role' => 'Driver Kecamatan Tanjung Priok', 'name' => 'Margono', 'nip' => '80124299'],
            // Seksi Pembangunan
            ['role' => 'Kepala Seksi Pembangunan', 'name' => 'Boris Karlop Lumbangaol', 'nip' => '197811062010011020'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Supiayn', 'nip' => '197907192009041001'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Sidiq', 'nip' => '196905072009041002'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Muhammad Fachri Maulvi', 'nip' => '19800272002301009'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Sanjaya', 'nip' => '198008220009041005'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Dewi Marlina', 'nip' => '197603092010012008'],
            // Seksi Pompa
            ['role' => 'Kepala Seksi Pompa', 'name' => 'Frans Agustinus Siahaan', 'nip' => '197908222010011022'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Slamet Riyanto', 'nip' => '197410220009041003'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Jaenal Abidin', 'nip' => '1981106200941002'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Jhoni Sariyanto Situmorang', 'nip' => '197706092010011021'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Wawan Suwandi', 'nip' => '197509182009041002'],
            ['role' => 'Tim Pendukung PPK', 'name' => 'Dadang Darmawan', 'nip' => '196910262000041001'],
            // Security Gudang
            ['role' => 'Security Gudang Material Ketel Uap', 'name' => 'Anton Suherman', 'nip' => '80542561'],
            ['role' => 'Security Gudang Material Ketel Uap', 'name' => 'Darfian', 'nip' => '80542515'],
            ['role' => 'Security Gudang Material Ketel Uap', 'name' => 'Hendra Sapjuli Anwar', 'nip' => '80444218'],
            ['role' => 'Security Gudang Material Ketel Uap', 'name' => 'Junaidi', 'nip' => '80224201'],
            ['role' => 'Security Gudang Material Ketel Uap', 'name' => 'Mohamad Wahet', 'nip' => '80224209'],
            ['role' => 'Security Gudang Material Ketel Uap', 'name' => 'Muhamad Fadli Elwaer', 'nip' => '80542517'],
            ['role' => 'Security Gudang Material Ketel Uap', 'name' => 'Sulaeman', 'nip' => '80542514'],
            ['role' => 'Security Gudang Material Ketel Uap', 'name' => 'Tamrin', 'nip' => '80542513'],
            ['role' => 'Security Gudang Material Ketel Uap', 'name' => 'Tedy Supriyanto', 'nip' => '80348146'],
            ['role' => 'Security Gudang Material Ketel Uap', 'name' => 'Supamo', 'nip' => '80224156'],
            ['role' => 'Security Gudang Material Pemeliharaan Drainase SDA JU', 'name' => 'Hartarto', 'nip' => '80444318'],
        ];

        foreach ($users as $userData) {
            $email = Str::of($userData['name'])
                ->replace(['.', ',', '  '], '')
                ->replace(' ', '.')
                ->lower()
                ->append('@jakut.go.id');

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