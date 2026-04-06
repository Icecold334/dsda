<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class KasatpelMenteng2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate([
            'name' => 'Kepala Satuan Pelaksana',
            'guard_name' => 'web',
        ]);

        $user = User::firstOrCreate(
            ['email' => 'kasatpel.pusat.menteng_2@test.com'],
            [
                'name' => 'Mohammad Irfansyah, ST',
                'username' => 'kasatpel.pusat.menteng_2',
                'nip' => '198004152009041007',
                'kecamatan_id' => 3,
                'unit_id' => 44,
                'password' => bcrypt('!nventory@Pusat2025'),
                'email_verified_at' => now(),
            ]
        );

        $user->roles()->syncWithoutDetaching([$role->id]);

        $this->command->info("User [{$user->email}] seeded successfully.");
    }
}
