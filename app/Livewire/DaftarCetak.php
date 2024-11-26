<?php

namespace App\Livewire;

use TCPDF;
use App\Models\Aset;
use App\Models\Kategori;
use Livewire\Component;
use Livewire\Attributes\On;


class DaftarCetak extends Component
{
    public $selectedAssets = [];
    public $selectedSize = 'none';

    // protected $listeners = [''];
    #[On('tambahAset')]
    public function addAsset($aset)
    {
        // Tambahkan aset tanpa memeriksa ID duplikat
        // Jika $aset sudah berisi data lengkap
        if (is_array($aset) || is_object($aset)) {
            $kategori = Kategori::find($aset['kategori_id']);
            // dd($kategori);
            $this->selectedAssets[] = [
                'id' => $aset['id'] ?? $aset->id,
                'nama' => $aset['nama'] ?? $aset->nama,
                'kategori' => $kategori->nama ?? 'Tidak Berkategori',
            ];
        }
    }

    public function removeAsset($index)
    {
        // Hapus aset berdasarkan indeks
        if (isset($this->selectedAssets[$index])) {
            unset($this->selectedAssets[$index]);
        }

        // Reindeks array agar tetap konsisten
        $this->selectedAssets = array_values($this->selectedAssets);
    }

    public function generatePDF()
    {
        if (empty($this->selectedAssets)) {
            session()->flash('error', 'Tidak ada aset yang dipilih untuk dicetak.');
            return;
        }

        // Initialize TCPDF
        $pdf = new TCPDF();
        $pdf->AddPage();

        // Ukuran QR Code
        $qrSize = match ($this->selectedSize) {
            'small' => 23,
            'medium' => 39,
            'large' => 61,
            default => 23,
        };

        // Margin dan jarak antar elemen
        $marginX = 10; // Margin kiri
        $marginY = 10; // Margin atas
        $spacingX = 50; // Jarak horizontal antar elemen
        $spacingY = 50; // Jarak vertical antar elemen

        // Posisi awal
        $currentX = $marginX;
        $currentY = $marginY;

        foreach ($this->selectedAssets as $index => $asset) {
            // Set posisi untuk nama aset
            $pdf->SetXY($currentX, $currentY);
            $pdf->SetFont('helvetica', 'B', 10);
            $pdf->Write(0, "Aset: {$asset['nama']}", '', 0, 'L', true, 0, false, false, 0);

            // Set posisi untuk ID aset
            $pdf->SetXY($currentX, $currentY + 5);
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Write(0, "ID: {$asset['id']}", '', 0, 'L', true, 0, false, false, 0);

            // Set posisi untuk QR Code (simulasi placeholder)
            $pdf->SetXY($currentX, $currentY + 10);
            $pdf->Cell($qrSize, $qrSize, 'QR', 1, 0, 'C'); // Placeholder QR code

            // Update posisi horizontal
            $currentX += $spacingX;

            // Pindah ke baris berikutnya jika melebihi lebar halaman
            if ($currentX + $qrSize > $pdf->getPageWidth() - $marginX) {
                $currentX = $marginX;
                $currentY += $spacingY;
            }

            // Tambahkan halaman baru jika posisi vertikal melebihi tinggi halaman
            if ($currentY + $spacingY > $pdf->getPageHeight() - $marginY) {
                $pdf->AddPage();
                $currentX = $marginX;
                $currentY = $marginY;
            }
        }

        // Output PDF
        return response()->streamDownload(
            fn() => $pdf->Output('Cetak Qr Code.pdf', 'I'),
            'Cetak Qr Code.pdf'
        );
    }


    public function render()
    {
        return view('livewire.daftar-cetak');
    }
}
