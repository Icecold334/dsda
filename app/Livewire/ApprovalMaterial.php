<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Rab;
use App\Models\Stok;
use App\Models\User;
use App\Models\Driver;
use BaconQrCode\Writer;
use Livewire\Component;
use App\Models\Security;
use App\Models\UnitKerja;
use Illuminate\Support\Str;
use App\Models\TransaksiStok;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserNotification;
use BaconQrCode\Renderer\GDLibRenderer;
use Illuminate\Support\Facades\Storage;
use App\Models\DetailPermintaanMaterial;
use Illuminate\Support\Facades\Notification;

class ApprovalMaterial extends Component
{

    public $permintaan;
    public $currentApprovalIndex;
    public $penulis;
    public $isPenulis;
    public $showCancelOption;
    public $user;
    public $userJabatanId;
    public $userApproval;
    public $showButtonApproval;
    public $files = [];
    public $roles = [];
    public $roleLists = []; // List users per role
    public $lastRoles = []; // Status whether last user per role
    public $listApproval;
    public $showButton;
    public $kategori;
    public $listDrivers = [];
    public $listSecurities = [];
    public $showModal = false;
    public $selectedDriverId, $selectedSecurityId, $nopol;
    public $noSuratJalan;


    public function openModal()
    {
        $this->reset(['selectedDriverId', 'selectedSecurityId', 'nopol']);
        $this->showModal = true;
    }

    public function submitPengirimanApproval()
    {
        $this->validate([
            'selectedDriverId' => 'required|exists:drivers,id',
            'selectedSecurityId' => 'required|exists:securities,id',
            'nopol' => 'required|string',
        ]);

        // Panggil approval logika seperti approveConfirmed(1, ...)
        $this->approveConfirmed(1, null, $this->selectedDriverId, $this->nopol, $this->selectedSecurityId, null, $this->noSuratJalan);

        $this->showModal = false;
    }
    public function mount()
    {
        $this->isPenulis = $this->permintaan->user_id === Auth::id();
        $this->penulis = $this->permintaan->user;
        $permintaan = $this->permintaan;
        if ($this->permintaan->persetujuan->where('file')) {
            $this->files = $this->permintaan->persetujuan->filter(fn($persetujuan) => $persetujuan->file !== null)->pluck('file');
        } else {
            $this->files = [];
        }
        $this->user = Auth::user();
        $creatorRoles = $this->permintaan->user->roles->pluck('name')->toArray();
        $hasRab = $this->permintaan->rab_id !== null;
        $isKasatpel = in_array('Kepala Satuan Pelaksana', $creatorRoles);
        // Jika yang membuat permintaan adalah Kepala Seksi
        if ($hasRab && $isKasatpel) {
            $this->roles = ['Kepala Seksi', 'Kepala Suku Dinas', 'Kepala Subbagian', 'Pengurus Barang'];
        } elseif ($hasRab && !$isKasatpel) {
            $this->roles = ['Kepala Suku Dinas', 'Kepala Subbagian', 'Pengurus Barang'];
        } elseif (!$hasRab && $isKasatpel) {
            $this->roles = ['Kepala Seksi', 'Kepala Subbagian', 'Pengurus Barang'];
        } else {
            $this->roles = ['Kepala Subbagian', 'Pengurus Barang'];
        }
        $this->listDrivers = \App\Models\Driver::where('unit_id', $this->unit_id)->get();
        $this->listSecurities = \App\Models\Security::where('unit_id', $this->unit_id)->get();

        $this->roleLists = [];
        $this->lastRoles = [];

        $date = Carbon::parse($this->permintaan->created_at);

        foreach ($this->roles as $role) {
            // dd($this->permintaan->user->unit_id);
            $users = User::whereHas('roles', function ($query) use ($role) {
                $query->where('name', 'LIKE', '%' . $role . '%');
            })
                ->where(function ($query) {
                    $query->whereHas('unitKerja', function ($subQuery) {
                        $subQuery->where('parent_id', $this->unit_id)->orWhere('id', $this->unit_id);
                    });
                })
                ->where(function ($query) use ($role) {
                    $query->whereHas('unitKerja', function ($subQuery) use ($role) {
                        $subQuery->when($role === 'Kepala Seksi', function ($query) {
                            return $query->where('nama', 'like', '%Pemeliharaan%');
                        })->when($role === 'Kepala Subbagian', function ($query) {
                            return $query->where('nama', 'like', '%Tata Usaha%');
                        });
                    });
                })->when($role === 'Penjaga Gudang', function ($query) {
                    return $query->where('lokasi_id', $this->permintaan->gudang_id);
                })
                ->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))
                ->limit(1)
                ->get();


            $propertyKey = Str::slug($role); // Generate dynamic key for roles
            $this->roleLists[$propertyKey] = $users;
            $this->lastRoles[$propertyKey] = $users->search(fn($user) => $user->id == Auth::id()) === $users->count() - 1;
        }
        // dd($users);

        // Calculate listApproval dynamically
        // $tipe = $this->permintaan->jenisStok->nama;
        // $unit = UnitKerja::find($this->permintaan->unit_id);
        $allApproval = collect();

        // Hitung jumlah persetujuan yang dibutuhkan
        $this->listApproval = collect($this->roleLists)->flatten(1)->count();
        // Menggabungkan semua approval untuk pengecekan urutan
        $allApproval = collect($this->roleLists)->flatten(1);
        $this->currentApprovalIndex = $allApproval->filter(function ($user) {
            $approval = $user->persetujuan()
                ->where('approvable_id', $this->permintaan->id ?? 0)
                ->where('approvable_type', DetailPermintaanMaterial::class)
                ->first();
            return $approval && $approval->is_approved === 1; // Hanya hitung persetujuan yang berhasil
        })->count();


        // Pengecekan urutan user dalam daftar persetujuan
        $index = $allApproval->search(fn($user) => $user->id == Auth::id());
        $allApproval = collect($this->roleLists)->flatten(1);
        $index = $allApproval->search(fn($user) => $user->id == Auth::id());
        $this->showButton = false;
        if ($index !== false) {
            $currentUser = $allApproval[$index];
            $currentApproved = $currentUser->persetujuan()
                ->where('approvable_id', $this->permintaan->id)
                ->where('approvable_type', DetailPermintaanMaterial::class)
                ->exists();

            if ($index === 0) {
                // Pertama di daftar
                $this->showButton = !$currentApproved || Auth::user()->hasRole('Admin Sudin');
            } else {
                $previousUser = $allApproval[$index - 1];
                $previousApproved = $previousUser->persetujuan()
                    ->where('approvable_id', $this->permintaan->id)
                    ->where('approvable_type', DetailPermintaanMaterial::class)
                    ->where('is_approved', 1)
                    ->exists();

                // Jika role saat ini adalah Pengurus Barang → cek syarat tambahan
                if ($currentUser->hasRole('Pengurus Barang')) {
                    // dump($index);
                    $lampiranOk = $this->permintaan->lampiran->count() > 0;
                    $alokasiOk = $this->permintaan->permintaanMaterial()->where('alocated', '!=', 1)->count() === 0;

                    $this->showButton = !$currentApproved
                        && $previousApproved
                        && $lampiranOk
                        && $alokasiOk
                        || Auth::user()->hasRole('Admin Sudin');
                } else {
                    // Role biasa (Kasie, Kasudin, Kasubbag TU)
                    $this->showButton = !$currentApproved
                        && $previousApproved
                        || Auth::user()->hasRole('Admin Sudin');
                }
            }
        }

        // $cancelAfter = $this->permintaan->opsiPersetujuan->cancel_persetujuan;
        // $this->showCancelOption = $this->currentApprovalIndex >= $cancelAfter;

        $this->userJabatanId = $this->user->roles->first()->id; // Ambil jabatan_id user
        // dd($this->permintaan->opsiPersetujuan->jabatanPersetujuan);
        // Cek apakah ada persetujuan untuk salah satu jabatan user
        // $this->userApproval = $this->permintaan->opsiPersetujuan
        //     ->jabatanPersetujuan
        //     ->whereIn('jabatan_id', $this->userJabatanId)
        //     ->pluck('approval')
        //     ->toArray(); // Ubah ke array agar mudah digunakan
        // dd($this->userApproval);
        // $this->showButtonApproval = in_array(1, $this->userApproval);
        // dd($this->currentApprovalIndex);
    }

    public function markAsCompleted()
    {
        $this->permintaan->update(['cancel' => false]);
        return redirect()->to('permintaan/' . $this->tipe . '/' . $this->rab->id);
    }
    public function cancelRequest()
    {
        // Logika untuk membatalkan permintaan
        $this->permintaan->update(['cancel' => true]);
        return redirect()->to('permintaan/' . $this->tipe . '/' . $this->rab->id);
    }

    public function approveConfirmed($status, $message = null, $driver = null, $nopol = null, $security = null, $sppb = null, $noSuratJalan = null)
    {
        $permintaan = $this->permintaan;
        $currentUser = Auth::user();
        $allApproval = collect($this->roleLists)->flatten(1);
        $currentIndex = $allApproval->search(fn($user) => $user->id === $currentUser->id);

        // Simpan persetujuan
        $permintaan->persetujuan()->create([
            'user_id' => $currentUser->id,
            'is_approved' => $status,
            'keterangan' => $message
        ]);

        // Jika ditolak
        if (!$status) {
            $permintaan->update([
                'status' => 0,
                'keterangan_ditolak' => $message
            ]);

            foreach ($permintaan->permintaanMaterial as $item) {
                $item->transaksi->first()?->delete();
            }

            Notification::send($permintaan->user, new UserNotification(
                "Permintaan dengan kode {$permintaan->kode_permintaan} ditolak dengan keterangan: {$message}.",
                "/permintaan/permintaan/{$permintaan->id}"
            ));

            return redirect()->to('permintaan/permintaan/' . $permintaan->id);
        }

        // Jika disetujui dan masih ada role selanjutnya
        if ($currentIndex !== false && $currentIndex < $allApproval->count() - 1) {
            $nextUser = $allApproval[$currentIndex + 1];
            Notification::send($nextUser, new UserNotification(
                "Permintaan dengan kode {$permintaan->kode_permintaan} membutuhkan persetujuan Anda.",
                "/permintaan/permintaan/{$permintaan->id}"
            ));
        }

        // Jika yang menyetujui adalah Kepala Subbagian (buat QR + ubah status = 1)
        if ($currentUser->hasRole(['Kepala Subbagian', 'Kepala Subbagian Tata Usaha'])) {
            $permintaan->update(['status' => 1, 'sppb' => $sppb]);

            // Buat QR Code
            $qrFolder = "qr_permintaan_material";
            $qrTarget = "{$qrFolder}/{$permintaan->kode_permintaan}.png";
            $qrContent = url("material/{$permintaan->id}/qrDownload");

            if (!Storage::disk('public')->exists($qrFolder)) {
                Storage::disk('public')->makeDirectory($qrFolder);
            }

            $renderer = new GDLibRenderer(500);
            $writer = new Writer($renderer);
            $filePath = Storage::disk('public')->path($qrTarget);
            $writer->writeFile($qrContent, $filePath);
            // dd('oke');
        }

        // Jika yang menyetujui adalah Pengurus Barang (buat Transaksi + ubah status = 2)
        if ($currentUser->hasRole('Pengurus Barang')) {
            foreach ($permintaan->permintaanMaterial as $item) {
                foreach ($item->stokDisetujui as $value) {
                    TransaksiStok::create([
                        'kode_transaksi_stok' => fake()->unique()->numerify('TRX#####'),
                        'tipe' => 'Pengeluaran',
                        'merk_id' => $value->merk_id,
                        'vendor_id' => null,
                        'lokasi_id' => $value->lokasi_id,
                        'bagian_id' => $value->bagian_id,
                        'posisi_id' => $value->posisi_id,
                        'harga' => fake()->numberBetween(1, 10) * 100,
                        'user_id' => $value->permintaanMaterial->detailPermintaan->user_id,
                        'tanggal' => Carbon::now()->format('Y-m-d'),
                        'jumlah' => $value->jumlah_disetujui,
                    ]);
                }

                $item->transaksi->first()?->delete();
            }

            $this->permintaan->update([
                'status' => 2,
                'driver' => optional(Driver::find($driver))->nama,
                'nopol' => $nopol,
                'security' => optional(Security::find($security))->nama,
                'suratJalan' => $noSuratJalan, // pastikan kolom ini ada
            ]);
        }

        // Khusus tanpa RAB, tetap kirim notifikasi ke Kasudin (meskipun dia bukan approver)
        // Kirim notifikasi ke Kasudin jika TANPA RAB dan disetujui oleh Kasubbag TU
        $hasRab = $permintaan->rab_id !== null;
        if (!$hasRab && $currentUser->hasRole('Kepala Subbagian')) {
            $kasudinUsers = User::role('Kepala Suku Dinas')->get();
            Notification::send($kasudinUsers, new UserNotification(
                "SPB permintaan {$permintaan->kode_permintaan} telah disetujui oleh Kepala Subbagian Tata Usaha.",
                "/permintaan/permintaan/{$permintaan->id}"
            ));
        }


        return redirect()->to('permintaan/permintaan/' . $permintaan->id);
    }

    public function render()
    {
        return view('livewire.approval-material');
    }
}
