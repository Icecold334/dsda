<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SecurityPusatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('securities')->insert([
            [
                'nama' => 'Ahmad Suryadi',
                'unit_id' => 44,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Budi Santoso',
                'unit_id' => 44,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Dedi Kurniawan',
                'unit_id' => 44,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Eko Prasetyo',
                'unit_id' => 44,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Fajar Nugroho',
                'unit_id' => 44,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
