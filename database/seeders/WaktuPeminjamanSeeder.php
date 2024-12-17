<?php

namespace Database\Seeders;

use App\Models\WaktuPeminjaman;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WaktuPeminjamanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $waktuPeminjaman = [
            [
                'waktu' => 'Pagi',
                'mulai' => '08:00:00',
                'selesai' => '12:00:00',
            ],
            [
                'waktu' => 'Siang',
                'mulai' => '12:00:00',
                'selesai' => '16:00:00',
            ],
            [
                'waktu' => 'Full Day',
                'mulai' => '08:00:00',
                'selesai' => '16:00:00',
            ],
        ];

        foreach ($waktuPeminjaman as $waktu) {
            WaktuPeminjaman::create($waktu);
        }
    }
}
