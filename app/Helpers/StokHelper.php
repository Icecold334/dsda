<?php

namespace App\Helpers;

use App\Models\ListRab;
use App\Models\TransaksiStok;
use App\Models\PermintaanStok;
use App\Models\PermintaanMaterial;
use App\Models\DetailPermintaanMaterial;
use App\Models\StokDisetujui;

class StokHelper
{
    /**
     * Menghitung limit maksimal permintaan barang berdasarkan RAB dan stok gudang
     * 
     * Logika baru:
     * - Jika RAB: 3000, Gudang: 5000 → Limit = 3000 (sesuai RAB)
     * - Jika RAB: 2000, Gudang: 1000 → Limit = 1000 (sesuai stok gudang)
     * - Jika sudah ada pengambilan parsial, kurangi dari limit yang berlaku
     * 
     * @param int $merkId ID merk stok
     * @param int $rabId ID RAB (optional)
     * @param int $gudangId ID gudang/lokasi stok
     * @return int Limit maksimal yang bisa diminta
     */
    public static function calculateMaxPermintaan($merkId, $rabId = null, $gudangId = null, $permintaanMaterialIdToIgnore = null)
    {
        // 1. Hitung stok tersedia (dengan mengabaikan item yang diedit)
        $stokGudang = self::getStokTersedia($merkId, $gudangId, $permintaanMaterialIdToIgnore);

        if (!$rabId) {
            return max($stokGudang, 0);
        }

        // 3. Hitung limit dari RAB (method ini tidak perlu diubah)
        $limitRab = self::getLimitRab($merkId, $rabId);

        // 4. Hitung yang sudah digunakan (dengan mengabaikan item yang diedit)
        $sudahDigunakan = self::getJumlahSudahDigunakan($merkId, $rabId, $permintaanMaterialIdToIgnore);

        // 5. Sisa limit RAB setelah penggunaan
        $sisaLimitRab = max($limitRab - $sudahDigunakan, 0);

        // 6. Logika return tetap sama, karena variabel di atas sudah benar
        return min($sisaLimitRab, max($stokGudang, 0));
    }

    /**
     * Menghitung stok tersedia di gudang untuk merk tertentu
     */
    // public static function getStokTersedia($merkId, $gudangId = null)
    // {
    //     $query = TransaksiStok::where('merk_id', $merkId);

    //     if ($gudangId) {
    //         $query->where(function ($q) use ($gudangId) {
    //             $q->where('lokasi_id', $gudangId)
    //                 ->orWhereHas('bagianStok', fn($q) => $q->where('lokasi_id', $gudangId))
    //                 ->orWhereHas('posisiStok.bagianStok', fn($q) => $q->where('lokasi_id', $gudangId));
    //         });
    //     }

    //     $transaksis = $query->get();

    //     $total = 0;
    //     foreach ($transaksis as $trx) {
    //         $jumlah = match ($trx->tipe) {
    //             'Penyesuaian' => (int) $trx->jumlah,
    //             'Pemasukan' => (int) $trx->jumlah,
    //             'Pengeluaran', 'Pengajuan' => -(int) $trx->jumlah,
    //             default => 0,
    //         };
    //         $total += $jumlah;
    //     }

    //     return max($total, 0);
    // }

    // [UBAH] Tambahkan parameter baru di sini
    public static function getStokTersedia($merkId, $gudangId = null, $permintaanMaterialIdToIgnore = null)
    {
        $query = TransaksiStok::where('merk_id', $merkId);

        // [TAMBAHAN] Kondisi untuk mengabaikan transaksi dari item yang sedang diedit
        $query->when($permintaanMaterialIdToIgnore, function ($q) use ($permintaanMaterialIdToIgnore) {
            $pm = \App\Models\PermintaanMaterial::find($permintaanMaterialIdToIgnore);
            if ($pm) {
                // Abaikan transaksi yang memiliki 'permintaan_id' yang sama dengan item yang diedit
                return $q->where('permintaan_id', '!=', $pm->id);
            }
        });

        // --- LOGIKA LAMA ANDA (TETAP SAMA) ---
        if ($gudangId) {
            $query->where(function ($q) use ($gudangId) {
                $q->where('lokasi_id', $gudangId)
                    ->orWhereHas('bagianStok', fn($q) => $q->where('lokasi_id', $gudangId))
                    ->orWhereHas('posisiStok.bagianStok', fn($q) => $q->where('lokasi_id', $gudangId));
            });
        }

        $transaksis = $query->get();

        $total = 0;
        foreach ($transaksis as $trx) {
            $jumlah = match ($trx->tipe) {
                'Penyesuaian' => (int) $trx->jumlah,
                'Pemasukan' => (int) $trx->jumlah,
                'Pengeluaran', 'Pengajuan' => -(int) $trx->jumlah,
                default => 0,
            };
            $total += $jumlah;
        }

        return max($total, 0);
    }

    /**
     * Mendapatkan limit dari RAB untuk merk tertentu
     */
    public static function getLimitRab($merkId, $rabId)
    {
        $listRab = ListRab::where('rab_id', $rabId)
            ->where('merk_id', $merkId)
            ->first();

        return $listRab ? $listRab->jumlah : 0;
    }

    /**
     * Menghitung jumlah yang sudah digunakan dari RAB
     * Hanya menghitung permintaan yang sudah dikirim/selesai (status 2 dan 3)
     */
    // 

    // [UBAH] Tambahkan parameter baru di sini
    public static function getJumlahSudahDigunakan($merkId, $rabId, $permintaanMaterialIdToIgnore = null)
    {
        $totalSudahDigunakan = 0;

        // --- LOGIKA LAMA ANDA (Bagian 1) ---
        $permintaanMaterial = PermintaanMaterial::where('merk_id', $merkId)
            ->where('rab_id', $rabId)
            // [TAMBAHAN] Abaikan item yang sedang diedit dari perhitungan
            ->when($permintaanMaterialIdToIgnore, function ($query) use ($permintaanMaterialIdToIgnore) {
                return $query->where('id', '!=', $permintaanMaterialIdToIgnore);
            })
            ->whereHas('detailPermintaan', function ($query) {
                $query->whereIn('status', [2, 3]); // 2 = Sedang Dikirim, 3 = Selesai
            })
            ->whereHas('stokDisetujui', function ($query) {
                $query->where('jumlah_disetujui', '>', 0);
            })
            ->with('stokDisetujui')
            ->get();

        // Loop ini tetap sama
        foreach ($permintaanMaterial as $permintaan) {
            $totalSudahDigunakan += $permintaan->stokDisetujui->sum('jumlah_disetujui');
        }

        // --- LOGIKA LAMA ANDA (Bagian 2) ---
        $detailPermintaanRAB = DetailPermintaanMaterial::where('rab_id', $rabId)
            ->whereIn('status', [2, 3]) // Status dikirim/selesai
            ->whereHas('permintaanMaterial', function ($query) use ($merkId, $permintaanMaterialIdToIgnore) {
                $query->where('merk_id', $merkId)
                    // [TAMBAHAN] Abaikan juga di sini agar relasi tidak terhitung jika hanya item ini yang ada
                    ->when($permintaanMaterialIdToIgnore, function ($q) use ($permintaanMaterialIdToIgnore) {
                        return $q->where('id', '!=', $permintaanMaterialIdToIgnore);
                    })
                    ->whereHas('stokDisetujui', function ($subQuery) {
                        $subQuery->where('jumlah_disetujui', '>', 0);
                    });
            })
            ->with([
                'permintaanMaterial' => function ($query) use ($merkId, $permintaanMaterialIdToIgnore) {
                    $query->where('merk_id', $merkId)
                        // [TAMBAHAN] Abaikan saat eager loading agar tidak masuk ke dalam hasil
                        ->when($permintaanMaterialIdToIgnore, function ($q) use ($permintaanMaterialIdToIgnore) {
                            return $q->where('id', '!=', $permintaanMaterialIdToIgnore);
                        })
                        ->with('stokDisetujui');
                }
            ])
            ->get();

        // Loop ini tetap sama
        foreach ($detailPermintaanRAB as $detail) {
            foreach ($detail->permintaanMaterial as $permintaan) {
                if ($permintaan->merk_id == $merkId) {
                    $totalSudahDigunakan += $permintaan->stokDisetujui->sum('jumlah_disetujui');
                }
            }
        }

        return $totalSudahDigunakan;
    }

    /**
     * Validasi apakah jumlah permintaan valid
     * Mendukung pengambilan parsial/bertahap
     */
    public static function validateJumlahPermintaan($merkId, $jumlahDiminta, $rabId = null, $gudangId = null)
    {
        $maxPermintaan = self::calculateMaxPermintaan($merkId, $rabId, $gudangId);
        $stokGudang = self::getStokTersedia($merkId, $gudangId);
        $limitRab = $rabId ? self::getLimitRab($merkId, $rabId) : null;
        $sudahDigunakan = $rabId ? self::getJumlahSudahDigunakan($merkId, $rabId) : 0;
        $sisaLimitRab = $rabId ? max($limitRab - $sudahDigunakan, 0) : null;

        $isValid = $jumlahDiminta <= $maxPermintaan && $jumlahDiminta > 0;

        // Pesan error yang lebih informatif
        $errorMessage = '';
        if (!$isValid && $jumlahDiminta > 0) {
            if ($rabId) {
                if ($stokGudang < $sisaLimitRab) {
                    $errorMessage = "Stok gudang tidak mencukupi. Tersedia: {$stokGudang}, diminta: {$jumlahDiminta}";
                } else {
                    $errorMessage = "Melebihi sisa limit RAB. Sisa limit: {$sisaLimitRab}, diminta: {$jumlahDiminta}";
                }
            } else {
                $errorMessage = "Melebihi stok gudang. Tersedia: {$stokGudang}, diminta: {$jumlahDiminta}";
            }
        }

        return [
            'valid' => $isValid,
            'max_allowed' => $maxPermintaan,
            'stok_gudang' => $stokGudang,
            'limit_rab' => $limitRab,
            'sudah_digunakan' => $sudahDigunakan,
            'sisa_limit_rab' => $sisaLimitRab,
            'error_message' => $errorMessage,
            'can_partial' => $maxPermintaan > 0, // Apakah bisa ambil sebagian
        ];
    }

    /**
     * Mendapatkan info detail untuk debugging/logging
     */
    public static function getDetailInfo($merkId, $rabId = null, $gudangId = null)
    {
        $stokTersedia = self::getStokTersedia($merkId, $gudangId);
        $limitRab = $rabId ? self::getLimitRab($merkId, $rabId) : null;
        $sudahDigunakan = $rabId ? self::getJumlahSudahDigunakan($merkId, $rabId) : 0;
        $sisaLimitRab = $rabId ? max($limitRab - $sudahDigunakan, 0) : null;
        $maxPermintaan = self::calculateMaxPermintaan($merkId, $rabId, $gudangId);

        return [
            'merk_id' => $merkId,
            'rab_id' => $rabId,
            'gudang_id' => $gudangId,
            'stok_tersedia' => $stokTersedia,
            'limit_rab' => $limitRab,
            'sudah_digunakan' => $sudahDigunakan,
            'sisa_limit_rab' => $sisaLimitRab,
            'max_permintaan' => $maxPermintaan,
            'can_request_partial' => $maxPermintaan > 0,
            'limiting_factor' => $rabId ?
                ($stokTersedia < $sisaLimitRab ? 'stok_gudang' : 'limit_rab') :
                'stok_gudang'
        ];
    }

    /**
     * Menghitung sisa kuota yang bisa diminta untuk pengambilan parsial
     */
    public static function getSisaKuotaPermintaan($merkId, $rabId = null, $gudangId = null)
    {
        return self::calculateMaxPermintaan($merkId, $rabId, $gudangId);
    }

    /**
     * Mencatat penggunaan stok untuk tracking pengambilan parsial
     */
    public static function catatPenggunaanStok($merkId, $jumlah, $permintaanId, $rabId = null, $gudangId = null, $tipe = 'Pengajuan')
    {
        return \App\Models\TransaksiStok::create([
            'kode_transaksi_stok' => 'TRX' . time() . rand(1000, 9999),
            'permintaan_id' => $permintaanId,
            'tipe' => $tipe,
            'merk_id' => $merkId,
            'jumlah' => $jumlah,
            'lokasi_id' => $gudangId,
            'bagian_id' => null,
            'posisi_id' => null,
            'user_id' => auth()->id(),
            'tanggal' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
