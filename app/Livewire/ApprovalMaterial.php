<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Rab;
use App\Models\Stok;
use App\Models\User;
use BaconQrCode\Writer;
use Livewire\Component;
use App\Models\UnitKerja;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserNotification;
use BaconQrCode\Renderer\GDLibRenderer;
use Illuminate\Support\Facades\Storage;
use App\Models\DetailPermintaanMaterial;
use App\Models\TransaksiStok;
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
        $this->roles = ['Kepala Seksi', 'Kepala Subbagian', 'Pengurus Barang'];
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
        if (collect($this->roles)->count() > 1 || 1) {
            if ($index === 0) {
                // Jika user adalah yang pertama dalam daftar
                $currentUser = $allApproval[$index];
                $this->showButton = !$currentUser->persetujuan()
                    ->where('approvable_id', $this->permintaan->id ?? 0)
                    ->where('approvable_type', DetailPermintaanMaterial::class)
                    ->exists();
            } else {
                // Jika user berada di tengah atau akhir
                $previousUser = $index > 0 ? $allApproval[$index - 1] : null;
                $currentUser = $allApproval[$index];
                $previousApprovalStatus = optional(optional($previousUser)->persetujuan()
                    ?->where('approvable_id', $this->permintaan->id ?? 0)
                    ->where('approvable_type', DetailPermintaanMaterial::class)
                    ->first())->is_approved;

                if ($this->currentApprovalIndex + 1 == 3) {
                    $this->showButton = $previousUser &&
                        !$currentUser->persetujuan()
                            ->where('approvable_id', $this->permintaan->id ?? 0)
                            ->where('approvable_type', DetailPermintaanMaterial::class)
                            ->exists() &&
                        $previousApprovalStatus === 1 &&
                        $permintaan->ttd_driver &&
                        $permintaan->ttd_security &&
                        $permintaan->lampiran->count() > 0 &&
                        $this->permintaan->permintaanMaterial()->where('alocated', '!=', 1)->count() === 0;
                } else {
                    $this->showButton = $previousUser &&
                        !$currentUser->persetujuan()
                            ->where('approvable_id', $this->permintaan->id ?? 0)
                            ->where('approvable_type', DetailPermintaanMaterial::class)
                            ->exists() &&
                        $previousApprovalStatus === 1;
                }
                // && ($this->currentApprovalIndex + 1 < $this->listApproval);
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

    public function approveConfirmed($status, $message = null, $driver = null, $nopol = null, $security = null)
    {
        $permintaan  = $this->permintaan;
        if ($status) {
            $currentIndex = collect($this->roleLists)->flatten(1)->search(Auth::user());
            if ($currentIndex != count($this->roleLists) - 1) {
                $mess = "Permintaan dengan kode {$permintaan->kode_permintaan} membutuhkan persetujuan Anda.";


                $user = User::find(collect($this->roleLists)->flatten(1)[$currentIndex + 1]->id);
                Notification::send($user, new UserNotification($mess, "/permintaan/permintaan/{$this->permintaan->id}"));
            }
        } else {
            $mess = "Permintaan dengan kode {$permintaan->kode_permintaan} ditolak dengan keterangan {$message}.";


            $user = $permintaan->user;
            Notification::send($user, new UserNotification($mess, "/permintaan/permintaan/{$this->permintaan->id}"));
        }





        if (!$status) {
            $this->permintaan->update(['status' => 0, 'keterangan_ditolak' => $message]);
            foreach ($this->permintaan->permintaanMaterial as $item) {
                $stok = Stok::where('merk_id', $item->merk_id)->where('lokasi_id', $this->permintaan->gudang_id)->first();

                $stok->update(['jumlah' => $stok->jumlah + $item->jumlah]);
            }
        }
        if ($this->currentApprovalIndex + 1 == 2 && $status) {
            $this->permintaan->update(['status' => $status]);
            // Tentukan folder dan path target file
            $qrFolder = "qr_permintaan_material";
            $qrTarget = "{$qrFolder}/{$this->permintaan->kode_permintaan}.png";

            // Konten QR Code (contohnya URL)
            $qrContent = url("/permintaan/permintaan/{$this->permintaan->id}");

            // Pastikan direktori untuk QR Code tersedia
            if (!Storage::disk('public')->exists($qrFolder)) {
                Storage::disk('public')->makeDirectory($qrFolder);
            }

            // Konfigurasi renderer untuk menggunakan GD dengan ukuran 400x400
            $renderer = new GDLibRenderer(500);
            $writer = new Writer($renderer);

            // Path absolut untuk menyimpan file
            $filePath = Storage::disk('public')->path($qrTarget);

            // Hasilkan QR Code ke file
            $writer->writeFile($qrContent, $filePath);
        }
        $transaksi = new TransaksiStok;
        if ($this->currentApprovalIndex + 1 == 3 && $status) {
            foreach ($this->permintaan->permintaanMaterial as $key => $item) {
                foreach ($item->stokDisetujui as $value) {
                    $data = [
                        'kode_transaksi_stok' => fake()->unique()->numerify('TRX#####'),
                        'tipe' => 'Pengeluaran',
                        'merk_id' => $value->merk_id,
                        'vendor_id' => null,
                        'lokasi_id' => $value->lokasi_id ?? null,
                        'bagian_id' => $value->bagian_id ?? null,
                        'posisi_id' => $value->posisi_id ?? null,
                        'harga' => fake()->numberBetween(1, 10) * 100,
                        'user_id' => $value->permintaanMaterial->detailPermintaan->user_id,
                        // 'kontrak_id' => $tipe === 'Penyesuaian' ? null : $kontrak->id,
                        'tanggal' => Carbon::now()->format('Y-m-d'),
                        'jumlah' => $value->jumlah_disetujui,
                    ];
                    $transaksi->create($data);
                }
                $item->transaksi->first()->delete();
            }
            $this->permintaan->update(['status' => 2, 'driver' => $driver, 'nopol' => $nopol, 'security' => $security]);
        }



        $this->permintaan->persetujuan()->create([
            'user_id' => $this->user->id,
            'is_approved' => $status, // Atur status menjadi disetujui
            'keterangan' => $message
        ]);
        return redirect()->to('permintaan/permintaan/' . $this->permintaan->id);
    }
    public function render()
    {
        return view('livewire.approval-material');
    }
}
