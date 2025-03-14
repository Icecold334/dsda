<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Aset;
use App\Models\Stok;
use App\Models\User;
use Livewire\Component;
use App\Models\Kategori;
use App\Models\UnitKerja;
use Illuminate\Support\Str;
use App\Models\StokDisetujui;
use Livewire\WithFileUploads;
use App\Models\JabatanPersetujuan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Storage;
use App\Models\PersetujuanPermintaanStok;
use Illuminate\Support\Facades\Notification;

class ApprovalPermintaan extends Component
{
    use WithFileUploads;

    public $permintaan;
    public $currentApprovalIndex;
    public $penulis;
    public $isPenulis;
    public $tipe;
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

        $this->tipe = Str::contains($this->permintaan->getTable(), 'permintaan') ? 'permintaan' : 'peminjaman';

        if ($this->permintaan->persetujuan->where('file')) {
            $this->files = $this->permintaan->persetujuan->filter(fn($persetujuan) => $persetujuan->file !== null)->pluck('file');
        } else {
            $this->files = [];
        }
        $this->user = Auth::user();
        $this->roles = $this->permintaan->opsiPersetujuan->jabatanPersetujuan->pluck('jabatan.name')->toArray();
        $this->roleLists = [];
        $this->lastRoles = [];

        $date = Carbon::parse($this->permintaan->created_at);

        foreach ($this->roles as $role) {
            $users = User::whereHas('roles', function ($query) use ($role) {
                $query->where('name', 'LIKE', '%' . $role . '%');
            })
                ->where(function ($query) use ($date) {
                    $query->whereHas('unitKerja', function ($subQuery) {
                        $subQuery->where('parent_id', $this->permintaan->unit_id);
                    })
                        ->orWhere('unit_id', $this->permintaan->unit_id);
                })
                ->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))
                ->limit(1)
                ->get();

            $propertyKey = Str::slug($role); // Generate dynamic key for roles
            $this->roleLists[$propertyKey] = $users;
            $this->lastRoles[$propertyKey] = $users->search(fn($user) => $user->id == Auth::id()) === $users->count() - 1;
        }

        // Calculate listApproval dynamically
        // $tipe = $this->permintaan->jenisStok->nama;
        // $unit = UnitKerja::find($this->permintaan->unit_id);
        $allApproval = collect();

        // Hitung jumlah persetujuan yang dibutuhkan
        $this->listApproval = collect($this->roleLists)->flatten(1)->count();

        // Menggabungkan semua approval untuk pengecekan urutan
        $allApproval = collect($this->roleLists)->flatten(1);
        $this->currentApprovalIndex = $allApproval->filter(function ($user) {
            $approval = $user->{"persetujuan{$this->tipe}"}()
                ->where('detail_' . $this->tipe . '_id', $this->permintaan->id ?? 0)
                ->first();
            return $approval && $approval->status === 1; // Hanya hitung persetujuan yang berhasil
        })->count();


        // Pengecekan urutan user dalam daftar persetujuan
        $index = $allApproval->search(fn($user) => $user->id == Auth::id());
        if (collect($this->roles)->count() > 1) {
            if ($index === 0) {
                // Jika user adalah yang pertama dalam daftar
                $currentUser = $allApproval[$index];
                $this->showButton = !$currentUser->{"persetujuan{$this->tipe}"}()
                    ->where('detail_' . $this->tipe . '_id', $this->permintaan->id ?? 0)
                    ->exists();
            } else {
                // Jika user berada di tengah atau akhir
                $previousUser = $index > 0 ? $allApproval[$index - 1] : null;
                $currentUser = $allApproval[$index];
                $previousApprovalStatus = optional(optional($previousUser)->{"persetujuan{$this->tipe}"}()
                    ?->where('detail_' . $this->tipe . '_id', $this->permintaan->id ?? 0)
                    ->first())->status;


                $this->showButton = $previousUser &&
                    !$currentUser->{"persetujuan{$this->tipe}"}()
                        ->where('detail_' . $this->tipe . '_id', $this->permintaan->id ?? 0)
                        ->exists() &&
                    $previousApprovalStatus === 1 &&
                    ($this->permintaan->cancel === 0 || $this->currentApprovalIndex + 1 < $this->listApproval);
            }
        }
        $cancelAfter = $this->permintaan->opsiPersetujuan->cancel_persetujuan;
        $this->showCancelOption = $this->currentApprovalIndex >= $cancelAfter;

        $this->userJabatanId = $this->user->roles->first()->id; // Ambil jabatan_id user
        // dd($this->permintaan->opsiPersetujuan->jabatanPersetujuan);
        // Cek apakah ada persetujuan untuk salah satu jabatan user
        $this->userApproval = $this->permintaan->opsiPersetujuan
            ->jabatanPersetujuan
            ->whereIn('jabatan_id', $this->userJabatanId)
            ->pluck('approval')
            ->toArray(); // Ubah ke array agar mudah digunakan
        // dd($this->userApproval);
        $this->showButtonApproval = in_array(1, $this->userApproval);
        // dd($this->currentApprovalIndex);
    }

    public function markAsCompleted()
    {
        $this->permintaan->update(['cancel' => false]);
        return redirect()->to('permintaan/' . $this->tipe . '/' . $this->permintaan->id);
    }
    public function cancelRequest()
    {
        // Logika untuk membatalkan permintaan
        $this->permintaan->update(['cancel' => true]);
        return redirect()->to('permintaan/' . $this->tipe . '/' . $this->permintaan->id);
    }

    public function approveConfirmed($status, $message = null)
    {
        $permintaan  = $this->permintaan;

        // dd($permintaan->kode_permintaan ?? $permintaan->kode_peminjaman);
        if ($status) {
            $currentIndex = collect($this->roleLists)->flatten(1)->search(Auth::user());
            if ($currentIndex != count($this->roleLists) - 1) {
                $alert = Str::ucfirst($this->tipe) . ' dengan kode <span class="font-bold">' .
                    (!is_null($permintaan->kode_permintaan) ? $permintaan->kode_permintaan : $permintaan->kode_peminjaman) .
                    '</span> membutuhkan persetujuan Anda.';


                $user = User::find(collect($this->roleLists)->flatten(1)[$currentIndex + 1]->id);
                Notification::send($user, new UserNotification($alert, "/permintaan/{$this->tipe}/{$this->permintaan->id}"));
            }
            $cancelAfter = $permintaan->opsiPersetujuan->cancel_persetujuan;
            // if ($currentIndex + 1 == $cancelAfter) {
            //     $this->permintaan->update([
            //         'cancel' => 0,
            //     ]);
            // }
        } else {
            $user = $this->permintaan->user;
            if ($user) {
                $alert = Str::ucfirst($this->tipe) . ' dengan kode <span class="font-bold">' .
                    (!is_null($permintaan->kode_permintaan) ? $permintaan->kode_permintaan : $permintaan->kode_peminjaman) .
                    '</span> menolak persetujuan Anda dengan keterangan <span class="font-bold">' . $message . '</span>';


                Notification::send($user, new UserNotification($alert, "/permintaan/{$this->tipe}/{$this->permintaan->id}"));
            }

            $this->permintaan->update(['status' => false]);
        }

        $this->permintaan->persetujuan()->create([
            'detail_' . $this->tipe . '_id' => $this->permintaan->id,
            'user_id' => $this->user->id,
            'status' => $status,
            'keterangan' => $message
        ]);
        if (($this->currentApprovalIndex + 2) == $this->listApproval && $this->permintaan->kategori_id == 4) {
            $this->permintaan->update([
                'cancel' => 0,
            ]);
        }

        if (($this->currentApprovalIndex + 1) == $this->listApproval) {
            $this->permintaan->update([
                'status' => 1,
                'proses' => 1  // Proses selesai
            ]);


            if ($this->tipe == 'permintaan') {
                $permintaanItems = $this->permintaan->permintaanStok;
                foreach ($permintaanItems as $merk) {
                    foreach ($merk->stokDisetujui as  $item) {
                        $this->adjustStockForApproval($item);
                    }
                }
            } else {
                $kategori_id = $this->permintaan->peminjamanAset->first()->aset->kategori_id;
                if ($kategori_id == 8) {
                    $peminjamanItems = $this->permintaan->peminjamanAset;
                } else {
                    $peminjamanItems = $this->permintaan->peminjamanAset()->first();

                    if ($peminjamanItems) {
                        // Ambil data aset berdasarkan aset_id
                        $aset = Aset::find($peminjamanItems->aset_id);
                        if ($aset) {
                            // Update peminjaman menjadi 0
                            $aset->update([
                                'peminjaman' => 0
                            ]);
                        }
                    }
                }
            };
        } else {
        }



        return redirect()->to('permintaan/' . $this->tipe . '/' . $this->permintaan->id);
    }

    protected function adjustStockForApproval($merk)
    {
        // Ambil stok berdasarkan merk_id, diurutkan berdasarkan lokasi atau logika lainnya
        $stocks =
            Stok::where('merk_id', $merk->merk_id)
            ->where('lokasi_id', $merk->lokasi_id)
            ->where('bagian_id', $merk->bagian_id)
            ->where('posisi_id', $merk->posisi_id)
            // ->where('jumlah', '>', 0)
            ->get();

        $remaining = $merk->jumlah_disetujui; // Jumlah yang harus dikurangi

        foreach ($stocks as $stock) {
            if ($remaining <= 0) break; // Hentikan jika jumlah sudah terpenuhi

            if ($stock->jumlah >= $remaining) {
                // Jika stok di lokasi ini cukup atau lebih dari jumlah yang dibutuhkan
                $stock->jumlah -= $remaining;
                $stock->save(); // Simpan perubahan stok
                $remaining = 0;
            } else {
                // Jika stok di lokasi ini kurang dari jumlah yang dibutuhkan
                $remaining -= $stock->jumlah; // Kurangi jumlah stok dari sisa yang diperlukan
                $stock->jumlah = 0;
                $stock->save(); // Simpan stok sebagai 0
            }
        }

        // Jika stok tidak mencukupi
        // if ($remaining > 0) {
        //     Log::warning("Stok tidak mencukupi untuk merk_id {$merkId}. Dibutuhkan {$jumlahApprove}, namun kekurangan {$remaining}.");
        //     // Tambahkan logika untuk menangani kekurangan stok, seperti pemberitahuan atau aksi lain
        // }
    }


    public function render()
    {
        return view('livewire.approval-permintaan');
    }
}
