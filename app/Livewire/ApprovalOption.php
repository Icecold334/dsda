<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class ApprovalOption extends Component
{
    public $roles = []; // Simpan ID role
    public $user = []; // Simpan ID role
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
    public $rolesApproval = []; // Menyimpan ID roles yang memiliki approval


    // public $approvalType = 'urut'; // Mekanisme default

    public function mount()
    {
        // dd(User::whereHas('roles', function ($role) {
        //     return $role->where('name', 'Pemeriksa Barang');
        // })->get());
        if ($this->tipe == 'pengiriman') {
            $unit_id = $this->unit_id;
            $this->roles = $rolesAvailable = $this->rolesAvailable = User::whereHas('unitKerja', function ($user) use ($unit_id) {
                return $user->where('parent_id', $unit_id)->orWhere('unit_id', $unit_id);
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'Pemeriksa Barang');
            })->get()->toArray();
            $latestApprovalConfiguration = \App\Models\OpsiPersetujuan::where('unit_id', $this->unit_id)
                ->where('jenis', $this->jenis)
                ->latest()
                ->first();
            $this->finalizerRole = $latestApprovalConfiguration->user_penyelesai_id;

            // dd($rolesAvailable, $latestApprovalConfiguration->userPenyelesai);
        } else {
            $this->approveAfter = $this->tipe == 'permintaan' ? 'Tentukan setelah persetujuan keberapa jumlah barang akan ditentukan' : 'Tentukan setelah persetujuan keberapa peminjaman akan ditentukan';
            $unit_id = $this->unit_id;
            $this->rolesAvailable = User::whereHas('unitKerja', function ($user) use ($unit_id) {
                return $user->where('parent_id', $unit_id)->orWhere('unit_id', $unit_id);
            })->get()->pluck('roles')->flatten()->unique('id')->values();
            $latestApprovalConfiguration = \App\Models\OpsiPersetujuan::where('unit_id', $this->unit_id)
                ->where('jenis', $this->jenis)
                ->latest()
                ->first();
            if ($latestApprovalConfiguration) {
                $this->roles = $latestApprovalConfiguration->jabatanPersetujuan->map(fn($jabatan) => [
                    'id' => $jabatan->jabatan_id,
                    'name' => $jabatan->jabatan->name,
                    'approval' => $jabatan->jabatan->approval,
                ])->toArray();

                $this->rolesApproval = $latestApprovalConfiguration->jabatanPersetujuan
                    ->pluck('approval', 'jabatan_id')
                    ->toArray();
                // dd($this->rolesApproval);
            }
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
            $this->user = User::whereHas('unitKerja', function ($user) use ($unit_id) {
                return $user->where('parent_id', $unit_id)->orWhere('unit_id', $unit_id);
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'Penanggung Jawab');
            })->get()->toArray();
        }
    }

    public function addRole()
    {
        $this->validate([
            'selectedRole' => 'required|integer', // Pastikan selectedRole adalah ID
        ]);

        // Pastikan role yang dipilih tidak duplikat di dalam $this->roles
        if (!collect($this->roles)->contains(fn($role) => $role->id == $this->selectedRole)) {
            $role = collect($this->rolesAvailable)->firstWhere('id', $this->selectedRole);
            if ($role) {
                $this->roles[] = $role; // Tambahkan role ke daftar roles
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
        // Hapus role dari daftar roles berdasarkan indeks
        $removedRole = collect($this->roles)->get($index); // Ambil role berdasarkan indeks
        $this->roles = collect($this->roles)->filter(function ($item, $key) use ($index) {
            return $key !== $index; // Hapus item dengan indeks yang sesuai
        })->values(); // Reindex koleksi

        // Ambil ulang data rolesAvailable dari model
        $unit_id = $this->unit_id; // Pastikan $unit_id sudah didefinisikan sebelumnya
        $this->rolesAvailable = User::whereHas('unitKerja', function ($query) use ($unit_id) {
            $query->where('parent_id', $unit_id)->orWhere('unit_id', $unit_id);
        })
            ->get()
            ->pluck('roles') // Ambil seluruh data role dari relasi
            ->flatten()
            ->unique('id')
            ->reject(fn($role) => collect($this->roles)->pluck('id')->contains($role->id)) // Hilangkan role yang sudah ada di $this->roles
            ->values();
    }


    public function updateRolesOrder($newOrder)
    {
        // Urutkan ulang daftar roles berdasarkan ID baru
        $this->roles = collect($newOrder)
            ->map(fn($id) => collect($this->roles)->firstWhere('id', $id))
            ->filter() // Hapus nilai null jika ID tidak ditemukan
            ->unique('id') // Hapus role dengan ID yang sama
            ->values(); // Reset indeks array
    }

    public function setApprovalStatus($roleId, $status)
    {
        // Pastikan $rolesApproval adalah array
        if (!is_array($this->rolesApproval)) {
            $this->rolesApproval = [];
        }
        // Set status untuk role tertentu
        $this->rolesApproval[$roleId] = $status;
    }

    public function saveApprovalConfiguration()
    {

        $arr = $this->tipe == 'pengiriman' ?
            [
                'unit_id' => $this->unit_id, // Ganti sesuai kebutuhan
                'uuid' => $this->faker->uuid, // Ganti sesuai kebutuhan
                'jenis' => $this->jenis, // Ganti sesuai kebutuhan
                'tipe' => $this->tipe, // Ganti sesuai kebutuhan
                'deskripsi' => $this->faker->text(400), // Ganti sesuai kebutuhan
                'user_penyelesai_id' => $this->finalizerRole, // Jabatan terakhir sebagai penyelesaian
            ]
            :
            [
                'unit_id' => $this->unit_id, // Ganti sesuai kebutuhan
                'uuid' => $this->faker->uuid, // Ganti sesuai kebutuhan
                'jenis' => $this->jenis, // Ganti sesuai kebutuhan
                'tipe' => $this->tipe, // Ganti sesuai kebutuhan
                'deskripsi' => $this->faker->text(400), // Ganti sesuai kebutuhan
                'urutan_persetujuan' => $this->approvalOrder,
                'cancel_persetujuan' => $this->cancelApprovalOrder,
                'jabatan_penyelesai_id' => $this->finalizerRole, // Jabatan terakhir sebagai penyelesaian
            ];
        // dd($this->faker->uuid);
        // Simpan opsi persetujuan ke database
        $approvalConfiguration = \App\Models\OpsiPersetujuan::create($arr);

        // dd($this->rolesApproval);
        // Simpan setiap role dalam konfigurasi ke tabel jabatan_persetujuan
        foreach ($this->roles as $index => $role) {
            $jabatanId = $role['id'];
            $approvalStatus = $this->rolesApproval[$jabatanId] ?? 0;
            \App\Models\JabatanPersetujuan::create([
                'opsi_persetujuan_id' => $approvalConfiguration->id,
                'jabatan_id' => $jabatanId,
                'urutan' => $index + 1,
                'approval' => $approvalStatus,
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

    public function render()
    {
        return view('livewire.approval-option', [
            'previewOrder' => $this->roles,
        ]);
    }
}
