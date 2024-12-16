<?php

namespace Database\Seeders;

use App\Models\JenisStok;
use App\Models\BarangStok;
use App\Models\SatuanBesar;
use Illuminate\Support\Str;
use App\Models\KategoriStok;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BarangStokSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public $faker;

    public function __construct()
    {
        $this->faker = Faker::create('id_ID');
    }
    public function run(): void
    {
        $this->jenis();
        $this->barangUmum();
        $this->barangNonUmum();
    }
    private function barangUmum()
    {
        $faker = $this->faker;
        $kategoriBarang = [
            'Alat Tulis Kantor (ATK)' => [
                [
                    'nama' => 'Pensil',
                    'satuan_besar' => 'Kotak',
                    'satuan_kecil' => 'Pcs',
                ],
                [
                    'nama' => 'Pulpen',
                    'satuan_besar' => 'Kotak',
                    'satuan_kecil' => 'Pcs',
                ],
                [
                    'nama' => 'Spidol',
                    'satuan_besar' => 'Kotak',
                    'satuan_kecil' => 'Pcs',
                ],
                [
                    'nama' => 'Penghapus',
                    'satuan_besar' => 'Kotak',
                    'satuan_kecil' => 'Pcs',
                ],
                [
                    'nama' => 'Penggaris',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Stabilo',
                    'satuan_besar' => 'Kotak',
                    'satuan_kecil' => 'Pcs',
                ],
                [
                    'nama' => 'Tipe-X',
                    'satuan_besar' => 'Kotak',
                    'satuan_kecil' => 'Pcs',
                ],
            ],
            'Peralatan Kantor' => [
                [
                    'nama' => 'Stapler',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Perforator',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Lemari Arsip',
                    'satuan_besar' => 'Unit',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Meja Kantor',
                    'satuan_besar' => 'Unit',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Kursi Kantor',
                    'satuan_besar' => 'Unit',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Laci Meja',
                    'satuan_besar' => 'Unit',
                    'satuan_kecil' => null,
                ],
            ],
            'Peralatan Kesehatan' => [
                [
                    'nama' => 'Termometer Digital',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Tensimeter',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Alat Pengukur Gula Darah',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Masker',
                    'satuan_besar' => 'Box',
                    'satuan_kecil' => 'Pcs',
                ],
                [
                    'nama' => 'Hand Sanitizer',
                    'satuan_besar' => 'Botol',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Kotak P3K',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
            ],
            'Alat Berkebun' => [
                [
                    'nama' => 'Sekop Kecil',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Cangkul',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Gunting Taman',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Pot Bunga',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Sprayer',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Benih Tanaman',
                    'satuan_besar' => 'Paket',
                    'satuan_kecil' => null,
                ],
            ],
            'Konsumsi' => [
                [
                    'nama' => 'Air Mineral Galon',
                    'satuan_besar' => 'Galon',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Kopi Sachet',
                    'satuan_besar' => 'Box',
                    'satuan_kecil' => 'Sachet',
                ],
                [
                    'nama' => 'Teh Celup',
                    'satuan_besar' => 'Box',
                    'satuan_kecil' => 'Pcs',
                ],
                [
                    'nama' => 'Gula Pasir',
                    'satuan_besar' => 'Kg',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Snack',
                    'satuan_besar' => 'Paket',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Roti Tawar',
                    'satuan_besar' => 'Bungkus',
                    'satuan_kecil' => null,
                ],
            ],
            'Aksesoris Komputer' => [
                [
                    'nama' => 'Mouse',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Keyboard',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Flashdisk',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Headset',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Cooling Pad',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Kabel HDMI',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
            ],
        ];

        foreach ($kategoriBarang as $kategoriNama => $barangList) {
            $kategori = KategoriStok::where('nama', $kategoriNama)->first();
            $jenisBarang = JenisStok::where('nama', 'Umum')->first();

            foreach ($barangList as $barang) {
                $satuanBesar = SatuanBesar::firstOrCreate(
                    ['nama' => $barang['satuan_besar']], // Kondisi pencarian
                    ['slug' => Str::slug($barang['satuan_besar'])] // Nilai default jika tidak ditemukan
                );

                $satuanKecil = $barang['satuan_kecil']
                    ? SatuanBesar::firstOrCreate(
                        ['nama' => $barang['satuan_kecil']], // Kondisi pencarian
                        ['slug' => Str::slug($barang['satuan_kecil'])] // Nilai default jika tidak ditemukan
                    )
                    : null;

                BarangStok::create([
                    'kode_barang' => $faker->unique()->numerify('BRG-#####-#####'),
                    'jenis_id' => $jenisBarang->id,
                    'nama' => $barang['nama'],
                    'slug' => Str::slug($barang['nama']),
                    'kategori_id' => $kategori->id,
                    'satuan_besar_id' => $satuanBesar->id,
                    'konversi' => $satuanKecil ? rand(5, 20) : null,
                    'satuan_kecil_id' => $satuanKecil ? $satuanKecil->id : null,
                    'deskripsi' => $barang['nama'] . ' yang tersedia di kategori ' . $kategoriNama,
                ]);
            }
        }
    }

    private function barangNonUmum()
    {
        $faker = $this->faker;
        $barangData = [
            'Material' => [
                [
                    'nama' => 'Batu Bata',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Semen',
                    'satuan_besar' => 'Sak',
                    'satuan_kecil' => 'Kg',
                ],
                [
                    'nama' => 'Pasir Beton',
                    'satuan_besar' => 'M3',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Kayu Jati',
                    'satuan_besar' => 'Batang',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Besi Beton',
                    'satuan_besar' => 'Batang',
                    'satuan_kecil' => 'Meter',
                ],
                [
                    'nama' => 'Pipa PVC',
                    'satuan_besar' => 'Batang',
                    'satuan_kecil' => 'Meter',
                ],
                [
                    'nama' => 'Gypsum',
                    'satuan_besar' => 'Lembar',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Cat Tembok',
                    'satuan_besar' => 'Kaleng',
                    'satuan_kecil' => 'Liter',
                ],
                [
                    'nama' => 'Kaca',
                    'satuan_besar' => 'Lembar',
                    'satuan_kecil' => 'Meter',
                ],
                [
                    'nama' => 'Keramik Lantai',
                    'satuan_besar' => 'Kotak',
                    'satuan_kecil' => 'Pcs',
                ],
            ],
            'Spare Part' => [
                [
                    'nama' => 'Filter Oli',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Filter Udara',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Busi',
                    'satuan_besar' => 'Kotak',
                    'satuan_kecil' => 'Pcs',
                ],
                [
                    'nama' => 'Kampas Rem',
                    'satuan_besar' => 'Set',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Belt Timing',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Radiator',
                    'satuan_besar' => 'Unit',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Alternator',
                    'satuan_besar' => 'Unit',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Baterai',
                    'satuan_besar' => 'Pcs',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Kompresor AC',
                    'satuan_besar' => 'Unit',
                    'satuan_kecil' => null,
                ],
                [
                    'nama' => 'Pompa Air',
                    'satuan_besar' => 'Unit',
                    'satuan_kecil' => null,
                ],
            ],
        ];

        foreach ($barangData as $jenisName => $barangList) {
            $jenisBarang = JenisStok::where('nama', $jenisName)->first();

            foreach ($barangList as $barang) {
                // Buat satuan besar dan kecil jika belum ada
                $satuanBesar = SatuanBesar::firstOrCreate(
                    ['nama' => $barang['satuan_besar']],
                    ['slug' => Str::slug($barang['satuan_besar'])]
                );

                $satuanKecil = $barang['satuan_kecil'] ? SatuanBesar::firstOrCreate(
                    ['nama' => $barang['satuan_kecil']],
                    ['slug' => Str::slug($barang['satuan_kecil'])]
                ) : null;

                // Seed untuk BarangStok
                BarangStok::create([
                    'kode_barang' => $faker->unique()->numerify('BRG-#####-#####'),
                    'jenis_id' => $jenisBarang->id,
                    'nama' => $barang['nama'],
                    'slug' => Str::slug($barang['nama']),
                    'kategori_id' => null, // Barang non umum tidak memiliki kategori
                    'satuan_besar_id' => $satuanBesar->id,
                    'konversi' => $satuanKecil ? rand(5, 20) : null,
                    'satuan_kecil_id' => $satuanKecil ? $satuanKecil->id : null,
                    'deskripsi' => $barang['nama'] . ' untuk jenis ' . $jenisName,
                ]);
            }
        }
    }
    private function jenis()
    {
        // Seed for JenisStok
        $jenis = ['Material', 'Spare Part', 'Umum'];
        foreach ($jenis as $nama) {
            JenisStok::create([
                'nama' => $nama,
            ]);
        }

        $kategori_umum = [
            'Alat Tulis Kantor (ATK)',
            'Peralatan Kantor',
            'Peralatan Kesehatan',
            'Alat Berkebun',
            'Konsumsi',
            'Aksesoris Komputer',
        ];
        foreach ($kategori_umum as $kategori) {
            KategoriStok::create([
                'nama' => $kategori,
                'slug' => Str::slug($kategori)
            ]);
        }


        $satuanBesarData = [
            [
                'nama' => 'Kotak',
                'slug' => Str::slug('Kotak')
            ],      // Box
            [
                'nama' => 'Palet',
                'slug' => Str::slug('Palet')
            ],      // Pallet
            [
                'nama' => 'Gulung',
                'slug' => Str::slug('Gulung')
            ],     // Roll
            [
                'nama' => 'Paket',
                'slug' => Str::slug('Paket')
            ],      // Packet
            [
                'nama' => 'Keranjang',
                'slug' => Str::slug('Keranjang')
            ],  // Crate
            [
                'nama' => 'Sak',
                'slug' => Str::slug('Sak')
            ],        // Sack
            [
                'nama' => 'Drum',
                'slug' => Str::slug('Drum')
            ],       // Drum
            [
                'nama' => 'Kardus',
                'slug' => Str::slug('Kardus')
            ],     // Carton
            [
                'nama' => 'Rim',
                'slug' => Str::slug('Rim')
            ],        // Rim
            [
                'nama' => 'Karton',
                'slug' => Str::slug('Karton')
            ],     // Cardboard
        ];

        foreach ($satuanBesarData as $data) {
            SatuanBesar::create($data);
        }
    }
}
