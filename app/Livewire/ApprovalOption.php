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
    public $approveAfter; // Urutan yang dipilih untuk persetujuan jumlah barang
    public $finalizerRole;
    public $cancelApprovalOrder; // Urutan persetujuan setelahnya user dapat membatalkan

    // public $approvalType = 'urut'; // Mekanisme default

    public function mount()
    {
        $this->approveAfter = $this->tipe == 'permintaan' ? 'Tentukan setelah persetujuan keberapa jumlah barang akan ditentukan' : 'Tentukan setelah persetujuan keberapa peminjaman akan ditentukan';
        $unit_id = $this->unit_id;
        $this->rolesAvailable = User::whereHas('unitKerja', function ($user) use ($unit_id) {
            return $user->where('parent_id', $unit_id)->orWhere('unit_id', $unit_id);
        })->get()->pluck('roles')->flatten()->unique('id')->values();
        $latestApprovalConfiguration = \App\Models\OpsiPersetujuan::where('unit_id', $this->unit_id)
            ->where('jenis', $this->jenis)
            ->latest()
            ->first();
        $this->roles = $latestApprovalConfiguration->jabatanPersetujuan->map(function ($jabatan) {
            $data = [
                'id' => $jabatan->jabatan->id,
                'name' => $jabatan->jabatan->name,
                'limit' => $jabatan->limit,
            ];
            return $data;
        });
        $this->rolesAvailable = collect($this->rolesAvailable)
            ->reject(fn($role) => $role->id == $this->selectedRole)
            ->values(); // Tetap dalam bentuk Collection
        if ($this->tipe == 'permintaan' || true) {
            $this->pesan = 'Urutkan peran sesuai alur persetujuan. Jabatan terakhir dalam daftar, bertugas menyelesaikan proses persetujuan.';
            if ($this->jenis == 'umum') {
            }
            $this->approvalOrder = $latestApprovalConfiguration->urutan_persetujuan;
            $this->cancelApprovalOrder = $latestApprovalConfiguration->cancel_persetujuan;
            $this->finalizerRole = $latestApprovalConfiguration->jabatan_penyelesai_id;
        }
    }

    public function addRole()
    {
        // $this->validate([
        //     'selectedRole' => 'required|integer', // Pastikan selectedRole adalah ID
        // ]);

        // Pastikan role yang dipilih tidak duplikat di dalam $this->roles
        if (!collect($this->roles)->contains(fn($role) => $role['id'] == $this->selectedRole)) {
            $role = collect($this->rolesAvailable)->firstWhere('id', $this->selectedRole);
            if ($role) {
                $data = [
                    'id' => $role->id,
                    'name' => $role->name,
                    'limit' => 1,
                ];
                $this->roles[] = $data; // Tambahkan role ke daftar roles
            }
        }

        // Perbarui rolesAvailable dengan menghapus role yang memiliki ID sama
        $this->rolesAvailable = collect($this->rolesAvailable)
            ->reject(fn($role) => $role->id == $this->selectedRole)
            ->values(); // Tetap dalam bentuk Collection

        // Reset pilihan setelah menambahkan role
        $this->selectedRole = null;
    }



    public function removeRole($index)
    {
        // Ambil role yang dihapus berdasarkan indeks
        $removedRole = collect($this->roles)->get($index); // Gunakan get() karena $this->roles adalah Collection

        // Hapus role dari daftar roles
        $this->roles = collect($this->roles)->filter(function ($role, $key) use ($index) {
            return $key !== $index; // Hapus item berdasarkan indeks
        })->values(); // Reset indeks setelah filter

        // Perbarui daftar rolesAvailable
        $unit_id = $this->unit_id; // Pastikan unit_id tersedia
        $this->rolesAvailable = User::whereHas('unitKerja', function ($query) use ($unit_id) {
            $query->where('parent_id', $unit_id)->orWhere('id', $unit_id);
        })
            ->get()
            ->pluck('roles') // Ambil seluruh role dari relasi
            ->flatten()
            ->unique('id') // Hilangkan duplikasi berdasarkan ID
            ->reject(function ($role) {
                return collect($this->roles)->pluck('id')->contains($role['id']); // Hilangkan role yang sudah ada di $this->roles
            })
            ->values(); // Reset indeks setelah reject
    }



    public function updateRolesOrder($newOrder)
    {
        // Urutkan ulang daftar roles berdasarkan ID baru
        $this->roles = collect($newOrder)
            ->map(function ($roleData) {
                $existingRole = collect($this->roles)->firstWhere('id', $roleData['id']);
                if ($existingRole) {
                    $existingRole['limit'] = $roleData['limit']; // Perbarui limit
                }
                return $existingRole;
            })
            ->filter() // Hapus nilai null jika ID tidak ditemukan
            ->unique('id') // Pastikan tidak ada duplikasi ID
            ->values(); // Reset indeks array

    }


    public function saveApprovalConfiguration()
    {
        // dd($this->faker->uuid);
        // Simpan opsi persetujuan ke database
        $approvalConfiguration = \App\Models\OpsiPersetujuan::create([
            'unit_id' => $this->unit_id, // Ganti sesuai kebutuhan
            'uuid' => $this->faker->uuid, // Ganti sesuai kebutuhan
            'jenis' => $this->jenis, // Ganti sesuai kebutuhan
            'tipe' => $this->tipe, // Ganti sesuai kebutuhan
            'deskripsi' => $this->faker->text(400), // Ganti sesuai kebutuhan
            'urutan_persetujuan' => $this->approvalOrder,
            'cancel_persetujuan' => $this->cancelApprovalOrder,
            'jabatan_penyelesai_id' => $this->finalizerRole, // Jabatan terakhir sebagai penyelesaian
        ]);

        // Simpan setiap role dalam konfigurasi ke tabel jabatan_persetujuan
        foreach ($this->roles as $index => $role) {
            \App\Models\JabatanPersetujuan::create([
                'opsi_persetujuan_id' => $approvalConfiguration->id,
                'jabatan_id' => $role['id'],
                'limit' => $role['limit'],
                'urutan' => $index + 1,
            ]);
        }

        // Reset data setelah berhasil disimpan
        // $this->roles = [];
        // $this->rolesAvailable = User::with('roles')->get()
        //     ->pluck('roles')
        //     ->flatten()
        //     ->unique('id')
        //     ->values();
        // $this->approvalOrder = null;
        $this->dispatch('success', 'Konfigurasi persetujuan berhasil disimpan!');

        // session()->flash('success', 'Konfigurasi persetujuan berhasil disimpan!');
    }

    public function rolePeople($index, $value)
    {
        $rolesArray = collect($this->roles)->toArray(); // Konversi ke array
        $rolesArray[$index]['limit'] = (int)$value; // Perbarui nilai
        $this->roles = collect($rolesArray); // Konversi kembali ke koleksi

    }



    public function render()
    {
        return view('livewire.approval-option', [
            'previewOrder' => $this->roles,
        ]);
    }
}
