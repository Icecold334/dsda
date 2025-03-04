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
use App\Models\OpsiPersetujuan;
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
            PermissionSeeder::class,
            KategoriAsetSeeder::class,
            LokasiAsetSeeder::class,
            MerkAsetSeeder::class,
            TokoSeeder::class,
            AsetSeeder::class,
            BaseInventaSeeder::class,
            BarangStokSeeder::class,
            MerkStokSeeder::class,
            WaktuPeminjamanSeeder::class,
            RuangSeeder::class,
        ]);
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
        for ($i = 1; $i <= 354; $i++) {
            KontrakVendorStok::create([
                'nomor_kontrak' => $faker->unique()->bothify('KV#####'),
                'metode_id' => MetodePengadaan::inRandomOrder()->first()->id,
                'jenis_id' => $faker->numberBetween(1, 3),
                'vendor_id' => Toko::inRandomOrder()->first()->id,
                'tanggal_kontrak' => strtotime($faker->date()),
                // 'merk_id' => MerkStok::inRandomOrder()->first()->id,
                'user_id' => User::inRandomOrder()->first()->id,
                'type' => 1,
                'status' => 1,
            ]);
        }


        for ($i = 0; $i < 1598; $i++) {
            $kontrak = KontrakVendorStok::inRandomOrder()->first();
            $f = $faker->boolean;
            $jenis_id = $kontrak->jenis_id;
            TransaksiStok::create([
                'kode_transaksi_stok' => $faker->unique()->numerify('TRX#####'),
                // 'tipe' => $faker->randomElement(['Pengeluaran', 'Pemasukan', 'Penggunaan Langsung']),
                'tipe' => $f ? 'Pemasukan' : 'Penggunaan Langsung',
                'merk_id' => MerkStok::whereHas('barangStok.jenisStok', function ($jenis) use ($jenis_id) {
                    return $jenis->where('id', $jenis_id);
                })->inRandomOrder()->first()->id,
                'vendor_id' => $kontrak->vendor_id,
                'harga' => $faker->numberBetween(200000, 1000000),
                'ppn' => $faker->randomElement([0, 11, 12]),
                'user_id' => User::inRandomOrder()->first()->id,
                'kontrak_id' => $f ? $kontrak->id : null,
                'tanggal' => strtotime(date('Y-m-d H:i:s')),
                'jumlah' => $faker->numberBetween(890000, 98725000),
                'deskripsi' => $f ? null : $faker->sentence(),
                'lokasi_penerimaan' => $f ? null : $faker->address(),
            ]);
        }


        // Seed for Stok
        for ($i = 1; $i <= 200; $i++) {
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
                'jumlah' => rand(10, max: 100), // Tentukan jumlah stok secara acak
                'lokasi_id' => $lokasi->id, // Lokasi stok
                'bagian_id' => $bagian->id ?? null, // Bagian stok (opsional)
                'posisi_id' => $posisi->id ?? null, // Posisi stok (opsional)
            ]);
        }




        $requests = [];
        for ($i = 0; $i < 578; $i++) {
            $parentUnit = UnitKerja::whereNull('parent_id')->inRandomOrder()->first();

            // Ambil unit sub yang merupakan anak dari unit induk yang dipilih
            // $subUnit = null;
            // if ($faker->boolean) { // Misal 50% kemungkinan sub_unit_id ada
            $subUnit = UnitKerja::where('parent_id', $parentUnit->id)->inRandomOrder()->first();
            // }
            $f = $faker->boolean;
            $requests[] = [
                'kode_permintaan' => 'REQ-' . strtoupper(Str::random(6)),
                'tanggal_permintaan' => strtotime(Carbon::now()),
                'user_id' => User::where('unit_id', $parentUnit->id)->inRandomOrder()->first()->id,
                'kategori_id' => $f ? KategoriStok::inRandomOrder()->first()->id : null,
                'approval_configuration_id' => OpsiPersetujuan::where('jenis', 'umum')
                    ->where('unit_id', $parentUnit->id)
                    ->where('created_at', '<=', now()) // Pastikan data sebelum waktu saat ini
                    ->latest()
                    ->first()->id,
                'jenis_id' => $f ? 3 : $faker->randomElement([1, 2]), // unit_id diambil dari unit induk
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
        // $lokasis = LokasiStok::all();

        for ($i = 0; $i < 2975; $i++) {
            $detail = $details->random();
            PermintaanStok::create([
                'detail_permintaan_id' => $detail->id,
                'user_id' => $users->random()->id,
                'barang_id' => $barang->where('kategori_id', $detail->kategori_id)->random()->id,
                'jumlah' => rand(10, 100),
                // 'lokasi_id' => $lokasis->random()->id,
            ]);
        }

        // foreach (range(1, 890) as $index) {
        //     DetailPengirimanStok::create([
        //         'kode_pengiriman_stok' => $faker->unique()->numerify('PB######'),
        //         'tanggal' => strtotime($faker->date),
        //         'penerima' => $faker->name,
        //         'user_id' => User::inRandomOrder()->first()->id,
        //         'pj1' => $faker->name,
        //         'pj2' => $faker->name,
        //         'kontrak_id' => KontrakVendorStok::where('type', true)->inRandomOrder()->first()->id
        //     ]);
        // }

        // foreach (range(1, 9509) as $index) {
        //     // Ambil unit kerja secara random yang memiliki kontrak vendor aktif
        //     $Unit = UnitKerja::whereHas('user.kontrakVendor')->inRandomOrder()->first();
        //     if (!$Unit) continue;  // Jika tidak ditemukan, skip iterasi ini

        //     // Pilih kontrak vendor yang terkait dengan unit kerja ini
        //     $kontrak = KontrakVendorStok::whereHas('user.unitKerja', function ($query) use ($Unit) {
        //         $query->where('parent_id', $Unit->id)->orWhere('id', $Unit->id);
        //     })->inRandomOrder()->first();
        //     if (!$kontrak) continue;  // Jika tidak ditemukan, skip iterasi ini

        //     // Pilih transaksi dan detail pengiriman yang terkait dengan kontrak ini
        //     $transaksi = TransaksiStok::where('kontrak_id', $kontrak->id)->inRandomOrder()->first();
        //     $detail_pengiriman = DetailPengirimanStok::whereHas('user.unitKerja', function ($unit) use ($Unit) {
        //         $unit->where('parent_id', $Unit->id)->orWhere('id', $Unit->id);
        //     })->where('kontrak_id', $kontrak->id)->inRandomOrder()->first();
        //     if (!$transaksi || !$detail_pengiriman) continue;  // Jika tidak ditemukan, skip iterasi ini

        //     // Ambil lokasi stok yang terkait dengan unit kerja
        //     $lokasi = LokasiStok::where('unit_id', $Unit->parent_id ?? $Unit->id)->inRandomOrder()->first();
        //     if (!$lokasi) continue;  // Jika tidak ditemukan, skip iterasi ini

        //     // Tentukan bagian dan posisi dalam lokasi
        //     $bagian = BagianStok::where('lokasi_id', $lokasi->id)->inRandomOrder()->first();
        //     $posisi = $bagian ? PosisiStok::where('bagian_id', $bagian->id)->inRandomOrder()->first() : null;

        //     // Buat pengiriman stok baru
        //     PengirimanStok::create([
        //         'detail_pengiriman_id' => $detail_pengiriman->id,
        //         'kontrak_id' => $kontrak->id,
        //         'merk_id' => $transaksi->merk_id,
        //         'tanggal_pengiriman' => strtotime($faker->date),
        //         'jumlah' => $faker->numberBetween(1, 30),
        //         'lokasi_id' => $lokasi->id,
        //         'bagian_id' => $bagian ? $bagian->id : null,
        //         'posisi_id' => $posisi ? $posisi->id : null,
        //     ]);
        // }
    }
}
