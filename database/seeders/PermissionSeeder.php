<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // === STEP 1: Create all necessary permissions ===
        $modules = [
            'dashboard',
            'rab',
            'permintaan_barang',
            'penerimaan_barang',
            'kontrak',
            'upload_spb',
            'upload_sppb',
            'upload_foto_barang_keluar',
            'upload_foto_barang_diterima',
            'spb',
            'sppb',
            'surat_jalan',
            'penyesuaian_stok',
            'riwayat_transaksi',
            'gudang',
            'manajemen_user',
            'input_driver_security'
        ];

        // Create CRUD permissions for each module
        foreach ($modules as $module) {
            foreach (['create', 'read', 'update', 'delete'] as $action) {
                Permission::firstOrCreate([
                    'name' => "{$module}.{$action}",
                    'guard_name' => 'web',
                ]);
            }
        }

        // Create additional specific permissions
        $additionalPermissions = [
            'approve.kontrak',
            'approve.task',
            'inventaris_edit_jumlah_diterima',
            // Legacy scan permissions for guest access
            'foto', 'nama', 'kode', 'systemcode', 'kategori', 'status',
            'aset_keterangan', 'nonaktif_tanggal', 'nonaktif_alasan', 'nonaktif_keterangan',
            'detil_merk', 'detil_tipe', 'detil_produsen', 'detil_noseri', 'detil_thnproduksi', 'detil_deskripsi'
        ];

        foreach ($additionalPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        // === STEP 2: Create roles based on the permission table ===
        $rolePermissions = [
            // 1. Super Admin (Pusdatin) - CRUD on everything
            'Super Admin' => $this->getAllPermissions($modules),

            // 2. Kadis - Read only on everything but without upload_foto_barang_keluar
            'Kadis' => [
                'dashboard.read',
                'rab.read',
                'permintaan_barang.read',
                'penerimaan_barang.read',
                'kontrak.read',
                'upload_spb.read',
                'upload_sppb.read',
                // 'upload_foto_barang_keluar' removed - should be dash (-) according to table
                'upload_foto_barang_diterima.read',
                'spb.read',
                'sppb.read',
                'surat_jalan.read',
                'penyesuaian_stok.read',
                'riwayat_transaksi.read',
                'gudang.read',
                'manajemen_user.read',
                'input_driver_security.read'
            ],

            // 3. Sekdis - Read only on everything but without upload_foto_barang_keluar
            'Sekdis' => [
                'dashboard.read',
                'rab.read',
                'permintaan_barang.read',
                'penerimaan_barang.read',
                'kontrak.read',
                'upload_spb.read',
                'upload_sppb.read',
                // 'upload_foto_barang_keluar' removed - should be dash (-) according to table
                'upload_foto_barang_diterima.read',
                'spb.read',
                'sppb.read',
                'surat_jalan.read',
                'penyesuaian_stok.read',
                'riwayat_transaksi.read',
                'gudang.read',
                'manajemen_user.read',
                'input_driver_security.read'
            ],

            // 4. Kasubag Umum - Read only on everything but without upload_foto_barang_keluar
            'Kasubag Umum' => [
                'dashboard.read',
                'rab.read',
                'permintaan_barang.read',
                'penerimaan_barang.read',
                'kontrak.read',
                'upload_spb.read',
                'upload_sppb.read',
                // 'upload_foto_barang_keluar' removed - should be dash (-) according to table
                'upload_foto_barang_diterima.read',
                'spb.read',
                'sppb.read',
                'surat_jalan.read',
                'penyesuaian_stok.read',
                'riwayat_transaksi.read',
                'gudang.read',
                'manajemen_user.read',
                'input_driver_security.read'
            ],

            // 5. Kasudin (PPK) - R on most, RU on RAB and Permintaan
            'Kasudin' => [
                'dashboard.read',
                'rab.read',
                'rab.update',
                'permintaan_barang.read',
                'permintaan_barang.update',
                'penerimaan_barang.read',
                'kontrak.read',
                // Upload modules removed - should be dash (-) according to table
                'spb.read',
                'sppb.read',
                'surat_jalan.read',
                'penyesuaian_stok.read',
                'riwayat_transaksi.read',
                'gudang.read'
                // 'manajemen_user' removed - should be dash (-) according to table
                // 'input_driver_security' removed - should be dash (-) according to table
            ],

            // 6. Kasubag TU - Complex permissions
            'Kasubag TU' => [
                'dashboard.read',
                'rab.read',
                'permintaan_barang.read',
                'permintaan_barang.update',
                'penerimaan_barang.read',
                'penerimaan_barang.update',
                'kontrak.read',
                'upload_sppb.create',
                'upload_sppb.read',
                'upload_sppb.update',
                'upload_sppb.delete',
                'upload_foto_barang_keluar.read', // Added missing permission
                'upload_foto_barang_diterima.create',
                'upload_foto_barang_diterima.read',
                'upload_foto_barang_diterima.update',
                'upload_foto_barang_diterima.delete',
                'spb.read',
                'sppb.create',
                'sppb.read',
                'sppb.update',
                'sppb.delete',
                'surat_jalan.create',
                'surat_jalan.read',
                'surat_jalan.update',
                'surat_jalan.delete',
                'penyesuaian_stok.create',
                'penyesuaian_stok.read',
                'penyesuaian_stok.update',
                'penyesuaian_stok.delete',
                'riwayat_transaksi.create',
                'riwayat_transaksi.read',
                'riwayat_transaksi.update',
                'riwayat_transaksi.delete',
                'gudang.read' // Changed from CRUD to R (Read only)
            ],

            // 7. Pengurus Barang
            'Pengurus Barang' => [
                'dashboard.read',
                'rab.read',
                'permintaan_barang.read',
                'permintaan_barang.update',
                'penerimaan_barang.read',
                'penerimaan_barang.update',
                'kontrak.read',
                'upload_spb.read',
                'upload_sppb.read',
                'upload_foto_barang_keluar.read',
                'upload_foto_barang_diterima.read',
                'spb.read',
                'sppb.create',
                'sppb.read',
                'sppb.update',
                'sppb.delete',
                'surat_jalan.create',
                'surat_jalan.read',
                'surat_jalan.update',
                'surat_jalan.delete',
                'penyesuaian_stok.create',
                'penyesuaian_stok.read',
                'penyesuaian_stok.update',
                'penyesuaian_stok.delete',
                'riwayat_transaksi.create',
                'riwayat_transaksi.read',
                'riwayat_transaksi.update',
                'riwayat_transaksi.delete',
                // 'gudang' removed - should be dash (-) according to table
                'input_driver_security.read',
                'input_driver_security.update'
            ],

            // 8. Kasie Perencanaan
            'Kasie Perencanaan' => [
                'dashboard.read',
                'rab.create',
                'rab.read',
                'rab.update',
                'rab.delete',
                'permintaan_barang.read',
                'penerimaan_barang.read',
                'kontrak.read',
                // 'spb' and 'sppb' removed - should be dash (-) according to table
                'penyesuaian_stok.read', // Fixed: added missing permission
                'riwayat_transaksi.read'
            ],

            // 9. Staff Perencanaan
            'Staff Perencanaan' => [
                'dashboard.read',
                'rab.create',
                'rab.read',
                'rab.update',
                // 'rab.delete' removed - CRU only, not CRUD according to table
                'permintaan_barang.read',
                'penerimaan_barang.read',
                'kontrak.read',
                'penyesuaian_stok.read', // Fixed: added missing permission
                'riwayat_transaksi.read'
            ],

            // 10. Kasie Pemeliharaan (PPTK)
            'Kasie Pemeliharaan' => [
                'dashboard.read',
                'rab.create',
                'rab.read',
                'rab.update',
                'rab.delete',
                'permintaan_barang.create',
                'permintaan_barang.read',
                'permintaan_barang.update',
                'permintaan_barang.delete',
                'penerimaan_barang.read',
                'kontrak.read',
                'upload_spb.create',
                'upload_spb.read',
                'upload_spb.update',
                'upload_spb.delete',
                'upload_foto_barang_diterima.create',
                'upload_foto_barang_diterima.read',
                'upload_foto_barang_diterima.update',
                'upload_foto_barang_diterima.delete',
                'spb.read',
                'spb.update',
                'sppb.read',
                'sppb.update',
                'surat_jalan.read',
                'penyesuaian_stok.read',
                'riwayat_transaksi.read',
                'input_driver_security.read'
            ],

            // 11. Kasatpel
            'Kasatpel' => [
                'dashboard.read',
                'rab.read',
                'permintaan_barang.create',
                'permintaan_barang.read',
                'permintaan_barang.update',
                'permintaan_barang.delete',
                // 'penerimaan_barang' removed - should be dash (-) according to table
                // 'kontrak' removed - should be dash (-) according to table
                'upload_spb.create',
                'upload_spb.read',
                'upload_spb.update',
                'upload_spb.delete',
                'upload_sppb.read',
                'upload_foto_barang_diterima.create',
                'upload_foto_barang_diterima.read',
                'upload_foto_barang_diterima.update',
                'upload_foto_barang_diterima.delete',
                'spb.read',
                'spb.update',
                'sppb.read',
                'surat_jalan.read',
                'surat_jalan.update',
                'penyesuaian_stok.read',
                'riwayat_transaksi.read',
                'input_driver_security.read'
            ],

            // 12. Kasie Pembangunan
            'Kasie Pembangunan' => [
                'dashboard.read',
                'rab.create',
                'rab.read',
                'rab.update',
                'rab.delete',
                'permintaan_barang.create',
                'permintaan_barang.read',
                'permintaan_barang.update',
                'permintaan_barang.delete',
                'penerimaan_barang.read',
                'kontrak.read',
                'upload_spb.create',
                'upload_spb.read',
                'upload_spb.update',
                'upload_spb.delete',
                'upload_foto_barang_diterima.create',
                'upload_foto_barang_diterima.read',
                'upload_foto_barang_diterima.update',
                'upload_foto_barang_diterima.delete',
                'spb.read',
                'spb.update',
                'sppb.read',
                'sppb.update',
                'surat_jalan.read',
                'penyesuaian_stok.read',
                'riwayat_transaksi.read',
                'input_driver_security.read'
            ],

            // 13. Kasie Pompa (PPTK) - Same as Kasie Pemeliharaan
            'Kasie Pompa' => [
                'dashboard.read',
                'rab.create',
                'rab.read',
                'rab.update',
                'rab.delete',
                'permintaan_barang.create',
                'permintaan_barang.read',
                'permintaan_barang.update',
                'permintaan_barang.delete',
                'penerimaan_barang.read',
                'kontrak.read',
                'upload_spb.create',
                'upload_spb.read',
                'upload_spb.update',
                'upload_spb.delete',
                'upload_foto_barang_diterima.create',
                'upload_foto_barang_diterima.read',
                'upload_foto_barang_diterima.update',
                'upload_foto_barang_diterima.delete',
                'spb.read',
                'spb.update',
                'sppb.read',
                'sppb.update',
                'surat_jalan.read',
                'penyesuaian_stok.read',
                'riwayat_transaksi.read',
                'input_driver_security.read'
            ],

            // 14. Tim Pendukung PPK
            'Tim Pendukung PPK' => [
                'dashboard.read',
                'penerimaan_barang.create',
                'penerimaan_barang.read',
                'penerimaan_barang.update',
                'penerimaan_barang.delete',
                'kontrak.read',
                'kontrak.update',
                'penyesuaian_stok.read',
                'riwayat_transaksi.read'
            ]
        ];

        // === STEP 3: Create roles and assign permissions ===
        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'web'
            ]);

            // Clear existing permissions
            $role->permissions()->detach();

            // Assign new permissions
            foreach ($permissions as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                if ($permission) {
                    $role->givePermissionTo($permission);
                }
            }
        }
    }

    private function getAllPermissions($modules)
    {
        $permissions = [];
        foreach ($modules as $module) {
            foreach (['create', 'read', 'update', 'delete'] as $action) {
                $permissions[] = "{$module}.{$action}";
            }
        }
        return $permissions;
    }

    private function getReadOnlyPermissions($modules)
    {
        $permissions = [];
        foreach ($modules as $module) {
            $permissions[] = "{$module}.read";
        }
        // Add specific read permissions for roles like Kadis, Sekdis, etc.
        $permissions[] = 'upload_foto_barang_diterima.read'; // Added for roles that have R on "Foto Barang Diterima"
        $permissions[] = 'input_driver_security.read'; // Added for roles that have R on "Input Driver & Security"
        return $permissions;
    }
}
