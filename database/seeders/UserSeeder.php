<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin (Pusdatin)',
                'email' => 'superadmin.pusdatin@test.com',
                'role' => 'Super Admin (Pusdatin)',
                'password' => 'superadmin123',
                'unit' => 'Pusat Data dan Informasi Sumber Daya Air',
            ],
            [
                'name' => 'Kadis',
                'email' => 'kadis@test.com',
                'role' => 'Kadis',
                'password' => 'kadis123',
                'unit' => 'Sekretariat',
            ],
            [
                'name' => 'Sekdis',
                'email' => 'sekdis@test.com',
                'role' => 'Sekdis',
                'password' => 'sekdis123',
                'unit' => 'Sekretariat',
            ],
            [
                'name' => 'Kasubag Umum',
                'email' => 'kasubag.umum@test.com',
                'role' => 'Kasubag Umum',
                'password' => 'kasubagumum123',
                'unit' => 'Sekretariat',
            ],
            [
                'name' => 'Kasudin (PPK)',
                'email' => 'kasudin.ppk@test.com',
                'role' => 'Kasudin (PPK)',
                'password' => 'kasudinppk123',
                'unit' => 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Pusat',
            ],
            [
                'name' => 'Kasubag TU',
                'email' => 'kasubag.tu@test.com',
                'role' => 'Kasubag TU',
                'password' => 'kasubagtu123',
                'unit' => 'Sekretariat',
            ],
            [
                'name' => 'Pengurus Barang',
                'email' => 'pengurus.barang@test.com',
                'role' => 'Pengurus Barang',
                'password' => 'pengurusbarang123',
                'unit' => 'Sekretariat',
            ],
            [
                'name' => 'Kasie Perencanaan',
                'email' => 'kasie.perencanaan@test.com',
                'role' => 'Kasie Perencanaan',
                'password' => 'kasieperencanaan123',
                'unit' => 'Bidang Pengendalian Banjir dan Drainase',
            ],
            [
                'name' => 'Staff Perencanaan',
                'email' => 'staff.perencanaan@test.com',
                'role' => 'Staff Perencanaan',
                'password' => 'staffperencanaan123',
                'unit' => 'Bidang Pengendalian Banjir dan Drainase',
            ],
            [
                'name' => 'Kasie Pemeliharaan (PPTK)',
                'email' => 'kasie.pemeliharaan@test.com',
                'role' => 'Kasie Pemeliharaan (PPTK)',
                'password' => 'kasiepemeliharaan123',
                'unit' => 'Bidang Pengendalian Banjir dan Drainase',
            ],
            [
                'name' => 'Kasatpel',
                'email' => 'kasatpel@test.com',
                'role' => 'Kasatpel',
                'password' => 'kasatpel123',
                'unit' => 'Suku Dinas Sumber Daya Air Kota Administrasi Jakarta Pusat',
            ],
            [
                'name' => 'Kasie Pembangunan',
                'email' => 'kasie.pembangunan@test.com',
                'role' => 'Kasie Pembangunan',
                'password' => 'kasiepembangunan123',
                'unit' => 'Bidang Pengendalian Banjir dan Drainase',
            ],
            [
                'name' => 'Kasie Pompa (PPTK)',
                'email' => 'kasie.pompa@test.com',
                'role' => 'Kasie Pompa (PPTK)',
                'password' => 'kasiepompa123',
                'unit' => 'Bidang Pengendalian Banjir dan Drainase',
            ],
            [
                'name' => 'Tim Pendukung PPK',
                'email' => 'tim.pendukungppk@test.com',
                'role' => 'Tim Pendukung PPK',
                'password' => 'timpendukungppk123',
                'unit' => 'Bidang Pengendalian Banjir dan Drainase',
            ],
        ];

        foreach ($users as $userData) {
            $unitId = null;
            if (!empty($userData['unit'])) {
                $unit = \App\Models\UnitKerja::where('nama', $userData['unit'])->first();
                $unitId = $unit ? $unit->id : null;
            }
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'unit_id' => $unitId,
                    'email_verified_at' => now(),
                    'password' => bcrypt($userData['password']),
                ]
            );
            $role = Role::firstOrCreate(['name' => $userData['role'], 'guard_name' => 'web']);
            $user->assignRole($role);
        }
    }
}