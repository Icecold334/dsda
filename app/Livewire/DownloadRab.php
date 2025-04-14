<?php

namespace App\Livewire;

use Livewire\Component;
use TCPDF;

class DownloadRab extends Component
{
    public $rab;

    public function download()
    {
        $rab = $this->rab;
        // membuat objek TCPDF
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);

        // set informasi dokumen
        $pdf->SetCreator('Aplikasi Anda');
        $pdf->SetAuthor('Aplikasi Anda');
        $pdf->SetTitle('Rencana Anggaran Biaya (RAB)');
        $pdf->SetSubject('RAB');

        // Tambahkan halaman baru
        $pdf->AddPage();

        // Atur font
        $pdf->SetFont('helvetica', '', 12);
        $table = '';

        $no = 1;
        foreach ($this->rab->list as $item) {

            $nama_barang = $item->merkStok->barangStok->nama ?? 'Tanpa Nama Barang';
            $nama = $item->merkStok->nama ?? 'Tanpa merk';
            $tipe = $item->merkStok->tipe ?? 'Tanpa tipe';
            $ukuran = $item->merkStok->ukuran ?? 'Tanpa ukuran';
            $satuan = $item->merkStok->barangStok->satuanBesar->nama ?? '-';

            $table .= '
        <tr>
            <td style="text-align:center;" width="40">' . $no++ . '</td>
            <td width="440">' . $nama_barang . ' - ' . $nama . ' - ' . $tipe . ' - ' . $ukuran . '</td>
            <td width="280" style="text-align:right;">' . $item->jumlah . ' ' . $satuan . '</td>
        </tr>';
        }



        // HTML untuk konten PDF
        $html = '
        <h3 style="text-align:center;">RENCANA ANGGARAN BIAYA (RAB)</h3>
        <br>
        <table border="0">
            <tr>
                <td width="150">PROGRAM</td>
                <td>: ' . $rab->nama . '</td>
            </tr>
            <tr>
                <td>LOKASI KEGIATAN</td>
                <td>: ' . $rab->lokasi . '</td>
            </tr>
            <tr>
                <td>TAHUN ANGGARAN</td>
                <td>: 2025</td>
            </tr>
            <tr>
                <td>UNIT</td>
                <td>: ' . ($rab->user->unitKerja->parent_id ? $rab->user->unitKerja->parent->nama : $rab->user->unitKerja->nama) . '</td>

            </tr>
        </table>
        <br><br>

        <table border="1" cellpadding="4">
            <thead>
                <tr style="text-align:center;">
                    <th width="40">NO</th>
                    <th width="440">BAHAN</th>
                    <th width="280">VOLUME</th>

                </tr>
            </thead>
            <tbody>

                ' . $table . '
                <!-- Lanjutkan baris tabel lainnya sesuai kebutuhan -->
                <tr style="font-weight:bold;">
                    <td colspan="2">JUMLAH  BAHAN</td>
                    <td> ' . $rab->list->sum('jumlah') . ' </td>
                </tr>
            </tbody>
        </table>
        ';

        // menulis HTML ke PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Output PDF
        $filename = 'RAB.pdf';
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->Output('', 'S');
        }, $filename);
    }

    public function render()
    {
        return view('livewire.download-rab');
    }
}
