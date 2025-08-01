<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Satuan;
use App\Models\BarangStok;
use App\Models\MerkStok;
use App\Models\TransaksiStok;
use App\Models\LokasiStok;
use App\Models\SatuanBesar;

class SoSeeder extends Seeder
{
    public function run(): void
    {
        // Contoh pemanggilan:
        // sudin barat
        $this->seedFromCsv('kembangan.csv', 'kembangan');
        $this->seedFromCsv('mercu.csv', 'mercu');
        $this->seedFromCsv('pengumben.csv', 'pengumben');
        $this->seedFromCsv('perumnas.csv', 'perumnas');
        $this->seedFromCsv('tomang.csv', 'tomang');

        // sudin pusat
        $this->seedFromCsv('melati.csv', 'melati');
        $this->seedFromCsv('sunter.csv', 'sunter');
        // sudin selatan
        $this->seedFromCsv('minyak.csv', 'minyak');
        // timur
        $this->seedFromCsv('bambu.csv', 'bambu');
        // utara
        $this->seedFromCsv('ketel.csv', 'ketel'); // belum ada data
        // tambahkan lagi sesuai gudang lainnya
    }

    protected function seedFromCsv(string $filename, string $lokasiLike): void
    {
        $file = public_path($filename);

        if (!file_exists($file)) {
            echo "File not found: $file\n";
            return;
        }

        $rows = array_map('str_getcsv', file($file));
        $header = array_map('trim', array_shift($rows));

        foreach ($rows as $row) {
            $data = array_combine($header, $row);

            // 1. Satuan
            $satuan = SatuanBesar::firstOrCreate(
                ['nama' => trim($data['satuan'])],
                ['slug' => Str::slug($data['satuan'])]
            );

            // 2. Barang
            $barang = BarangStok::firstOrCreate(
                [
                    'slug' => Str::slug($data['nama_barang']),
                ],
                [
                    'nama' => trim($data['nama_barang']),
                    'kode_barang' => 'BRG-' . strtoupper(Str::random(5)),
                    'jenis_id' => 1,
                    'kategori_id' => null,
                    'satuan_besar_id' => $satuan->id,
                    'satuan_kecil_id' => null,
                    'deskripsi' => null,
                    'konversi' => null,
                    'minimal' => 10
                ]
            );

            // 3. Merek
            $merk = MerkStok::firstOrCreate(
                ['kode' => trim($data['kode'])],
                [
                    'barang_id' => $barang->id,
                    'nama' => trim($data['merk'] ?? $data['nama_barang']),
                    'tipe' => trim($data['merk'] ?? null),
                    'ukuran' => trim($data['merk'] ?? null),
                ]
            );

            // 4. Lokasi dari parameter
            $lokasi = LokasiStok::where('nama', 'like', '%' . $lokasiLike . '%')->first();

            // 5. Transaksi
            TransaksiStok::create([
                'status' => 1,
                'keterangan_status' => 'Opname Seeder',
                'kode_transaksi_stok' => 'TRX' . strtoupper(Str::random(6)),
                'harga' => null,
                'ppn' => null,
                'img' => null,
                'tipe' => 'Pemasukan',
                'merk_id' => $merk->id,
                'vendor_id' => null,
                'pj_id' => null,
                'pptk_id' => null,
                'user_id' => null,
                'ppk_id' => null,
                'lokasi_id' => $lokasi?->id,
                'bagian_id' => null,
                'posisi_id' => null,
                'kontrak_id' => null,
                'tanggal' => strtotime($data['tanggal']),
                'jumlah' => $data['stok'],
                'deskripsi' => 'Opname Gudang ' . ucfirst($lokasiLike),
                'lokasi_penerimaan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "✅ Seeder selesai untuk gudang: $lokasiLike\n";
    }
}
