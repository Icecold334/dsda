<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Aset;
use App\Models\User;
use Livewire\Component;
use App\Models\UnitKerja;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;


class ApprovalRab extends Component
{
    public $rab;
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
        $this->isPenulis = $this->rab->user_id === Auth::id();
        $this->penulis = $this->rab->user;

        if ($this->rab->persetujuan->where('file')) {
            $this->files = $this->rab->persetujuan->filter(fn($persetujuan) => $persetujuan->file !== null)->pluck('file');
        } else {
            $this->files = [];
        }
        $this->user = Auth::user();
        $this->roles = $this->rab->opsiPersetujuan->jabatanPersetujuan->pluck('jabatan.name')->toArray();
        $this->roleLists = [];
        $this->lastRoles = [];

        $date = Carbon::parse($this->rab->created_at);

        foreach ($this->roles as $role) {
            $users = User::whereHas('roles', function ($query) use ($role) {
                $query->where('name', 'LIKE', '%' . $role . '%');
            })
                ->where(function ($query) use ($date) {
                    $query->whereHas('unitKerja', function ($subQuery) {
                        $subQuery->where('parent_id', $this->rab->unit_id);
                    })
                        ->orWhere('unit_id', $this->rab->unit_id);
                })
                ->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))
                ->limit(1)
                ->get();

            $propertyKey = Str::slug($role); // Generate dynamic key for roles
            $this->roleLists[$propertyKey] = $users;
            $this->lastRoles[$propertyKey] = $users->search(fn($user) => $user->id == Auth::id()) === $users->count() - 1;
        }

        // Calculate listApproval dynamically
        // $tipe = $this->rab->jenisStok->nama;
        // $unit = UnitKerja::find($this->rab->unit_id);
        $allApproval = collect();

        // Hitung jumlah persetujuan yang dibutuhkan
        $this->listApproval = collect($this->roleLists)->flatten(1)->count();

        // Menggabungkan semua approval untuk pengecekan urutan
        $allApproval = collect($this->roleLists)->flatten(1);
        $this->currentApprovalIndex = $allApproval->filter(function ($user) {
            $approval = $user->{"persetujuan{$this->tipe}"}()
                ->where('detail_' . $this->tipe . '_id', $this->rab->id ?? 0)
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
                    ->where('detail_' . $this->tipe . '_id', $this->rab->id ?? 0)
                    ->exists();
            } else {
                // Jika user berada di tengah atau akhir
                $previousUser = $index > 0 ? $allApproval[$index - 1] : null;
                $currentUser = $allApproval[$index];
                $previousApprovalStatus = optional(optional($previousUser)->{"persetujuan{$this->tipe}"}()
                    ?->where('detail_' . $this->tipe . '_id', $this->rab->id ?? 0)
                    ->first())->status;


                $this->showButton = $previousUser &&
                    !$currentUser->{"persetujuan{$this->tipe}"}()
                        ->where('detail_' . $this->tipe . '_id', $this->rab->id ?? 0)
                        ->exists() &&
                    $previousApprovalStatus === 1 &&
                    ($this->rab->cancel === 0 || $this->currentApprovalIndex + 1 < $this->listApproval);
            }
        }
        $cancelAfter = $this->rab->opsiPersetujuan->cancel_persetujuan;
        $this->showCancelOption = $this->currentApprovalIndex >= $cancelAfter;

        $this->userJabatanId = $this->user->roles->first()->id; // Ambil jabatan_id user
        // dd($this->rab->opsiPersetujuan->jabatanPersetujuan);
        // Cek apakah ada persetujuan untuk salah satu jabatan user
        $this->userApproval = $this->rab->opsiPersetujuan
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
        $this->rab->update(['cancel' => false]);
        return redirect()->to('rab/' . $this->tipe . '/' . $this->rab->id);
    }
    public function cancelRequest()
    {
        // Logika untuk membatalkan rab
        $this->rab->update(['cancel' => true]);
        return redirect()->to('rab/' . $this->tipe . '/' . $this->rab->id);
    }

    public function approveConfirmed($status, $message = null)
    {
        $rab  = $this->rab;
        // dd($rab->kode_rab ?? $rab->kode_peminjaman);
        if ($status) {
            $currentIndex = collect($this->roleLists)->flatten(1)->search(Auth::user());
            if ($currentIndex != count($this->roleLists) - 1) {
                $message = Str::ucfirst($this->tipe) . ' dengan kode <span class="font-bold">' .
                    (!is_null($rab->kode_rab) ? $rab->kode_rab : $rab->kode_peminjaman) .
                    '</span> membutuhkan persetujuan Anda.';


                $user = User::find(collect($this->roleLists)->flatten(1)[$currentIndex + 1]->id);
                Notification::send($user, new UserNotification($message, "/rab/{$this->tipe}/{$this->rab->id}"));
            }
            $cancelAfter = $rab->opsiPersetujuan->cancel_persetujuan;
            // if ($currentIndex + 1 == $cancelAfter) {
            //     $this->rab->update([
            //         'cancel' => 0,
            //     ]);
            // }
        }

        $this->rab->persetujuan()->create([
            'detail_' . $this->tipe . '_id' => $this->rab->id,
            'user_id' => $this->user->id,
            'status' => $status,
            'keterangan' => $message
        ]);
        if (($this->currentApprovalIndex + 2) == $this->listApproval && $this->rab->kategori_id == 4) {
            $this->rab->update([
                'cancel' => 0,
            ]);
        }

        if (($this->currentApprovalIndex + 1) == $this->listApproval) {
            $this->rab->update([
                'status' => 1,
                'proses' => 1  // Proses selesai
            ]);


            if ($this->tipe == 'rab') {
                $rabItems = $this->rab->rabStok;
                foreach ($rabItems as $merk) {
                    foreach ($merk->stokDisetujui as  $item) {
                        $this->adjustStockForApproval($item);
                    }
                }
            } else {
                $kategori_id = $this->rab->peminjamanAset->first()->aset->kategori_id;
                if ($kategori_id == 8) {
                    $peminjamanItems = $this->rab->peminjamanAset;
                } else {
                    $peminjamanItems = $this->rab->peminjamanAset()->first();

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



        return redirect()->to('rab/' . $this->tipe . '/' . $this->rab->id);
    }
    public function render()
    {
        return view('livewire.approval-rab');
    }
}
