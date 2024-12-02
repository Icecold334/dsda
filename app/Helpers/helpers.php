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

if (!function_exists('rupiah')) {
  function rupiah($angka)
  {
    return 'Rp ' . number_format($angka, 0, ',', '.');
  }
}

if (!function_exists('usia_aset')) {
  function usia_aset($tgl_beli)
  {
    // Mendapatkan bulan dan tahun dari tanggal pembelian
    $bulan_beli = date("n", $tgl_beli);
    $tahun_beli = date("Y", $tgl_beli);

    // Menghitung jumlah bulan sejak tahun 0 untuk tanggal pembelian
    $jml_bulan_beli = ($tahun_beli * 12) + $bulan_beli;

    // Mendapatkan bulan dan tahun sekarang
    $sekarang = strtotime("now");
    $bulan_sekarang = date("n", $sekarang);
    $tahun_sekarang = date("Y", $sekarang);

    // Menghitung jumlah bulan sejak tahun 0 untuk tanggal sekarang
    $jml_bulan_sekarang = ($tahun_sekarang * 12) + $bulan_sekarang;

    // Menghitung umur aset dalam bulan
    $umur_bulan = $jml_bulan_sekarang - $jml_bulan_beli;

    // Konversi ke tahun dan bulan
    $tahun = floor($umur_bulan / 12);
    $bulan = $umur_bulan % 12;

    // Menentukan output
    if ($tahun == 0 && $bulan > 0) {
      return $bulan . " Bulan";
    } else if ($bulan == 0 && $tahun > 0) {
      return $tahun . " Tahun";
    } else if ($tahun == 0 && $bulan == 0) {
      return "Kurang dari 1 Bulan";
    } else {
      return $tahun . " Tahun " . $bulan . " Bulan";
    }
  }
}
