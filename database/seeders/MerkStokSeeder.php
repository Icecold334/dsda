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

        // Ambil daftar barang
        $barangList = BarangStok::all();

        $barangDariKategori123 = BarangStok::where('kategori_id', 123)->pluck('id')->toArray();

        foreach ($barangList as $barang) {
            $jenisBarang = $barang->jenisStok->nama;

            // **Cek apakah barang adalah tinta berdasarkan slug**
            $isTinta = collect($specialMerk)->pluck('slug')->contains($barang->slug);

            if ($isTinta) {
                // 🚫 **Hanya gunakan special merk untuk tinta**
                foreach ($specialMerk as $merkName => $details) {
                    if ($barang->slug === $details['slug']) {
                        // ✅ **Ambil semua kombinasi tipe dan ukuran**
                        foreach ($details['tipe'] as $tipe) {
                            foreach ($details['ukuran'] as $ukuran) {
                                // ✅ **Pengecekan kombinasi lengkap nama + tipe + ukuran**
                                $existingMerk = MerkStok::where('barang_id', $barang->id)
                                    ->where('nama', $merkName)
                                    ->where('tipe', $tipe)
                                    ->where('ukuran', $ukuran)
                                    ->exists();

                                // ➡️ Jika kombinasi belum ada → Simpan ke database
                                if (!$existingMerk) {
                                    MerkStok::create([
                                        'barang_id' => $barang->id,
                                        'nama' => $merkName,
                                        'tipe' => $tipe,
                                        'ukuran' => $ukuran,
                                    ]);
                                }
                            }
                        }
                    }
                }
            } else {
                // **Untuk barang non-tinta, gunakan merk umum**
                $merkList = $merkData[$jenisBarang] ?? ['Generic'];

                // Jumlah merek untuk setiap barang (3 hingga 8 merek)
                $numMerkForBarang = rand(3, 8);

                foreach (range(1, $numMerkForBarang) as $i) {
                    $merkName = $faker->randomElement($merkList);

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

                    $barangId = !empty($barangDariKategori123)
                        ? $faker->randomElement($barangDariKategori123)
                        : $barang->id;

                    if (!$barangId) {
                        continue;
                    }

                    // ✅ **Pengecekan kombinasi nama + tipe + ukuran**
                    $existingMerk = MerkStok::where('barang_id', $barangId)
                        ->where('nama', $merkName)
                        ->where('tipe', $tipe)
                        ->where('ukuran', $ukuran)
                        ->exists();

                    // ➡️ Jika kombinasi belum ada → Simpan ke database
                    if (!$existingMerk) {
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
    }
}
