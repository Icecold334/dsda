<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use App\Models\DetailPermintaanMaterial;
use App\Models\LokasiStok;
use App\Models\Rab;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DetailPermintaanMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $data = [];

        // for ($i = 0; $i < 15; $i++) {
        //     $data[] = [
        //         'kode_permintaan' => fake()->numerify('ABCD#######'),
        //         'tanggal_permintaan' => strtotime(fake()->date()),
        //         'user_id' => 244,
        //         'gudang_id' => LokasiStok::inRandomOrder()->first()->id,
        //         'keterangan' => fake()->paragraph(),
        //         'lokasi' => fake()->address(),
        //         'created_at' => now(),
        //         'updated_at' => now()
        //     ];
        // }
        DetailPermintaanMaterial::insert($data);
    }
}
