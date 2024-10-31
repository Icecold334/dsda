<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Buat atau dapatkan role superadmin dan admin
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

        // Buat daftar permissions
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
            'riwayat_keterangan'
        ];

        // Masukkan permissions ke database jika belum ada dan ambil id-nya
        $permissionIds = [];
        foreach ($permissions as $permission) {
            $permissionIds[] = DB::table('permissions')->insertGetId([
                'name' => $permission,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Buat user superadmin dan admin
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

        // Hubungkan superadmin dengan semua permissions
        foreach ($permissionIds as $permissionId) {
            DB::table('role_has_permissions')->insert([
                'role_id' => $superAdminRoleId,
                'permission_id' => $permissionId,
            ]);
        }

        // Hubungkan admin hanya dengan satu permission (contohnya: 'nama')
        $permissionNamaId = DB::table('permissions')->where('name', 'nama')->value('id');
        DB::table('role_has_permissions')->insert([
            'role_id' => $adminRoleId,
            'permission_id' => $permissionNamaId,
        ]);

        // Hubungkan role ke pengguna
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
    }
}
