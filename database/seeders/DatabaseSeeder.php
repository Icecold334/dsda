<?php

namespace Database\Seeders;

use App\Models\Aset;
use App\Models\Merk;
use App\Models\Stok;
use App\Models\Toko;
use App\Models\Lokasi;
use App\Models\Person;
use App\Models\Kategori;
use App\Models\MerkStok;
use App\Models\JenisStok;
use App\Models\MerekStok;
use App\Models\BagianStok;
use App\Models\BarangStok;
use App\Models\LokasiStok;
use App\Models\PosisiStok;
use App\Models\VendorStok;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use App\Models\KontrakVendor;
use App\Models\TransaksiStok;
use App\Models\PengirimanStok;
use App\Models\PermintaanStok;
use Illuminate\Database\Seeder;
use App\Models\KontrakVendorStok;
use Illuminate\Support\Facades\DB;
use App\Models\TransaksiDaruratStok;
use Illuminate\Support\Facades\Hash;
use App\Models\KontrakRetrospektifStok;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create or get roles for superadmin and admin
        $superAdminRoleId = DB::table('roles')->insertGetId([
            'name' => 'superadmin',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $adminRoleId = DB::table('roles')->insertGetId([
            'name' => 'admin',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Permission list
        $permissions = [
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
            'riwayat_tanggal',
            'riwayat_person',
            'riwayat_lokasi',
            'riwayat_jumlah',
            'riwayat_kondisi',
            'riwayat_kelengkapan',
            'riwayat_keterangan',
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
        ];

        // Insert permissions if they don't exist and store their IDs
        $permissionIds = [];
        foreach ($permissions as $permission) {
            $permissionIds[] = DB::table('permissions')->insertGetId([
                'name' => $permission,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create users for superadmin and admin
        $superAdminId = DB::table('users')->insertGetId([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign all permissions to superadmin
        foreach ($permissionIds as $permissionId) {
            DB::table('role_has_permissions')->insert([
                'role_id' => $superAdminRoleId,
                'permission_id' => $permissionId,
            ]);
        }

        // Assign only the 'nama' permission to admin
        $permissionNamaId = DB::table('permissions')->where('name', 'nama')->value('id');
        DB::table('role_has_permissions')->insert([
            'role_id' => $adminRoleId,
            'permission_id' => $permissionNamaId,
        ]);

        // Attach roles to users
        DB::table('model_has_roles')->insert([
            'role_id' => $superAdminRoleId,
            'model_type' => 'App\Models\User',
            'model_id' => $superAdminId,
        ]);

        DB::table('model_has_roles')->insert([
            'role_id' => $adminRoleId,
            'model_type' => 'App\Models\User',
            'model_id' => $adminId,
        ]);

        // User ID for seeding data
        $userId = $superAdminId;

        // Seeder for Kategori with 5 main categories and each having 5 children
        $kategoriUtama = [];
        for ($i = 1; $i <= 5; $i++) {
            $kategoriUtama[$i] = Kategori::create([
                'user_id' => $userId,
                'nama' => 'Kategori Utama ' . $i,
                'keterangan' => 'Keterangan untuk Kategori Utama ' . $i,
                'status' => 1
            ]);

            for ($j = 1; $j <= 5; $j++) {
                Kategori::create([
                    'user_id' => $userId,
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
                'user_id' => $userId,
                'nama' => 'Lokasi ' . $i,
                'nama_nospace' => Str::slug('Lokasi ' . $i),
                'keterangan' => 'Deskripsi untuk Lokasi ' . $i,
                'status' => 1
            ]);
        }

        // // Seeder for 6 stock locations
        // for ($i = 1; $i <= 6; $i++) {
        //     LokasiStok::create([
        //         'nama' => 'Lokasi Stok ' . $i
        //     ]);
        // }

        // // Seeder for 5 stock items
        // for ($i = 1; $i <= 5; $i++) {
        //     BarangStok::create([
        //         'nama' => 'Barang ' . $i,
        //         'kode' => 'BRG' . str_pad($i, 3, '0', STR_PAD_LEFT),
        //         'deskripsi' => 'Deskripsi untuk Barang ' . $i
        //     ]);
        // }

        // Seeder for 5 brands
        for ($i = 1; $i <= 5; $i++) {
            Merk::create([
                'user_id' => $userId,
                'nama' => 'Merek ' . $i,
                'nama_nospace' => Str::slug('Merek ' . $i),
                'keterangan' => 'Deskripsi untuk Merek ' . $i,
                'status' => 1
            ]);
        }

        // // Seeder for 5 stock brands
        // $barangList = BarangStok::all();
        // $lokasiStokList = LokasiStok::all();
        // foreach ($barangList as $barang) {
        //     foreach ($lokasiStokList as $lokasiStok) {
        //         MerekStok::create([
        //             'barang_id' => $barang->id,
        //             'nama' => 'Merek Stok ' . $barang->nama . ' di ' . $lokasiStok->nama,
        //             'jumlah' => rand(10, 100),
        //             'satuan' => 'pcs',
        //             'lokasi_id' => $lokasiStok->id,
        //             'stok_awal' => rand(10, 100),
        //             'stok_sisa' => rand(5, 50)
        //         ]);
        //     }
        // }

        // // Seeder for 5 stock vendors
        // for ($i = 1; $i <= 5; $i++) {
        //     VendorStok::create([
        //         'nama' => 'Vendor ' . $i,
        //         'alamat' => 'Jl. Vendor ' . $i,
        //         'telepon' => '021' . str_pad($i, 4, '0', STR_PAD_LEFT),
        //         'email' => 'vendor' . $i . '@example.com'
        //     ]);
        // }

        // // Seeder for 5 vendor contracts
        // $vendorList = VendorStok::all();
        // foreach ($vendorList as $vendor) {
        //     KontrakVendor::create([
        //         'vendor_id' => $vendor->id,
        //         'tanggal_mulai' => now()->subMonths(rand(1, 12)),
        //         'tanggal_selesai' => now()->addMonths(rand(1, 12)),
        //         'keterangan' => 'Kontrak dengan ' . $vendor->nama
        //     ]);
        // }

        // Seed example Toko
        Toko::create([
            'user_id' => 1,
            'nama' => 'PT Elektronik Jaya',
            'nama_nospace' => Str::slug('PT Elektronik Jaya'),
            'alamat' => 'Jl. Jaya No. 10, Jakarta',
            'telepon' => '02112345678',
            'email' => 'info@elektronikjaya.com',
            'petugas' => 'Andi',
            'keterangan' => 'Supplier elektronik terkemuka',
            'status' => 1
        ]);

        Toko::create([
            'user_id' => 1,
            'nama' => 'Toko Komputer ABC',
            'nama_nospace' => Str::slug('Toko Komputer ABC'),
            'alamat' => 'Jl. Komputer No. 5, Surabaya',
            'telepon' => '03198765432',
            'email' => 'contact@tokokomputerabc.com',
            'petugas' => 'Budi',
            'keterangan' => 'Distributor komputer dan aksesoris',
            'status' => 1
        ]);

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
        for ($i = 1; $i <= 5; $i++) {
            Aset::create([
                'user_id' => $userId,
                'nama' => 'Aset ' . $i,
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
                'user_id' => $userId,
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
                'user_id' => $userId,
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
                'user_id' => $userId,
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
                'user_id' => $userId,
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
                'kategori' => $nama
            ]);
        }

        // Seed for BarangStok
        for ($i = 1; $i <= 5; $i++) {
            BarangStok::create([
                'jenis_id' => JenisStok::inRandomOrder()->first()->id,
                'nama' => 'Barang ' . $i,
                'deskripsi' => 'Deskripsi untuk Barang ' . $i,
            ]);
        }

        // Seed for MerkStok
        for ($i = 1; $i <= 5; $i++) {
            MerkStok::create([
                'barang_id' => BarangStok::inRandomOrder()->first()->id,
                'nama' => 'Merek ' . $i,
            ]);
        }

        // Seed for VendorStok
        for ($i = 1; $i <= 5; $i++) {
            VendorStok::create([
                'nama' => 'Vendor ' . $i,
                'alamat' => $faker->address,
                'kontak' => $faker->phoneNumber,
            ]);
        }

        // Seed for LokasiStok
        for ($i = 1; $i <= 5; $i++) {
            LokasiStok::create([
                'nama' => 'Lokasi ' . $i,
                'alamat' => $faker->address,
            ]);
        }

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

        // Seed for KontrakVendorStok
        for ($i = 1; $i <= 5; $i++) {
            KontrakVendorStok::create([
                'vendor_id' => VendorStok::inRandomOrder()->first()->id,
                'merk_id' => MerkStok::inRandomOrder()->first()->id,
                'tanggal_kontrak' => strtotime($faker->date()),
                'jumlah_total' => rand(100, 1000),
            ]);
        }

        // Seed for PengirimanStok
        for (
            $i = 1;
            $i <= 5;
            $i++
        ) {
            PengirimanStok::create([
                'kontrak_id' => KontrakVendorStok::inRandomOrder()->first()->id,
                'merk_id' => MerkStok::inRandomOrder()->first()->id,
                'tanggal_pengiriman' => strtotime($faker->date()),
                'jumlah' => rand(1, 50),
                'lokasi_id' => LokasiStok::inRandomOrder()->first()->id,
                'bagian_id' => BagianStok::inRandomOrder()->first()->id,
                'posisi_id' => PosisiStok::inRandomOrder()->first()->id,
            ]);
        }

        // Seed for TransaksiStok
        for (
            $i = 1;
            $i <= 5;
            $i++
        ) {
            TransaksiStok::create([
                'tipe' => $faker->randomElement(['Pengeluaran', 'Pemasukan', 'Penggunaan Langsung']),
                'merk_id' => MerkStok::inRandomOrder()->first()->id,
                'jumlah' => rand(1, 20),
                'tanggal' => strtotime($faker->date()),
                'user_id' => 1, // Assuming you have a user with ID 1
                'lokasi_id' => LokasiStok::inRandomOrder()->first()->id,
                'pengiriman_id' => PengirimanStok::inRandomOrder()->first()->id,
            ]);
        }

        // Seed for TransaksiDaruratStok
        for ($i = 1; $i <= 5; $i++) {
            TransaksiDaruratStok::create([
                'merk_id' => MerkStok::inRandomOrder()->first()->id,
                'vendor_id' => VendorStok::inRandomOrder()->first()->id,
                'user_id' => 1, // Assuming you have a user with ID 1
                'tanggal' => strtotime($faker->date()),
                'jumlah' => rand(1, 10),
                'tipe' => 'Penggunaan Langsung',
                'deskripsi' => $faker->sentence,
                'lokasi_penerimaan' => $faker->address,
            ]);
        }

        // Seed for KontrakRetrospektifStok
        for (
            $i = 1;
            $i <= 5;
            $i++
        ) {
            KontrakRetrospektifStok::create([
                'vendor_id' => VendorStok::inRandomOrder()->first()->id,
                'merk_id' => MerkStok::inRandomOrder()->first()->id,
                'tanggal_kontrak' => strtotime($faker->date()),
                'jumlah_total' => rand(100, 500),
                'deskripsi_kontrak' => $faker->sentence,
            ]);
        }

        // Seed for Stok
        for ($i = 1; $i <= 5; $i++) {
            Stok::create([
                'merk_id' => MerkStok::inRandomOrder()->first()->id,
                'jumlah' => rand(10, 100),
                'lokasi_id' => LokasiStok::inRandomOrder()->first()->id,
            ]);
        }

        // Seed for PermintaanStok
        for (
            $i = 1;
            $i <= 5;
            $i++
        ) {
            PermintaanStok::create([
                'user_id' => 1, // Assuming you have a user with ID 1
                'merk_id' => MerkStok::inRandomOrder()->first()->id,
                'jumlah' => rand(1, 10),
                'tanggal_permintaan' => strtotime($faker->date()),
                'status' => $faker->randomElement(['Disetujui', 'Ditunda', 'Ditolak']),
                // 'lokasi_id' => LokasiStok::inRandomOrder()->first()->id,
            ]);
        }
    }
}
