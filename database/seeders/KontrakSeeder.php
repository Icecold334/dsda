<?php

namespace Database\Seeders;

use App\Models\Toko;
use App\Models\User;
use App\Models\MerkStok;
use App\Models\ListKontrakStok;
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
        for ($i = 1; $i <= 1000; $i++) {
            $kontraks[] = [
                'nomor_kontrak' => fake()->unique()->bothify('KV#####'),
                'metode_id' => $metodes->random()->id,
                'jenis_id' => fake()->numberBetween(1, 3),
                'vendor_id' => $tokos->random()->id,
                'tanggal_kontrak' => strtotime(fake()->date()),
                'user_id' => $users->random()->id,
                'type' => 1,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        foreach (array_chunk($kontraks, 50) as $chunk) {
            KontrakVendorStok::insert($chunk);
        }

        $kontraks = KontrakVendorStok::all();

        $list = [];

        foreach ($kontraks as $kontrak) {
            $jumlahItem = rand(3, 10);
            // Pilih satu jenis_id random
            $jenisId = fake()->numberBetween(1, 3);

            // Ambil merk-merk yang sesuai jenis
            $merkList = MerkStok::with('barangStok')
                ->get()
                ->filter(fn($merk) => $merk->barangStok?->jenis_id === $jenisId)
                ->values();
            if ($merkList->isEmpty())
                continue;

            // Pilih merk random dari jenis tersebut
            $jumlahItem = rand(3, 7);
            $selectedMerks = $merkList->random(min($jumlahItem, $merkList->count()));
            foreach ($selectedMerks as $merk) {
                $jumlah = fake()->numberBetween(10, 500);
                $harga = fake()->numberBetween(100, 1000) * 100;
                $ppn = fake()->randomElement([null, '11', '12']);

                $list[] = [
                    'kontrak_id' => $kontrak->id,
                    'merk_id' => $merk->id,
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'ppn' => $ppn,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        $chunks = array_chunk($list, 50);
        foreach ($chunks as $chunk) {
            ListKontrakStok::insert($chunk);
        }
    }
}