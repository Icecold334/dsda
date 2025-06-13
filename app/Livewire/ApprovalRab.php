<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Aset;
use App\Models\Rab;
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
        $this->roles = ['Kepala Seksi', 'Kepala Suku Dinas'];
        $this->roleLists = [];
        $this->lastRoles = [];

        $date = Carbon::parse($this->rab->created_at);

        foreach ($this->roles as $role) {
            // dd($this->rab->user->unit_id);
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
                            return $query->where('nama', 'like', '%Seksi Perencanaan%');
                        });
                    });
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
        // $tipe = $this->rab->jenisStok->nama;
        // $unit = UnitKerja::find($this->rab->unit_id);
        $allApproval = collect();

        // Hitung jumlah persetujuan yang dibutuhkan
        $this->listApproval = collect($this->roleLists)->flatten(1)->count();
        // Menggabungkan semua approval untuk pengecekan urutan
        $allApproval = collect($this->roleLists)->flatten(1);
        $this->currentApprovalIndex = $allApproval->filter(function ($user) {
            $approval = $user->persetujuan()
                ->where('approvable_id', $this->rab->id ?? 0)
                ->where('approvable_type', Rab::class)
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
                    ->where('approvable_id', $this->rab->id ?? 0)
                    ->where('approvable_type', Rab::class)
                    ->exists() || Auth::user()->hasRole(['Admin Sudin']);
            } else {
                // Jika user berada di tengah atau akhir
                $previousUser = $index > 0 ? $allApproval[$index - 1] : null;
                $currentUser = $allApproval[$index];
                $previousApprovalStatus = optional(optional($previousUser)->persetujuan()
                    ?->where('approvable_id', $this->rab->id ?? 0)
                    ->where('approvable_type', Rab::class)
                    ->first())->is_approved;
                $this->showButton = $previousUser &&
                    !$currentUser->persetujuan()
                        ->where('approvable_id', $this->rab->id ?? 0)
                        ->where('approvable_type', Rab::class)
                        ->exists() &&
                    $previousApprovalStatus === 1 || Auth::user()->hasRole(['Admin Sudin']);
                // && ($this->currentApprovalIndex + 1 < $this->listApproval);
            }
        }

        if ($this->currentApprovalIndex + 1 == 3) {
            $this->showButton = false;
        }
        // $cancelAfter = $this->rab->opsiPersetujuan->cancel_persetujuan;
        // $this->showCancelOption = $this->currentApprovalIndex >= $cancelAfter;

        $this->userJabatanId = $this->user->roles->first()->id; // Ambil jabatan_id user
        // dd($this->rab->opsiPersetujuan->jabatanPersetujuan);
        // Cek apakah ada persetujuan untuk salah satu jabatan user
        // $this->userApproval = $this->rab->opsiPersetujuan
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
        if ($status) {
            $currentIndex = collect($this->roleLists)->flatten(1)->search(Auth::user());
            if ($currentIndex != count($this->roleLists) - 1) {
                $mess =
                    'RAB <span class="font-semibold">' . $rab->nama . '</span> membutuhkan persetujuan Anda.';


                $user = User::find(collect($this->roleLists)->flatten(1)[$currentIndex + 1]->id);
                Notification::send($user, new UserNotification($mess, "/rab/{$this->rab->id}"));
            }
        } else {
            $mess = 'RAB <span class="font-semibold">' . $rab->nama . '</span> ditolak dengan keterangan {$message}.';


            $user = $rab->user;
            Notification::send($user, new UserNotification($mess, "/rab/{$this->rab->id}"));
        }
        $this->listApproval = collect($this->roleLists)->flatten(1)->count();
        // Menggabungkan semua approval untuk pengecekan urutan
        $allApproval = collect($this->roleLists)->flatten(1);
        $userrr = $allApproval[$this->currentApprovalIndex];
        $this->rab->persetujuan()->create([
            'user_id' => $userrr->id,
            'is_approved' => $status, // Atur status menjadi disetujui
            'keterangan' => $message
        ]);


        if ($this->currentApprovalIndex + 1 == $this->listApproval || !$status) {
            $this->rab->update(['status' => $status ? 2 : 0, 'keterangan' => $message]);
        }




        return redirect()->to('rab/' . $this->rab->id);
    }
    public function render()
    {
        return view('livewire.approval-rab');
    }
}
