<?php

namespace App\Http\Controllers;

abstract class Controller
{
    /**
     * Calculate the current value of an asset based on purchase date, lifespan, and initial price.
     *
     * @param float $harga - Initial price of the asset
     * @param int $tgl_beli - Purchase date as a timestamp
     * @param int $umur - Lifespan of the asset in years
     * @param bool $tampil - Whether to format the result as a string
     * @return mixed - Formatted string or raw numeric value of the current asset value
     */
    public function nilaiSekarang($harga, $tgl_beli, $umur, $tampil = true)
    {
        $sekarang = strtotime("now");

        // Calculate months from purchase date and current date
        $bulan_beli = date("n", $tgl_beli);
        $tahun_beli = date("Y", $tgl_beli);
        $jml_bulan_beli = ($tahun_beli * 12) + $bulan_beli;

        $bulan_sekarang = date("n", $sekarang);
        $tahun_sekarang = date("Y", $sekarang);
        $jml_bulan_sekarang = ($tahun_sekarang * 12) + $bulan_sekarang;

        $umur_bulan = $jml_bulan_sekarang - $jml_bulan_beli;
        $umurbulan_asli = $umur * 12;
        $invert_umurbulan = $umurbulan_asli - $umur_bulan;

        // Calculate current value based on depreciation
        if ($umurbulan_asli > 0) {
            $nilai_sekarang = ($invert_umurbulan / $umurbulan_asli) * $harga;
            if ($nilai_sekarang < 0) {
                $nilai_sekarang = 0;
            }
        } else {
            $nilai_sekarang = $harga;
        }

        // Return formatted or raw value based on $tampil flag
        return $tampil ? number_format((float)$nilai_sekarang, 2, '.', '') : $nilai_sekarang;
    }

    function rupiah($angka)
    {
        return 'Rp ' . number_format($angka, 2, ',', '.');
    }
}
