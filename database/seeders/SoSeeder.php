<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\SatuanBesar;
use App\Models\BarangStok;
use App\Models\MerkStok;
use App\Models\LokasiStok;
use App\Models\TransaksiStok;

class SoSeeder extends Seeder
{
    public function run(): void
    {
        // Ganti sesuai file dan lokasi
        $this->seedFromCsv('Gudangalteng - Sheet1.csv', 'Gudang Material Ex Alteng');
    }

    protected function seedFromCsv(string $filename, string $lokasiLike): void
    {
        $file = public_path($filename);

        if (!file_exists($file)) {
            $this->command->error("File not found: $file");
            return;
        }

        $rows = array_map('str_getcsv', file($file));
        $header = array_map('trim', array_shift($rows));

        // Memulai loop untuk setiap baris di file CSV
        foreach ($rows as $row) {
            if (count($header) !== count($row)) {
                continue;
            }

            $data = array_combine($header, $row);

            if ($data['stok'] < 1) {
                continue;
            }

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

            // Penting: Cek jika lokasi ditemukan
            if (!$lokasi) {
                $this->command->warn("Lokasi '$lokasiLike' tidak ditemukan. Melewati baris...");
                continue;
            }

            $transaksis = TransaksiStok::where('lokasi_id', $lokasi->id)->where('merk_id', $merk->id)->get();

            $jumlah = 0;
            foreach ($transaksis as $trx) {
                if ($trx->tipe === 'Penyesuaian') {
                    $jumlah = $jumlah + (int) $trx->jumlah;
                } elseif ($trx->tipe === 'Pemasukan') {
                    $jumlah = $jumlah + (int) $trx->jumlah;
                } elseif ($trx->tipe === 'Pengeluaran') {
                    $jumlah = $jumlah - (int) $trx->jumlah;
                }
            }
            $jmlhexcel = (int) $data['stok'];
            
            $jumlahquery = $jmlhexcel - $jumlah;

            // 5. Transaksi
            TransaksiStok::create([
                'status' => 1,
                'keterangan_status' => 'Opname Seeder',
                'kode_transaksi_stok' => 'TRX' . strtoupper(Str::random(6)),
                'harga' => null,
                'ppn' => null,
                'img' => null,
                'tipe' => 'Penyesuaian',
                'merk_id' => $merk->id,
                'lokasi_id' => $lokasi?->id,
                'tanggal' => $data['tanggal'], // Pastikan format tanggal Y-m-d di CSV
                'jumlah' => $jumlahquery,
                'deskripsi' => 'Opname Gudang ' . ucfirst($lokasiLike),
                'lokasi_penerimaan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        } // <-- 💡 INI POSISI YANG BENAR UNTUK PENUTUP 'foreach'

        // Pesan ini sekarang ada di dalam fungsi, setelah loop selesai.
        $this->command->info("✅ Seeder selesai untuk gudang: $lokasiLike");
    }
    // Komentar kode lama bisa dihapus agar lebih bersih
}