<?php

namespace App\Livewire;

use TCPDF;
use App\Models\User;
use Livewire\Component;
use App\Models\UnitKerja;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use App\Models\FotoPermintaanMaterial;
use Illuminate\Support\Facades\Storage;

class ShowPermintaanMaterial extends Component
{

    use WithFileUploads;
    public $permintaan, $isOut = false, $Rkb, $RKB, $sudin, $isSeribu, $withRab;

    public $signature, $securitySignature;
    protected $listeners = ['signatureSaved'];
    public $attachments = [];
    public $newAttachments = [];



    public function suratJalan($sign)
    {
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetMargins(20, 5, 20);

        $pdf->SetCreator('Sistem Permintaan Barang');
        $pdf->SetAuthor('Dinas SDA');
        $pdf->SetTitle('Surat Jalan');

        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 11);

        // optional kalau ada ttd atau cap
        $ttdPath = storage_path('app/public/ttdPengiriman/nurdin.png');

        $permintaan = $this->permintaan;
        $unit_id = $this->unit_id;
        $permintaan->unit = UnitKerja::find($unit_id);
        $kasatpel =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('id', $unit_id);
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Kepala Satuan Pelaksana%');
            })->first();
        $penjaga =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('id', $unit_id);
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Penjaga Gudang%');
            })->where('lokasi_id', $this->permintaan->gudang_id)->first();
        $pengurus =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('id', $unit_id);
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Pengurus Barang%');
            })->first();
        $Rkb = $this->Rkb;
        $RKB = $this->RKB;
        $sudin = $this->sudin;
        $isSeribu = $this->isSeribu;
        $html = view('pdf.surat-jalan', compact('permintaan', 'kasatpel', 'penjaga', 'pengurus', 'ttdPath', 'Rkb', 'RKB', 'sudin', 'isSeribu', 'sign'))->render();

        $pdf->writeHTML($html, true, false, true, false, '');
        $this->statusRefresh();
        // return 1;
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->Output('', 'S');
        }, 'Surat-Jalan.pdf');
    }
    public function bast($sign)
    {
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetMargins(20, 5, 20);

        $pdf->SetCreator('Sistem Permintaan Barang');
        $pdf->SetAuthor('Dinas SDA');
        $pdf->SetTitle('Berita Acara Serah Terima Barang');

        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        // optional kalau ada ttd atau cap
        $ttdPath = storage_path('app/public/ttdPengiriman/nurdin.png');

        $permintaan = $this->permintaan;
        $unit_id = $this->unit_id;
        $permintaan->unit = UnitKerja::find($unit_id);
        $kasatpel =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('id', $unit_id);
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Kepala Satuan Pelaksana%');
            })->first();
        $penjaga =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('id', $unit_id);
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Penjaga Gudang%');
            })->where('lokasi_id', $this->permintaan->gudang_id)->first();
        $pengurus =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('id', $unit_id);
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Pengurus Barang%');
            })->first();
        $Rkb = $this->Rkb;
        $RKB = $this->RKB;
        $sudin = $this->sudin;
        $isSeribu = $this->isSeribu;
        $html = view('pdf.bastKeluar', compact('permintaan', 'kasatpel', 'penjaga', 'pengurus', 'ttdPath', 'Rkb', 'RKB', 'sudin', 'isSeribu', 'sign'))->render();

        $pdf->writeHTML($html, true, false, true, false, '');
        $this->statusRefresh();
        // return 1;
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->Output('', 'S');
        }, 'BAST.pdf');
    }



    public function signatureSaved($signatureData, $type)
    {
        // Decode base64
        $image = str_replace('data:image/png;base64,', '', $signatureData);
        $image = str_replace(' ', '+', $image);
        $imageName = Str::random(20) . '.png';


        Storage::disk('public')->put('ttdPengiriman/' . $imageName, base64_decode($image));
        // Simpan nama file ke database

        if ($type == 'driver') {
            $this->signature = $imageName;
            $this->permintaan->update([
                'ttd_driver' => $imageName,  // pastikan kolom signature tersedia
            ]);
        } else {
            $this->securitySignature = $imageName;
            $this->permintaan->update([
                'ttd_security' => $imageName,  // pastikan kolom signature tersedia
            ]);
        }
        $this->statusRefresh();
    }

    public function resetSignature($type)
    {
        if ($type == 'driver') {
            # code...
            $this->signature = null;
        } else {
            $this->securitySignature = null;
        }
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
        if ($this->permintaan->ttd_security) {
            # code...
            $this->securitySignature = $this->permintaan->ttd_security;
        }
        if ($this->permintaan->saluran_jenis) {
            switch ($this->permintaan->saluran_jenis) {
                case 'tersier':
                    $keySaluran = 'idPhb';
                    $namaSaluran = 'namaPhb';
                    break;
                case 'sekunder':
                    $keySaluran = 'idAliran';
                    $namaSaluran = 'namaSungai';
                    break;
                case 'primer':
                    $keySaluran = 'idPrimer';
                    $namaSaluran = 'namaSungai';
                    break;

                default:
                    $keySaluran = 'null';
                    break;
            }

            $this->permintaan->saluran_nama = collect(app('JakartaDataset')[$this->permintaan->saluran_jenis])->where($keySaluran, $this->permintaan->saluran_id)->first()[$namaSaluran];
        }
        if ($this->permintaan->rab_id && $this->permintaan->rab->saluran_jenis) {
            switch ($this->permintaan->rab->saluran_jenis) {
                case 'tersier':
                    $keySaluran = 'idPhb';
                    $namaSaluran = 'namaPhb';
                    break;
                case 'sekunder':
                    $keySaluran = 'idAliran';
                    $namaSaluran = 'namaSungai';
                    break;
                case 'primer':
                    $keySaluran = 'idPrimer';
                    $namaSaluran = 'namaSungai';
                    break;

                default:
                    $keySaluran = 'null';
                    break;
            }
            $this->permintaan->saluran_nama = collect(app('JakartaDataset')[$this->permintaan->rab->saluran_jenis])->where($keySaluran, $this->permintaan->rab->saluran_id)->first()[$namaSaluran];
        }
        $this->statusRefresh();
    }

    public function statusRefresh()
    {
        $statusMap = [
            null => ['label' => 'Diproses', 'color' => 'warning'],
            0 => ['label' => 'Ditolak', 'color' => 'danger'],
            1 => ['label' => 'Disetujui', 'color' => 'success'],
            2 => ['label' => 'Sedang Dikirim', 'color' => 'info'],
            3 => ['label' => 'Selesai', 'color' => 'primary'],
        ];

        // Tambahkan properti dinamis
        $this->permintaan->status_teks = $statusMap[$this->permintaan->status]['label'] ?? 'Tidak diketahui';
        $this->permintaan->status_warna = $statusMap[$this->permintaan->status]['color'] ?? 'gray';

        if ($this->isSeribu) {
            $this->withRab = $this->permintaan->permintaanMaterial->first()->rab_id;
        } else {
            $this->withRab = $this->permintaan->rab_id;
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

    public function sppb($sign)
    {
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetMargins(20, 5, 20);

        $pdf->SetCreator('Sistem Permintaan Barang');
        $pdf->SetAuthor('Dinas SDA');
        $pdf->SetTitle('SPPB');

        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        // optional kalau ada ttd atau cap
        $ttdPath = storage_path('app/public/ttdPengiriman/nurdin.png');

        $permintaan = $this->permintaan;
        $unit_id = $this->unit_id;
        $permintaan->unit = UnitKerja::find($unit_id);
        $kasatpel =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('id', $unit_id);
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Kepala Satuan Pelaksana%');
            })->first();
        $penjaga =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('id', $unit_id);
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Penjaga Gudang%');
            })->where('lokasi_id', $this->permintaan->gudang_id)->first();
        $pengurus =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('id', $unit_id);
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Pengurus Barang%');
            })->first();

        $kasubag =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('parent_id', $unit_id)->where('nama', 'like', '%Tata Usaha%');
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Kepala Subbagian%');
            })->first();
        $Rkb = $this->Rkb;
        $RKB = $this->RKB;
        $sudin = $this->sudin;
        $isSeribu = $this->isSeribu;
        $html = view('pdf.sppb', compact('permintaan', 'kasatpel', 'penjaga', 'sign', 'pengurus', 'ttdPath', 'kasubag', 'Rkb', 'RKB', 'sudin', 'isSeribu'))->render();

        $pdf->writeHTML($html, true, false, true, false, '');
        $this->statusRefresh();
        // return 1;
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->Output('', 'S');
        }, 'SPPB.pdf');
    }
    public function qrCode()
    {
        $kode = $this->permintaan->kode_permintaan;

        // Path filesystem yang benar
        $path = storage_path('app/public/qr_permintaan_material/' . $kode . '.png');

        if (!file_exists($path)) {
            abort(404, 'QR Code not found.');
        }
        $this->statusRefresh();

        return response()->download($path, $kode . '.png');
    }

    public function spb($sign = false)
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        // Set margin (Left, Top, Right)
        $pdf->SetMargins(20, 5, 20);
        $pdf->SetCreator('Sistem Permintaan Bahan');
        $pdf->SetAuthor('Dinas SDA');
        $pdf->SetTitle('Surat Permintaan Barang Material');

        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 11, '', '',);
        $ttdPath = storage_path('app/public/ttdPengiriman/nurdin.png');

        $permintaan = $this->permintaan;
        $unit_id = $this->unit_id;
        $permintaan->unit = UnitKerja::find($unit_id);

        $kasatpel =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('id', $unit_id);
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Kepala Satuan Pelaksana%');
            })->first();
        $pemel =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('parent_id', $unit_id)->where('nama', 'like', '%Pemeliharaan%');
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Kepala Seksi%');
            })->first();

        $Rkb = $this->Rkb;
        $RKB = $this->RKB;
        $sudin = $this->sudin;
        $isSeribu = $this->isSeribu;
        if ($isSeribu) {
            $withRab = $this->permintaan->permintaanMaterial->first()->rab_id;
        } else {
            $withRab = $this->permintaan->rab_id;
        }
        // dd(
        //     !$withRab ? 'pdf.nodin' : ($this->isSeribu ? 'pdf.spb1000' : 'pdf.spb')
        // );
        $html = view(!$withRab ? 'pdf.nodin' : ($this->isSeribu ? 'pdf.spb1000' : 'pdf.spb'), compact('ttdPath', 'permintaan', 'kasatpel', 'pemel', 'Rkb', 'RKB', 'sudin', 'isSeribu', 'sign'))->render();

        $pdf->writeHTML($html, true, false, true, false, '');
        $this->statusRefresh();
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->Output('', 'S');
        }, 0 ? 'Nota Dinas.pdf' : 'Surat Permintaan Barang.pdf');
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

    public function downloadDoc($params)
    {
        $type = $params['type'];
        $withSign = $params['withSign'];
        switch ($type) {
            case 'spb':
                return $this->spb($withSign);
            case 'sppb':
                return $this->sppb($withSign);
            case 'suratJalan':
                return $this->suratJalan($withSign);
            case 'bast':
                return $this->bast($withSign);
        }
    }

    public function uploadDokumen($type, $fileDataUrl, $originalName)
    {
        // Ambil extension
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        // Validasi ekstensi
        if (!in_array($extension, ['pdf', 'jpg', 'jpeg', 'png'])) {
            session()->flash('error', 'Format file tidak diperbolehkan.');
            return;
        }

        // Decode base64 data
        $fileData = explode(',', $fileDataUrl);
        if (count($fileData) < 2) {
            session()->flash('error', 'File tidak valid.');
            return;
        }

        $filename = $type . '_' . time() . '.' . $extension;
        $filePath = "{$type}/{$filename}";
        Storage::disk('public')->put($filePath, base64_decode($fileData[1]));
        $this->permintaan->update([
            "{$type}_path" => $filename
        ]);
        $this->statusRefresh();

        session()->flash('success', strtoupper($type) . ' berhasil diunggah.');
    }


    public function render()
    {
        return view('livewire.show-permintaan-material');
    }
}
