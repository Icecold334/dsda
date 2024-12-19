<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class ApprovalOption extends Component
{
    // public $roles = ['Penanggung Jawab', 'Pejabat Pembuat Komitmen', 'Kepala Seksi']; // Contoh data awal
    public $roles = []; // Contoh data awal
    public $rolesAvailable;
    public $newRole = '';
    public $selectedRole;
    public $tipe;
    public $jenis;
    public $pesan;
    public $approvalType = 'urut'; // Mekanisme default



    public function mount()
    {
        if ($this->tipe == 'permintaan') {
            if ($this->jenis == 'umum') {
                $this->pesan = 'Urutkan peran sesuai alur persetujuan. Jabatan terakhir dalam daftar bertugas menyelesaikan proses persetujuan.';
            }
        }
        $unit_id = $this->unit_id;
        $this->rolesAvailable = User::whereHas('unitKerja', function ($user) use ($unit_id) {
            return $user->where('parent_id', $unit_id)->orWhere('unit_id', $unit_id);
        })
            ->get()
            ->pluck('roles.*.name') // Ambil nama role dari relasi
            ->flatten()
            ->unique()
            ->values()
            ->toArray();
    }



    public function addRole()
    {
        $this->validate(['selectedRole' => 'required|string']);

        // Pastikan role yang dipilih tidak duplikat
        if (!in_array($this->selectedRole, $this->roles)) {
            $this->roles[] = $this->selectedRole;
        }
        $this->rolesAvailable =
            collect($this->rolesAvailable)->diff($this->roles)->values();

        $this->selectedRole = null; // Reset pilihan setelah menambahkan role
    }

    public function removeRole($index)
    {
        unset($this->roles[$index]);
        $this->roles = array_values($this->roles); // Reindex array
    }

    public function updateRolesOrder($newOrder)
    {
        // Hapus elemen yang duplikat dari $newOrder
        $this->roles = collect($newOrder)->unique()->values()->toArray();
    }

    public function saveApprovalConfiguration()
    {
        // Simpan konfigurasi ke database atau session
        // Contoh:
        session()->flash('message', 'Konfigurasi persetujuan berhasil disimpan.');
    }
    public function render()
    {
        return view('livewire.approval-option', [
            'previewOrder' => $this->roles,
            'previewMechanism' => $this->approvalType === 'sequential' ? 'Urut' : 'Bersamaan',
        ]);
    }
}
