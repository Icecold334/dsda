<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\FotoPermintaanMaterial;
use Illuminate\Support\Facades\Storage;
use TCPDF;

class ShowPermintaanMaterial extends Component
{

    use WithFileUploads;
    public $permintaan, $isOut = false;

    public $signature;
    protected $listeners = ['signatureSaved'];
    public $attachments = [];
    public $newAttachments = [];



    public function suratJalan()
    {
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Sistem Permintaan Barang');
        $pdf->SetAuthor('Dinas SDA Jakbar');
        $pdf->SetTitle('Surat Jalan');

        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 11);

        // optional kalau ada ttd atau cap
        $ttdPath = storage_path('app/public/ttdPengiriman/nurdin.png');

        $html = view('pdf.surat-jalan', [
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



    public function signatureSaved($signatureData)
    {
        // Decode base64
        $image = str_replace('data:image/png;base64,', '', $signatureData);
        $image = str_replace(' ', '+', $image);
        $imageName = Str::random(20) . '.png';


        Storage::disk('public')->put('ttdPengiriman/' . $imageName, base64_decode($image));
        // Simpan nama file ke database
        $this->signature = $imageName;

        // Contoh simpan ke database (misal di tabel permintaan)
        $this->permintaan->update([
            'ttd_driver' => $imageName,  // pastikan kolom signature tersedia
        ]);
    }

    public function resetSignature()
    {
        $this->signature = null;
    }

    public function mount()
    {
        if ($this->permintaan->lampiran->count()) {
            $this->isOut = true;
        }
        if ($this->permintaan->ttd_driver) {
            # code...
            $this->signature = $this->permintaan->ttd_driver;
        }
    }

    public function saveDoc()
    {
        $this->validate([
            'attachments.*' => 'file|max:5024',  // Validate before saving
        ]);

        $data = [];
        foreach ($this->attachments as $file) {
            $path = str_replace('dokumenKontrak' . '/', '', $file->storeAs('dokumenKontrak' . '', $file->getClientOriginalName(), 'public'));  // Store the file

            $data[] = [
                'detail_permintaan_id' => $this->permintaan->id,
                'path' => $path,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        FotoPermintaanMaterial::insert($data);

        // Optionally reset the attachments after saving
        $this->reset('attachments');
        return redirect()->to('permintaan/permintaan/' . $this->permintaan->id);
    }

    public function updatedNewAttachments()
    {
        // $this->validate([
        //     'newAttachments.*' => 'max:5024', // Validation for each new attachment
        // ]);

        foreach ($this->newAttachments as $file) {
            // $this->attachments[] = $file->store('attachments', 'public');
            $this->attachments[] = $file;
        }

        $this->dispatch('dokumenCount', count: count($this->attachments));

        // Clear the newAttachments to make ready for next files
        $this->reset('newAttachments');
    }
    public function spb()
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('Sistem Permintaan Bahan');
        $pdf->SetAuthor('Dinas SDA Jaktim');
        $pdf->SetTitle('Surat Permohonan Bahan Material');

        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 11);
        $ttdPath = storage_path('app/public/ttdPengiriman/nurdin.png');

        $html = view('pdf.spb', [
            'tanggal' => now()->translatedFormat('d F Y'),
            'lokasi' => 'Perbaikan tutup dan pemasangan tutup saluran',
            'detailLokasi' => 'Jl. Sembilan 3 RT.03 RW.06 Kel. Makasar',
            'kecamatan' => 'Makasar',
            'barang' => [
                ['nama' => 'Semen', 'volume' => 28, 'satuan' => 'Zak'],
                ['nama' => 'Benang Nylon', 'volume' => 4, 'satuan' => 'Gulungan'],
            ],
            'mengetahui' => 'Puryanto Palebangan',
            'pemohon' => 'Nurdin',
            'ttd_pemohon' => $ttdPath, // << kirim path tanda tangan
        ])->render();

        $pdf->writeHTML($html, true, false, true, false, '');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->Output('', 'S');
        }, 'SPB.pdf');
    }

    public function removeAttachment($index)
    {
        if (isset($this->attachments[$index])) {
            // If it's a file path, delete the file
            // Storage::disk('public')->delete($this->attachments[$index]);
            // Remove from the array
            unset($this->attachments[$index]);
            // Reindex array
            $this->attachments = array_values($this->attachments);
            $this->dispatch('dokumenCount', count: count($this->attachments));
        }
    }
    public function render()
    {
        return view('livewire.show-permintaan-material');
    }
}
