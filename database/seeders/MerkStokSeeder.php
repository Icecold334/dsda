<?php

namespace Database\Seeders;

use App\Models\MerkStok;
use App\Models\BarangStok;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class MerkStokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $merkData = [
            'Material' => [
                'Semen Gresik',
                'Holcim',
                'Bima',
                'Jayamix',
                'Adhimix',
                'Tiga Roda',
                'Nippon Paint',
                'Betonmix',
            ],
            'Spare Part' => [
                'Denso',
                'Bosch',
                'Onda',
                'NGK',
                'Osram',
                'Sumitomo',
            ],
            'Umum' => [
                'Pilot',
                'Faber-Castell',
                'Staedtler',
                'Joyko',
            ],
        ];

        // Ambil semua barang dari tabel BarangStok
        $barangList = BarangStok::all();

        $totalMerk = 0;

        foreach ($barangList as $barang) {
            // Tentukan merek berdasarkan jenis barang
            $jenisBarang = $barang->jenisStok->nama;

            // Pilih daftar merek berdasarkan jenis barang, fallback ke 'Generic'
            $merkList = $merkData[$jenisBarang];

            // Tambahkan lebih banyak merek untuk setiap barang
            $numMerkForBarang = rand(3, 8); // Setiap barang memiliki 3 hingga 5 merek
            for ($i = 0; $i < $numMerkForBarang; $i++) {
                // if ($totalMerk >= 100) {
                //     break; // Hentikan jika sudah mencapai 100 data
                // }

                // Pilih merek secara acak
                $merkName = $faker->randomElement($merkList);

                // Buat MerkStok
                MerkStok::create([
                    'barang_id' => $barang->id,
                    'nama' => $merkName,
                    'tipe' => $faker->boolean ? $faker->randomElement([
                        'Standard',
                        'Premium',
                        'Heavy Duty',
                        'Profesional',
                        'Khusus',
                    ]) : null,
                    'ukuran' => $faker->boolean ? $faker->randomElement([
                        $faker->numberBetween(5, 50) . ' cm',
                        $faker->numberBetween(1, 10) . ' m',
                        $faker->numberBetween(10, 500) . ' mm',
                        $faker->numberBetween(50, 500) . ' ml',
                        $faker->numberBetween(1, 20) . ' L',
                        $faker->numberBetween(1, 1000) . ' gr',
                        $faker->numberBetween(1, 50) . ' kg',
                    ]) : null,
                ]);

                $totalMerk++; // Hitung total merek yang dibuat
            }

            // if ($totalMerk >= 100) {
            //     break; // Hentikan jika sudah mencapai 100 data
            // }
        }
    }
}
