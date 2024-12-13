<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

abstract class Controller
{

    public $unit_id;

    public function __construct()
    {
        $unitId = Auth::user()->unit_id;
        $unit = UnitKerja::find($unitId);
        $this->unit_id = $unit && $unit->parent_id ? $unit->parent_id : $unitId;

        // Pastikan user dalam keadaan login
        if (Auth::check()) {
            $user = Auth::user();

            if (!Request::is('profil/profile/*')) {
                // Periksa jika NIP atau TTD kosong
                // dd(empty($user->nip) || empty($user->ttd));
                if (empty($user->nip) || empty($user->foto)) {
                    session()->flash('alert');
                }
            }
        }
    }
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

    public function nilaiAwalBulan($bulan)
    {
        // Run the query using Eloquent or Query Builder
        $nilaiTotal = Aset::where('aktif', true)
            ->where('tanggalbeli', '<', $bulan)
            ->where(function ($query) use ($bulan) {
                $query->where('tglnonaktif', 0)
                    ->orWhere('tglnonaktif', '>', $bulan);
            })
            ->sum('hargatotal');

        // Format the result to two decimal points
        return number_format($nilaiTotal, 2, ".", "");
    }

    protected function nilaiSekarangBulan($harga, $tgl_beli, $umur, $unix)
    {
        $bulan_beli = date("n", $tgl_beli);
        $tahun_beli = date("Y", $tgl_beli);
        $jml_bulan_beli = ($tahun_beli * 12) + $bulan_beli;

        $bulan_sekarang = date("n", $unix);
        $tahun_sekarang = date("Y", $unix);
        $jml_bulan_sekarang = ($tahun_sekarang * 12) + $bulan_sekarang;

        $umur_bulan = $jml_bulan_sekarang - $jml_bulan_beli;
        $umurbulan_asli = $umur * 12;
        $invert_umurbulan = $umurbulan_asli - $umur_bulan;

        if ($umurbulan_asli > 0) {
            $nilai_sekarang = ($invert_umurbulan / $umurbulan_asli) * $harga;
            return max($nilai_sekarang, 0); // Ensures the value doesn't go below 0
        }

        return $harga;
    }

    public function nilaiSusutBulan($bulan)
    {
        $assets = Aset::where('aktif', true)
            ->where('tanggalbeli', '<', $bulan)
            ->where(function ($query) use ($bulan) {
                $query->where('tglnonaktif', 0)
                    ->orWhere('tglnonaktif', '>', $bulan);
            })
            ->get(['hargatotal', 'tanggalbeli', 'umur']);

        $nilai = 0;

        foreach ($assets as $asset) {
            $nilai_sekarang = $this->nilaiSekarangBulan(
                $asset->hargatotal,
                $asset->tanggalbeli,
                $asset->umur,
                $bulan
            );
            $nilai += $nilai_sekarang;
        }

        return number_format($nilai, 2, ".", "");
    }

    function jumlahAset($section, $id)
    {
        // Building the query with Laravel's query builder
        $jumlahAset = Aset::where($section, $id)
            ->where('aktif', true)
            ->count(); // Count the results directly

        return $jumlahAset;
    }
    function nilaiAset($section, $id)
    {
        // Building the query with Laravel's query builder
        $nilaiAset = Aset::where($section, $id)
            ->where('aktif', true)
            ->sum('hargatotal'); // Calculate the total value directly

        return $nilaiAset * 1;
    }

    function rupiah($angka)
    {
        return 'Rp ' . number_format($angka, 2, ',', '.');
    }
}
