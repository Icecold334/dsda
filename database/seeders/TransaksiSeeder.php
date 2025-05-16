<?php

namespace Database\Seeders;

use App\Models\MerkStok;
use App\Models\BagianStok;
use App\Models\LokasiStok;
use App\Models\PosisiStok;
use App\Models\TransaksiStok;
use Illuminate\Database\Seeder;
use App\Models\KontrakVendorStok;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $trans = [];
        $existingCombos = [];

        for ($i = 0; $i < 8000; $i++) {
            $kontrak = KontrakVendorStok::inRandomOrder()->first();
            $lokasi = LokasiStok::inRandomOrder()->first();
            if (!$kontrak || !$lokasi) continue;

            $user = $kontrak->user;
            if (!$user) continue;

            $bagian = fake()->boolean() ? BagianStok::where('lokasi_id', $lokasi->id)->inRandomOrder()->first() : null;
            $posisi = ($bagian && fake()->boolean()) ? PosisiStok::where('bagian_id', $bagian->id)->inRandomOrder()->first() : null;

            $jenis_id = $kontrak->jenis_id;

            $merk = MerkStok::whereHas('barangStok.jenisStok', function ($jenis) use ($jenis_id) {
                $jenis->where('id', $jenis_id);
            })->inRandomOrder()->first();

            if (!$merk) continue;

            // Kombinasi unik
            $comboKey = implode('-', [
                $merk->id,
                $lokasi->id,
                $bagian?->id ?? null,
                $posisi?->id ?? null,
            ]);

            $isFirst = !isset($existingCombos[$comboKey]);
            $tipe = $isFirst ? 'Pemasukan' : fake()->randomElement(['Pemasukan', 'Pengeluaran', 'Penyesuaian']);
            $jumlah = match ($tipe) {
                'Penyesuaian' => fake()->randomElement(['+', '-']) . fake()->numberBetween(1, 10000),
                default => fake()->numberBetween(1, 10000) * 100,
            };

            $trans[] = [
                'kode_transaksi_stok' => fake()->unique()->numerify('TRX#####'),
                'tipe' => $tipe,
                'merk_id' => $merk->id,
                'vendor_id' => null,
                'lokasi_id' => $lokasi->id,
                'bagian_id' => $bagian?->id,
                'posisi_id' => $posisi?->id,
                'harga' => fake()->numberBetween(100, 1000) * 1000,
                'ppn' => fake()->randomElement([0, 11, 12]),
                'user_id' => $user->id,
                'kontrak_id' => $tipe === 'Penyesuaian' ? null : $kontrak->id,
                'tanggal' => fake()->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
                'jumlah' => (string)$jumlah,
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Tandai kombinasi ini sudah pernah dipakai
            $existingCombos[$comboKey] = true;
        }

        if (!empty($trans)) {
            foreach (array_chunk($trans, 500) as $chunk) {
                TransaksiStok::insert($chunk);
            }
        }
    }
}
