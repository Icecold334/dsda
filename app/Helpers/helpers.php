<?php

// app/Helpers/helpers.php
if (!function_exists('nilaiSekarang')) {
  function nilaiSekarang($harga, $tgl_beli, $umur, $tampil = true)
  {
    $sekarang = strtotime("now");
    $bulan_beli = date("n", $tgl_beli);
    $tahun_beli = date("Y", $tgl_beli);
    $jml_bulan_beli = ($tahun_beli * 12) + $bulan_beli;
    $bulan_sekarang = date("n", $sekarang);
    $tahun_sekarang = date("Y", $sekarang);
    $jml_bulan_sekarang = ($tahun_sekarang * 12) + $bulan_sekarang;
    $umur_bulan = $jml_bulan_sekarang - $jml_bulan_beli;
    $umurbulan_asli = $umur * 12;
    $invert_umurbulan = $umurbulan_asli - $umur_bulan;

    if ($umurbulan_asli > 0) {
      $nilai_sekarang = ($invert_umurbulan / $umurbulan_asli) * $harga;
      if ($nilai_sekarang < 0) {
        $nilai_sekarang = 0;
      }
    } else {
      $nilai_sekarang = $harga;
    }

    return $tampil ? number_format((float)$nilai_sekarang, 2, '.', '') : $nilai_sekarang;
  }
}
