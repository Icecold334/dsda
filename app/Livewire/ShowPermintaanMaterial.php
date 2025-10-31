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
    public $alert = null; // Tambahan untuk alert

    public $signature, $securitySignature;
    public $selectedDriverId, $selectedSecurityId, $inputNopol;
    protected $listeners = ['signatureSaved'];
    public $attachments = [];
    public $newAttachments = [];

    // Edit mode properties
    public $isEditMode = false;
    public $editDriverId, $editSecurityId, $editNopol;



    public function suratJalan($sign)
    {
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetMargins(10, 5, 10);

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
        $pemohon = $permintaan->user;
        $pemohonRole = $pemohon->roles->pluck('name')->first(); // ambil 1 role
        $html = view('pdf.surat-jalan', compact('permintaan', 'kasatpel', 'penjaga', 'pemohon', 'pemohonRole', 'pengurus', 'ttdPath', 'Rkb', 'RKB', 'sudin', 'isSeribu', 'sign'))->render();

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

    public function saveDriverInfo()
    {
        $this->validate([
            'selectedDriverId' => 'required',
            'selectedSecurityId' => 'required',
            'inputNopol' => 'required|string'
        ]);

        $driver = \App\Models\Driver::find($this->selectedDriverId);
        $security = \App\Models\Security::find($this->selectedSecurityId);

        $this->permintaan->update([
            'driver' => $driver->nama,
            'security' => $security->nama,
            'nopol' => $this->inputNopol
        ]);

        $this->reset(['selectedDriverId', 'selectedSecurityId', 'inputNopol']);

        // Dispatch event to refresh the approval component
        $this->dispatch('driverInfoSaved');

        $this->statusRefresh();

        session()->flash('message', 'Data pengiriman berhasil disimpan.');
    }

    public function enableEditMode()
    {
        // Set edit mode values with current data
        $this->isEditMode = true;
        $this->editNopol = $this->permintaan->nopol;

        // Find driver and security IDs based on current names
        $driver = \App\Models\Driver::where('nama', $this->permintaan->driver)
            ->where('unit_id', auth()->user()->unit_id)
            ->first();
        $security = \App\Models\Security::where('nama', $this->permintaan->security)
            ->where('unit_id', auth()->user()->unit_id)
            ->first();

        $this->editDriverId = $driver ? $driver->id : null;
        $this->editSecurityId = $security ? $security->id : null;
    }

    public function cancelEdit()
    {
        $this->isEditMode = false;
        $this->reset(['editDriverId', 'editSecurityId', 'editNopol']);
    }

    public function updateDriverInfo()
    {
        $this->validate([
            'editDriverId' => 'required',
            'editSecurityId' => 'required',
            'editNopol' => 'required|string'
        ], [
            'editDriverId.required' => 'Driver harus dipilih',
            'editSecurityId.required' => 'Security harus dipilih',
            'editNopol.required' => 'Nomor polisi harus diisi'
        ]);

        $driver = \App\Models\Driver::find($this->editDriverId);
        $security = \App\Models\Security::find($this->editSecurityId);

        $this->permintaan->update([
            'driver' => $driver->nama,
            'security' => $security->nama,
            'nopol' => $this->editNopol
        ]);

        $this->isEditMode = false;
        $this->reset(['editDriverId', 'editSecurityId', 'editNopol']);

        $this->statusRefresh();

        session()->flash('message', 'Data pengiriman berhasil diperbarui.');
    }

    public function deleteAttachment($attachmentId)
    {
        $attachment = FotoPermintaanMaterial::find($attachmentId);

        if ($attachment && $attachment->permintaan_id == $this->permintaan->id) {
            // Delete file from storage
            if (Storage::disk('public')->exists('dokumenKontrak/' . $attachment->path)) {
                Storage::disk('public')->delete('dokumenKontrak/' . $attachment->path);
            }

            // Delete record from database
            $attachment->delete();

            session()->flash('message', 'Foto berhasil dihapus.');
        }
    }

    public function resetSignatureDriver()
    {
        if ($this->permintaan->ttd_driver) {
            // Delete file from storage
            if (Storage::disk('public')->exists('ttdPengiriman/' . $this->permintaan->ttd_driver)) {
                Storage::disk('public')->delete('ttdPengiriman/' . $this->permintaan->ttd_driver);
            }

            // Update database
            $this->permintaan->update(['ttd_driver' => null]);
            $this->signature = null;

            $this->dispatch('signatureReset');
        }
    }

    public function resetSignatureSecurity()
    {
        if ($this->permintaan->ttd_security) {
            // Delete file from storage
            if (Storage::disk('public')->exists('ttdPengiriman/' . $this->permintaan->ttd_security)) {
                Storage::disk('public')->delete('ttdPengiriman/' . $this->permintaan->ttd_security);
            }

            // Update database
            $this->permintaan->update(['ttd_security' => null]);
            $this->securitySignature = null;

            $this->dispatch('signatureReset');
        }
    }

    public function retrySignatureDriver()
    {
        // Hanya Pengurus Barang yang bisa mengulang TTD saat status sedang dikirim
        if (!auth()->user()->hasRole('Pengurus Barang') || $this->permintaan->status != 2) {
            session()->flash('error', 'Anda tidak memiliki akses untuk mengulang tanda tangan.');
            return;
        }

        if ($this->permintaan->ttd_driver) {
            // Delete old signature file from storage
            if (Storage::disk('public')->exists('ttdPengiriman/' . $this->permintaan->ttd_driver)) {
                Storage::disk('public')->delete('ttdPengiriman/' . $this->permintaan->ttd_driver);
            }

            // Reset signature in database
            $this->permintaan->update(['ttd_driver' => null]);
            $this->signature = null;

            $this->dispatch('signatureReset');
            session()->flash('message', 'Silakan buat tanda tangan driver yang baru.');
        }
    }

    public function retrySignatureSecurity()
    {
        // Hanya Pengurus Barang yang bisa mengulang TTD saat status sedang dikirim
        if (!auth()->user()->hasRole('Pengurus Barang') || $this->permintaan->status != 2) {
            session()->flash('error', 'Anda tidak memiliki akses untuk mengulang tanda tangan.');
            return;
        }

        if ($this->permintaan->ttd_security) {
            // Delete old signature file from storage
            if (Storage::disk('public')->exists('ttdPengiriman/' . $this->permintaan->ttd_security)) {
                Storage::disk('public')->delete('ttdPengiriman/' . $this->permintaan->ttd_security);
            }

            // Reset signature in database
            $this->permintaan->update(['ttd_security' => null]);
            $this->securitySignature = null;

            $this->dispatch('signatureReset');
            session()->flash('message', 'Silakan buat tanda tangan security yang baru.');
        }
    }

    public function mount()
    {
        // Cek apakah ada alert dari session untuk SweetAlert
        if (session('alert') && is_array(session('alert'))) {
            $this->alert = session('alert');
            // Dispatch event untuk SweetAlert
            $this->dispatch(
                'showAlert',
                type: $this->alert['type'],
                message: $this->alert['message']
            );
        }

        // Tambahkan status_teks ke permintaan jika belum ada
        if (!isset($this->permintaan->status_teks)) {
            $statusMap = [
                null => ['label' => 'Diproses', 'color' => 'warning'],
                0 => ['label' => 'Ditolak', 'color' => 'danger'],
                1 => ['label' => 'Disetujui', 'color' => 'success'],
                2 => ['label' => 'Sedang Dikirim', 'color' => 'info'],
                3 => ['label' => 'Selesai', 'color' => 'primary'],
                4 => ['label' => 'Draft', 'color' => 'secondary'],
            ];
            $this->permintaan->status_teks = $statusMap[$this->permintaan->status]['label'] ?? 'Tidak diketahui';
        }

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

            $saluran = collect(app('JakartaDataset')[$this->permintaan->saluran_jenis])->where($keySaluran, $this->permintaan->saluran_id)->first();
            $this->permintaan->saluran_nama = $saluran[$namaSaluran];
            $this->permintaan->p_saluran = $saluran['panjang'];
            $this->permintaan->l_saluran = $saluran['lebar'];
            $this->permintaan->k_saluran = $saluran['kedalaman'];
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
            4 => ['label' => 'Draft', 'color' => 'secondary'],
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
        $pdf->SetMargins(10, 5, 10);

        $pdf->SetCreator('Sistem Permintaan Barang');
        $pdf->SetAuthor('Dinas SDA');
        $pdf->SetTitle('SPPB');

        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        $permintaan = $this->permintaan;
        $unit_id = $this->unit_id;
        $permintaan->unit = UnitKerja::find($unit_id);

        $kasatpel = User::whereHas('unitKerja', function ($unit) use ($unit_id) {
            return $unit->where('id', $unit_id);
        })->whereHas('roles', function ($role) {
            return $role->where('name', 'like', '%Kepala Satuan Pelaksana%');
        })->first();

        $kasubag = User::whereHas('unitKerja', function ($unit) use ($unit_id) {
            return $unit->where('parent_id', $unit_id)->where('nama', 'like', '%Tata Usaha%');
        })->whereHas('roles', function ($role) {
            return $role->where('name', 'like', '%Kepala Subbagian%');
        })->first();

        // Get requester and determine signature roles based on requester's role
        $pemohon = $permintaan->user;
        $isKasatpel = $pemohon->hasRole('Kepala Satuan Pelaksana') || $pemohon->roles->contains(function ($role) {
            return str_contains($role->name, 'Kepala Satuan Pelaksana') || str_contains($role->name, 'Ketua Satuan Pelaksana');
        });
        $isKepalaSeksi = $pemohon->hasRole('Kepala Seksi') || $pemohon->roles->contains(function ($role) {
            return str_contains($role->name, 'Kepala Seksi');
        });

        // Get Kepala Seksi Pemeliharaan for when requester is Kasatpel
        $kepalaSeksiPemeliharaan = User::whereHas('unitKerja', function ($unit) use ($unit_id) {
            return $unit->where('parent_id', $unit_id)->where('nama', 'like', '%Pemeliharaan%');
        })->whereHas('roles', function ($role) {
            return $role->where('name', 'like', '%Kepala Seksi%');
        })->first();

        // BYPASS: Untuk periode 12-19 Agustus 2025, jika ada approval dari Yusuf (252),
        // maka dia yang akan ditampilkan sebagai Kepala Seksi Pemeliharaan di SPPB
        $transferPeriodApproval = $this->permintaan->persetujuan()
            ->where('user_id', 252)
            ->where('is_approved', 1)
            ->whereBetween('created_at', ['2025-08-12 00:00:00', '2025-08-19 23:59:59'])
            ->first();

        if ($transferPeriodApproval) {
            $kepalaSeksiPemeliharaan = User::find(252); // Override dengan Yusuf
        }

        // Get Kepala Suku Dinas for when requester is Kepala Seksi
        $kepalaSudin = User::whereHas('unitKerja', function ($unit) use ($unit_id) {
            return $unit->where('id', $unit_id);
        })->whereHas('roles', function ($role) {
            return $role->where('name', 'like', '%Kepala Suku Dinas%');
        })->first();

        $Rkb = $this->Rkb;
        $RKB = $this->RKB;
        $sudin = $this->sudin;
        $isSeribu = $this->isSeribu;

        // ===== FIX: Generate view HTML dengan semua variabel yang dibutuhkan =====
        $html = view('pdf.sppb', compact(
            'permintaan',
            'kasatpel',
            'sign',        // Pastikan variabel $sign dipass ke view
            'kasubag',
            'Rkb',
            'RKB',
            'sudin',
            'isSeribu',
            'pemohon',
            'isKasatpel',
            'isKepalaSeksi',
            'kepalaSeksiPemeliharaan',
            'kepalaSudin'
        ))->render();

        $pdf->writeHTML($html, true, false, true, false, '');
        $this->statusRefresh();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->Output('', 'S');
        }, 'SPPB.pdf');
    }
    public function qrCode()
    {
        $kode = $this->permintaan->kode_permintaan;
        // Path filesystem yang benar
        $path = storage_path('app/public/qr_permintaan_material/' . $kode . '.png');
        // dd($path);

        // if (!file_exists($path)) {
        //     abort(404, 'QR Code not found.');
        // }
        $this->statusRefresh();

        return response()->download($path, $kode . '.png');
    }

    public function spb($sign = false)
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetMargins(10, 5, 10);
        $pdf->SetCreator('Sistem Permintaan Bahan');
        $pdf->SetAuthor('Dinas SDA');
        $pdf->SetTitle('Surat Permintaan Barang Material');

        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 11, '', '');


        // DIUBAH: Tambahkan 'user.unitKerja.parent' untuk eager load
        $permintaan = $this->permintaan->fresh(['kelurahan.kecamatan', 'rab', 'user.unitKerja.parent']);
        $pemohon = $permintaan->user;
        $unitKerja = $pemohon ? $pemohon->unitKerja : null;
        $unit_id = $unitKerja ? $unitKerja->id : null;

        $lokasiLengkap = '';
        if (!$permintaan->rab_id) {
            if ($permintaan->kelurahan && $permintaan->kelurahan->kecamatan) {
                $lokasiLengkap .= 'Kelurahan ' . $permintaan->kelurahan->nama . ', ';
                $lokasiLengkap .= 'Kecamatan ' . $permintaan->kelurahan->kecamatan->kecamatan . ' – ';
            }
            $lokasiLengkap .= $permintaan->lokasi;
        } else {
            // Pastikan relasi rab tidak null sebelum diakses
            if ($permintaan->rab) {
                $lokasiLengkap = $permintaan->rab->lokasi;
            }
        }
        $permintaan->unit = $unitKerja;

        $kasatpel = User::whereHas('unitKerja', function ($unit) use ($unit_id) {
            return $unit->where('id', $unit_id);
        })->whereHas('roles', function ($role) {
            return $role->where('name', 'like', '%Kepala Satuan Pelaksana%');
        })->first();

        $pemel = User::whereHas('unitKerja', function ($unit) use ($unit_id) {
            return $unit->where('parent_id', $unit_id)->where('nama', 'like', '%Pemeliharaan%');
        })->whereHas('roles', function ($role) {
            return $role->where('name', 'like', '%Kepala Seksi%');
        })->first();

        // BYPASS: Untuk periode 12-19 Agustus 2025, jika ada approval dari Yusuf (252),
        // maka dia yang akan ditampilkan sebagai pemel (kepala seksi pemeliharaan)
        $transferPeriodApproval = $this->permintaan->persetujuan()
            ->where('user_id', 252)
            ->where('is_approved', 1)
            ->whereBetween('created_at', ['2025-08-12 00:00:00', '2025-08-19 23:59:59'])
            ->first();

        if ($transferPeriodApproval) {
            $pemel = User::find(252); // Override dengan Yusuf
        }

        // $pemohon = $permintaan->user;
        $pemohonRole = $pemohon->roles->pluck('name')->first();

        $Rkb = $this->Rkb;
        $RKB = $this->RKB;
        $sudin = $unitKerja ? $unitKerja->nama : null;
        $isSeribu = $unitKerja ? str_contains($unitKerja->nama, 'Kepulauan Seribu') : false;

        if ($isSeribu) {
            $withRab = $this->permintaan->permintaanMaterial->first()->rab_id;
        } else {
            $withRab = $this->permintaan->rab_id;
        }

        $approvedUsers = $this->permintaan->persetujuan()
            ->where('is_approved', 1)
            ->get()
            ->pluck('user_id')
            ->unique();

        $usersWithRoles = \App\Models\User::whereIn('id', $approvedUsers)->with('roles')->get();
        $pemelDone = $usersWithRoles->contains(function ($user) {
            return $user->hasRole('Kepala Seksi');
        });

        // FIX: Path TTD untuk pemohon dan pemel
        $ttdPemohon = null;
        $ttdPemel = null;

        // Cek TTD pemohon
        if ($pemohon && $pemohon->ttd && $sign) {
            $ttdPemohonPath = storage_path('app/public/usersTTD/' . $pemohon->ttd);
            if (file_exists($ttdPemohonPath)) {
                $ttdPemohon = $ttdPemohonPath;
            }
        }

        // Cek TTD pemel
        if ($pemel && $pemel->ttd && $sign) {
            $ttdPemelPath = storage_path('app/public/usersTTD/' . $pemel->ttd);
            if (file_exists($ttdPemelPath)) {
                $ttdPemel = $ttdPemelPath;
            }
        }

        // Pass semua variabel yang diperlukan ke view
        // $html = view(!$withRab ? 'pdf.nodin' : ($this->isSeribu ? 'pdf.spb1000' : 'pdf.spb'), compact(
        $html = view('pdf.nodin', compact(
            'pemelDone',
            'ttdPemohon',   // Ganti dari 'ttdPath'
            'ttdPemel',     // Tambahan untuk TTD pemel
            'pemohon',
            'pemohonRole',
            'permintaan',
            'kasatpel',
            'pemel',
            'Rkb',
            'RKB',
            'sudin',
            'isSeribu',
            'sign',
            'lokasiLengkap'
        ))->render();

        $pdf->writeHTML($html, true, false, true, false, '');
        $this->statusRefresh();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->Output('', 'S');
        }, 'Surat Permintaan Barang.pdf');
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
        $withSign = $params['withSign'] ?? false; // Default false jika tidak ada

        // Debug log untuk memastikan parameter diterima
        \Log::info('downloadDoc called', [
            'type' => $type,
            'withSign' => $withSign,
            'params' => $params
        ]);

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
