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
use App\Models\DetailPengirimanStok;
use Illuminate\Support\Facades\Notification;

class ApprovalPengirimanMaterial extends Component
{

    public $pengiriman;
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
        $this->isPenulis = $this->pengiriman->user_id === Auth::id();
        $this->penulis = $this->pengiriman->user;

        if ($this->pengiriman->persetujuan->where('file')) {
            $this->files = $this->pengiriman->persetujuan->filter(fn($persetujuan) => $persetujuan->file !== null)->pluck('file');
        } else {
            $this->files = [];
        }
        $this->user = Auth::user();
        $this->roles = ['Pengurus Barang', 'Pejabat Pelaksana Teknis Kegiatan', 'Pejabat Pembuat Komitmen'];
        $this->roleLists = [];
        $this->lastRoles = [];

        $date = Carbon::parse($this->pengiriman->created_at);

        foreach ($this->roles as $role) {
            // dd($this->pengiriman->user->unit_id);
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
                    return $query->where('lokasi_id', $this->pengiriman->pengirimanStok->first()->lokasi_id);
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
        // $tipe = $this->pengiriman->jenisStok->nama;
        // $unit = UnitKerja::find($this->pengiriman->unit_id);
        $allApproval = collect();

        // Hitung jumlah persetujuan yang dibutuhkan
        $this->listApproval = collect($this->roleLists)->flatten(1)->count();
        // Menggabungkan semua approval untuk pengecekan urutan
        $allApproval = collect($this->roleLists)->flatten(1);
        $this->currentApprovalIndex = $allApproval->filter(function ($user) {
            $approval = $user->persetujuan()
                ->where('approvable_id', $this->pengiriman->id ?? 0)
                ->where('approvable_type', DetailPengirimanStok::class)
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
                    ->where('approvable_id', $this->pengiriman->id ?? 0)
                    ->where('approvable_type', DetailPengirimanStok::class)
                    ->exists() || Auth::user()->hasRole(['Admin Sudin']);
            } else {
                // Jika user berada di tengah atau akhir
                $previousUser = $index > 0 ? $allApproval[$index - 1] : null;
                $currentUser = $allApproval[$index];
                $previousApprovalStatus = optional(optional($previousUser)->persetujuan()
                    ?->where('approvable_id', $this->pengiriman->id ?? 0)
                    ->where('approvable_type', DetailPengirimanStok::class)
                    ->first())->is_approved;
                $this->showButton = $previousUser &&
                    !$currentUser->persetujuan()
                        ->where('approvable_id', $this->pengiriman->id ?? 0)
                        ->where('approvable_type', DetailPengirimanStok::class)
                        ->exists() &&
                    $previousApprovalStatus === 1 || Auth::user()->hasRole(['Admin Sudin']);
                // && ($this->currentApprovalIndex + 1 < $this->listApproval);
            }
        }

        if ($this->currentApprovalIndex + 1 == 4) {
            $this->showButton = false;
        }
        // $cancelAfter = $this->pengiriman->opsiPersetujuan->cancel_persetujuan;
        // $this->showCancelOption = $this->currentApprovalIndex >= $cancelAfter;

        $this->userJabatanId = $this->user->roles->first()->id; // Ambil jabatan_id user
        // dd($this->pengiriman->opsiPersetujuan->jabatanPersetujuan);
        // Cek apakah ada persetujuan untuk salah satu jabatan user
        // $this->userApproval = $this->pengiriman->opsiPersetujuan
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
        $this->pengiriman->update(['cancel' => false]);
        return redirect()->to('pengiriman/' . $this->tipe . '/' . $this->rab->id);
    }
    public function cancelRequest()
    {
        // Logika untuk membatalkan pengiriman
        $this->pengiriman->update(['cancel' => true]);
        return redirect()->to('pengiriman/' . $this->tipe . '/' . $this->rab->id);
    }

    public function approveConfirmed($status, $message = null)
    {

        $pengiriman  = $this->pengiriman;
        if ($status) {
            $currentIndex = collect($this->roleLists)->flatten(1)->search(Auth::user());
            if ($currentIndex != count($this->roleLists) - 1) {
                $mess = "pengiriman dengan kode {$pengiriman->kode_pengiriman} membutuhkan persetujuan Anda.";


                $user = User::find(collect($this->roleLists)->flatten(1)[$currentIndex + 1]->id);
                Notification::send($user, new UserNotification($mess, "/pengiriman-stok/{$this->pengiriman->id}"));
            }
        } else {
            $mess = "pengiriman dengan kode {$pengiriman->kode_pengiriman} ditolak dengan keterangan {$message}.";


            $user = $pengiriman->user;
            Notification::send($user, new UserNotification($mess, "/pengiriman-stok/{$this->pengiriman->id}"));
        }
        $allApproval = collect($this->roleLists)->flatten(1);
        $userRR = $allApproval[$this->currentApprovalIndex];
        $this->pengiriman->persetujuanMorph()->create([
            'user_id' => $userRR->id,
            'is_approved' => $status, // Atur status menjadi disetujui
            'keterangan' => $message
        ]);


        if (!$status) {
            $this->pengiriman->update(['status' => 0, 'keterangan_ditolak' => $message]);
            foreach ($this->pengiriman->pengirimanMaterial as $item) {
                $stok = Stok::where('merk_id', $item->merk_id)->where('lokasi_id', $this->pengiriman->gudang_id)->first();

                $stok->update(['jumlah' => $stok->jumlah + $item->jumlah]);
            }
        }
        // if ($this->currentApprovalIndex + 1 == 2 && $status) {
        //     $this->pengiriman->update(['status' => $status]);
        //     // Tentukan folder dan path target file
        //     $qrFolder = "qr_pengiriman_material";
        //     $qrTarget = "{$qrFolder}/{$this->pengiriman->kode_pengiriman}.png";

        //     // Konten QR Code (contohnya URL)
        //     $qrContent = url("/pengiriman-stok/{$this->pengiriman->id}");

        //     // Pastikan direktori untuk QR Code tersedia
        //     if (!Storage::disk('public')->exists($qrFolder)) {
        //         Storage::disk('public')->makeDirectory($qrFolder);
        //     }

        //     // Konfigurasi renderer untuk menggunakan GD dengan ukuran 400x400
        //     $renderer = new GDLibRenderer(500);
        //     $writer = new Writer($renderer);

        //     // Path absolut untuk menyimpan file
        //     $filePath = Storage::disk('public')->path($qrTarget);

        //     // Hasilkan QR Code ke file
        //     $writer->writeFile($qrContent, $filePath);
        // }
        if ($this->currentApprovalIndex + 1 == 3 && $status) {
            $this->pengiriman->update(['status' => 1]);

            $pengirimanItems = $this->pengiriman->pengirimanStok;

            foreach ($pengirimanItems as $item) {
                \App\Models\TransaksiStok::create([
                    'kode_transaksi_stok' => fake()->unique()->numerify('TRX#####'),
                    'tipe' => 'Pemasukan',
                    'merk_id' => $item->merk_id,
                    'jumlah' => $item->jumlah,
                    'lokasi_id' => $item->lokasi_id,
                    'bagian_id' => $item->bagian_id,
                    'posisi_id' => $item->posisi_id,
                    'user_id' => Auth::id(),
                    'tanggal' => now(),
                ]);
            }
        }





        return redirect()->to('pengiriman-stok/' . $this->pengiriman->id);
    }
    public function render()
    {
        return view('livewire.approval-pengiriman-material');
    }
}
