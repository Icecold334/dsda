<?php

use App\Models\Aset;
use App\Models\Option;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

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

if (!function_exists('loadOptionSettings')) {
  function loadOptionSettings()
  {
    $option = Option::find(1); // Anda bisa ganti dengan logika sesuai kebutuhan
    return [
      'kodeaset' => $option->kodeaset,
      'qr_judul' => $option->qr_judul,
      'qr_judul_other' => $option->qr_judul_other ?? null,
      'qr_baris1' => $option->qr_baris1,
      'qr_baris1_other' => $option->qr_baris1_other ?? null,
      'qr_baris2' => $option->qr_baris2,
      'qr_baris2_other' => $option->qr_baris2_other ?? null,
    ];
  }
}

if (!function_exists('resolveOptionValue')) {
  function resolveOptionValue($optionKey, $asset, $otherValue = null)
  {
    switch ($optionKey) {
      case 'perusahaan':
        return strtoupper(Auth::user()->perusahaan);
      case 'kategori':
        return strtoupper($asset->kategori->nama);
      case 'tanggalbeli':
        return strtoupper(date('d M Y', strtotime($asset->tanggalbeli)));
      case 'hargatotal':
        return 'Rp ' . number_format($asset->hargatotal, 0, ',', '.');
      case 'person':
        return strtoupper($asset->person->nama);
      case 'lokasi':
        return strtoupper($asset->lokasi->nama);
      case 'other':
        return strtoupper(substr($otherValue, 0, 25));
      case 'kosong':
        return '';
      default:
        return strtoupper($asset->{$optionKey} ?? '');
    }
  }
}

if (!function_exists('getAssetWithSettings')) {
  function getAssetWithSettings($assetId)
  {
    $settings = loadOptionSettings(); // Memanggil fungsi loadOptionSettings()
    $asset = Aset::findOrFail($assetId);

    $judul = resolveOptionValue($settings['qr_judul'], $asset, $settings['qr_judul_other']);
    $baris1 = resolveOptionValue($settings['qr_baris1'], $asset, $settings['qr_baris1_other']);
    $baris2 = resolveOptionValue($settings['qr_baris2'], $asset, $settings['qr_baris2_other']);

    $judul = Str::limit($judul ?? 'QR Code Title', 20, '');
    $baris1 = Str::limit($baris1 ?? 'Line 1', 25, '');
    $baris2 = Str::limit($baris2 ?? 'Line 2', 25, '');

    return [
      'id' => $asset->id,
      'nama' => $asset->nama,
      'systemcode' => $asset->systemcode,
      'kategori' => $asset->kategori->nama ?? 'Tidak Berkategori',
      'qr_image' => "storage/qr/{$asset->systemcode}.png",
      'judul' => $judul,
      'baris1' => $baris1,
      'baris2' => $baris2,
    ];
  }
}

if (!function_exists('filterByParentUnit')) {
  /**
   * Filter query berdasarkan parent unit ID.
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @param int $parentUnitId
   * @return \Illuminate\Database\Eloquent\Builder
   */
  function filterByParentUnit($query, $parentUnitId)
  {
    return $query->whereHas('unitKerja', function ($unitQuery) use ($parentUnitId) {
      // Pastikan kita selalu memfilter berdasarkan unit parent
      $unitQuery->where('parent_id', $parentUnitId)
        ->orWhere('id', $parentUnitId); // Menampilkan kategori yang terkait dengan parent atau child
    });
  }
}

if (!function_exists('formatRole')) {
  /**
   * Helper untuk memformat role.
   *
   * @param string $role
   * @return string
   */
  function formatRole($role)
  {
    if ($role === 'penanggungjawab') {
      return 'Penanggung Jawab';
    } elseif ($role === 'ppk') {
      return 'Pejabat Pembuat Komitmen (PPK)';
    } elseif ($role === 'pptk') {
      return 'Pejabat Pelaksana Teknis Kegiatan (PPTK)';
    } else {
      return ucwords(str_replace('_', ' ', $role));
    }
  }
}

if (!function_exists('canViewAdditionalUsers')) {
  function canViewAdditionalUsers($user)
  {
    $authUser = Auth::user();
    return $authUser->unit_id == null ||
      $user->unitKerja->id == $authUser->unit_id && $user->unitKerja->parent_id === null;

    // dd($authUser);
  }
}
