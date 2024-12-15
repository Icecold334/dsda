<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Kategori;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KategoriAsetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kategoriData = [
            'KDO' => [],
            'Ruangan' => [
                'Ruang Meeting',
                'Ruang Kerja',
                'Ruang Server',
                'Ruang Arsip',
                'Ruang Istirahat'
            ],
            'Peralatan Kantor' => [
                'Meja Kerja',
                'Kursi Kantor',
                'Lemari Dokumen',
                'Papan Tulis',
                'Komputer'
            ],
            'Peralatan Elektronik' => [
                'Printer',
                'Scanner',
                'Proyektor',
                'Monitor',
                'UPS'
            ],
            'Furnitur' => [
                'Sofa',
                'Rak Buku',
                'Meja Tamu',
                'Lemari Arsip',
                'Kursi Tunggu'
            ],
        ];

        foreach ($kategoriData as $kategoriUtama => $subKategoris) {
            // Buat kategori utama
            $parentKategori = Kategori::create([
                'user_id' => User::inRandomOrder()->first()->id,
                'nama' => $kategoriUtama,
                'keterangan' => 'Keterangan untuk ' . $kategoriUtama,
                'status' => 1,
            ]);

            // Tambahkan subkategori
            foreach ($subKategoris as $subKategori) {
                Kategori::create([
                    'user_id' => User::inRandomOrder()->first()->id,
                    'nama' => $subKategori,
                    'keterangan' => 'Keterangan untuk subkategori ' . $subKategori,
                    'parent_id' => $parentKategori->id,
                    'status' => 1,
                ]);
            }
        }
    }
}
