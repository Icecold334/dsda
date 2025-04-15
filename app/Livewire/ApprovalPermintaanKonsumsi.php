<?php

namespace App\Livewire;

use App\Models\DetailPermintaanStok;
use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;

class ApprovalPermintaanKonsumsi extends Component
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
    public $unit_id;
    public $kepalaPemohon;
    public $kepalaSubbagian;
    public $tipe;

    public function mount()
    {
        $this->isPenulis = $this->permintaan->user_id === Auth::id();
        $this->penulis = $this->permintaan->user;
        $this->user = Auth::user();
        $this->unit_id = $this->permintaan->unit_id ?? $this->user->unit_id;
        $this->tipe = Str::contains($this->permintaan->getTable(), 'permintaan') ? 'permintaan' : 'peminjaman';

        $this->files = $this->permintaan->persetujuan
            ->filter(fn($persetujuan) => $persetujuan->file !== null)
            ->pluck('file')
            ->toArray();

        $this->roles = ['Customer Services', 'Penanggung Jawab'];
        $this->roleLists = [];
        $this->lastRoles = [];

        $date = Carbon::parse($this->permintaan->created_at);

        foreach ($this->roles as $role) {
            $baseQuery = User::whereHas('roles', function ($query) use ($role) {
                $query->where('name', 'LIKE', '%' . $role . '%');
            })
                ->whereHas('unitKerja', function ($query) {
                    $query->where('parent_id', $this->unit_id)
                        ->orWhere('id', $this->unit_id);
                })
                ->when($role === 'Customer Services', function ($query) {
                    $query->where('name', 'like', '%Nisya%');
                })

                ->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'));

            $users = $role === 'Penanggung Jawab'
                ? collect([$baseQuery->first()])->filter() // wrap in collection, filter null
                : $baseQuery->get();

            $propertyKey = Str::slug($role);
            $this->roleLists[$propertyKey] = $users;
            $this->lastRoles[$propertyKey] = $users->search(fn($user) => $user->id == Auth::id()) === $users->count() - 1;
        }

        $allApproval = collect($this->roleLists)->flatten(1)->values();
        $this->listApproval = $allApproval->count();
        $this->currentApprovalIndex = $allApproval->filter(function ($user) {
            $approval = $user->persetujuan()
                ->where('approvable_id', $this->permintaan->id ?? 0)
                ->where('approvable_type', DetailPermintaanStok::class)
                ->first();
            return $approval && $approval->is_approved === 1;
        })->count();

        $index = $allApproval->search(fn($user) => $user->id == Auth::id());
        $this->showButton = false;

        if ($index !== false) {
            $currentUser = $allApproval[$index];

            $currentApproved = $currentUser->persetujuan()
                ->where('approvable_id', $this->permintaan->id ?? 0)
                ->where('approvable_type', DetailPermintaanStok::class)
                ->exists();

            if ($index === 0) {
                $this->showButton = !$currentApproved;
            } else {
                $previousUser = $allApproval[$index - 1];
                $previousApproved = $previousUser->persetujuan()
                    ->where('approvable_id', $this->permintaan->id ?? 0)
                    ->where('approvable_type', DetailPermintaanStok::class)
                    ->first();
                $this->showButton = $previousApproved && $previousApproved->is_approved && !$currentApproved;
            }
        }

        $this->userJabatanId = $this->user->roles->first()?->id;

        $pemohon = $this->permintaan->user; // User yang membuat permintaan

        if ($pemohon->hasRole('Kepala Unit')) {
            // Jika pemohon adalah Kepala Unit, maka tidak ada atasan di atasnya
            $this->kepalaPemohon = null;
        } elseif ($pemohon->hasRole('Kepala Subbagian')) {
            // Jika pemohon adalah Kepala Subbagian, maka cari Kepala Unit di atasnya
            $this->kepalaPemohon = User::whereHas('roles', function ($query) {
                $query->where('name', 'Kepala Unit');
            })
                ->where(function ($query) use ($pemohon) {
                    $query->where('unit_id', $pemohon->unitKerja->parent_id);
                })
                ->first();
        } else {
            // Jika pemohon adalah staf, maka cari Kepala Subbagian di unit kerja pemohon
            $this->kepalaPemohon = User::whereHas('roles', function ($query) {
                $query->where('name', 'Kepala Subbagian');
            })
                ->where(function ($query) use ($pemohon) {
                    $query->where('unit_id', $pemohon->unit_id);
                })
                ->first();
        }

        $this->kepalaSubbagian = User::whereHas('roles', function ($query) {
            $query->where('name', 'Kepala Subbagian');
        })
            ->where(function ($query) use ($pemohon) {
                $query->where('unit_id', $pemohon->unit_id);
            })
            ->first();
    }

    public function markAsCompleted($message = null)
    {
        $this->permintaan->update(['cancel' => false]);

        $alert = Str::ucfirst($this->tipe) . ' dengan kode <span class="font-bold">' .
            (!is_null($this->permintaan->kode_permintaan) ? $this->permintaan->kode_permintaan : $this->permintaan->kode_peminjaman) .
            '</span> telah Disetujui dan Selesai dengan keterangan <span class="font-bold">' . $message . '</span>';

        if ($this->kepalaSubbagian) {
            Notification::send($this->kepalaSubbagian, new UserNotification($alert, "/permintaan/{$this->tipe}/{$this->permintaan->id}"));
        }

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
        $permintaan = $this->permintaan;
        $approvers = collect($this->roleLists)->flatten(1)->values();
        $currentIndex = $approvers->search(fn($user) => $user->id === Auth::id());



        if ($status) {
            if ($currentIndex !== false && isset($approvers[$currentIndex + 1])) {
                $nextUser = $approvers[$currentIndex + 1];
                $mess = "Permintaan dengan kode {$permintaan->kode_permintaan} membutuhkan persetujuan Anda.";
                Notification::send($nextUser, new UserNotification(
                    $mess,
                    "/permintaan/permintaan/{$this->permintaan->id}"
                ));
            }
        } else {
            $mess = "Permintaan dengan kode {$permintaan->kode_permintaan} ditolak dengan keterangan: {$message}.";
            $user = $permintaan->user;
            Notification::send($user, new UserNotification(
                $mess,
                "/permintaan/permintaan/{$this->permintaan->id}"
            ));
        }

        $this->permintaan->persetujuan()->create([
            'user_id' => $this->user->id,
            'is_approved' => $status,
            'keterangan' => $message
        ]);

        $nextIndex = $this->currentApprovalIndex + 1;
        $totalApproval = $approvers->count();

        if ($nextIndex == 1 && $status) {
            $this->permintaan->update(['status' => $status]);
        } elseif ($nextIndex == 1 && !$status) {
            $this->permintaan->update([
                'status' =>  1,
                'cancel' =>  0,
                'proses' =>  0,
            ]);
        }

        if ($nextIndex === $totalApproval && $status) {
            $this->permintaan->update([
                'status' =>  1,
            ]);

            $alert = Str::ucfirst($this->tipe) . ' dengan kode <span class="font-bold">' .
                (!is_null($permintaan->kode_permintaan) ? $permintaan->kode_permintaan : $permintaan->kode_peminjaman) .
                '</span> telah Disetujui dengan keterangan <span class="font-bold">' . $message . '</span>';

            if ($this->kepalaSubbagian) {
                Notification::send($this->kepalaSubbagian, new UserNotification($alert, "/permintaan/{$this->tipe}/{$this->permintaan->id}"));
            }
        } elseif ($nextIndex === $totalApproval && !$status) {
            $this->permintaan->update([
                'status' =>  1,
                'cancel' =>  0,
                'proses' =>  0,
            ]);
        }

        return redirect()->to('permintaan/permintaan/' . $this->permintaan->id);
    }
    public function render()
    {
        return view('livewire.approval-permintaan-konsumsi');
    }
}
