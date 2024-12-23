<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Stok;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Models\StokDisetujui;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\PersetujuanPermintaanStok;
use App\Models\UnitKerja;

class ApprovalPermintaan extends Component
{
    use WithFileUploads;

    public $permintaan;
    public $currentApprovalIndex;
    public $penulis;
    public $isPenulis;
    public $showCancelOption;
    public $user;
    public $files = [];
    public $roles = [];
    public $roleLists = []; // List users per role
    public $lastRoles = []; // Status whether last user per role
    public $listApproval;
    public $showButton;

    public function mount()
    {
        $this->isPenulis = $this->permintaan->user_id === Auth::id();
        $this->penulis = Auth::user();

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
            $approval = $user->persetujuanPermintaan()
                ->where('detail_permintaan_id', $this->permintaan->id ?? 0)
                ->first();
            return $approval && $approval->status === 1; // Hanya hitung persetujuan yang berhasil
        })->count();


        // Pengecekan urutan user dalam daftar persetujuan
        $index = $allApproval->search(fn($user) => $user->id == Auth::id());
        if (collect($this->roles)->count() > 1) {
            if ($index === 0) {
                // Jika user adalah yang pertama dalam daftar
                $currentUser = $allApproval[$index];
                $this->showButton = !$currentUser->persetujuanPermintaan()
                    ->where('detail_permintaan_id', $this->permintaan->id ?? 0)
                    ->exists();
            } else {
                // Jika user berada di tengah atau akhir
                $previousUser = $index > 0 ? $allApproval[$index - 1] : null;
                $currentUser = $allApproval[$index];
                $previousApprovalStatus = optional(optional($previousUser)->persetujuanPermintaan()
                    ?->where('detail_permintaan_id', $this->permintaan->id ?? 0)
                    ->first())->status;


                $this->showButton = $previousUser &&
                    !$currentUser->persetujuanPermintaan()
                        ->where('detail_permintaan_id', $this->permintaan->id ?? 0)
                        ->exists() &&
                    $previousApprovalStatus === 1; // Tombol hanya muncul jika persetujuan sebelumnya berhasil (true)
            }
        }
        $cancelAfter = $this->permintaan->opsiPersetujuan->cancel_persetujuan;

        $this->showCancelOption = $this->currentApprovalIndex >= $cancelAfter;
        // dd($this->currentApprovalIndex);
    }

    public function cancelRequest()
    {
        // Logika untuk membatalkan permintaan
        $this->permintaan->update(['cancel' => true]);
        session()->flash('message', 'Permintaan telah dibatalkan.');
    }

    public function approveConfirmed($status, $message = null)
    {
        $this->permintaan->persetujuan()->create([
            'detail_permintaan_id' => $this->permintaan->id,
            'user_id' => $this->user->id,
            'status' => $status,
            'keterangan' => $message
        ]);


        return redirect()->route('permintaan-stok.show', ['permintaan_stok' => $this->permintaan->id]);
    }

    public function render()
    {
        return view('livewire.approval-permintaan');
    }
}
