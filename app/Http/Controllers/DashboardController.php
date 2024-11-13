<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Aset;
use App\Models\User;
use App\Models\Agenda;
use App\Models\Jurnal;
use App\Models\History;
use App\Models\Keuangan;
use App\Models\TransaksiStok;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class DashboardController extends Controller
{
    public function index()
    {

        $agendas = Agenda::with('aset')
            ->where([['status', 1], ['tipe', 'mingguan']])
            ->orderBy('aset_id')
            ->orderBy('tanggal', 'desc')
            ->get()
            ->unique('aset_id'); // Mengambil entri terakhir berdasarkan aset_id
        foreach ($agendas as $agenda) {
            $agenda->formatted_date = Carbon::parse($agenda->tanggal)->translatedFormat('l, j M Y');
        }
        $jurnals = Jurnal::with('aset')
            ->where('status', 1)
            ->orderBy('aset_id')
            ->orderBy('tanggal', 'desc')
            ->get()
            ->unique('aset_id'); // Mengambil entri terakhir berdasarkan aset_id
        foreach ($jurnals as $jurnal) {
            $jurnal->formatted_date = Carbon::parse($jurnal->tanggal)->translatedFormat('j M Y');
        }
        $histories = History::with('aset', 'lokasi')
            ->where('status', 1)
            ->orderBy('aset_id')
            ->orderBy('tanggal', 'desc')
            ->get()
            ->unique('aset_id'); // Mengambil entri terakhir berdasarkan aset_id
        foreach ($histories as $histori) {
            $histori->formatted_date = Carbon::parse($histori->tanggal)->translatedFormat('j M Y');
        }
        $transactions = Keuangan::with('aset')
            ->where('status', 1)
            ->orderBy('aset_id')
            ->orderBy('tanggal', 'desc')
            ->get()
            ->unique('aset_id'); // Mengambil entri terakhir berdasarkan aset_id
        foreach ($transactions as $transaksi) {
            $transaksi->formatted_date = Carbon::parse($transaksi->tanggal)->translatedFormat('j M Y');
        }
        $asets_limit = Aset::where('status', true)
            ->orderBy('tanggalbeli', 'desc')
            ->take(5) // Ambil 5 aset terbaru
            ->get();
        foreach ($asets_limit as $aset) {
            $aset->formatted_date = Carbon::parse($aset->tanggalbeli)->translatedFormat('j M Y');
        }

        $count_aset = Aset::where([['aktif', true], ['status', true]])->count();
        // Mengambil semua aset yang memiliki status true
        $asets = Aset::where([['aktif', true], ['status', true]])->get();

        // Variabel untuk menampung total keseluruhan
        $totalNilaiSekarang = 0;
        $totalHarga = 0;
        $totalPenyusutan = 0;
        $penyusutan_bulan = 0;

        foreach ($asets as $aset) {
         // Cek jika umur aset tidak nol
        if ($aset->umur > 0) {
            // Hitung nilai sekarang untuk setiap aset
            $nilaiSekarang = $this->nilaiSekarang($aset->hargatotal, strtotime($aset->tanggalbeli), $aset->umur);
            
            // Akumulasi total untuk nilai sekarang, harga total, dan penyusutan
            $totalNilaiSekarang += $nilaiSekarang;
            $totalHarga += $aset->hargatotal;
            $totalPenyusutan += $aset->hargatotal - $nilaiSekarang;

            // Hitung penyusutan per bulan berdasarkan umur dan harga total
            $penyusutanBulanan = $aset->hargatotal / ($aset->umur * 12);

            // Hitung jumlah bulan yang telah berlalu sejak tanggal pembelian hingga akhir bulan ini
            $tanggalBeli = Carbon::parse($aset->tanggalbeli);
            $bulanBerjalan = $tanggalBeli->diffInMonths(Carbon::now()->endOfMonth());

            // Total penyusutan sampai akhir bulan ini
            $penyusutan_bulan += $penyusutanBulanan * $bulanBerjalan;
        } else {
            // Jika umur 0, lewati penyusutan untuk aset ini atau set nilai ke 0
            $aset->nilaiSekarang = 0;
            $aset->totalpenyusutan = 0;
        }
        }
        // dd($umur);

        // Format total dalam Rupiah
        $totalNilaiNow = $this->rupiah($totalNilaiSekarang);
        $totalHargaFormatted = $this->rupiah($totalHarga);
        $totalPenyusutanFormatted = $this->rupiah($totalPenyusutan);
        $PenyusutanBulanFormatted = $this->rupiah($penyusutan_bulan);

        return view('dashboard.index', compact('agendas', 'jurnals', 'histories', 'transactions', 'asets', 'count_aset', 'asets_limit', 'totalNilaiNow', 'totalHargaFormatted', 'totalPenyusutanFormatted', 'PenyusutanBulanFormatted'));
    }
}
