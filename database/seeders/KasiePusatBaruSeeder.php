<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class KasiePusatBaruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate([
            'name' => 'Kepala Seksi',
            'guard_name' => 'web',
        ]);

        $user = User::firstOrCreate(
            ['email' => 'kasie_perencanaan.pusat_2@test.com'],
            [
                'name' => 'Dewi Marlina',
                'username' => 'kasie_perencanaan.pusat2',
                'nip' => '197603092010012008',
                'unit_id' => 46,
                'password' => bcrypt('!nventory@Pusat2025'),
                'email_verified_at' => now(),
            ]
        );

        $user->roles()->syncWithoutDetaching([$role->id]);

        $this->command->info("User [{$user->email}] seeded successfully.");
    }
}
