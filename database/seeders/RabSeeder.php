<?php

namespace Database\Seeders;

use App\Models\BarangStok;
use App\Models\ListRab;
use App\Models\MerkStok;
use Carbon\Carbon;
use App\Models\Rab;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class RabSeeder extends Seeder
{

    public $projects;

    public function __construct()
    {
        $this->projects =
            [
                'Pembangunan Saluran Irigasi Utama',
                'Rehabilitasi Bendungan dan Waduk',
                'Perbaikan Tanggul dan Penguatan Tebing Sungai',
                'Pembangunan Jaringan Distribusi Air Bersih',
                'Pembersihan dan Pemeliharaan Saluran Air',
                'Pembuatan Embung untuk Irigasi',
                'Normalisasi Sungai dan Anak Sungai',
                'Penguatan Struktur Bendungan',
                'Pembangunan Kolam Retensi',
                'Pengadaan Pompa Air untuk Irigasi',
                'Pembuatan Saluran Pembuangan Air Hujan',
                'Perbaikan Saluran Primer dan Sekunder',
                'Rehabilitasi Sistem Irigasi Tetes',
                'Peningkatan Kapasitas Waduk',
                'Pembuatan Sistem Pengendalian Banjir',
                'Pembuatan Bak Penampungan Air',
                'Peningkatan Efisiensi Irigasi di Lahan Pertanian',
                'Pembangunan Saluran Pembuangan Air Limbah',
                'Pembangunan Sumur Resapan',
                'Perbaikan dan Pemeliharaan Pintu Air',
                'Pembangunan Bendungan Penahan Banjir',
                'Pembangunan Saluran Tersier',
                'Pembuatan Jaringan Pipa Distribusi Air',
                'Normalisasi Saluran Drainase Kota',
                'Pembangunan Pompa Air untuk Daerah Kering',
                'Pengembangan Sistem Irigasi Otomatis',
                'Rehabilitasi Kolam Penampungan Air',
                'Perbaikan Struktur Saluran Primer',
                'Pembuatan Jaringan Irigasi Perdesaan',
                'Pengadaan dan Instalasi Pintu Air Otomatis',
                'Perbaikan Saluran Irigasi di Daerah Rawan Banjir',
                'Pembangunan Sistem Pengelolaan Air Limbah Terpusat',
                'Pembuatan Kanal Pengendali Banjir',
                'Perbaikan Struktur Penguatan Sungai',
                'Peningkatan Kapasitas Pipa Distribusi Air Bersih',
                'Rehabilitasi Embung dan Kolam Resapan',
                'Pembuatan Saluran Air untuk Daerah Pertanian',
                'Pembangunan Sistem Pemantauan Debit Air',
                'Pembuatan dan Perbaikan Saluran Air Kota',
                'Peningkatan Kapasitas Kolam Resapan Air Hujan',
                'Peningkatan Kualitas Air Bersih di Daerah Tertinggal',
                'Perbaikan Sistem Pengairan di Daerah Rawan Kekeringan',
                'Pembangunan Sistem Irigasi Mikro',
                'Pembangunan Sistem Pengelolaan Air Limbah Domestik',
                'Pengembangan Sistem Pengelolaan Air Terpadu',
                'Pengadaan Sistem Pengontrol Air Secara Otomatis',
                'Rehabilitasi dan Peningkatan Efisiensi Pompa Air',
                'Pengembangan Sistem Pemantauan Kualitas Air',
                'Pembuatan Kanal Distribusi Air ke Lahan Pertanian',
                'Peningkatan Kapasitas Waduk dan Bendungan',
                'Pembuatan dan Pengembangan Kanal Irigasi Baru'
            ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        foreach ($this->projects as $value) {
            $randomDate = Carbon::now()->subDays(rand(0, 30));
            $endDate = $randomDate->copy()->addDays(rand(30, 365));
            $user = User::inRandomOrder()->whereHas('unitKerja', function ($unit) {
                return $unit->where('hak', 0);
            })->first();
            Rab::create([
                'user_id' => $user->id,
                'nama' => $value,
                'lokasi' => $faker->address(),
                'mulai' => $randomDate,
                'selesai' => $endDate
            ]);
        }

        for ($i = 0; $i < 80; $i++) {
            $rab = Rab::inRandomOrder()->first();
            // $merk = BarangStok::inRandomOrder()->where('jenis_id', 1)->first();
            $merk = MerkStok::whereHas('barangStok', function ($barang) {
                return $barang->where('jenis_id', 1);
            })->inRandomOrder()->first();
            ListRab::create([
                'rab_id' => $rab->id,
                'merk_id' => $merk->id,
                'jumlah' => $faker->numberBetween(100, 1000)
            ]);
        }
    }
}
