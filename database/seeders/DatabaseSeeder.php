<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Aset;
use App\Models\Merk;
use App\Models\Stok;
use App\Models\Toko;
use App\Models\User;
use App\Models\Lokasi;
use App\Models\Person;
use App\Models\Kategori;
use App\Models\MerkStok;
use App\Models\JenisStok;
use App\Models\MerekStok;
use App\Models\UnitKerja;
use App\Models\BagianStok;
use App\Models\BarangStok;
use App\Models\LokasiStok;
use App\Models\PosisiStok;
use App\Models\VendorStok;
use App\Models\SatuanBesar;
use App\Models\SatuanKecil;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use App\Models\KategoriStok;
use App\Models\KontrakVendor;
use App\Models\TransaksiStok;
use App\Models\PengirimanStok;
use App\Models\PermintaanStok;
// use App\Models\DetailPengirimanStok;
// use App\Models\TransaksiDaruratStok;
use App\Models\MetodePengadaan;
use Illuminate\Database\Seeder;
use App\Models\KontrakVendorStok;
use Illuminate\Support\Facades\DB;
use App\Models\DetailPengirimanStok;
use App\Models\DetailPermintaanStok;
use Illuminate\Support\Facades\Hash;
// use App\Models\KontrakRetrospektifStok;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $faker = Faker::create('id_ID');

        $this->call([
            UnitSeeder::class,
        ]);


        for ($i = 1; $i <= 4; $i++) {
            $namaWilayah = '';
            switch ($i) {
                case 1:
                    $namaWilayah = 'Jakarta Utara';
                    break;
                case 2:
                    $namaWilayah = 'Jakarta Selatan';
                    break;
                case 3:
                    $namaWilayah = 'Jakarta Timur';
                    break;
                case 4:
                    $namaWilayah = 'Jakarta Barat';
                    break;
            }

            LokasiStok::create([
                'unit_id' => UnitKerja::inRandomOrder()->first()->id,
                'nama' => $namaWilayah,
                'slug' => Str::slug($namaWilayah),
                'alamat' => $faker->address,
            ]);
        }



        // Permission list (assuming permissions from the previous example)
        $permissionsGuest = [
            'nama',
            'kategori',
            'kode',
            'systemcode',
            'aset_keterangan',
            'status',
            'foto',
            'lampiran',
            'nonaktif_tanggal',
            'nonaktif_alasan',
            'nonaktif_keterangan',
            'detil_merk',
            'detil_tipe',
            'detil_produsen',
            'detil_noseri',
            'detil_thnproduksi',
            'detil_deskripsi',
            'tanggalbeli',
            'toko',
            'invoice',
            'jumlah',
            'hargasatuan',
            'hargatotal',
            'umur',
            'penyusutan',
            'usia',
            'nilaisekarang',
            'keuangan',
            'agenda',
            'jurnal',
            'riwayat_terakhir',
            'riwayat_semua',
            'riwayat_tidak',
            'riwayat_tanggal',
            'riwayat_person',
            'riwayat_lokasi',
            'riwayat_jumlah',
            'riwayat_kondisi',
            'riwayat_kelengkapan',
            'riwayat_keterangan',
        ];
        $permissionsSystem = [
            'aset_price',
            'aset_new',
            'aset_edit',
            'aset_del',
            'aset_pdf',
            'aset_xls',
            'aset_noaktif',
            'aset_reaktif',
            'history_view',
            'history_newedit',
            'history_del',
            'trans_view',
            'trans_newedit',
            'trans_del',
            'data_kategori',
            'data_merk',
            'data_toko',
            'data_person',
            'data_lokasi',
            'qr_print',
            'inventaris_edit_lokasi_penerimaan',
            'inventaris_tambah_barang_datang',
            'inventaris_unggah_foto_barang_datang',
            'permintaan_persetujuan_jumlah_barang',
            'permintaan_penyelesaian_permintaan',
            'persetujuan',
        ];

        // Insert permissions and get their IDs
        $permissionIds = [];
        foreach ($permissionsGuest as $permission) {
            $permissionIds[] = DB::table('permissions')->insertGetId([
                'name' => $permission,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        foreach ($permissionsSystem as $permission) {
            $permissionIds[] = DB::table('permissions')->insertGetId([
                'name' => $permission,
                'type' => true,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        foreach ($permissionIds as $permissionId) {
            DB::table('role_has_permissions')->insert([
                'role_id' => 1,
                'permission_id' => $permissionId,
            ]);
        }



        // Seeder for Kategori with 5 main categories and each having 5 children
        $kategoriUtama = [];
        for ($i = 1; $i <= 5; $i++) {
            $kategoriUtama[$i] = Kategori::create([
                'user_id' => User::inRandomOrder()->first()->id,
                'nama' => 'Kategori Utama ' . $i,
                'keterangan' => 'Keterangan untuk Kategori Utama ' . $i,
                'status' => 1
            ]);

            for ($j = 1; $j <= 5; $j++) {
                Kategori::create([
                    'user_id' => User::inRandomOrder()->first()->id,
                    'nama' => 'Kategori Anak ' . $i . '-' . $j,
                    'keterangan' => 'Keterangan untuk Kategori Anak ' . $i . '-' . $j,
                    'parent_id' => $kategoriUtama[$i]->id,
                    'status' => 1
                ]);
            }
        }
        // Seeder for 5 locations
        for ($i = 1; $i <= 5; $i++) {
            Lokasi::create([
                'user_id' => User::inRandomOrder()->first()->id,
                'nama' => $faker->city,
                'nama_nospace' => Str::slug('Lokasi ' . $i),
                'keterangan' => 'Deskripsi untuk Lokasi ' . $i,
                'status' => 1
            ]);
        }


        // Seeder for 5 brands
        for ($i = 1; $i <= 5; $i++) {
            Merk::create([
                'user_id' => User::inRandomOrder()->first()->id,
                'nama' => 'Merek ' . $i,
                'nama_nospace' => Str::slug('Merek ' . $i),
                'keterangan' => 'Deskripsi untuk Merek ' . $i,
                'status' => 1
            ]);
        }


        $tokos = [
            [
                'nama' => 'Toko Elektronik Canggih',
                'alamat' => 'Jl. Teknologi No. 20, Bandung',
                'telepon' => $faker->phoneNumber,
                'email' => $faker->email,
                'petugas' => $faker->firstName,
                'keterangan' => 'Pusat teknologi dan gadget terbaru',
            ],
            [
                'nama' => 'Bengkel Otomotif Modern',
                'alamat' => 'Jl. Mekanik No. 15, Semarang',
                'telepon' => $faker->phoneNumber,
                'email' => $faker->email,
                'petugas' => $faker->firstName,
                'keterangan' => 'Spesialis kendaraan roda empat dan dua',
            ],
            [
                'nama' => 'Toko Fashion Hits',
                'alamat' => 'Jl. Mode No. 7, Yogyakarta',
                'telepon' => $faker->phoneNumber,
                'email' => $faker->email,
                'petugas' => $faker->firstName,
                'keterangan' => 'Supplier pakaian trendy dan kekinian',
            ],
            [
                'nama' => 'Sentra Kuliner Nusantara',
                'alamat' => 'Jl. Rasa No. 3, Makassar',
                'telepon' => $faker->phoneNumber,
                'email' => $faker->email,
                'petugas' => $faker->firstName,
                'keterangan' => 'Distributor makanan khas nusantara',
            ],
            [
                'nama' => 'Toko Buku Pintar',
                'alamat' => 'Jl. Literasi No. 1, Malang',
                'telepon' => $faker->phoneNumber,
                'email' => $faker->email,
                'petugas' => $faker->firstName,
                'keterangan' => 'Pusat buku dan alat tulis sekolah',
            ],
        ];

        foreach ($tokos as $toko) {
            Toko::create([
                'user_id' => 1,
                'nama' => $toko['nama'],
                'nama_nospace' => Str::slug($toko['nama']),
                'alamat' => $toko['alamat'],
                'telepon' => $toko['telepon'],
                'email' => $toko['email'],
                'petugas' => $toko['petugas'],
                'keterangan' => $toko['keterangan'],
                'status' => 1,
            ]);
        }

        // Seed example Persons
        Person::create([
            'user_id' => 1,
            'nama' => 'Andi Setiawan',
            'nama_nospace' => Str::slug('Andi Setiawan'),
            'jabatan' => 'Manager Operasional',
            'alamat' => 'Jl. Merdeka No. 45, Jakarta',
            'telepon' => '081234567890',
            'email' => 'andi.setiawan@example.com',
            'keterangan' => 'Bertanggung jawab atas operasional harian',
            'status' => 1
        ]);

        Person::create([
            'user_id' => 1,
            'nama' => 'Budi Prasetyo',
            'nama_nospace' => Str::slug('Budi Prasetyo'),
            'jabatan' => 'Kepala Divisi IT',
            'alamat' => 'Jl. Informatika No. 12, Surabaya',
            'telepon' => '081987654321',
            'email' => 'budi.prasetyo@example.com',
            'keterangan' => 'Bertanggung jawab atas sistem informasi perusahaan',
            'status' => 1
        ]);

        // Seeder for assets
        $faker = Faker::create();
        $asetJenis = [
            'Mobil',
            'Laptop',
            'Kursi',
            'Meja',
            'Lemari',
            'Printer',
            'Scanner',
            'Komputer',
            'Televisi',
            'Motor',
            'Proyektor',
            'Kamera',
            'Rak',
            'Sofa',
            'AC',
            'Speaker',
            'Buku',
            'Jam Dinding',
            'Lampu',
            'Tangga'
        ];

        // Nama bagian kedua
        $asetDeskripsi = [
            'Kantor',
            'Rumah',
            'Kayu',
            'Besi',
            'Aluminium',
            'Plastik',
            'Kecil',
            'Besar',
            'Portabel',
            'Sedan',
            'Klasik',
            'Modern',
            'Digital',
            'Listrik',
            'Manual',
            'Pro',
            'Premium',
            'Ekonomis',
            'Gaming',
            'Lipat'
        ];
        for ($i = 1; $i <= 50; $i++) {
            $nama = $faker->randomElement($asetJenis) . ' ' . $faker->randomElement($asetDeskripsi);
            Aset::create([
                'user_id' => User::inRandomOrder()->first()->id,
                'nama' => $nama,
                'slug' => Str::slug($nama),
                'systemcode' => $faker->unique()->regexify('[A-Z0-9]{8}'),
                'kategori_id' => $kategoriUtama[array_rand($kategoriUtama)]->id,
                'merk_id' => Merk::inRandomOrder()->first()->id ?? null,
                'person_id' => Person::inRandomOrder()->first()->id ?? null,
                'toko_id' => Toko::inRandomOrder()->first()->id ?? null,
                'lokasi_id' => Lokasi::inRandomOrder()->first()->id ?? null,
                'tanggalbeli' => strtotime('now'),
                'jumlah' => rand(1, 10),
                'hargasatuan' => rand(1000000, 5000000),
                'hargatotal' => rand(5000000, 25000000),
                'aktif' => 1,
                'status' => 1
            ]);
        }

        // Seeder for History (Riwayat)
        for ($i = 1; $i <= 10; $i++) {
            DB::table('history')->insert([
                'user_id' => User::inRandomOrder()->first()->id,
                'aset_id' => Aset::inRandomOrder()->first()->id,
                'tanggal' => strtotime('now'),
                'person_id' => Person::inRandomOrder()->first()->id ?? null,
                'lokasi_id' => Lokasi::inRandomOrder()->first()->id ?? null,
                'jumlah' => rand(1, 10),
                'kondisi' => rand(80, 100),
                'kelengkapan' => rand(80, 100),
                'keterangan' => $faker->sentence,
                'status' => 1,
            ]);
        }

        // Seeder for Financial Records (Keuangan)
        for ($i = 1; $i <= 10; $i++) {
            DB::table('keuangan')->insert([
                'user_id' => User::inRandomOrder()->first()->id,
                'aset_id' => Aset::inRandomOrder()->first()->id,
                'tanggal' => strtotime('now'),
                'tipe' => $faker->randomElement(['in', 'out']),
                'keterangan' => $faker->sentence,
                'nominal' => $faker->numberBetween(1000, 10000),
                'status' => 1,
            ]);
        }

        // Seeder for Agendas
        for ($i = 1; $i <= 10; $i++) {
            DB::table('agenda')->insert([
                'user_id' => User::inRandomOrder()->first()->id,
                'aset_id' => Aset::inRandomOrder()->first()->id,
                'tipe' => $faker->randomElement(['mingguan', 'bulanan']),
                'hari' => $faker->numberBetween(1, 7),
                'tanggal' => strtotime('now'),
                'bulan' => $faker->numberBetween(1, 12),
                'tahun' => $faker->numberBetween(2020, 2023),
                'keterangan' => $faker->sentence,
                'status' => 1,
            ]);
        }

        // Seeder for Journals (Jurnal)
        for ($i = 1; $i <= 10; $i++) {
            DB::table('jurnal')->insert([
                'user_id' => User::inRandomOrder()->first()->id,
                'aset_id' => Aset::inRandomOrder()->first()->id,
                'tanggal' => strtotime('now'),
                'keterangan' => $faker->sentence,
                'status' => 1,
            ]);
        }
        DB::table('option')->insert([
            'user_id' => 1,
            'kodeaset' => '[nomor]/INV/[tahun]',
            'qr_judul' => 'perusahaan',
            'qr_judul_other' => 'PT SAD',
            'qr_baris1' => 'nama',
            'qr_baris1_other' => 'Joko Wiyono',
            'qr_baris2' => 'kode',
            'qr_baris2_other' => null,
            'scan_qr_history' => 'enabled',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // arc stok


        $faker = Faker::create();

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


        // Seed for BarangStok
        for ($i = 1; $i <= 20; $i++) {
            $kategori = KategoriStok::inRandomOrder()->first();
            $jenisBarang = JenisStok::where('nama', 'Umum')->first();
            $nama = $faker->randomElement([
                'Pensil',
                'Kertas',
                'Buku',
                'Pulpen',
                'Spidol',
                'Penggaris',
                'Kalkulator',
                'Klip Kertas',
                'Sticky Note',
                'Lem',
                'Amplop',
                'Binder',
                'Map',
                'Stapler',
            ]) . ' ' . $faker->randomElement([
                'Cetak',          // Kertas Cetak, Tinta Cetak
                'Tulisan',        // Buku Tulisan, Pulpen Tulisan
                'Warna',          // Spidol Warna
                'Tulis',          // Buku Tulis, Pulpen Tulis
                'Marker',         // Spidol Marker
                'A4',             // Kertas A4
                'Isi Ulang',      // Pulpen Isi Ulang, Tinta Isi Ulang
                'Planner',        // Buku Planner
                'Kantor',         // Alat-alat Kantor
                'Sekolah',        // Alat-alat Sekolah
                'Premium',        // Produk Premium
                'Standar',        // Produk Standar
                'Portabel',       // Kalkulator Portabel
                'Ekstra',         // Kertas Ekstra
                'Tahan Air',      // Amplop Tahan Air
                'Transparan',     // Penggaris Transparan
                'Refill',         // Isi Ulang
                'Dekoratif',      // Sticky Note Dekoratif
                'Kuat',           // Lem Kuat
                'Minimalis',      // Map Minimalis
                'Klasik',         // Binder Klasik
            ]);
            $barang = BarangStok::create([
                'kode_barang' => $faker->unique()->numerify('BRG-#####-#####'),
                'jenis_id' => $jenisBarang->id,
                'nama' => $nama,
                'slug' => Str::slug($nama),
                'kategori_id' => $kategori->id,  // Assign kategori dari kategori yang acak
                'satuan_besar_id' => SatuanBesar::inRandomOrder()->first()->id,
                'konversi' => $faker->randomElement([5, 10, 15, 20, 25, 30, 35, 40, 45, 50]),
                'satuan_kecil_id' => SatuanBesar::inRandomOrder()->first()->id,
                'deskripsi' => 'Deskripsi untuk Barang ' . $i,
            ]);
        }

        for ($i = 1; $i <= 50; $i++) {
            $tipe = $faker->boolean ? $faker->randomElement([
                'Standar',
                'Premium',
                'Ekonomis',
                'Heavy Duty',
                'Ringan',
                'Super',
                'Profesional',
                'Khusus',
                'Multifungsi',
                'Universal'
            ]) : null;

            $ukuran = $faker->boolean ? $faker->randomElement([
                $faker->numberBetween(5, 50) . ' cm',         // Ukuran dalam cm
                $faker->numberBetween(1, 10) . ' m',          // Ukuran dalam meter
                $faker->numberBetween(10, 500) . ' mm',       // Ukuran dalam milimeter
                $faker->numberBetween(50, 500) . ' ml',       // Ukuran volume cair
                $faker->numberBetween(1, 20) . ' L',          // Ukuran volume besar
                $faker->numberBetween(1, 1000) . ' gr',       // Ukuran berat kecil
                $faker->numberBetween(1, 50) . ' kg',         // Ukuran berat besar
                $faker->numberBetween(10, 200) . ' sheets',   // Jumlah lembar
                $faker->numberBetween(1, 5) . ' packs',       // Jumlah kemasan
                $faker->numberBetween(1, 20) . ' pcs'         // Jumlah satuan
            ]) : null;

            // Pastikan salah satu tidak null
            if (is_null($tipe) && is_null($ukuran)) {
                if ($faker->boolean) {
                    $tipe = $faker->randomElement([
                        'Standar',
                        'Premium',
                        'Ekonomis',
                        'Heavy Duty',
                        'Ringan',
                        'Super',
                        'Profesional',
                        'Khusus',
                        'Multifungsi',
                        'Universal'
                    ]);
                } else {
                    $ukuran = $faker->randomElement([
                        $faker->numberBetween(5, 50) . ' cm',
                        $faker->numberBetween(1, 10) . ' m',
                        $faker->numberBetween(10, 500) . ' mm',
                        $faker->numberBetween(50, 500) . ' ml',
                        $faker->numberBetween(1, 20) . ' L',
                        $faker->numberBetween(1, 1000) . ' gr',
                        $faker->numberBetween(1, 50) . ' kg',
                        $faker->numberBetween(10, 200) . ' sheets',
                        $faker->numberBetween(1, 5) . ' packs',
                        $faker->numberBetween(1, 20) . ' pcs'
                    ]);
                }
            }
            $nama = $faker->boolean ? $faker->randomElement([
                'Sinar Dunia',
                'Tiga Roda',
                'IndoPrima',
                'SariKarya',
                'MegaJaya',
                'BerkahMakmur',
                'CiptaSentosa',
                'MandiriUtama',
                'TunasHarapan',
                'SuryaNusantara',
                'BintangTerang',
                'MitraAbadi',
                'SejahteraJaya',
                'RajawaliKencana',
                'GemilangIndah',
                'PusakaRaya',
                'GarudaPerkasa',
                // Merek luar
                'Super Glue',
                'Sharp Note',
                'Quick Fix',
                'Rapid Print',
                'Bright Vision',
                'Next Level',
                'Prime Star',
                'Eagle Pro',
                'Global Edge',
                'True Mark',
                'Apex Tech',
                'Zenith Gear',
                'Eco Green',
                'Ultra Bond',
                'Future Craft',
                'Vista Clear',
                'Master Seal',
                'Top Choice',
                'Champion Paper',
                'King Grip',
                'Orbit Line'
            ]) : null;
            MerkStok::create([
                'barang_id' => BarangStok::inRandomOrder()->first()->id,
                'nama' => $nama,
                // 'slug' => Str::slug($nama),
                'tipe' => $tipe,
                'ukuran' => $ukuran,
            ]);
        }



        $jenis_material = ['Material'];
        $barang_material = [
            'Pipa PVC',
            'Kabel Listrik',
            'Semen',
            'Pasir',
            'Batu Bata',
            'Besi Beton',
            'Cat Tembok',
            'Kunci',
            'Gasket',
            'Klem'
        ];
        foreach ($jenis_material as $index => $jenis) {
            // Ambil jenis stok berdasarkan nama
            $jenisBarang = JenisStok::where('nama', $jenis)->first();

            for ($i = 1; $i <= 10; $i++) {
                // Seed untuk BarangStok
                $barang = BarangStok::create([
                    'kode_barang' => $faker->unique()->numerify('BRG-#####-#####'),
                    'jenis_id' => $jenisBarang->id,
                    'nama' => $barang_material[$i - 1],
                    'slug' => Str::slug($barang_material[$i - 1]),
                    'satuan_besar_id' => SatuanBesar::inRandomOrder()->first()->id,
                    'konversi' => $faker->randomElement([5, 10, 15, 20, 25, 30, 35, 40, 45, 50]),
                    'satuan_kecil_id' => SatuanBesar::inRandomOrder()->first()->id,
                    'deskripsi' => $faker->sentence(),
                ]);
                $nama = $faker->randomElement([
                    'Semen Gresik',
                    'Swarovski',
                    'Holcim',
                    'Bima',
                    'Onda',
                    'BP',
                    'Denso'
                ]);
                MerkStok::create([
                    'barang_id' => $barang->id,
                    'nama' => $nama,
                    // 'slug' => Str::slug($nama),
                    'tipe' => $faker->boolean ? $faker->randomElement([
                        'Standard',
                        'Premium',
                        'Heavy Duty',
                        'Ringan',
                        'Super',
                        'Profesional',
                        'Khusus',
                        'Multifungsi',
                        'Universal'
                    ]) : null,
                    'ukuran' => $faker->boolean ? $faker->randomElement([
                        $faker->numberBetween(5, 50) . ' cm',
                        $faker->numberBetween(1, 10) . ' m',
                        $faker->numberBetween(10, 500) . ' mm',
                        $faker->numberBetween(50, 500) . ' ml',
                        $faker->numberBetween(1, 20) . ' L',
                        $faker->numberBetween(1, 1000) . ' gr',
                        $faker->numberBetween(1, 50) . ' kg'
                    ]) : null,
                ]);
            }
        }


        // Seed for VendorStok
        // for ($i = 1; $i <= 5; $i++) {
        //     VendorStok::create([
        //         'nama' => 'Vendor ' . $i,
        //         'alamat' => $faker->address,
        //         'kontak' => $faker->phoneNumber,
        //     ]);
        // }

        // Seed for LokasiStok


        // Seed for BagianStok
        for ($i = 1; $i <= 5; $i++) {
            BagianStok::create([
                'lokasi_id' => LokasiStok::inRandomOrder()->first()->id,
                'nama' => 'Bagian ' . $i,
            ]);
        }

        // Seed for PosisiStok
        for ($i = 1; $i <= 5; $i++) {
            PosisiStok::create([
                'bagian_id' => BagianStok::inRandomOrder()->first()->id,
                'nama' => 'Posisi ' . $i,
            ]);
        }

        $methods = [
            'Pengadaan Langsung',
            'Penunjukan Langsung',
            'Tender',
            'E-Purchasing / E-Katalog',
            'Tender Cepat',
            'Swakelola',
        ];
        foreach ($methods as $method) {
            MetodePengadaan::create(['nama' => $method]);
        }

        // Seed for KontrakVendorStok
        for ($i = 1; $i <= 5; $i++) {
            KontrakVendorStok::create([
                'nomor_kontrak' => $faker->unique()->bothify('KV#####'),
                'metode_id' => MetodePengadaan::inRandomOrder()->first()->id,
                // 'vendor_id' => Toko::inRandomOrder()->first()->id,
                'vendor_id' => $i,
                'tanggal_kontrak' => strtotime($faker->date()),
                // 'merk_id' => MerkStok::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                'type' => 1,
                'status' => 1,
            ]);
        }


        for ($i = 0; $i < 10; $i++) {
            $vendorid = Toko::inRandomOrder()->first()->id;
            TransaksiStok::create([
                'kode_transaksi_stok' => $faker->unique()->numerify('TRX#####'),
                // 'tipe' => $faker->randomElement(['Pengeluaran', 'Pemasukan', 'Penggunaan Langsung']),
                'tipe' => $i < 6 ? 'Pemasukan' : 'Penggunaan Langsung',
                'merk_id' => MerkStok::inRandomOrder()->first()->id,
                'vendor_id' => $vendorid,
                'user_id' => User::inRandomOrder()->first()->id,
                'lokasi_id' => LokasiStok::inRandomOrder()->first()->id,
                'kontrak_id' => $i < 6 ? $vendorid : null,
                'tanggal' => strtotime(date('Y-m-d H:i:s')),
                'jumlah' => $faker->numberBetween(1, 100),
                'deskripsi' => $faker->sentence(),
                'lokasi_penerimaan' => $faker->address(),
            ]);
        }


        // Seed for Stok
        for ($i = 1; $i <= 100; $i++) {
            // Pilih lokasi secara acak
            $lokasi = LokasiStok::inRandomOrder()->first();

            // Tentukan apakah lokasi memiliki bagian
            $bagian = null;
            if ($faker->boolean) { // Random pilihan untuk bagian
                $bagian = BagianStok::where('lokasi_id', $lokasi->id)->inRandomOrder()->first();
            }

            // Tentukan apakah bagian memiliki posisi
            $posisi = null;
            if ($bagian && $faker->boolean) { // Random pilihan untuk posisi
                $posisi = PosisiStok::where('bagian_id', $bagian->id)->inRandomOrder()->first();
            }

            // Buat entri stok baru
            Stok::create([
                'merk_id' => MerkStok::inRandomOrder()->first()->id, // Pilih merk secara acak
                'jumlah' => rand(10, 100), // Tentukan jumlah stok secara acak
                'lokasi_id' => $lokasi->id, // Lokasi stok
                'bagian_id' => $bagian->id ?? null, // Bagian stok (opsional)
                'posisi_id' => $posisi->id ?? null, // Posisi stok (opsional)
            ]);
        }





        $requests = [];
        for ($i = 0; $i < 6; $i++) {
            $parentUnit = UnitKerja::whereNull('parent_id')->inRandomOrder()->first();

            // Ambil unit sub yang merupakan anak dari unit induk yang dipilih
            $subUnit = null;
            if ($faker->boolean) { // Misal 50% kemungkinan sub_unit_id ada
                $subUnit = UnitKerja::where('parent_id', $parentUnit->id)->inRandomOrder()->first();
            }
            $requests[] = [
                'kode_permintaan' => 'REQ-' . strtoupper(Str::random(6)),
                'tanggal_permintaan' => strtotime(Carbon::now()),
                'user_id' => User::where('unit_id', $parentUnit->id)->inRandomOrder()->first()->id,
                'kategori_id' => KategoriStok::inRandomOrder()->first()->id,
                'jenis_id' => 3, // unit_id diambil dari unit induk
                'unit_id' => $parentUnit->id, // unit_id diambil dari unit induk
                'keterangan' => $faker->paragraph(),
                'sub_unit_id' => $subUnit ? $subUnit->id : null, // jika ada sub-unit, pakai id-nya, jika tidak null
                'jumlah' => rand(1, 30), // Jumlah acak antara 1 dan 30
            ];
        }

        foreach ($requests as $request) {
            DetailPermintaanStok::create($request);
        }


        $users = User::all();
        $barang = BarangStok::all();
        $details = DetailPermintaanStok::all();
        $lokasis = LokasiStok::all();

        for ($i = 0; $i < 20; $i++) {
            $detail = $details->random();
            PermintaanStok::create([
                'detail_permintaan_id' => $detail->id,
                'user_id' => $users->random()->id,
                'barang_id' => $barang->where('kategori_id', $detail->kategori_id)->random()->id,
                'jumlah' => rand(10, 100),
                'lokasi_id' => $lokasis->random()->id,
            ]);
        }

        // foreach (range(1, 5) as $index) {
        DetailPengirimanStok::create([
            'kode_pengiriman_stok' => Str::upper($faker->word),
            'tanggal' => strtotime(now()),
            'penerima' => $faker->name,
            'user_id' => User::inRandomOrder()->first()->id,
            'pj1' => $faker->name,
            'pj2' => $faker->name,
            'kontrak_id' => KontrakVendorStok::where('type', true)->inRandomOrder()->first()->id
        ]);
        // }

        foreach (range(1, 3) as $index) {

            // Pilih kontrak yang memiliki transaksi
            $kontrak = KontrakVendorStok::has('transaksiStok')->where('type', true)->inRandomOrder()->first();

            // Pilih transaksi yang terkait dengan kontrak yang dipilih
            $transaksi = TransaksiStok::where('kontrak_id', $kontrak->id)->inRandomOrder()->first();

            $lokasi = LokasiStok::inRandomOrder()->first();

            // Tentukan apakah lokasi memiliki bagian
            $bagian = BagianStok::where('lokasi_id', $lokasi->id)->inRandomOrder()->first();

            // Tentukan apakah bagian memiliki posisi
            $posisi = null;
            if ($bagian) {
                $posisi = PosisiStok::where('bagian_id', $bagian->id)->inRandomOrder()->first();
            }
            PengirimanStok::create([
                'detail_pengiriman_id' => DetailPengirimanStok::find(1)->id,
                'kontrak_id' => $kontrak->id,
                'merk_id' => $transaksi->merk_id,
                'tanggal_pengiriman' => strtotime(now()), // strtotime untuk konversi string ke timestamp
                'jumlah' => $faker->numberBetween(1, 5),
                'lokasi_id' => $lokasi->id,
                'bagian_id' => $bagian ? $bagian->id : null,  // Bagian bisa null jika tidak ada
                'posisi_id' => $posisi ? $posisi->id : null,
            ]);
        }
    }
}
