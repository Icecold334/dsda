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
            'Otomotif' => [
                'Toyota' => [
                    'Mobil Avanza',
                    'Mobil Rush',
                    'Mobil Innova',
                    'Forklift 3 Ton',
                    'Mobil Corolla Cross',
                    'SUV Fortuner',
                    'Truk Dyna',
                ],
                'Honda' => [
                    'Motor Beat',
                    'Motor Vario',
                    'Motor PCX',
                    'Mobil HRV',
                    'Mobil BRV',
                    'Motor CBR 150R',
                    'Scoopy Stylish',
                ],
                'Suzuki' => [
                    'Motor Satria FU',
                    'Motor Nex II',
                    'Mobil Ertiga',
                    'Motor GSX-R150',
                    'APV Arena',
                    'Mobil Carry Pick-up',
                    'Motor Burgman Street',
                ],
                'Yamaha' => [
                    'Motor NMAX',
                    'Motor Aerox',
                    'Motor MT-15',
                    'Motor R15 V4',
                    'Motor XSR 155',
                    'Scooter Mio M3',
                    'Motor Tracer 900',
                ],
                'Daihatsu' => [
                    'Mobil Xenia',
                    'Mobil Terios',
                    'Mobil Gran Max',
                    'Mobil Sigra',
                    'Mobil Ayla',
                    'Mobil Rocky',
                    'Pick-up Hi-Max',
                ],
            ],
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
            if ($kategoriName == 'Otomotif') {
                $kategori = Kategori::find(1);
            } else {
                $kategori = Kategori::whereNotIn('id', [1, 2, 3, 4, 5, 6, 7])->inRandomOrder()->first();
            }
            foreach ($merkData as $merkName => $asetList) {
                // $merk = Merk::where('nama', $merkName)->first();
                $merk = Merk::firstOrCreate(['nama' => $merkName, 'nama_nospace' => Str::slug($merkName)], ['user_id' => User::inRandomOrder()->first()->id]);


                foreach ($asetList as $asetName) {
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

        $asetRuangan = [
            [
                'nama' => 'Ruangan Lantai 1',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan utama di lantai 1 untuk keperluan umum.',
            ],
            [
                'nama' => 'Ruangan Lantai 2',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan utama di lantai 2 untuk keperluan staf.',
            ],
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
            [
                'nama' => 'Ruang Rapat 1 (Lt. 7)',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan rapat untuk keperluan diskusi dan presentasi di lantai 7.',
            ],
            [
                'nama' => 'Ruang Rapat 2 (Lt. 7)',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan rapat kedua di lantai 7 untuk meeting tambahan.',
            ],
            [
                'nama' => 'Ruang Rapat ex BPK (Lt. 8)',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan rapat bekas BPK di lantai 8.',
            ],
            [
                'nama' => 'Ruang Rapat Bidang ROB (Lt. 8)',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan rapat khusus bidang ROB di lantai 8.',
            ],
            [
                'nama' => 'Ruang Rapat Unit Pengadaan Tanah (Lt. 8)',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan rapat untuk unit pengadaan tanah di lantai 8.',
            ],
            [
                'nama' => 'Ruang Rapat Bidang Geologi (Lt. 9)',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan rapat bidang geologi di lantai 9.',
            ],
            [
                'nama' => 'Ruang Rapat Bidang Banjir (Lt. 9)',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan rapat untuk bidang pengendalian banjir di lantai 9.',
            ],
            [
                'nama' => 'Ruang Rapat Bidang Limbah (Lt. 10)',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan rapat untuk bidang pengelolaan limbah di lantai 10.',
            ],
            [
                'nama' => 'Ruang Rapat Keuangan (Lt. 10)',
                'kategori' => 'Ruangan',
                'deskripsi' => 'Ruangan rapat untuk tim keuangan di lantai 10.',
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
    }
}
