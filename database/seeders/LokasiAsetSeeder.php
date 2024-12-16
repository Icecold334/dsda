<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lokasi;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LokasiAsetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 5; $i++) {
            $faker = Faker::create('id_ID');
            Lokasi::create([
                'user_id' => User::inRandomOrder()->first()->id,
                'nama' => $faker->city,
                'nama_nospace' => Str::slug('Lokasi ' . $i),
                'keterangan' => 'Deskripsi untuk Lokasi ' . $i,
                'status' => 1
            ]);
        }
    }
}
