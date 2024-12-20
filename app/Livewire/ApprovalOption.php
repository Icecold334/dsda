<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class ApprovalOption extends Component
{
    public $roles = []; // Simpan ID role
    public $rolesAvailable;
    public $newRole = '';
    public $selectedRole;
    public $tipe;
    public $jenis;
    public $pesan;
    public $approvalOrder; // Urutan yang dipilih untuk persetujuan jumlah barang
    public $approvalType = 'urut'; // Mekanisme default

    public function mount()
    {
        if ($this->tipe == 'permintaan') {
            if ($this->jenis == 'umum') {
                $this->pesan = 'Urutkan peran sesuai alur persetujuan.';
            }
        }

        $unit_id = $this->unit_id;
        $this->rolesAvailable = User::whereHas('unitKerja', function ($user) use ($unit_id) {
            return $user->where('parent_id', $unit_id)->orWhere('unit_id', $unit_id);
        })
            ->get()
            ->pluck('roles') // Ambil seluruh data role dari relasi
            ->flatten()
            ->unique('id')
            ->values();
    }

    public function addRole()
    {
        $this->validate([
            'selectedRole' => 'required|integer', // Pastikan selectedRole adalah ID
        ]);

        // Pastikan role yang dipilih tidak duplikat
        if (!in_array($this->selectedRole, array_column($this->roles, 'id'))) {
            $role = collect($this->rolesAvailable)->firstWhere('id', $this->selectedRole);
            if ($role) {
                $this->roles[] = $role; // Tambahkan data role (dengan ID) ke daftar roles
            }
        }

        // Perbarui rolesAvailable dengan menghapus role yang sudah dipilih
        $this->rolesAvailable = collect($this->rolesAvailable)
            ->reject(fn($role) => $role['id'] == $this->selectedRole)
            ->values(); // Tetap sebagai Collection, tanpa toArray()

        $this->selectedRole = null; // Reset pilihan setelah menambahkan role
    }


    public function removeRole($index)
    {
        // Hapus role dari daftar roles berdasarkan indeks
        $removedRole = $this->roles[$index];
        unset($this->roles[$index]);
        $this->roles = array_values($this->roles); // Reindex array

        // Tambahkan kembali role yang dihapus ke rolesAvailable
        $this->rolesAvailable[] = $removedRole;
        $this->rolesAvailable = collect($this->rolesAvailable)->unique('id')->values()->toArray();
    }

    public function updateRolesOrder($newOrder)
    {
        // Urutkan ulang daftar roles berdasarkan ID baru
        $this->roles = collect($newOrder)
            ->map(fn($id) => collect($this->roles)->firstWhere('id', $id))
            ->filter()
            ->values()
            ->toArray();
    }

    public function saveApprovalConfiguration()
    {
        // Simpan konfigurasi ke database atau session
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
