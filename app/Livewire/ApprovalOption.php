<?php

namespace App\Livewire;

use App\Models\KategoriStok;
use App\Models\User;
use Livewire\Component;

class ApprovalOption extends Component
{
    public $roles = []; // Simpan ID role
    public $user = []; // Simpan ID role
    // public $rolesAvailable;
    public $rolesAvailable = [];
    public $newRole = '';
    // public $selectedRole;
    public $selectedRole = [];
    public $tipe;
    public $jenis;
    public $kategori;
    public $pesan;
    public $approvalOrder; // Urutan yang dipilih untuk persetujuan jumlah barang
    public $approveAfter; // Urutan yang dipilih untuk persetujuan jumlah barang
    public $finalizerRole;
    public $activeKategoriId;
    public $kategoriPenanggungJawab = [];
    public $cancelApprovalOrder; // Urutan persetujuan setelahnya user dapat membatalkan
    public $rolesApproval = []; // Menyimpan ID roles yang memiliki approval
    public $kategoriUser = [];


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

            $this->kategori = KategoriStok::pluck('id', 'nama');

            $this->kategoriUser = KategoriStok::pluck('id')->mapWithKeys(fn($id) => [$id => null])->toArray();

            $this->user = User::whereHas('unitKerja', function ($user) use ($unit_id) {
                return $user->where('parent_id', $unit_id)->orWhere('unit_id', $unit_id);
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'Penanggung Jawab');
            })->get()->toArray();
        }
    }

    // public function addRole()
    // {
    //     $this->validate([
    //         'selectedRole' => 'required|integer', // Pastikan selectedRole adalah ID
    //     ]);

    //     // Pastikan role yang dipilih tidak duplikat di dalam $this->roles
    //     if (!collect($this->roles)->contains(function ($role) {
    //         return (is_array($role) && $role['id'] == $this->selectedRole) ||
    //             (is_object($role) && $role->id == $this->selectedRole);
    //     })) {
    //         $role = collect($this->rolesAvailable)->firstWhere('id', $this->selectedRole);
    //         if ($role) {
    //             $this->roles[] = $role; // Tambah role
    //         }
    //     }
    //     // Perbarui rolesAvailable dengan menghapus role yang memiliki ID sama
    //     $this->rolesAvailable = collect($this->rolesAvailable)
    //         ->reject(fn($role) => $role->id == $this->selectedRole)
    //         ->values(); // Tetap dalam bentuk Collection

    //     // Reset pilihan setelah menambahkan role
    //     $this->selectedRole = null;
    // }

    public function addRole($kategoriId)
    {
        $this->validate([
            "selectedRole.$kategoriId" => 'required|integer',
        ]);

        $roleId = $this->selectedRole[$kategoriId];
        $role = collect($this->rolesAvailable)->firstWhere('id', $roleId);

        if ($role && !collect($this->roles[$kategoriId] ?? [])->contains(fn($r) => (is_array($r) ? $r['id'] : $r->id) == $roleId)) {
            $this->roles[$kategoriId][] = is_array($role) ? $role : (array) $role;
        }

        $this->selectedRole[$kategoriId] = null;;
    }


    // public function removeRole($index)
    // {
    //     // Hapus role dari daftar roles berdasarkan indeks
    //     $removedRole = collect($this->roles)->get($index); // Ambil role berdasarkan indeks
    //     $this->roles = collect($this->roles)->filter(function ($item, $key) use ($index) {
    //         return $key !== $index; // Hapus item dengan indeks yang sesuai
    //     })->values(); // Reindex koleksi

    //     // Ambil ulang data rolesAvailable dari model
    //     $unit_id = $this->unit_id; // Pastikan $unit_id sudah didefinisikan sebelumnya
    //     $this->rolesAvailable = User::whereHas('unitKerja', function ($query) use ($unit_id) {
    //         $query->where('parent_id', $unit_id)->orWhere('unit_id', $unit_id);
    //     })
    //         ->get()
    //         ->pluck('roles') // Ambil seluruh data role dari relasi
    //         ->flatten()
    //         ->unique('id')
    //         ->reject(fn($role) => collect($this->roles)->pluck('id')->contains($role->id)) // Hilangkan role yang sudah ada di $this->roles
    //         ->values();
    // }

    public function removeRole($kategoriId, $index)
    {
        if (isset($this->roles[$kategoriId][$index])) {
            unset($this->roles[$kategoriId][$index]);
            $this->roles[$kategoriId] = array_values($this->roles[$kategoriId]);
        }
    }


    // public function updateRolesOrder($newOrder)
    // {
    //     // Urutkan ulang daftar roles berdasarkan ID baru
    //     $this->roles = collect($newOrder)
    //         ->map(fn($id) => collect($this->roles)->firstWhere('id', $id))
    //         ->filter() // Hapus nilai null jika ID tidak ditemukan
    //         ->unique('id') // Hapus role dengan ID yang sama
    //         ->values(); // Reset indeks array
    // }

    // public function updateRolesOrder($newOrder)
    // {
    //     $this->roles = collect($newOrder)->map(function ($id) {
    //         return collect($this->roles)->first(function ($role) use ($id) {
    //             return (is_array($role) && $role['id'] == $id) ||
    //                 (is_object($role) && $role->id == $id);
    //         });
    //     })->filter()->values()->all(); // filter() untuk buang null
    // }


    public function updateRolesOrder($kategoriId, $newOrder)
    {
        $this->roles[$kategoriId] = collect($newOrder)
            ->map(function ($id) use ($kategoriId) {
                return collect($this->roles[$kategoriId])->firstWhere('id', (int) $id);
            })
            ->filter()
            ->values()
            ->all(); // hasil: array lengkap ['id' => ..., 'name' => ...]
        dd($this->roles[$kategoriId]);
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
        // $arr = $this->tipe == 'pengiriman' ?
        //     [
        //         'unit_id' => $this->unit_id, // Ganti sesuai kebutuhan
        //         'uuid' => $this->faker->uuid, // Ganti sesuai kebutuhan
        //         'jenis' => $this->jenis, // Ganti sesuai kebutuhan
        //         'tipe' => $this->tipe, // Ganti sesuai kebutuhan
        //         'deskripsi' => $this->faker->text(400), // Ganti sesuai kebutuhan
        //         'user_penyelesai_id' => $this->finalizerRole, // Jabatan terakhir sebagai penyelesaian
        //     ]
        //     :
        //     [
        //         'unit_id' => $this->unit_id, // Ganti sesuai kebutuhan
        //         'uuid' => $this->faker->uuid, // Ganti sesuai kebutuhan
        //         'jenis' => $this->jenis, // Ganti sesuai kebutuhan
        //         'tipe' => $this->tipe, // Ganti sesuai kebutuhan
        //         'deskripsi' => $this->faker->text(400), // Ganti sesuai kebutuhan
        //         'urutan_persetujuan' => $this->approvalOrder,
        //         'cancel_persetujuan' => $this->cancelApprovalOrder,
        //         'jabatan_penyelesai_id' => $this->finalizerRole, // Jabatan terakhir sebagai penyelesaian
        //     ];
        // dd($this->faker->uuid);
        // Simpan opsi persetujuan ke database
        // $approvalConfiguration = \App\Models\OpsiPersetujuan::create($arr);

        // dd($this->rolesApproval);
        // Simpan setiap role dalam konfigurasi ke tabel jabatan_persetujuan
        // foreach ($this->roles as $index => $role) {
        //     $jabatanId = $role['id'];
        // $approvalStatus = $this->rolesApproval[$jabatanId] ?? 0;
        // \App\Models\JabatanPersetujuan::create([
        //     'opsi_persetujuan_id' => $approvalConfiguration->id,
        //     'jabatan_id' => $jabatanId,
        //     'urutan' => $index + 1,
        // 'approval' => $approvalStatus,
        //     ]);
        // }
        // Reset data setelah berhasil disimpan
        // $this->roles = [];
        // $this->rolesAvailable = User::with('roles')->get()
        //     ->pluck('roles')
        //     ->flatten()
        //     ->unique('id')
        //     ->values();
        // $this->approvalOrder = null;
        $userPenyelesaiId = $this->kategoriUser[$this->activeKategoriId] ?? null;

        $kategoriTerisi = collect($this->kategoriUser)
            ->filter() // Hapus null
            ->keys()
            ->toArray(); // Dapatkan kategori_id yang memiliki user

        // Data umum
        $data = [
            'uuid' => $this->faker->uuid,
            'deskripsi' => $this->faker->text(400),
            'user_penyelesai_id' => $userPenyelesaiId,
        ];

        // Data khusus tergantung tipe
        if ($this->tipe === 'pengiriman') {
            $data = array_merge($data, [
                // Pengiriman tidak menggunakan approval urutan
                'jabatan_penyelesai_id' => $this->finalizerRole,
            ]);
        } else {
            $data = array_merge($data, [
                'urutan_persetujuan' => $this->approvalOrder,
                'cancel_persetujuan' => $this->cancelApprovalOrder,
                'jabatan_penyelesai_id' => $this->finalizerRole,
                'kategori_id' => $kategoriTerisi,
            ]);
        }

        dd($data, $kategoriTerisi);

        // Simpan atau update OpsiPersetujuan berdasarkan unique combination
        $approvalConfiguration = \App\Models\OpsiPersetujuan::updateOrCreate(
            [
                'unit_id' => $this->unit_id,
                'jenis' => $this->jenis,
                'tipe' => $this->tipe,
            ],
            array_merge($data, [
                'unit_id' => $this->unit_id,
                'jenis' => $this->jenis,
                'tipe' => $this->tipe,
            ])
        );

        // Simpan jabatan persetujuan
        foreach ($this->roles as $kategoriId => $rolesInKategori) {
            foreach ($rolesInKategori as $index => $role) {
                \App\Models\JabatanPersetujuan::updateOrCreate(
                    [
                        'opsi_persetujuan_id' => $approvalConfiguration->id,
                        'jabatan_id' => $role['id'],
                    ],
                    [
                        'urutan' => $index + 1,
                    ]
                );
            }
        }
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
