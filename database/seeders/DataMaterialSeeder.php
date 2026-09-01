<?php

namespace Database\Seeders;

use League\Csv\Reader;
use App\Models\MerkStok;
use App\Models\BarangStok;
use App\Models\SatuanBesar;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DataMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = public_path('databarang.csv');

        if (!file_exists($filePath)) {
            $this->command->warn('File databarang.csv tidak ditemukan di folder public/. Seeder dilewati.');
            $this->command->warn('Letakkan file databarang.csv di: ' . $filePath);
            return;
        }

        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0); // Kolom pertama sebagai header
        foreach ($csv as $row) {
            DB::transaction(function () use ($row) {
                $satuan = SatuanBesar::firstOrCreate(['slug' => Str::slug(trim($row['Satuan']))], ['nama' => trim($row['Satuan'])]);

                $barang = BarangStok::create([
                    'kode_barang' => $row['Kode'],
                    'nama' => $row['Nama Barang'],
                    'slug' => Str::slug($row['Nama Barang']),
                    'satuan_besar_id' => $satuan->id,
                    'jenis_id' => 1, // default atau mapping manual
                    'kategori_id' => null,
                    'satuan_kecil_id' => null,
                    'konversi' => null,
                    'deskripsi' => null,
                ]);

                if (!empty($row['Spesifikasi']) && $row['Spesifikasi'] !== '-') {
                    MerkStok::create([
                        'barang_id' => $barang->id,
                        'nama' => $row['Nama Barang'],
                        'tipe' => null,
                        'ukuran' => $row['Spesifikasi'],
                    ]);
                }
            });
        }
    }
}
