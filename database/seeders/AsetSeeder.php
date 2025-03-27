<?php

namespace Database\Seeders;

use App\Models\Aset;
use App\Models\Merk;
use App\Models\Toko;
use App\Models\User;
use App\Models\Lokasi;
use App\Models\Person;
use App\Models\Kategori;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AsetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');


        // Daftar aset berdasarkan kategori dan merk
        $asetData = [
            'Elektronik' => [
                'Samsung' => [
                    'Televisi LED 42 Inch',
                    'Kulkas Dua Pintu',
                    'AC Split 1 PK',
                    'Monitor Komputer 24 Inch',
                    'Smartphone Galaxy S22',
                    'Tablet Galaxy Tab S8',
                    'Soundbar Dolby Atmos',
                ],
                'Sony' => [
                    'Home Theater',
                    'Speaker Bluetooth',
                    'PlayStation 5',
                    'Kamera Digital',
                    'Headphone Noise Cancelling',
                    'Televisi OLED 55 Inch',
                    'Walkman MP3 Player',
                ],
                'LG' => [
                    'Mesin Cuci Front Loading',
                    'Proyektor Portabel',
                    'Microwave Oven',
                    'Monitor 27 Inch',
                    'Kulkas Side-by-Side',
                    'AC Dual Inverter 1.5 PK',
                    'Smart TV NanoCell 4K',
                ],
                'Apple' => [
                    'MacBook Pro M1',
                    'iPad Pro 12.9 Inch',
                    'iPhone 14 Pro Max',
                    'Apple Watch Series 8',
                    'iMac 24 Inch',
                    'AirPods Pro',
                    'HomePod Mini',
                ],
                'Asus' => [
                    'Laptop ROG Zephyrus',
                    'Monitor Gaming 144Hz',
                    'Motherboard ROG Strix',
                    'Router WiFi 6',
                    'Mini PC PN50',
                    'Projector LED',
                    'Chromebook Flip',
                ],
            ],
            // 'Otomotif' => [
            //     'Toyota' => [
            //         'Mobil Avanza',
            //         'Mobil Rush',
            //         'Mobil Innova',
            //         'Forklift 3 Ton',
            //         'Mobil Corolla Cross',
            //         'SUV Fortuner',
            //         'Truk Dyna',
            //     ],
            //     'Honda' => [
            //         'Motor Beat',
            //         'Motor Vario',
            //         'Motor PCX',
            //         'Mobil HRV',
            //         'Mobil BRV',
            //         'Motor CBR 150R',
            //         'Scoopy Stylish',
            //     ],
            //     'Suzuki' => [
            //         'Motor Satria FU',
            //         'Motor Nex II',
            //         'Mobil Ertiga',
            //         'Motor GSX-R150',
            //         'APV Arena',
            //         'Mobil Carry Pick-up',
            //         'Motor Burgman Street',
            //     ],
            //     'Yamaha' => [
            //         'Motor NMAX',
            //         'Motor Aerox',
            //         'Motor MT-15',
            //         'Motor R15 V4',
            //         'Motor XSR 155',
            //         'Scooter Mio M3',
            //         'Motor Tracer 900',
            //     ],
            //     'Daihatsu' => [
            //         'Mobil Xenia',
            //         'Mobil Terios',
            //         'Mobil Gran Max',
            //         'Mobil Sigra',
            //         'Mobil Ayla',
            //         'Mobil Rocky',
            //         'Pick-up Hi-Max',
            //     ],
            // ],
            'Furniture' => [
                'IKEA' => [
                    'Meja Kerja Minimalis',
                    'Kursi Ergonomis',
                    'Lemari Pakaian 3 Pintu',
                    'Rak Buku Kayu',
                    'Meja Rapat Besar',
                    'Kursi Bar',
                    'Kabinet Laci Modular',
                ],
                'Ace Hardware' => [
                    'Sofa Tamu',
                    'Lemari Besi Arsip',
                    'Meja Makan 6 Kursi',
                    'Kursi Lipat Aluminium',
                    'Tempat Tidur King Size',
                    'Set Rak Dapur',
                    'Meja Lipat Portabel',
                ],
                'VIVERE' => [
                    'Tempat Tidur Queen Size',
                    'Meja Konsol Kayu',
                    'Buffet TV Modern',
                    'Rak Sepatu',
                    'Meja Kopi Marmer',
                    'Kursi Santai Rotan',
                    'Sofa L-Shape',
                ],
                'Olympic' => [
                    'Lemari Sliding Door',
                    'Meja Belajar Anak',
                    'Kabinet Dapur Kayu',
                    'Kursi Kantor',
                    'Meja Tamu Minimalis',
                    'Rak TV Minimalis',
                    'Tempat Tidur Double',
                ],
                'Informa' => [
                    'Meja Kantor Besar',
                    'Kursi Gaming',
                    'Lemari Gantung',
                    'Rak Dinding',
                    'Meja Makan Lipat',
                    'Kabinet Arsip',
                    'Set Kursi Makan',
                ],
            ],
        ];

        foreach ($asetData as $kategoriName => $merkData) {
            foreach ($merkData as $merkName => $asetList) {
                // $merk = Merk::where('nama', $merkName)->first();
                $merk = Merk::firstOrCreate(['nama' => $merkName, 'nama_nospace' => Str::slug($merkName)], ['user_id' => User::inRandomOrder()->first()->id]);


                foreach ($asetList as $asetName) {
                    // if ($kategoriName == 'Otomotif') {
                    //     $kategori = Kategori::find(1);
                    // } else {
                    $kategori = Kategori::whereNotIn('id', [1, 2, 3, 4, 5, 6, 7])->inRandomOrder()->first();
                    // }
                    $hargaSatuan = rand(1000000, 5000000); // Hitung harga satuan
                    $jumlah = rand(1, 10); // Tentukan jumlah
                    $hargatotal = $hargaSatuan * $jumlah; // Hitung harga total
                    Aset::create([
                        'user_id' => User::inRandomOrder()->first()->id,
                        'nama' => $asetName,
                        'slug' => Str::slug($asetName),
                        'systemcode' => $faker->unique()->regexify('[A-Z0-9]{8}'),
                        'kategori_id' => $kategori->id,
                        'merk_id' => $merk->id,
                        'person_id' => Person::inRandomOrder()->first()->id ?? null,
                        'toko_id' => Toko::inRandomOrder()->first()->id ?? null,
                        'lokasi_id' => Lokasi::inRandomOrder()->first()->id ?? null,
                        'tanggalbeli' => $faker->dateTimeBetween('-2 years', 'now')->getTimestamp(),
                        'jumlah' => $jumlah,
                        'hargasatuan' => $hargaSatuan,
                        'hargatotal' => $hargatotal,
                        'aktif' => 1,
                        'status' => 1,
                    ]);
                }
            }
        }

        $umumkdoData = [
            [
                'nama' => 'Hiace',
                'tipe' => 'Mirobus',
                'merk' => 'Toyota',
                'deskripsi' => 'Silver Metalik',
                'thproduksi' => 2018,
                'noseri' => 'B 7670 PPA',
                'keterangan' => 'Sekretariat (Umum)(Pengurus Barang)',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'Hiace',
                'tipe' => 'Micro/Minibus',
                'merk' => 'Toyota',
                'deskripsi' => 'Putih',
                'thproduksi' => 2024,
                'noseri' => 'B 7582 PPB',
                'keterangan' => 'Gedung Pompa Cideng',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'Hiace',
                'tipe' => 'Micro/Minibus',
                'merk' => 'Toyota',
                'deskripsi' => 'Putih',
                'thproduksi' => 2024,
                'noseri' => 'B 7584 PPB',
                'keterangan' => 'Gedung Pompa Cideng',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'Hilux Rangga',
                'tipe' => 'Pick UP',
                'merk' => 'Toyota',
                'deskripsi' => 'Putih',
                'thproduksi' => 2024,
                'noseri' => 'B 9936 PTB',
                'keterangan' => 'Sekretariat (Umum)(Pengurus Barang)',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'Avanza',
                'tipe' => 'Minibus',
                'merk' => 'Toyota',
                'deskripsi' => 'Silver Metalik',
                'thproduksi' => 2006,
                'noseri' => 'B 8515 WU',
                'keterangan' => 'Sekretariat (Umum)(Pengurus Barang)',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'Panther Tour',
                'tipe' => 'Minibus',
                'merk' => 'Isuzu',
                'deskripsi' => 'Hitam',
                'thproduksi' => 2004,
                'noseri' => 'B 2184 BQ',
                'keterangan' => 'Sekretariat (Umum)(Pengurus Barang)',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'Hillux',
                'tipe' => 'Pick UP',
                'merk' => 'Toyota',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2009,
                'noseri' => 'B 9010 PTA',
                'keterangan' => 'Sekretariat (Umum)(Pengurus Barang)',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'XL7',
                'tipe' => 'Mobil Penumpang',
                'merk' => 'Suzuki',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2024,
                'noseri' => 'B 1770 PQG',
                'keterangan' => 'Sekretariat (Umum)(Pengurus Barang)',
                'peminjaman' => 0,
            ],
            [
                'nama' => 'XL7',
                'tipe' => 'Mobil Penumpang',
                'merk' => 'Suzuki',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2024,
                'noseri' => 'B 1772 PQG',
                'keterangan' => 'Sekretariat (Umum)(Pengurus Barang)',
                'peminjaman' => 0,
            ],
            [
                'nama' => 'Altis',
                'tipe' => 'Sedan',
                'merk' => 'Toyota',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2007,
                'noseri' => 'B 2295 UQ',
                'keterangan' => 'Sekretariat (Umum)(Pengurus Barang)',
                'peminjaman' => 0,
            ],
            [
                'nama' => 'Ford',
                'tipe' => 'Pick UP',
                'merk' => 'Ford',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2010,
                'noseri' => 'B 9073 PSC',
                'keterangan' => 'Sekretariat (Umum)(Pengurus Barang)',
                'peminjaman' => 0,
            ],
            [
                'nama' => 'Kijang Inova Zenix',
                'tipe' => 'MPV',
                'merk' => 'Toyota',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2023,
                'noseri' => 'B 1294 PQG',
                'keterangan' => 'Pool Sekretariat',
                'peminjaman' => 0,
            ],
        ];

        foreach ($umumkdoData as $data) {
            $merk = Merk::firstOrCreate(
                ['nama' => $data['merk'], 'nama_nospace' => Str::slug($data['merk'])],
                ['user_id' => User::inRandomOrder()->first()->id]
            );

            // Tentukan kategori khusus KDO
            $kategori = Kategori::find(1);

            Aset::create([
                'user_id' => User::whereBetween('unit_id', [1, 7])->inRandomOrder()->first()->id,
                'nama' => $data['nama'],
                'slug' => Str::slug($data['nama']),
                'tipe' => $data['tipe'],
                'systemcode' => $faker->unique()->regexify('[A-Z0-9]{8}'),
                'kode' => $faker->unique()->regexify('[A-Z0-9]{5}'),
                'kategori_id' => $kategori->id,
                'merk_id' => $merk->id,
                'deskripsi' => $data['deskripsi'],
                'thproduksi' => $data['thproduksi'],
                'noseri' => $data['noseri'],
                'keterangan' => $data['keterangan'],
                'person_id' => Person::inRandomOrder()->first()->id ?? null,
                'toko_id' => Toko::inRandomOrder()->first()->id ?? null,
                'lokasi_id' => Lokasi::inRandomOrder()->first()->id ?? null,
                'tanggalbeli' => $faker->dateTimeBetween('-2 years', 'now')->getTimestamp(),
                'jumlah' => 1,
                'hargasatuan' => rand(50000000, 500000000),
                'hargatotal' => rand(50000000, 500000000),
                'aktif' => 1,
                'status' => 1,
                'peminjaman' => $data['peminjaman'],
            ]);
        }

        $geologikdoData = [
            [
                'nama' => 'XL7',
                'tipe' => 'Mobil Penumpang',
                'merk' => 'Suzuki ',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2024,
                'noseri' => 'B 1766 PQG',
                'keterangan' => 'Bidang Geologi, Konservasi Air Baku dan Penyediaan Air Bersih',
            ],
            [
                'nama' => 'Altis',
                'tipe' => 'Sedan',
                'merk' => 'Toyota ',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2010,
                'noseri' => 'B 2279 OQ',
                'keterangan' => 'Kabid Geologi, Konservasi Air Baku dan Penyediaan Air Bersih',
            ],
            [
                'nama' => 'Altis',
                'tipe' => 'Sedan',
                'merk' => 'Toyota ',
                'deskripsi' => 'Silver Metalik',
                'thproduksi' => 2007,
                'noseri' => 'B 2277 OQ',
                'keterangan' => 'Bidang Geologi, Konservasi Air Baku dan Penyediaan Air Bersih',
            ],
            [
                'nama' => 'Ford',
                'tipe' => 'Pick UP',
                'merk' => 'Ford ',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2010,
                'noseri' => 'B 9072 PSC',
                'keterangan' => 'Bidang Geologi, Konservasi Air Baku dan Penyediaan Air Bersih',
            ],
        ];

        foreach ($geologikdoData as $data) {
            $merk = Merk::firstOrCreate(
                ['nama' => $data['merk'], 'nama_nospace' => Str::slug($data['merk'])],
                ['user_id' => User::inRandomOrder()->first()->id]
            );

            // Tentukan kategori khusus KDO
            $kategori = Kategori::find(1);

            Aset::create([
                'user_id' => User::whereBetween('unit_id', [14, 19])->inRandomOrder()->first()->id,
                'nama' => $data['nama'],
                'slug' => Str::slug($data['nama']),
                'tipe' => $data['tipe'],
                'systemcode' => $faker->unique()->regexify('[A-Z0-9]{8}'),
                'kode' => $faker->unique()->regexify('[A-Z0-9]{5}'),
                'kategori_id' => $kategori->id,
                'merk_id' => $merk->id,
                'deskripsi' => $data['deskripsi'],
                'thproduksi' => $data['thproduksi'],
                'noseri' => $data['noseri'],
                'keterangan' => $data['keterangan'],
                'person_id' => Person::inRandomOrder()->first()->id ?? null,
                'toko_id' => Toko::inRandomOrder()->first()->id ?? null,
                'lokasi_id' => Lokasi::inRandomOrder()->first()->id ?? null,
                'tanggalbeli' => $faker->dateTimeBetween('-2 years', 'now')->getTimestamp(),
                'jumlah' => 1,
                'hargasatuan' => rand(50000000, 500000000),
                'hargatotal' => rand(50000000, 500000000),
                'aktif' => 1,
                'status' => 1,
            ]);
        }

        $limbahkdoData = [
            [
                'nama' => 'XL7',
                'tipe' => 'Mobil Penumpang',
                'merk' => 'Suzuki ',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2024,
                'noseri' => 'B 1764 PQG',
                'keterangan' => 'Bidang Pengelolaan Air Limbah',
            ],
            [
                'nama' => 'XL7',
                'tipe' => 'Mobil Penumpang',
                'merk' => 'Suzuki ',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2024,
                'noseri' => 'B 1762 PQG',
                'keterangan' => 'Bidang Pengelolaan Air Limbah',
            ],
            [
                'nama' => 'New Corolla Altis',
                'tipe' => 'Sedan',
                'merk' => 'Toyota',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2012,
                'noseri' => 'B 1526 PQA',
                'keterangan' => 'Bidang Pengelolaan Air Limbah',
            ],
            [
                'nama' => 'Kijang',
                'tipe' => 'Pick Up',
                'merk' => 'Toyota',
                'deskripsi' => 'Hitam',
                'thproduksi' => 2003,
                'noseri' => 'B 9520 PQ',
                'keterangan' => 'Bidang Pengelolaan Air Limbah',
            ],
            [
                'nama' => 'Strada Double Cabin',
                'tipe' => 'Double Cabine',
                'merk' => 'Mitsubishi',
                'deskripsi' => 'Hitam',
                'thproduksi' => 2013,
                'noseri' => 'B 9520 PQ',
                'keterangan' => 'Bidang Pengelolaan Air Limbah',
            ],
        ];

        foreach ($limbahkdoData as $data) {
            $merk = Merk::firstOrCreate(
                ['nama' => $data['merk'], 'nama_nospace' => Str::slug($data['merk'])],
                ['user_id' => User::inRandomOrder()->first()->id]
            );

            // Tentukan kategori khusus KDO
            $kategori = Kategori::find(1);

            Aset::create([
                'user_id' => User::whereBetween('unit_id', [26, 31])->inRandomOrder()->first()->id,
                'nama' => $data['nama'],
                'slug' => Str::slug($data['nama']),
                'tipe' => $data['tipe'],
                'systemcode' => $faker->unique()->regexify('[A-Z0-9]{8}'),
                'kode' => $faker->unique()->regexify('[A-Z0-9]{5}'),
                'kategori_id' => $kategori->id,
                'merk_id' => $merk->id,
                'deskripsi' => $data['deskripsi'],
                'thproduksi' => $data['thproduksi'],
                'noseri' => $data['noseri'],
                'keterangan' => $data['keterangan'],
                'person_id' => Person::inRandomOrder()->first()->id ?? null,
                'toko_id' => Toko::inRandomOrder()->first()->id ?? null,
                'lokasi_id' => Lokasi::inRandomOrder()->first()->id ?? null,
                'tanggalbeli' => $faker->dateTimeBetween('-2 years', 'now')->getTimestamp(),
                'jumlah' => 1,
                'hargasatuan' => rand(50000000, 500000000),
                'hargatotal' => rand(50000000, 500000000),
                'aktif' => 1,
                'status' => 1,
            ]);
        }
        $banjirkdoData = [
            [
                'nama' => 'XL7',
                'tipe' => 'Mobil Penumpang',
                'merk' => 'Suzuki ',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2024,
                'noseri' => 'B 1756 PQG',
                'keterangan' => 'Bidang Pengendalian Banjir dan Drainase',
            ],
            [
                'nama' => 'XL7',
                'tipe' => 'Mobil Penumpang',
                'merk' => 'Suzuki ',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2024,
                'noseri' => 'B 1754 PQG',
                'keterangan' => 'Bidang Pengendalian Banjir dan Drainase',
            ],
            [
                'nama' => 'Navara',
                'tipe' => 'Double Cabin',
                'merk' => 'Nissan',
                'deskripsi' => 'Silver Metalik',
                'thproduksi' => 2017,
                'noseri' => 'B 9697 PSD',
                'keterangan' => 'Bidang Pengendalian Banjir dan Drainase',
            ],
            [
                'nama' => 'Navara',
                'tipe' => 'Double Cabin',
                'merk' => 'Nissan',
                'deskripsi' => 'Silver Metalik',
                'thproduksi' => 2018,
                'noseri' => 'B 9788 PSD',
                'keterangan' => 'Bidang Pengendalian Banjir dan Drainase',
            ],
            [
                'nama' => 'Atlis',
                'tipe' => 'Sedan',
                'merk' => 'Toyota',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2015,
                'noseri' => 'B 1072 PQB',
                'keterangan' => 'Bidang Pengendalian Banjir dan Drainase',
            ],
            [
                'nama' => 'Ford',
                'tipe' => 'Double Cabin',
                'merk' => 'Ford',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2010,
                'noseri' => 'B 9070 PSC',
                'keterangan' => 'Bidang Pengendalian Banjir dan Drainase',
            ],
            [
                'nama' => 'Strada Double Cabin',
                'tipe' => 'Double Cabin',
                'merk' => 'Mitsubishi',
                'deskripsi' => 'Putih',
                'thproduksi' => 2012,
                'noseri' => 'B 9622 PSC',
                'keterangan' => 'Bidang Pengendalian Banjir dan Drainase',
            ],
            [
                'nama' => 'Strada Double Cabin',
                'tipe' => 'Double Cabin',
                'merk' => 'Mitsubishi',
                'deskripsi' => 'Hitam',
                'thproduksi' => 2013,
                'noseri' => 'B 9153 PSD',
                'keterangan' => 'Bidang Pengendalian Banjir dan Drainase',
            ],
        ];

        foreach ($banjirkdoData as $data) {
            $merk = Merk::firstOrCreate(
                ['nama' => $data['merk'], 'nama_nospace' => Str::slug($data['merk'])],
                ['user_id' => User::inRandomOrder()->first()->id]
            );

            // Tentukan kategori khusus KDO
            $kategori = Kategori::find(1);

            Aset::create([
                'user_id' => User::whereBetween('unit_id', [8, 13])->inRandomOrder()->first()->id,
                'nama' => $data['nama'],
                'slug' => Str::slug($data['nama']),
                'tipe' => $data['tipe'],
                'systemcode' => $faker->unique()->regexify('[A-Z0-9]{8}'),
                'kode' => $faker->unique()->regexify('[A-Z0-9]{5}'),
                'kategori_id' => $kategori->id,
                'merk_id' => $merk->id,
                'deskripsi' => $data['deskripsi'],
                'thproduksi' => $data['thproduksi'],
                'noseri' => $data['noseri'],
                'keterangan' => $data['keterangan'],
                'person_id' => Person::inRandomOrder()->first()->id ?? null,
                'toko_id' => Toko::inRandomOrder()->first()->id ?? null,
                'lokasi_id' => Lokasi::inRandomOrder()->first()->id ?? null,
                'tanggalbeli' => $faker->dateTimeBetween('-2 years', 'now')->getTimestamp(),
                'jumlah' => 1,
                'hargasatuan' => rand(50000000, 500000000),
                'hargatotal' => rand(50000000, 500000000),
                'aktif' => 1,
                'status' => 1,
            ]);
        }

        $robkdoData = [
            [
                'nama' => 'XL7',
                'tipe' => 'Mobil Penumpang',
                'merk' => 'Suzuki',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2024,
                'noseri' => 'B 1758 PQG',
                'keterangan' => 'Bidang Pengendalian Rob dan Pengembangan Pesisir Pantai',
            ],
            [
                'nama' => 'XL7',
                'tipe' => 'Mobil Penumpang',
                'merk' => 'Suzuki',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2024,
                'noseri' => 'B 1760 PQG',
                'keterangan' => 'Bidang Pengendalian Rob dan Pengembangan Pesisir Pantai',
            ],
            [
                'nama' => 'New Corolla Atlis',
                'tipe' => 'Sedan',
                'merk' => 'Toyota',
                'deskripsi' => 'Hitam',
                'thproduksi' => 2006,
                'noseri' => 'B 8658 WU',
                'keterangan' => 'Bidang Pengendalian Rob dan Pengembangan Pesisir Pantai',
            ],
            [
                'nama' => 'Panther',
                'tipe' => 'Minibus',
                'merk' => 'Izuzu',
                'deskripsi' => 'Silver Metalik',
                'thproduksi' => 2007,
                'noseri' => 'B 2168 JQ',
                'keterangan' => 'Bidang Pengendalian Rob dan Pengembangan Pesisir Pantai',
            ],
            [
                'nama' => 'Ford',
                'tipe' => 'Double Cabin',
                'merk' => 'Ford',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2008,
                'noseri' => 'B 9053 BQ',
                'keterangan' => 'Bidang Pengendalian Rob dan Pengembangan Pesisir Pantai',
            ],
            [
                'nama' => 'Ford Ranger R',
                'tipe' => 'Double Cabin',
                'merk' => 'Ford',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2013,
                'noseri' => 'B 9135 PSD',
                'keterangan' => 'Bidang Pengendalian Rob dan Pengembangan Pesisir Pantai',
            ],
            [
                'nama' => 'TBR 54 PU Turbo',
                'tipe' => 'Pick Up',
                'merk' => 'Izuzu',
                'deskripsi' => 'Hitam',
                'thproduksi' => 2017,
                'noseri' => 'B 9226 PTB',
                'keterangan' => 'Bidang Pengendalian Rob dan Pengembangan Pesisir Pantai',
            ],
        ];

        foreach ($robkdoData as $data) {
            $merk = Merk::firstOrCreate(
                ['nama' => $data['merk'], 'nama_nospace' => Str::slug($data['merk'])],
                ['user_id' => User::inRandomOrder()->first()->id]
            );

            // Tentukan kategori khusus KDO
            $kategori = Kategori::find(1);

            Aset::create([
                'user_id' => User::whereBetween('unit_id', [20, 25])->inRandomOrder()->first()->id,
                'nama' => $data['nama'],
                'slug' => Str::slug($data['nama']),
                'tipe' => $data['tipe'],
                'systemcode' => $faker->unique()->regexify('[A-Z0-9]{8}'),
                'kode' => $faker->unique()->regexify('[A-Z0-9]{5}'),
                'kategori_id' => $kategori->id,
                'merk_id' => $merk->id,
                'deskripsi' => $data['deskripsi'],
                'thproduksi' => $data['thproduksi'],
                'noseri' => $data['noseri'],
                'keterangan' => $data['keterangan'],
                'person_id' => Person::inRandomOrder()->first()->id ?? null,
                'toko_id' => Toko::inRandomOrder()->first()->id ?? null,
                'lokasi_id' => Lokasi::inRandomOrder()->first()->id ?? null,
                'tanggalbeli' => $faker->dateTimeBetween('-2 years', 'now')->getTimestamp(),
                'jumlah' => 1,
                'hargasatuan' => rand(50000000, 500000000),
                'hargatotal' => rand(50000000, 500000000),
                'aktif' => 1,
                'status' => 1,
            ]);
        }

        $pusdatinkdoData = [
            [
                'nama' => 'Hilux',
                'tipe' => 'Pick Up',
                'merk' => 'Toyota',
                'deskripsi' => 'Abu-abu Metalik',
                'thproduksi' => 2015,
                'noseri' => 'B 9809 PTA',
                'keterangan' => 'Pusat Data dan Informasi Sumber Daya Air',
            ],
            [
                'nama' => 'Expander',
                'tipe' => 'Mobil Penumpang',
                'merk' => 'Mitsubishi',
                'deskripsi' => 'Hitam',
                'thproduksi' => 2019,
                'noseri' => 'B 1916 PQT',
                'keterangan' => 'Pusat Data dan Informasi Sumber Daya Air',
            ],
            [
                'nama' => 'Xenia',
                'tipe' => 'Mobil Penumpang',
                'merk' => 'Daihatsu',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2022,
                'noseri' => 'B 1916 PQT',
                'keterangan' => 'Pusat Data dan Informasi Sumber Daya Air',
            ],
            [
                'nama' => 'Xenia',
                'tipe' => 'Mobil Penumpang',
                'merk' => 'Daihatsu',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2022,
                'noseri' => 'B 1984 PQF',
                'keterangan' => 'Pusat Data dan Informasi Sumber Daya Air',
            ],
            [
                'nama' => 'XL7',
                'tipe' => 'Mobil Penumpang',
                'merk' => 'Suzuki',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2024,
                'noseri' => 'B 1768 PQG',
                'keterangan' => 'Pusat Data dan Informasi Sumber Daya Air',
            ],
        ];

        foreach ($pusdatinkdoData as $data) {
            $merk = Merk::firstOrCreate(
                ['nama' => $data['merk'], 'nama_nospace' => Str::slug($data['merk'])],
                ['user_id' => User::inRandomOrder()->first()->id]
            );

            // Tentukan kategori khusus KDO
            $kategori = Kategori::find(1);

            Aset::create([
                'user_id' => User::whereBetween('unit_id', [38, 40])->inRandomOrder()->first()->id,
                'nama' => $data['nama'],
                'slug' => Str::slug($data['nama']),
                'tipe' => $data['tipe'],
                'systemcode' => $faker->unique()->regexify('[A-Z0-9]{8}'),
                'kode' => $faker->unique()->regexify('[A-Z0-9]{5}'),
                'kategori_id' => $kategori->id,
                'merk_id' => $merk->id,
                'deskripsi' => $data['deskripsi'],
                'thproduksi' => $data['thproduksi'],
                'noseri' => $data['noseri'],
                'keterangan' => $data['keterangan'],
                'person_id' => Person::inRandomOrder()->first()->id ?? null,
                'toko_id' => Toko::inRandomOrder()->first()->id ?? null,
                'lokasi_id' => Lokasi::inRandomOrder()->first()->id ?? null,
                'tanggalbeli' => $faker->dateTimeBetween('-2 years', 'now')->getTimestamp(),
                'jumlah' => 1,
                'hargasatuan' => rand(50000000, 500000000),
                'hargatotal' => rand(50000000, 500000000),
                'aktif' => 1,
                'status' => 1,
            ]);
        }

        $alkalkdoData = [
            [
                'nama' => 'Hilux Rangga',
                'tipe' => 'Pick Up',
                'merk' => 'Toyota',
                'deskripsi' => 'Putih',
                'thproduksi' => 2024,
                'noseri' => 'B 9940 PTB',
                'keterangan' => 'Unit Peralatan dan Perbekalan Sumber Daya Air',
            ],
            [
                'nama' => 'Hilux Rangga',
                'tipe' => 'Double Cabine',
                'merk' => 'Toyota',
                'deskripsi' => 'Hitam',
                'thproduksi' => 2024,
                'noseri' => 'B 9317 PSE',
                'keterangan' => 'Unit Peralatan dan Perbekalan Sumber Daya Air',
            ],
            [
                'nama' => 'Panther',
                'tipe' => 'Pick Up',
                'merk' => 'Isuzu',
                'deskripsi' => 'Hitam',
                'thproduksi' => 2012,
                'noseri' => 'B 9542 PQU',
                'keterangan' => 'Unit Peralatan dan Perbekalan Sumber Daya Air',
            ],
            [
                'nama' => 'Altis',
                'tipe' => 'Sedan',
                'merk' => 'Toyota',
                'deskripsi' => 'Silver',
                'thproduksi' => 2012,
                'noseri' => 'B 2320 UQ',
                'keterangan' => 'Unit Peralatan dan Perbekalan Sumber Daya Air',
            ],
            [
                'nama' => 'Strada',
                'tipe' => 'Double Cabin',
                'merk' => 'Mitsubishi',
                'deskripsi' => 'Hitam',
                'thproduksi' => 2013,
                'noseri' => 'B 9914 PSC',
                'keterangan' => 'Unit Peralatan dan Perbekalan Sumber Daya Air',
            ],
            [
                'nama' => 'Hilux',
                'tipe' => 'Pick Up',
                'merk' => 'Toyota',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2015,
                'noseri' => 'B 9896 PTA',
                'keterangan' => 'Unit Peralatan dan Perbekalan Sumber Daya Air',
            ],
            [
                'nama' => 'Grandmax',
                'tipe' => 'Mobil Barang/Del Van',
                'merk' => 'Daihatsu',
                'deskripsi' => 'Putih',
                'thproduksi' => 2015,
                'noseri' => 'B 9174 POV',
                'keterangan' => 'Unit Peralatan dan Perbekalan Sumber Daya Air',
            ],
            [
                'nama' => 'Grandmax',
                'tipe' => 'Mobil Barang/Del Van',
                'merk' => 'Daihatsu',
                'deskripsi' => 'Putih',
                'thproduksi' => 2015,
                'noseri' => 'B 9175 POV',
                'keterangan' => 'Unit Peralatan dan Perbekalan Sumber Daya Air',
            ],
            [
                'nama' => 'Expander',
                'tipe' => 'Mobil Penumpang',
                'merk' => 'Mitsubishi',
                'deskripsi' => 'Hitam',
                'thproduksi' => 2019,
                'noseri' => 'B 1915 PQT',
                'keterangan' => 'Unit Peralatan dan Perbekalan Sumber Daya Air',
            ],
            [
                'nama' => 'Taft',
                'tipe' => 'Jeep',
                'merk' => 'Daihatsu',
                'deskripsi' => 'Biru',
                'thproduksi' => 1994,
                'noseri' => 'B 2322 DQ',
                'keterangan' => 'Unit Peralatan dan Perbekalan Sumber Daya Air',
            ],
        ];

        foreach ($alkalkdoData as $data) {
            $merk = Merk::firstOrCreate(
                ['nama' => $data['merk'], 'nama_nospace' => Str::slug($data['merk'])],
                ['user_id' => User::inRandomOrder()->first()->id]
            );

            // Tentukan kategori khusus KDO
            $kategori = Kategori::find(1);

            Aset::create([
                'user_id' => User::whereBetween('unit_id', [38, 40])->inRandomOrder()->first()->id,
                'nama' => $data['nama'],
                'slug' => Str::slug($data['nama']),
                'tipe' => $data['tipe'],
                'systemcode' => $faker->unique()->regexify('[A-Z0-9]{8}'),
                'kode' => $faker->unique()->regexify('[A-Z0-9]{5}'),
                'kategori_id' => $kategori->id,
                'merk_id' => $merk->id,
                'deskripsi' => $data['deskripsi'],
                'thproduksi' => $data['thproduksi'],
                'noseri' => $data['noseri'],
                'keterangan' => $data['keterangan'],
                'person_id' => Person::inRandomOrder()->first()->id ?? null,
                'toko_id' => Toko::inRandomOrder()->first()->id ?? null,
                'lokasi_id' => Lokasi::inRandomOrder()->first()->id ?? null,
                'tanggalbeli' => $faker->dateTimeBetween('-2 years', 'now')->getTimestamp(),
                'jumlah' => 1,
                'hargasatuan' => rand(50000000, 500000000),
                'hargatotal' => rand(50000000, 500000000),
                'aktif' => 1,
                'status' => 1,
            ]);
        }

        $tanahkdoData = [
            [
                'nama' => 'Hilux',
                'tipe' => 'Double Cabin',
                'merk' => 'Toyota',
                'deskripsi' => 'Hitam',
                'thproduksi' => 2024,
                'noseri' => 'B 9315 PSE',
                'keterangan' => 'Unit Peralatan dan Perbekalan Sumber Daya Air',
            ],
            [
                'nama' => 'Hilux Rangga',
                'tipe' => 'Double Cabin',
                'merk' => 'Toyota',
                'deskripsi' => 'Putih',
                'thproduksi' => 2024,
                'noseri' => 'B 9937 PTB',
                'keterangan' => 'Unit Peralatan dan Perbekalan Sumber Daya Air',
            ],
            [
                'nama' => 'Strada',
                'tipe' => 'Single Cabin',
                'merk' => 'Mitsubishi',
                'deskripsi' => 'Hitam',
                'thproduksi' => 2013,
                'noseri' => 'B 9602 PTA',
                'keterangan' => 'Unit Peralatan dan Perbekalan Sumber Daya Air',
            ],
            [
                'nama' => 'Xenia',
                'tipe' => 'Minibus',
                'merk' => 'Daihatsu',
                'deskripsi' => 'Hijau Muda',
                'thproduksi' => 2008,
                'noseri' => 'B 1278 PQN',
                'keterangan' => 'Unit Peralatan dan Perbekalan Sumber Daya Air',
            ],
            [
                'nama' => 'Panther',
                'tipe' => 'Pick Up',
                'merk' => 'Izuzu',
                'deskripsi' => 'Hitam',
                'thproduksi' => 2013,
                'noseri' => 'B 9583 PTA',
                'keterangan' => 'Unit Peralatan dan Perbekalan Sumber Daya Air',
            ],
            [
                'nama' => 'Panther',
                'tipe' => 'Pick Up',
                'merk' => 'Izuzu',
                'deskripsi' => 'Biru',
                'thproduksi' => 2005,
                'noseri' => 'B 9076 OQ',
                'keterangan' => 'Unit Peralatan dan Perbekalan Sumber Daya Air',
            ],
            [
                'nama' => 'Katana Standard',
                'tipe' => 'Mobil Penumpang',
                'merk' => 'Suzuki',
                'deskripsi' => 'Hitam',
                'thproduksi' => 1997,
                'noseri' => 'B 1212 LQ',
                'keterangan' => 'Unit Peralatan dan Perbekalan Sumber Daya Air',
            ],
        ];

        foreach ($tanahkdoData as $data) {
            $merk = Merk::firstOrCreate(
                ['nama' => $data['merk'], 'nama_nospace' => Str::slug($data['merk'])],
                ['user_id' => User::inRandomOrder()->first()->id]
            );

            // Tentukan kategori khusus KDO
            $kategori = Kategori::find(1);

            Aset::create([
                'user_id' => User::whereBetween('unit_id', [41, 43])->inRandomOrder()->first()->id,
                'nama' => $data['nama'],
                'slug' => Str::slug($data['nama']),
                'tipe' => $data['tipe'],
                'systemcode' => $faker->unique()->regexify('[A-Z0-9]{8}'),
                'kode' => $faker->unique()->regexify('[A-Z0-9]{5}'),
                'kategori_id' => $kategori->id,
                'merk_id' => $merk->id,
                'deskripsi' => $data['deskripsi'],
                'thproduksi' => $data['thproduksi'],
                'noseri' => $data['noseri'],
                'keterangan' => $data['keterangan'],
                'person_id' => Person::inRandomOrder()->first()->id ?? null,
                'toko_id' => Toko::inRandomOrder()->first()->id ?? null,
                'lokasi_id' => Lokasi::inRandomOrder()->first()->id ?? null,
                'tanggalbeli' => $faker->dateTimeBetween('-2 years', 'now')->getTimestamp(),
                'jumlah' => 1,
                'hargasatuan' => rand(50000000, 500000000),
                'hargatotal' => rand(50000000, 500000000),
                'aktif' => 1,
                'status' => 1,
            ]);
        }

        $upppkdoData = [
            [
                'nama' => 'Hilux',
                'tipe' => 'Double Cabin',
                'merk' => 'Toyota',
                'deskripsi' => 'Hitam',
                'thproduksi' => 2024,
                'noseri' => 'B 9316 PSE',
                'keterangan' => 'Unit Pengelola Penyelidikan, Pengujian dan Pengukuran Sumber Daya Air',
            ],
            [
                'nama' => 'Atlis',
                'tipe' => 'Sedan',
                'merk' => 'Toyota',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2010,
                'noseri' => 'B 1295 PQA',
                'keterangan' => 'Unit Pengelola Penyelidikan, Pengujian dan Pengukuran Sumber Daya Air',
            ],
            [
                'nama' => 'Avanza',
                'tipe' => 'Minibus',
                'merk' => 'Toyota',
                'deskripsi' => 'Silver Metalik',
                'thproduksi' => 2006,
                'noseri' => 'B 8483 WU',
                'keterangan' => 'Unit Pengelola Penyelidikan, Pengujian dan Pengukuran Sumber Daya Air',
            ],
            [
                'nama' => 'Ford',
                'tipe' => 'Single Cabin',
                'merk' => 'Ford',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2008,
                'noseri' => 'B 9052 BQ',
                'keterangan' => 'Unit Pengelola Penyelidikan, Pengujian dan Pengukuran Sumber Daya Air',
            ],
            [
                'nama' => 'Strada',
                'tipe' => 'Single Cabin',
                'merk' => 'Mitsubishi ',
                'deskripsi' => 'Hitam',
                'thproduksi' => 2013,
                'noseri' => 'B 9599 PTA',
                'keterangan' => 'Unit Pengelola Penyelidikan, Pengujian dan Pengukuran Sumber Daya Air',
            ],
            [
                'nama' => 'Hillux',
                'tipe' => 'Pick Up',
                'merk' => 'Toyota',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2015,
                'noseri' => 'B 9061 PTB',
                'keterangan' => 'Unit Pengelola Penyelidikan, Pengujian dan Pengukuran Sumber Daya Air',
            ],
            [
                'nama' => 'Mitsubishi Single Cabin',
                'tipe' => 'Pick Up',
                'merk' => 'Mitsubishi',
                'deskripsi' => 'Hitam Metalik',
                'thproduksi' => 2014,
                'noseri' => 'B 9601 PTA',
                'keterangan' => 'Unit Pengelola Penyelidikan, Pengujian dan Pengukuran Sumber Daya Air',
            ],
        ];

        foreach ($upppkdoData as $data) {
            $merk = Merk::firstOrCreate(
                ['nama' => $data['merk'], 'nama_nospace' => Str::slug($data['merk'])],
                ['user_id' => User::inRandomOrder()->first()->id]
            );

            // Tentukan kategori khusus KDO
            $kategori = Kategori::find(1);

            Aset::create([
                'user_id' => User::whereBetween('unit_id', [41, 43])->inRandomOrder()->first()->id,
                'nama' => $data['nama'],
                'slug' => Str::slug($data['nama']),
                'tipe' => $data['tipe'],
                'systemcode' => $faker->unique()->regexify('[A-Z0-9]{8}'),
                'kode' => $faker->unique()->regexify('[A-Z0-9]{5}'),
                'kategori_id' => $kategori->id,
                'merk_id' => $merk->id,
                'deskripsi' => $data['deskripsi'],
                'thproduksi' => $data['thproduksi'],
                'noseri' => $data['noseri'],
                'keterangan' => $data['keterangan'],
                'person_id' => Person::inRandomOrder()->first()->id ?? null,
                'toko_id' => Toko::inRandomOrder()->first()->id ?? null,
                'lokasi_id' => Lokasi::inRandomOrder()->first()->id ?? null,
                'tanggalbeli' => $faker->dateTimeBetween('-2 years', 'now')->getTimestamp(),
                'jumlah' => 1,
                'hargasatuan' => rand(50000000, 500000000),
                'hargatotal' => rand(50000000, 500000000),
                'aktif' => 1,
                'status' => 1,
            ]);
        }

        $asetRuangan = [
            [
                'nama' => 'Ruangan Meeting Utama',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan besar untuk rapat internal dan eksternal.',
            ],
            [
                'nama' => 'Ruangan Arsip',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan khusus untuk menyimpan dokumen arsip.',
            ],
            [
                'nama' => 'Ruangan Server',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan dengan kontrol suhu untuk perangkat server.',
            ],
            [
                'nama' => 'Ruangan Kepala Divisi',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan khusus untuk kepala divisi.',
            ],
            [
                'nama' => 'Ruangan HRD',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan kerja tim Human Resources.',
            ],
            [
                'nama' => 'Ruangan IT Support',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan untuk tim pendukung teknologi informasi.',
            ],
            [
                'nama' => 'Ruang Tunggu Tamu',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan dengan sofa untuk menunggu tamu.',
            ],
            [
                'nama' => 'Ruangan Pantry',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan kecil untuk persiapan minuman dan makanan ringan.',
            ],
            [
                'nama' => 'Ruang Meeting Kecil',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan kecil untuk diskusi tim atau rapat internal.',
            ],
            [
                'nama' => 'Ruang Presentasi',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan dengan fasilitas presentasi modern.',
            ],
            [
                'nama' => 'Ruangan Training',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan untuk pelatihan karyawan dan workshop.',
            ],
            [
                'nama' => 'Ruangan Logistik',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan untuk menyimpan barang-barang logistik.',
            ],
            [
                'nama' => 'Ruang Rekreasi',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan untuk hiburan dan relaksasi karyawan.',
            ],

        ];

        foreach ($asetRuangan as $ruangan) {
            Aset::create([
                'user_id' => User::inRandomOrder()->first()->id,
                'nama' => $ruangan['nama'],
                'slug' => Str::slug($ruangan['nama']),
                'systemcode' => $faker->unique()->regexify('[A-Z0-9]{8}'),
                'kategori_id' => Kategori::where('nama', $ruangan['kategori'])->first()->id,
                'deskripsi' => $ruangan['deskripsi'],
                'jumlah' => 1, // Jumlah ruangan biasanya satu per entri
                'hargasatuan' => 0, // Ruangan mungkin tidak memerlukan harga
                'hargatotal' => 0,
                'aktif' => 1,
                'status' => 1,
            ]);
        }

        $pelkantorData = [
            [
                'nama' => 'Kursi Hitam Plastik',
                'tipe' => 'Kursi',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'Kursi Hitam Lipat',
                'tipe' => 'Kursi',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'Meja Lipat',
                'tipe' => 'Meja',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'Kabel Roll Besar',
                'tipe' => 'Kabel',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'Kabel Roll Kecil',
                'tipe' => 'Kabel',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'Speaker/Sound System',
                'tipe' => 'Sound',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'Printer',
                'tipe' => 'Printer',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'Laptop',
                'tipe' => 'Laptop',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'Tenda Portable',
                'tipe' => 'Tenda',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'Terpal',
                'tipe' => 'Tenda',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'Tangga',
                'tipe' => 'Alat Bantu',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'Trolly Besar',
                'tipe' => 'Trolly',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'Karpet Hitam',
                'tipe' => 'Karpet',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'Karpet Mushola',
                'tipe' => 'Karpet',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'Vacum Cleaner',
                'tipe' => 'Vacum',
                'peminjaman' => 1,
            ],
            [
                'nama' => 'Tool Kit',
                'tipe' => 'Alat Bantu',
                'peminjaman' => 1,
            ],


        ];

        foreach ($pelkantorData as $data) {
            // Tentukan kategori khusus KDO
            $kategori = Kategori::find(8);

            Aset::create([
                'user_id' => User::whereBetween('unit_id', [1, 7])->inRandomOrder()->first()->id,
                'nama' => $data['nama'],
                'slug' => Str::slug($data['nama']),
                'tipe' => $data['tipe'],
                'systemcode' => $faker->unique()->regexify('[A-Z0-9]{8}'),
                'kode' => $faker->unique()->regexify('[A-Z0-9]{5}'),
                'kategori_id' => $kategori->id,
                'merk_id' => Merk::inRandomOrder()->first()->id,
                'person_id' => Person::inRandomOrder()->first()->id ?? null,
                'toko_id' => Toko::inRandomOrder()->first()->id ?? null,
                'lokasi_id' => Lokasi::inRandomOrder()->first()->id ?? null,
                'tanggalbeli' => $faker->dateTimeBetween('-2 years', 'now')->getTimestamp(),
                'jumlah' => rand(1, 15),
                'hargasatuan' => rand(500000, 500000000),
                'hargatotal' => rand(5000000, 500000000),
                'aktif' => 1,
                'status' => 1,
                'peminjaman' => $data['peminjaman'],
            ]);
        }
    }
}
