<?php

namespace App\Livewire;

use Livewire\Component;

class PdfForm extends Component
{
    public $permintaan;


    public function mount($permintaan)
    {
        $this->permintaan = $permintaan;
    }

    public function UnduhPDF()
    {
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Sistem Permintaan Barang');
        $pdf->SetAuthor('Dinas SDA Jakbar');
        $pdf->SetTitle('Surat Jalan');

        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 11);

        // optional kalau ada ttd atau cap
        $ttdPath = storage_path('app/public/ttdPengiriman/nurdin.png');

        $html = view('pdf.form-umum', [
            'no_surat' => '8201/3.01.01',
            'lokasi' => 'Jl. Terusan Meruya, Kel Meruya Utara, Kec. Kembangan',
            'nama_barang' => 'Semen',
            'volume' => '6 Zak',
            'tanggal' => now()->format('d-m-Y'),
            'penerima' => 'Asep Sugara',
            'pengeluar' => 'Ahmad M.',
            'pengurus' => 'Sigit Rendang',
            'ttd_pengeluar' => $ttdPath, // opsional
        ])->render();

        $pdf->writeHTML($html, true, false, true, false, '');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->Output('', 'S');
        }, 'Surat-Jalan.pdf');
    }

    public function render()
    {
        return view('livewire.pdf-form');
    }
}
