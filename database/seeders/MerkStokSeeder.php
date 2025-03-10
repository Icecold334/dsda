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
                'Brother LC',
                'Epson',
                'Epson 008',
            ],
        ];

        // Mapping khusus untuk merek tertentu (Brother LC, Epson, Epson 008)
        $specialMerk = [
            'Brother LC' => [
                'tipe' => ['Black', 'Cyan', 'Magenta', 'Yellow'],
                'ukuran' => ['538'],
                'slug' => 'tinta-printer-brother-mfc',
            ],
            'Epson' => [
                'tipe' => ['Black', 'Cyan', 'Magenta', 'Yellow', 'Light Cyan', 'Light Magenta'],
                'ukuran' => ['664', '673'],
                'slug' => 'tinta-printer',
            ],
            'Epson 008' => [
                'tipe' => ['Black', 'Cyan', 'Magenta', 'Yellow'],
                'ukuran' => ['L15160'],
                'slug' => 'tinta-printer',
            ],
        ];

        // Ambil semua barang dari tabel BarangStok
        $barangList = BarangStok::all();

        // Ambil barang_id dari kategori_id 123
        $barangDariKategori123 = BarangStok::where('kategori_id', 123)->pluck('id')->toArray();

        foreach ($barangList as $barang) {
            // Tentukan merek berdasarkan jenis barang (gunakan fallback jika tidak ditemukan)
            $jenisBarang = $barang->jenisStok->nama;
            $merkList = $merkData[$jenisBarang] ?? ['Generic'];

            // Jumlah merek untuk setiap barang (3 hingga 8 merek)
            $numMerkForBarang = rand(3, 8);

            foreach (range(1, $numMerkForBarang) as $i) {
                // Pilih merek secara acak
                $merkName = $faker->randomElement($merkList);

                if (isset($specialMerk[$merkName])) {
                    // Gunakan nilai khusus untuk Brother LC, Epson, dan Epson 008
                    $tipe = $faker->randomElement($specialMerk[$merkName]['tipe']);
                    $ukuran = $faker->randomElement($specialMerk[$merkName]['ukuran']);

                    // Cek apakah ada slug yang valid untuk mendapatkan barang_id
                    $barangId = isset($specialMerk[$merkName]['slug'])
                        ? BarangStok::where('slug', $specialMerk[$merkName]['slug'])->value('id')
                        : null;
                } else {
                    // Gunakan nilai acak untuk merek lainnya
                    $tipe = $faker->optional()->randomElement([
                        'Standard',
                        'Premium',
                        'Heavy Duty',
                        'Profesional',
                        'Khusus',
                    ]);
                    $ukuran = $faker->optional()->randomElement([
                        $faker->numberBetween(5, 50) . ' cm',
                        $faker->numberBetween(1, 10) . ' m',
                        $faker->numberBetween(10, 500) . ' mm',
                        $faker->numberBetween(50, 500) . ' ml',
                        $faker->numberBetween(1, 20) . ' L',
                        $faker->numberBetween(1, 1000) . ' gr',
                        $faker->numberBetween(1, 50) . ' kg',
                    ]);

                    // Ambil barang_id dari kategori 123 jika tersedia
                    $barangId = !empty($barangDariKategori123) ? $faker->randomElement($barangDariKategori123) : $barang->id;
                }

                // Jika barang_id masih null (tidak ditemukan di database), lewati iterasi ini
                if (!$barangId) {
                    continue;
                }

                // Simpan ke database
                MerkStok::create([
                    'barang_id' => $barangId,
                    'nama' => $merkName,
                    'tipe' => $tipe,
                    'ukuran' => $ukuran,
                ]);
            }
        }
    }
}
