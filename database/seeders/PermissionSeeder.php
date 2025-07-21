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
            "superadmin" => array_merge(...array_values(array_map(fn($a) => array_map(fn($b) => "$a.$b", ['create', 'read', 'update', 'delete']), $modules))),
            // "Admin Sudin" => array_merge(...array_values(array_map(fn($a) => array_map(fn($b) => "$a.$b", ['create', 'read', 'update', 'delete']), $modules))),
            "Admin Sudin" => Permission::all()->pluck('name')->toArray(),

            "Kadis" => array_map(fn($m) => "$m.read", $modules),

            "Sekdis" => array_map(fn($m) => "$m.read", $modules),

            "Kepala Subbagian" => array_map(fn($m) => "$m.read", $modules),

            "Kepala Suku Dinas" => [
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

            "Kepala Subbagian Tata Usaha" => [
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
                'upload_foto',
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
                'upload_foto',
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
                'input_driver.read',
                'input_driver.update',
                'input_security.read',
                'input_security.update',
                'data_barang',
            ],

            "Kepala Seksi" => [
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
                'upload_sppb.read',
                'upload_foto',
                'data_barang',
                'spb.read',
                'spb.update',
                'sppb.read',
                'sppb.update',
                'surat_jalan.read',
                'penyesuaian.read',
                'riwayat_transaksi.read',
                'input_driver.read',
                'input_security.read',
            ],

            "Perencanaan" => [
                'dashboard.read',
                'rab.create',
                'rab.read',
                'rab.update',
                'permintaan.read',
                'penerimaan.read',
                'kontrak.read',
                'penyesuaian.read',
                'riwayat_transaksi.read',
            ],

            "Kepala Satuan Pelaksana" => [
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
                'upload_sppb.read',
                'upload_foto',
                'spb.read',
                'spb.update',
                'sppb.read',
                'surat_jalan.read',
                'surat_jalan.update',
                'penyesuaian.read',
                'riwayat_transaksi.read',
                'input_driver.read',
                'input_security.read',
            ],

            "P3K" => [
                'dashboard.read',
                'penerimaan.create',
                'penerimaan.read',
                'penerimaan.update',
                'penerimaan.delete',
                'kontrak.create',
                'kontrak.read',
                'kontrak.update',
                'penyesuaian.read',
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