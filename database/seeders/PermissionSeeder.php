<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // === STEP 1: Permission lama yang sudah ada ===
        $existingPermissions = [
            // Modul Aset
            'aset_price',
            'aset_new',
            'aset_edit',
            'aset_del',
            'aset_pdf',
            'aset_xls',
            'aset_noaktif',
            'aset_reaktif',

            // Riwayat
            'history_view',
            'history_newedit',
            'history_del',

            // Transaksi
            'trans_view',
            'trans_newedit',
            'trans_del',

            // Data Master
            'data_kategori',
            'data_merk',
            'data_barang',
            'data_toko',
            'data_penanggung_jawab',
            'data_kategori_stok',
            'data_ruang',
            'data_lokasi',
            'data_lokasi_gudang',
            'data_unit_kerja',
            'data_driver',
            'data_security',

            // Inventaris dan QR
            'qr_print',
            'pengaturan',
            'inventaris_edit_lokasi_penerimaan',
            'inventaris_tambah_barang_datang',
            'inventaris_unggah_foto_barang_datang',
            'inventaris_edit_jumlah_diterima',
            'inventaris_upload_foto_bukti',

            // Permintaan dan Peminjaman
            'permintaan_tambah_permintaan',
            'permintaan_persetujuan_jumlah_barang',
            'permintaan_upload_foto_dan_ttd_driver',
            'peminjaman_persetujuan_peminjaman_aset',

            // Kontrak, Pelayanan, Stok, RAB
            'persetujuan',
            'kontrak_tambah_kontrak_baru',
            'pelayanan_xls',
            'stok_show_detail',
            'stok_xls',
            'RAB_tambah_rab',
        ];

        foreach ($existingPermissions as $perm) {
            Permission::firstOrCreate([
                'name' => $perm,
                'guard_name' => 'web',
            ]);
        }

        // === STEP 2: Tambahkan permission dari tabel hak akses ===
        $modules = [
            'dashboard',
            'rab',
            'permintaan',
            'penerimaan',
            'kontrak',
            'upload_spb',
            'upload_sppb',
            'upload_foto',
            'spb',
            'sppb',
            'surat_jalan',
            'penyesuaian',
            'riwayat_transaksi',
            'gudang',
            'manajemen',
            'driver',
            'security'
        ];
        foreach ($modules as $mod) {
            foreach (['create', 'read', 'update', 'delete'] as $act) {
                Permission::firstOrCreate([
                    'name' => "{$mod}.{$act}",
                    'guard_name' => 'web',
                ]);
            }
        }
        // === STEP 3: Assign permission ke role berdasarkan mapping (versi aman) ===
        $rolePermissions = [
            // 1. Super Admin (Pusdatin)
            "Super Admin (Pusdatin)" => [
                // All modules CRUD
                ...array_merge(...array_map(fn($m) => ["$m.create", "$m.read", "$m.update", "$m.delete"], [
                    'dashboard',
                    'rab',
                    'permintaan',
                    'penerimaan',
                    'kontrak',
                    'upload_spb',
                    'upload_sppb',
                    'upload_foto',
                    'spb',
                    'sppb',
                    'surat_jalan',
                    'penyesuaian',
                    'riwayat_transaksi',
                    'gudang',
                    'manajemen',
                    'driver',
                    'security'
                ])),
            ],
            // 2. Kadis
            "Kadis" => [
                'dashboard.read',
                'rab.read',
                'permintaan.read',
                'penerimaan.read',
                'kontrak.read',
                'upload_spb.read',
                'upload_sppb.read',
                'upload_foto.read',
                'spb.read',
                'sppb.read',
                'surat_jalan.read',
                'penyesuaian.read',
                'riwayat_transaksi.read',
                'gudang.read',
                'manajemen.read',
                'driver.read',
                'security.read',
            ],
            // 3. Sekdis
            "Sekdis" => [
                'dashboard.read',
                'rab.read',
                'permintaan.read',
                'penerimaan.read',
                'kontrak.read',
                'upload_spb.read',
                'upload_sppb.read',
                'upload_foto.read',
                'spb.read',
                'sppb.read',
                'surat_jalan.read',
                'penyesuaian.read',
                'riwayat_transaksi.read',
                'gudang.read',
                'manajemen.read',
                'driver.read',
                'security.read',
            ],
            // 4. Kasubag Umum
            "Kasubag Umum" => [
                'dashboard.read',
                'rab.read',
                'permintaan.read',
                'penerimaan.read',
                'kontrak.read',
                'upload_spb.read',
                'upload_sppb.read',
                'upload_foto.read',
                'spb.read',
                'sppb.read',
                'surat_jalan.read',
                'penyesuaian.read',
                'riwayat_transaksi.read',
                'gudang.read',
                'manajemen.read',
                'driver.read',
                'security.read',
            ],
            // 5. Kasudin (PPK)
            "Kasudin (PPK)" => [
                'dashboard.read',
                'rab.read',
                'rab.update',
                'permintaan.read',
                'permintaan.update',
                'penerimaan.read',
                'kontrak.read',
                'spb.read',
                'sppb.read',
                'surat_jalan.read',
                'penyesuaian.read',
                'riwayat_transaksi.read',
                'gudang.read',
                'manajemen.read',
            ],
            // 6. Kasubag TU
            "Kasubag TU" => [
                'dashboard.read',
                'rab.read',
                'permintaan.read',
                'permintaan.update',
                'penerimaan.read',
                'penerimaan.update',
                'kontrak.read',
                'upload_sppb.create',
                'upload_sppb.read',
                'upload_sppb.update',
                'upload_sppb.delete',
                'upload_foto.read',
                'spb.read',
                'sppb.create',
                'sppb.read',
                'sppb.update',
                'sppb.delete',
                'surat_jalan.create',
                'surat_jalan.read',
                'surat_jalan.update',
                'surat_jalan.delete',
                'penyesuaian.create',
                'penyesuaian.read',
                'penyesuaian.update',
                'penyesuaian.delete',
                'riwayat_transaksi.create',
                'riwayat_transaksi.read',
                'riwayat_transaksi.update',
                'riwayat_transaksi.delete',
                'gudang.create',
                'gudang.read',
                'gudang.update',
                'gudang.delete',
                'manajemen.read',
            ],
            // 7. Pengurus Barang
            "Pengurus Barang" => [
                'dashboard.read',
                'rab.read',
                'permintaan.read',
                'permintaan.update',
                'penerimaan.read',
                'penerimaan.update',
                'kontrak.read',
                'upload_spb.read',
                'upload_sppb.read',
                'upload_foto.read',
                'spb.read',
                'sppb.create',
                'sppb.read',
                'sppb.update',
                'sppb.delete',
                'surat_jalan.create',
                'surat_jalan.read',
                'surat_jalan.update',
                'surat_jalan.delete',
                'penyesuaian.create',
                'penyesuaian.read',
                'penyesuaian.update',
                'penyesuaian.delete',
                'riwayat_transaksi.create',
                'riwayat_transaksi.read',
                'riwayat_transaksi.update',
                'riwayat_transaksi.delete',
                'driver.read',
                'driver.update',
                'security.read',
                'security.update',
            ],
            // 8. Kasie Perencanaan
            "Kasie Perencanaan" => [
                'dashboard.read',
                'rab.create',
                'rab.read',
                'rab.update',
                'rab.delete',
                'permintaan.read',
                'penerimaan.read',
                'kontrak.read',
                'riwayat_transaksi.read',
            ],
            // 9. Staff Perencanaan
            "Staff Perencanaan" => [
                'dashboard.read',
                'rab.create',
                'rab.read',
                'rab.update',
                'permintaan.read',
                'penerimaan.read',
                'kontrak.read',
                'riwayat_transaksi.read',
            ],
            // 10. Kasie Pemeliharaan (PPTK)
            "Kasie Pemeliharaan (PPTK)" => [
                'dashboard.read',
                'rab.create',
                'rab.read',
                'rab.update',
                'rab.delete',
                'permintaan.create',
                'permintaan.read',
                'permintaan.update',
                'permintaan.delete',
                'penerimaan.read',
                'kontrak.read',
                'upload_spb.create',
                'upload_spb.read',
                'upload_spb.update',
                'upload_spb.delete',
                'upload_foto.read',
                'spb.read',
                'spb.update',
                'sppb.read',
                'sppb.update',
                'surat_jalan.read',
                'penyesuaian.read',
                'riwayat_transaksi.read',
                'driver.read',
            ],
            // 11. Kasatpel
            "Kasatpel" => [
                'dashboard.read',
                'rab.read',
                'permintaan.create',
                'permintaan.read',
                'permintaan.update',
                'permintaan.delete',
                'upload_spb.create',
                'upload_spb.read',
                'upload_spb.update',
                'upload_spb.delete',
                'upload_foto.read',
                'spb.read',
                'spb.update',
                'sppb.read',
                'surat_jalan.read',
                'surat_jalan.update',
                'penyesuaian.read',
                'riwayat_transaksi.read',
                'driver.read',
                'security.read',
            ],
            // 12. Kasie Pembangunan
            "Kasie Pembangunan" => [
                'dashboard.read',
                'rab.create',
                'rab.read',
                'rab.update',
                'rab.delete',
                'permintaan.create',
                'permintaan.read',
                'permintaan.update',
                'permintaan.delete',
                'penerimaan.read',
                'kontrak.read',
                'upload_spb.create',
                'upload_spb.read',
                'upload_spb.update',
                'upload_spb.delete',
                'upload_foto.read',
                'spb.read',
                'spb.update',
                'sppb.read',
                'sppb.update',
                'surat_jalan.read',
                'penyesuaian.read',
                'riwayat_transaksi.read',
                'driver.read',
            ],
            // 13. Kasie Pompa (PPTK)
            "Kasie Pompa (PPTK)" => [
                'dashboard.read',
                'rab.create',
                'rab.read',
                'rab.update',
                'rab.delete',
                'permintaan.create',
                'permintaan.read',
                'permintaan.update',
                'permintaan.delete',
                'penerimaan.read',
                'kontrak.read',
                'upload_spb.create',
                'upload_spb.read',
                'upload_spb.update',
                'upload_spb.delete',
                'upload_foto.read',
                'spb.read',
                'spb.update',
                'sppb.read',
                'sppb.update',
                'surat_jalan.read',
                'penyesuaian.read',
                'riwayat_transaksi.read',
                'driver.read',
            ],
            // 14. Tim Pendukung PPK
            "Tim Pendukung PPK" => [
                'dashboard.read',
                'penerimaan.create',
                'penerimaan.read',
                'penerimaan.update',
                'penerimaan.delete',
                'kontrak.read',
                'kontrak.update',
                'riwayat_transaksi.read',
            ],
        ];


        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);

            foreach ($permissions as $permName) {
                $perm = Permission::where('name', $permName)->first();
                if ($perm && !$role->hasPermissionTo($perm)) {
                    $role->givePermissionTo($perm);
                }
            }
        }
    }
}
