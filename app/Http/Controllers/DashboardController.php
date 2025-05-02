<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Aset;
use App\Models\Agenda;
use App\Models\Jurnal;
use App\Models\History;
use App\Models\Kategori;
use App\Models\Keuangan;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (!Auth::user()->unitKerja->hak) {
            $roleRedirectMap = [
                'Kepala Suku Dinas' => 'dashboard',
                'Kepala Satuan Pelaksana' => 'permintaan/material',
                'Perencanaan' => 'rab',
                'Kepala Seksi' => 'permintaan/material',
                'Kepala Subbagian' => 'permintaan/material',
                'Pengurus Barang' => 'permintaan/material',
            ];

            foreach ($roleRedirectMap as $role => $target) {
                if (Auth::user()->hasRole($role)) {
                    $to = $target;
                    break;
                }
            }

            return redirect()->to($to);
        }

        $agendas = Agenda::with('aset')
            ->where([['status', 1], ['tipe', 'mingguan']])
            ->orderBy('aset_id')
            ->orderBy('tanggal', 'desc')
            ->take(5) // Ambil 5 
            ->get()
            ->unique('aset_id'); // Mengambil entri terakhir berdasarkan aset_id
        foreach ($agendas as $agenda) {
            $agenda->formatted_date = Carbon::parse($agenda->tanggal)->translatedFormat('l, j M Y');
        }
        $jurnals = Jurnal::with('aset')
            ->where('status', 1)
            ->orderBy('aset_id')
            ->orderBy('tanggal', 'desc')
            ->take(5) // Ambil 5 
            ->get()
            ->unique('aset_id'); // Mengambil entri terakhir berdasarkan aset_id
        foreach ($jurnals as $jurnal) {
            $jurnal->formatted_date = Carbon::parse($jurnal->tanggal)->translatedFormat('j M Y');
        }
        $histories = History::with('aset', 'lokasi')
            ->where('status', 1)
            ->orderBy('aset_id')
            ->orderBy('tanggal', 'desc')
            ->take(5) // Ambil 5 
            ->get()
            ->unique('aset_id'); // Mengambil entri terakhir berdasarkan aset_id
        foreach ($histories as $histori) {
            $histori->formatted_date = Carbon::parse($histori->tanggal)->translatedFormat('j M Y');
        }
        $transactions = Keuangan::with('aset')
            ->where('status', 1)
            ->orderBy('aset_id')
            ->orderBy('tanggal', 'desc')
            ->take(5) // Ambil 5 
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
                $nilaiSekarang = $this->nilaiSekarang($aset->hargatotal, $aset->tanggalbeli, $aset->umur);
                $tanggalBeli = strtotime($aset->tanggalbeli);
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
        // dd($nilaiSekarang);

        // Format total dalam Rupiah
        $totalNilaiNow = $this->rupiah(abs($totalNilaiSekarang));
        $totalHargaFormatted = $this->rupiah($totalHarga);
        $totalPenyusutanFormatted = $this->rupiah(abs($totalPenyusutan));
        $PenyusutanBulanFormatted = $this->rupiah($penyusutan_bulan);

        //nilai aset chart line
        $nilaiPerolehan = [1, 2, 3, 1, 3, 10, 1]; // Replace with actual data
        $nilaiPenyusutan = [3, 2, 1, 2, 1, 3, 1]; // Replace with actual data

        // Dapatkan bulan dan tahun saat ini
        $bulan_sekarang = date("n");
        $tahun_sekarang = date("Y");

        $label_arr = $nilai_awal_arr = $nilai_susut_arr = [];

        // Loop dari -12 hingga 0 untuk mendapatkan 12 bulan terakhir
        for ($x = -12; $x <= 0; $x++) {
            // Mengubah bulan sesuai iterasi
            $rubah_bulan = $bulan_sekarang + $x;

            // Mendapatkan timestamp awal bulan
            $unix = mktime(0, 1, 0, $rubah_bulan, 1, $tahun_sekarang);
            $unix_awal = mktime(0, 1, 0, $rubah_bulan + 1, 1, $tahun_sekarang);
            $unix_susut = mktime(0, 1, 0, $rubah_bulan + 1, 1, $tahun_sekarang);

            // Format tanggal untuk label
            $label_arr[] = "'" . date("t M Y", $unix) . "'";

            // Mendapatkan nilai awal dan nilai susut
            $nilai_awal = $this->nilaiAwalBulan($unix_awal);
            $nilai_awal_arr[] = $nilai_awal;

            $nilai_susut = $this->nilaiSusutBulan($unix_susut);

            // Jika nilai susut kurang dari 1, gunakan nilai awal
            $nilai_susut_arr[] = $nilai_susut < 1 ? $nilai_awal : $nilai_susut;
        }

        // Menggabungkan array menjadi string untuk dikirim ke view
        $categories = implode(",", $label_arr);
        $nilaiPerolehan = implode(",", $nilai_awal_arr);
        $nilaiPenyusutan = implode(",", $nilai_susut_arr);

        //grafik jumlah aset perkategori
        $label_jumlahArr = [];
        $data_jumlahArr = [];

        // Count assets without a category (assuming 'kategori_id' is the foreign key)
        $jmlAsetNoKat = $this->jumlahAset('kategori_id', 0);
        if ($jmlAsetNoKat > 0) {
            $label_jumlahArr[] = 'Tak Berkategori';
            $data_jumlahArr[] = $jmlAsetNoKat;
        }

        $kategoris = Kategori::where('status', '1')->get();


        foreach ($kategoris as $category) {
            $jmlAset = $this->jumlahAset('kategori_id', $category->id);
            if ($jmlAset > 0) {
                $label_jumlahArr[] = $category->nama;
                $data_jumlahArr[] = $jmlAset;
            }
        }

        // Prepare data for the chart
        $label_jumlah = json_encode($label_jumlahArr);
        $data_jumlah = json_encode($data_jumlahArr);

        // nilai aset grafik
        $label_nilaiArr = [];
        $data_nilaiArr = [];

        // Calculate total asset value without a category (assuming 'kategori_id' is the foreign key in the Aset model)
        $nilaiAsetNoKat = $this->nilaiAset('kategori_id', 0);
        if ($nilaiAsetNoKat > 0) {
            $label_nilaiArr[] = 'Tak Berkategori';
            $data_nilaiArr[] = $nilaiAsetNoKat;
        }

        foreach ($kategoris as $category) {
            $jmlAset = $this->nilaiAset('kategori_id', $category->id);
            if ($jmlAset > 0) {
                $label_nilaiArr[] = $category->nama;
                $data_nilaiArr[] = $jmlAset;
            }
        }

        // Prepare data for the chart
        $label_nilai = json_encode($label_nilaiArr); // Convert labels to JSON format
        $data_nilai = json_encode($data_nilaiArr);   // Convert data to JSON format
        // dd($data_nilai);

        return view('dashboard.index', compact(
            'agendas',
            'jurnals',
            'histories',
            'transactions',
            'asets',
            'count_aset',
            'asets_limit',
            'totalNilaiNow',
            'totalHargaFormatted',
            'totalPenyusutanFormatted',
            'PenyusutanBulanFormatted',
            'nilaiPerolehan',
            'nilaiPenyusutan',
            'categories',
            'label_jumlah',
            'data_jumlah',
            'label_nilai',
            'data_nilai',
        ));
    }
}
