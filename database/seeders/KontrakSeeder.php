<?php

namespace Database\Seeders;

use App\Models\Toko;
use App\Models\User;
use App\Models\MerkStok;
use App\Models\MetodePengadaan;
use Illuminate\Database\Seeder;
use App\Models\KontrakVendorStok;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KontrakSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            'Pengadaan Langsung',
            'Penunjukan Langsung',
            'Tender',
            'E-Purchasing / E-Katalog',
            'Tender Cepat',
            'Swakelola',
        ];
        $datas = [];
        foreach ($methods as $method) {
            $datas[] = [
                'nama' => $method,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        MetodePengadaan::insert($datas);

        $metodes = MetodePengadaan::all();
        $tokos = Toko::all();
        $users = User::all();
        $kontraks = [];
        // Seed for KontrakVendorStok
        for ($i = 1; $i <= 354; $i++) {
            $kontraks[] = [
                'nomor_kontrak' => fake()->unique()->bothify('KV#####'),
                'metode_id' => $metodes->random()->first()->id,
                'jenis_id' => fake()->numberBetween(1, 3),
                'vendor_id' => $tokos->random()->first()->id,
                'tanggal_kontrak' => strtotime(fake()->date()),
                'user_id' => $users->random()->first()->id,
                'type' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        KontrakVendorStok::insert($kontraks);
    }
}
