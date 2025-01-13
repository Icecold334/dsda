<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->truncate();
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
            'data_barang',
            'data_toko',
            'data_penanggung_jawab',
            'data_kategori_stok',
            'data_lokasi',
            'data_lokasi_gudang',
            'data_unit_kerja',
            'qr_print',
            'pengaturan',
            'inventaris_edit_lokasi_penerimaan',
            'inventaris_tambah_barang_datang',
            'inventaris_unggah_foto_barang_datang',
            'permintaan_persetujuan_jumlah_barang',
            'peminjaman_persetujuan_peminjaman_aset',
            'inventaris_edit_jumlah_diterima',
            'inventaris_upload_foto_bukti',
            'persetujuan',
            'kontrak_tambah_kontrak_baru',
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

        $users = Role::all()->pluck('id')->toArray();
        foreach ($users as $user) {
            foreach ($permissionIds as $permissionId) {
                DB::table('role_has_permissions')->insert([
                    'role_id' => $user,
                    'permission_id' => $permissionId,
                ]);
            }
        }
    }
}
