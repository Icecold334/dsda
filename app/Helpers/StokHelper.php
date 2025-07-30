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
    public static function calculateMaxPermintaan($merkId, $rabId = null, $gudangId = null)
    {
        // 1. Hitung stok tersedia di gudang
        $stokGudang = self::getStokTersedia($merkId, $gudangId);

        // 2. Jika tidak ada RAB, return stok gudang
        if (!$rabId) {
            return max($stokGudang, 0);
        }

        // 3. Hitung limit dari RAB
        $limitRab = self::getLimitRab($merkId, $rabId);

        // 4. Hitung yang sudah digunakan dari RAB
        $sudahDigunakan = self::getJumlahSudahDigunakan($merkId, $rabId);

        // 5. Sisa limit RAB setelah penggunaan
        $sisaLimitRab = max($limitRab - $sudahDigunakan, 0);

        // 6. Logika baru: Return yang terkecil antara sisa limit RAB dan stok gudang
        // Ini memastikan:
        // - Tidak melebihi limit RAB yang tersisa
        // - Tidak melebihi stok fisik yang ada di gudang
        // - Mendukung pengambilan bertahap/parsial
        return min($sisaLimitRab, max($stokGudang, 0));
    }

    /**
     * Menghitung stok tersedia di gudang untuk merk tertentu
     */
    public static function getStokTersedia($merkId, $gudangId = null)
    {
        $query = TransaksiStok::where('merk_id', $merkId);

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
    public static function getJumlahSudahDigunakan($merkId, $rabId)
    {
        $totalSudahDigunakan = 0;

        // Hitung dari PermintaanMaterial yang menggunakan RAB dan sudah dikirim/selesai
        $permintaanMaterial = PermintaanMaterial::where('merk_id', $merkId)
            ->where('rab_id', $rabId)
            ->whereHas('detailPermintaan', function ($query) {
                $query->whereIn('status', [2, 3]); // 2 = Sedang Dikirim, 3 = Selesai
            })
            ->whereHas('stokDisetujui', function ($query) {
                $query->where('jumlah_disetujui', '>', 0);
            })
            ->with('stokDisetujui')
            ->get();

        foreach ($permintaanMaterial as $permintaan) {
            $totalSudahDigunakan += $permintaan->stokDisetujui->sum('jumlah_disetujui');
        }

        // Hitung juga dari DetailPermintaanMaterial yang menggunakan RAB dan sudah dikirim/selesai
        $detailPermintaanRAB = DetailPermintaanMaterial::where('rab_id', $rabId)
            ->whereIn('status', [2, 3]) // Status dikirim/selesai
            ->whereHas('permintaanMaterial', function ($query) use ($merkId) {
                $query->where('merk_id', $merkId)
                    ->whereHas('stokDisetujui', function ($subQuery) {
                        $subQuery->where('jumlah_disetujui', '>', 0);
                    });
            })
            ->with([
                'permintaanMaterial' => function ($query) use ($merkId) {
                    $query->where('merk_id', $merkId)->with('stokDisetujui');
                }
            ])
            ->get();

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
