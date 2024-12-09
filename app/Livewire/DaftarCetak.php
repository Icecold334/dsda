<?php

namespace App\Livewire;

use TCPDF;
use App\Models\Aset;
use App\Models\Option;
use Livewire\Component;
use App\Models\Kategori;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;


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
            $assetData = $this->getAssetWithSettings($aset['id']);
            $this->selectedAssets[] = $assetData;
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

    public function loadOptionSettings()
    {
        // Ambil konfigurasi pengguna berdasarkan user_id atau global (misalnya id=1 untuk default)
        $option = Option::find(1);

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

    public function getAssetWithSettings($assetId)
    {
        // Ambil konfigurasi dari tabel `option`
        $settings = $this->loadOptionSettings();

        // Ambil data aset berdasarkan ID
        $asset = Aset::findOrFail($assetId);

        // Judul
        $judul = $this->resolveOptionValue($settings['qr_judul'], $asset, $settings['qr_judul_other']);

        // Baris 1
        $baris1 = $this->resolveOptionValue($settings['qr_baris1'], $asset, $settings['qr_baris1_other']);

        // Baris 2
        $baris2 = $this->resolveOptionValue($settings['qr_baris2'], $asset, $settings['qr_baris2_other']);


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

    public function resolveOptionValue($optionKey, $asset, $otherValue = null)
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

    public function generateQrImage($asset)
    {
        // Path ke template qrbase
        $qrBasePath = public_path('img/qrbase.png');
        $qrImagePath = storage_path("app/public/qr/{$asset['systemcode']}.png");

        // Periksa apakah file template dan QR Code ada
        if (!file_exists($qrBasePath) || !file_exists($qrImagePath)) {
            return null;
        }

        // Load gambar qrbase dan QR Code
        $qrBase = imagecreatefrompng($qrBasePath);
        $qrImage = imagecreatefrompng($qrImagePath);

        // Set DPI for the image (increasing clarity)
        $dpi = 300; // Set higher DPI for better quality
        $scalingFactor = $dpi / 96; // Default resolution is 96 DPI in most image libraries

        // Tentukan dimensi gambar dan posisi QR Code berdasarkan ukuran yang dipilih
        $qrDimensions = match ($this->selectedSize) {
            'small' => ['qrWidth' => 70 * $scalingFactor, 'qrHeight' => 70 * $scalingFactor, 'padding' => 8 * $scalingFactor, 'fontSize' => 4 * $scalingFactor],
            'medium' => ['qrWidth' => 100 * $scalingFactor, 'qrHeight' => 100 * $scalingFactor, 'padding' => 12 * $scalingFactor, 'fontSize' => 6 * $scalingFactor],
            'large' => ['qrWidth' => 130 * $scalingFactor, 'qrHeight' => 130 * $scalingFactor, 'padding' => 16 * $scalingFactor, 'fontSize' => 8 * $scalingFactor],
            default => ['qrWidth' => 70 * $scalingFactor, 'qrHeight' => 70 * $scalingFactor, 'padding' => 8 * $scalingFactor, 'fontSize' => 4 * $scalingFactor],
        };

        $qrWidth = $qrDimensions['qrWidth'];
        $qrHeight = $qrDimensions['qrHeight'];
        $padding = $qrDimensions['padding'];
        $fontSize = $qrDimensions['fontSize'];

        // Dynamically resize the qrbase template to accommodate QR and text
        $baseWidth = $qrWidth + $padding * 2;
        $baseHeight = $qrHeight + $padding * 4; // Extra space for text
        $newQrBase = imagecreatetruecolor($baseWidth, $baseHeight);

        // Fill the background with white
        $white = imagecolorallocate($newQrBase, 255, 255, 255);
        imagefill($newQrBase, 0, 0, $white);

        // Copy the original qrbase into the new scaled template
        imagecopyresampled(
            $newQrBase,
            $qrBase,
            0,
            0,
            0,
            0,
            $baseWidth,
            $baseHeight,
            imagesx($qrBase),
            imagesy($qrBase)
        );

        // Posisi QR Code dalam template
        $qrX = ($baseWidth - $qrWidth) / 2;
        $qrY = $padding + 6;

        // Gabungkan QR Code ke dalam template
        imagecopyresampled(
            $newQrBase,
            $qrImage,
            $qrX,
            $qrY,
            0,
            0,
            $qrWidth,
            $qrHeight,
            imagesx($qrImage),
            imagesy($qrImage)
        );

        // Warna teks
        $black = imagecolorallocate($newQrBase, 0, 0, 0);

        // Path font
        $fontPath = public_path('fonts/courbd.ttf'); // Ganti dengan path font Anda
        $fontPath_line2 = public_path('fonts/cour.ttf'); // Ganti dengan path font Anda

        // Tambahkan judul (misal dari option)
        $judul = $asset['judul'] ?? 'QR Code Title';
        $judulX = ($baseWidth - imagettfbbox($fontSize, 0, $fontPath, $judul)[2]) / 2;
        imagettftext($newQrBase, $fontSize, 0, $judulX, $qrY - 10, $white, $fontPath, $judul);

        // Tambahkan baris 1
        $baris1 = $asset['baris1'] ?? 'Line 1';
        $baris1X = ($baseWidth - imagettfbbox($fontSize, 0, $fontPath, $baris1)[2]) / 2;
        imagettftext($newQrBase, $fontSize, 0, $baris1X, $qrY + $qrHeight + $padding, $black, $fontPath, $baris1);

        // Calculate the vertical position for the second line based on the selected size
        $line2Padding = $this->selectedSize === 'small' ? $padding + 20 : $padding + 25;

        // Add the second line of text
        $baris2 = $asset['baris2'] ?? 'Line 2';
        $baris2X = ($baseWidth - imagettfbbox($fontSize, 0, $fontPath, $baris2)[2]) / 2;
        imagettftext($newQrBase, $fontSize, 0, $baris2X, $qrY + $qrHeight + $line2Padding, $black, $fontPath_line2, $baris2);


        // Simpan gambar ke path baru
        $outputPath = storage_path("app/public/qr/rendered_{$asset['systemcode']}.png");
        imagepng($newQrBase, $outputPath);

        // Hapus dari memori
        imagedestroy($newQrBase);
        imagedestroy($qrBase);
        imagedestroy($qrImage);

        return $outputPath;
    }

    // public function generatePDF()
    // {
    //     if (empty($this->selectedAssets)) {
    //         session()->flash('error', 'Tidak ada aset yang dipilih untuk dicetak.');
    //         return;
    //     }

    //     // Initialize TCPDF
    //     $pdf = new TCPDF();
    //     $pdf->SetMargins(0, 0, 0); // Set margin ke 0
    //     $pdf->SetAutoPageBreak(true, 0);
    //     $pdf->AddPage();

    //     // Path ke template border
    //     $borderPath = public_path('img/qrbase.png');

    //     // Ukuran dan posisi elemen
    //     $qrSize = 50; // Ukuran QR Code
    //     $marginX = 10; // Margin kiri
    //     $marginY = 10; // Margin atas
    //     $spacingX = 10; // Jarak horizontal antar elemen
    //     $spacingY = 10; // Jarak vertikal antar elemen

    //     $currentX = $marginX;
    //     $currentY = $marginY;



    //     foreach ($this->selectedAssets as $asset) {
    //         // Tambahkan template border
    //         $pdf->Image($borderPath, $currentX, $currentY, 70, 90, 'PNG'); // Ukuran border disesuaikan dengan gambar

    //         // Path QR Code
    //         $qrPath = public_path("storage/qr/{$asset['systemcode']}.png");

    //         // Tambahkan QR Code
    //         if (file_exists($qrPath)) {
    //             $qrX = $currentX + 10; // Posisi QR di dalam border
    //             $qrY = $currentY + 20; // Posisi QR di dalam border
    //             $pdf->Image($qrPath, $qrX, $qrY, $qrSize, $qrSize, 'PNG');
    //         }

    //         $pdf->SetFont('helvetica', 'B', 10);
    //         $pdf->SetXY($currentX + 5, $currentY + 72);
    //         $pdf->Cell(60, 5, $asset['nama'], 0, 0, 'C');


    //         $pdf->SetFont('helvetica', '', 9);
    //         $pdf->SetXY($currentX + 5, $currentY + 78);
    //         $pdf->Cell(60, 5, $asset['systemcode'], 0, 0, 'C');

    //         // Tambahkan footer (e.g., www.inventa.id)
    //         // $pdf->SetFont('helvetica', 'I', 8);
    //         // $pdf->SetXY($currentX + 5, $currentY + 85);
    //         // $pdf->Cell(60, 5, 'www.inventa.id', 0, 0, 'C');

    //         // Update posisi horizontal
    //         $currentX += 80; // Lebar border (70) + spacing (10)

    //         // Pindah ke baris berikutnya jika melebihi lebar halaman
    //         if ($currentX + 70 > $pdf->getPageWidth()) {
    //             $currentX = $marginX;
    //             $currentY += 100; // Tinggi border (90) + spacing (10)
    //         }

    //         // Tambahkan halaman baru jika posisi vertikal melebihi tinggi halaman
    //         if ($currentY + 100 > $pdf->getPageHeight()) {
    //             $pdf->AddPage();
    //             $currentX = $marginX;
    //             $currentY = $marginY;
    //         }
    //     }

    //     // Output PDF langsung di browser
    //     // $pdf->Output('Cetak_QR_Code.pdf', 'I');
    //     // Output PDF
    //     return response()->streamDownload(
    //         fn() => $pdf->Output('Cetak Qr Code.pdf', 'I'),
    //         'Cetak Qr Code.pdf'
    //     );
    // }

    public function generatePDF()
    {
        if (empty($this->selectedAssets)) {
            session()->flash('error', 'Tidak ada aset yang dipilih untuk dicetak.');
            return;
        }

        // Inisialisasi TCPDF
        $pdf = new \TCPDF();
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->AddPage();

        // Tentukan margin dan ukuran gambar berdasarkan ukuran yang dipilih
        $dimensions = match ($this->selectedSize) {
            'small' => ['imageWidth' => 30, 'imageHeight' => 40, 'marginX' => 10, 'marginY' => 10, 'spacingX' => 1, 'spacingY' => 3],
            'medium' => ['imageWidth' => 50, 'imageHeight' => 65, 'marginX' => 10, 'marginY' => 10, 'spacingX' => 1, 'spacingY' => 3],
            'large' => ['imageWidth' => 80, 'imageHeight' => 95, 'marginX' => 10, 'marginY' => 10, 'spacingX' => 1, 'spacingY' => 3],
            default => ['imageWidth' => 30, 'imageHeight' => 40, 'marginX' => 10, 'marginY' => 10, 'spacingX' => 1, 'spacingY' => 3],
        };

        $imageWidth = $dimensions['imageWidth'];
        $imageHeight = $dimensions['imageHeight'];
        $marginX = $dimensions['marginX'];
        $marginY = $dimensions['marginY'];
        $spacingX = $dimensions['spacingX'];
        $spacingY = $dimensions['spacingY'];

        $currentX = $marginX;
        $currentY = $marginY;

        foreach ($this->selectedAssets as $asset) {
            $qrImagePath = $this->generateQrImage($asset);

            if ($qrImagePath && file_exists($qrImagePath)) {
                $pdf->Image($qrImagePath, $currentX, $currentY, $imageWidth, $imageHeight, 'PNG');
            }

            // Perbarui posisi horizontal
            $currentX += $imageWidth + $spacingX;

            // Jika posisi horizontal melebihi lebar halaman, pindah ke baris berikutnya
            if ($currentX + $imageWidth > $pdf->getPageWidth() - $marginX) {
                $currentX = $marginX;
                $currentY += $imageHeight + $spacingY;
            }

            // Tambahkan halaman baru jika posisi vertikal melebihi tinggi halaman
            if ($currentY + $imageHeight + $spacingY > $pdf->getPageHeight() - $marginY) {
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
