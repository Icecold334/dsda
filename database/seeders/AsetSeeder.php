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
                ],
                'Sony' => [
                    'Home Theater',
                    'Speaker Bluetooth',
                    'PlayStation 5',
                    'Kamera Digital',
                ],
                'LG' => [
                    'Mesin Cuci Front Loading',
                    'Proyektor Portabel',
                    'Microwave Oven',
                    'Monitor 27 Inch',
                ],
            ],
            'Otomotif' => [
                'Toyota' => [
                    'Mobil Avanza',
                    'Mobil Rush',
                    'Mobil Innova',
                    'Forklift 3 Ton',
                ],
                'Honda' => [
                    'Motor Beat',
                    'Motor Vario',
                    'Motor PCX',
                    'Mobil HRV',
                ],
                'Suzuki' => [
                    'Motor Satria FU',
                    'Motor Nex II',
                    'Mobil Ertiga',
                    'Motor GSX-R150',
                ],
            ],
            'Furniture' => [
                'IKEA' => [
                    'Meja Kerja Minimalis',
                    'Kursi Ergonomis',
                    'Lemari Pakaian 3 Pintu',
                    'Rak Buku Kayu',
                ],
                'Ace Hardware' => [
                    'Sofa Tamu',
                    'Lemari Besi Arsip',
                    'Meja Makan 6 Kursi',
                    'Kursi Lipat Aluminium',
                ],
                'VIVERE' => [
                    'Tempat Tidur Queen Size',
                    'Meja Konsol Kayu',
                    'Buffet TV Modern',
                    'Rak Sepatu',
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
                $merk = Merk::where('nama', $merkName)->first();

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
