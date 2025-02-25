<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ruang;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RuangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ruangList = [
            'Ruang Rapat 1 (Lt. 7)',
            'Ruang Rapat 2 (Lt. 7)',
            'Ruang Rapat ex BPK (Lt.8)',
            'Ruang Rapat Bidang ROB (Lt. 8)',
            'Ruang Rapat Unit Pengadaan Tanah (Lt. 8)',
            'Ruang Rapat Bidang Geologi (Lt. 9)',
            'Ruang Rapat Bidang Banjir (Lt. 9)',
            'Ruang Rapat Bidang Limbah (Lt. 10)',
            'Ruang Rapat (Lt. 10)',
        ];

        foreach ($ruangList as $toko) {
            Ruang::create([
                // 'user_id' => User::inRandomOrder()->first()->id,
                'user_id' => 3,
                'nama' => $toko,
                'slug' => Str::slug($toko),
                'pj_id' => User::whereBetween('unit_id', [1, 7])->inRandomOrder()->first()->id ?? null,
            ]);
        }
    }
}
