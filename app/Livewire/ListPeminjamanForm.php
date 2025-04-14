<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Aset;
use App\Models\User;
use App\Models\Ruang;
use BaconQrCode\Writer;
use Livewire\Component;
use App\Models\Kategori;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use App\Models\PeminjamanAset;
use App\Models\OpsiPersetujuan;
use App\Models\WaktuPeminjaman;
use App\Models\DetailPeminjamanAset;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserNotification;
use BaconQrCode\Renderer\GDLibRenderer;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PersetujuanPeminjamanAset;
use Illuminate\Support\Facades\Notification;

class ListPeminjamanForm extends Component
{

    use WithFileUploads;
    public $approve_after;
    public $approvals;
    public $showNew;
    public $tipe;
    public $newWaktu;
    public $waktus;
    public $unit_id;
    public $sub_unit_id;
    public $tanggal_peminjaman;
    public $keterangan;
    public $last;
    public $atasanLangsung;
    public $peminjaman;
    public $list = [];
    public $newAsetId;
    public $newMerkJenis;
    public $newPeminjaman = 1;
    public $newDisetujui;
    public $newPeserta;
    public $newKeterangan;
    public $fotoPengembalian;
    public $keteranganPengembalian;
    public $newBarangId; // Input for new barang
    public $newBarang; // Input for new barang
    public $newJumlah; // Input for new jumlah
    public $newDokumen; // Input for new dokumen
    public $showAdd; // Input for new dokumen
    public $requestIs;
    public $newFoto;
    public $availableJumlah = 0;
    public $barangSuggestions = []; // Suggestions for barang
    public $assetSuggestions = [];
    public $asets = [];
    public $ruangs = [];
    public $suggestions = [
        'barang' => [],
        'aset' => []
    ];

    public function fetchSuggestions($field, $value = '')
    {
        $this->suggestions[$field] = [];
        // if ($value) {
        $key = Str::slug($value);

        if ($field === 'aset') {
            $tipe = 'KDO';
            $this->suggestions[$field] = Aset::whereHas('kategori', function ($kategori) use ($tipe) {
                return $kategori->where('nama', $tipe);
            })->where('slug', 'like', '%' . $key . '%')
                ->pluck('nama')->toArray();
        }
        // }
    }
    public function selectSuggestion($field, $value)
    {
        // if ($field === 'aset') {
        //     $this->newAset = $value;
        // }
        $this->suggestions[$field] = [];
    }
    public function blurSpecification($key)
    {
        $this->suggestions[$key] = [];
    }

    public function addToList()
    {
        // $this->validate([
        //     'newAset' => 'required',
        //     'newPermintaan' => 'required|integer|min:1',
        //     'newTanggalPeminjaman' => 'required|date',
        //     'newTanggalPengembalian' => 'required|date|after:newTanggalPeminjaman',
        //     'newKeterangan' => 'nullable|string',
        // ]);

        if ($this->newJumlah > $this->availableJumlah) {
            $this->dispatch('error', 'Jumlah melebihi stok aset yang tersedia.');
            return;
        }

        $this->list[] = [
            'id' => null,
            'aset_id' => $this->newAsetId,
            'aset_name' => $this->tipe == 'Ruangan'
                ? optional(Ruang::find($this->newAsetId))->nama
                : optional(Aset::find($this->newAsetId))->nama,
            'aset_merk' => Aset::find($this->newAsetId)->merk->nama,
            'aset_noseri' => Aset::find($this->newAsetId)->noseri,
            'foto' => Aset::find($this->newAsetId)?->foto
                ? asset('storage/asetImg/' . Aset::find($this->newAsetId)->foto)
                : asset('img/default-pic-thumb.png'),

            'waktu_id' => $this->newWaktu,
            'waktu' => WaktuPeminjaman::find($this->newWaktu),
            'jumlah' => $this->newJumlah,
            'jumlah_peserta' => $this->newPeserta,
            'keterangan' => $this->newKeterangan,
            'img' => $this->newDokumen,
        ];
        $this->dispatch('listCount', count: count($this->list));

        $this->reset(['newAsetId', 'newJumlah', 'newPeserta', 'newDokumen', 'newWaktu', 'newKeterangan', 'newFoto']);
        // $this->availableJumlah = 0;
    }

    public function removeFromList($index)
    {
        unset($this->list[$index]);
        $this->list = array_values($this->list); // Reindex the array
        $this->dispatch('listCount', count: count($this->list));
    }

    #[On('unit_id')]
    public function fillUnitId($unit_id)
    {
        $this->unit_id = $unit_id;
    }
    #[On('peminjaman')]
    public function fillTipe($peminjaman)
    {
        $this->tipe = $peminjaman;
        $tipe = $this->tipe;
        $kategori = Kategori::where('nama', $tipe)->first();
        // dd($kategori->children);
        // if ($this->tipe == 'Ruangan') {
        //     $this->showAdd = $this->newAsetId && $this->newWaktu && $this->newPeserta && $this->newKeterangan && $this->newDokumen;
        // }
        $cond = true;
        // dd($tipe);
        if ($tipe == 'Ruangan') {
            $this->asets =  Ruang::when($cond, function ($query) {
                $query->whereHas('user', function ($query) {
                    return $query->whereHas('unitKerja', function ($query) {
                        return $query->where('parent_id', $this->unit_id)
                            ->orWhere('id', $this->unit_id);
                    });
                });
            })->where('peminjaman', 1)->get();
        } else {
            $this->asets =
                Aset::when($cond, function ($query) {
                    $query->whereHas('user', function ($query) {
                        return $query->whereHas('unitKerja', function ($query) {
                            return $query->where('parent_id', $this->unit_id)
                                ->orWhere('id', $this->unit_id);
                        });
                    });
                })->whereHas('kategori', function ($query) use ($kategori) {
                    return $query->where('parent_id', $kategori->id)->orWhere('id', $kategori->id);
                })->where('peminjaman', 1)->get();
        }
    }

    #[On('sub_unit_id')]
    public function fillSubUnitId($sub_unit_id)
    {
        $this->sub_unit_id = $sub_unit_id;
    }
    #[On('keterangan')]
    public function fillKeterangan($keterangan)
    {
        $this->keterangan = $keterangan;
    }

    #[On('tanggal_permintaan')]
    public function fillTanggalPermintaan($tanggal_permintaan)
    {
        $this->tanggal_peminjaman = $tanggal_permintaan;
    }

    public function removePhoto()
    {
        $this->newDokumen = null;
    }

    public function saveData()
    {
        $latestApprovalConfiguration = OpsiPersetujuan::where('jenis', Str::lower($this->tipe == 'Peralatan Kantor' ? 'alat' : $this->tipe))
            ->where('unit_id', $this->unit_id)
            ->where('created_at', '<=', now()) // Pastikan data sebelum waktu saat ini
            ->latest()
            ->first();
        $kodepeminjaman = Str::random(10); // Generate a unique code

        // Create Detail peminjaman Stok
        $detailPeminjaman = DetailPeminjamanAset::create([
            // 'kode_peminjaman' => $kodepeminjaman,
            'kode_peminjaman' => $this->generateQRCode(),
            'tanggal_peminjaman' => strtotime(datetime: $this->tanggal_peminjaman),
            'unit_id' => $this->unit_id,
            'sub_unit_id' => $this->sub_unit_id ?? null,
            'user_id' => Auth::id(),
            'kategori_id' => Kategori::where('nama', $this->tipe)->first()->id,
            'approval_configuration_id' => $latestApprovalConfiguration->id,
            'keterangan' => $this->keterangan,
            'status' => null
        ]);
        $this->peminjaman = $detailPeminjaman;
        foreach ($this->list as $item) {
            $storedFilePath = $item['img'] ? str_replace('suratPeminjaman/', '', $item['img']->storeAs(
                'suratPeminjaman', // Directory
                $item['img']->getClientOriginalName(), // File name
                'public' // Storage disk
            )) : null;
            PeminjamanAset::create([
                'detail_peminjaman_id' => $detailPeminjaman->id,
                'user_id' => Auth::id(),
                'aset_id' => $item['aset_id'] ?? null,
                'deskripsi' => $item['keterangan'] ?? null,
                // 'catatan' => $item['catatan'] ?? null,
                'img' => $storedFilePath,
                'waktu_id' => $item['waktu_id'],
                'jumlah_orang' => $item['jumlah_peserta'],
                'jumlah' => $item['jumlah'],
            ]);
        }
        $message = 'Permintaan ' . $detailPeminjaman->kategori->nama . ' <span class="font-bold">' . $detailPeminjaman->kode_peminjaman . '</span> membutuhkan persetujuan Anda.';

        $this->tipe = Str::contains($this->peminjaman->getTable(), 'permintaan') ? 'permintaan' : 'peminjaman';

        $user = Auth::user();
        $roles = $this->peminjaman->opsiPersetujuan->jabatanPersetujuan->pluck('jabatan.name')->toArray();
        $roleLists = [];
        $lastRoles = [];

        $date = Carbon::parse($this->peminjaman->created_at);

        foreach ($roles as $role) {
            $users = User::whereHas('roles', function ($query) use ($role) {
                $query->where('name', 'LIKE', '%' . $role . '%');
            })
                ->where(function ($query) use ($date) {
                    $query->whereHas('unitKerja', function ($subQuery) {
                        $subQuery->where('parent_id', $this->peminjaman->unit_id);
                    })
                        ->orWhere('unit_id', $this->peminjaman->unit_id);
                })
                ->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))
                ->limit(1)
                ->get();

            $propertyKey = Str::slug($role); // Generate dynamic key for roles
            $roleLists[$propertyKey] = $users;
            $lastRoles[$propertyKey] = $users->search(fn($user) => $user->id == Auth::id()) === $users->count() - 1;
        }

        // Calculate listApproval dynamically
        // $tipe = $this->permintaan->jenisStok->nama;
        // $unit = UnitKerja::find($this->permintaan->unit_id);
        $allApproval = collect();

        // Hitung jumlah persetujuan yang dibutuhkan
        $listApproval = collect($roleLists)->flatten(1)->count();

        // Menggabungkan semua approval untuk pengecekan urutan
        $allApproval = collect($roleLists)->flatten(1);
        $currentApprovalIndex = $allApproval->filter(function ($user) {
            $approval = $user->{"persetujuan{$this->tipe}"}()
                ->where('detail_' . $this->tipe . '_id', $this->peminjaman->id ?? 0)
                ->first();
            return $approval && $approval->status === 1; // Hanya hitung persetujuan yang berhasil
        })->count();


        // Pengecekan urutan user dalam daftar persetujuan
        $index = $allApproval->search(fn($user) => $user->id == Auth::id());
        // dd($allApproval);
        $nextUser = $allApproval[$currentApprovalIndex];
        if (collect($roles)->count() > 1) {
            if ($index === 0) {
                // Jika user adalah yang pertama dalam daftar
                $currentUser = $allApproval[$index];
            } else {
                // Jika user berada di tengah atau akhir
                $previousUser = $index > 0 ? $allApproval[$index - 1] : null;
                $currentUser = $allApproval[$index];
                $previousApprovalStatus = optional(optional($previousUser)->{"persetujuan{$this->tipe}"}()
                    ?->where('detail_' . $this->tipe . '_id', $this->peminjaman->id ?? 0)
                    ->first())->status;
            }
        }
        // $role_id = $latestApprovalConfiguration->jabatanPersetujuan->first()->jabatan->id;
        // $user = Role::where('id', $role_id)->first()?->users->where('unit_id', $this->unit_id)->first();
        Notification::send($nextUser, new UserNotification($message, "/permintaan/peminjaman/{$detailPeminjaman->id}"));

        $messageAtasan = 'Permintaan ' . $detailPeminjaman->kategori->nama . ' <span class="font-bold">' . $detailPeminjaman->kode_peminjaman . '</span> telah diajukan oleh staf Anda dan memerlukan perhatian Anda.';
        $pemohon = $this->peminjaman->user;

        // Reset atasan langsung
        $this->atasanLangsung = null;

        // 1. Jika pemohon adalah Kepala Unit → Atasan langsung null
        if ($pemohon->hasRole('Kepala Unit') && $this->peminjaman->unit_id) {
            $this->atasanLangsung = null;
        }
        // 2. Jika pemohon adalah Kepala Subbagian, cari Kepala Unit di unit utama
        elseif ($pemohon->hasRole('Kepala Subbagian') && $this->peminjaman->sub_unit_id) {
            $this->atasanLangsung = User::role('Kepala Unit')
                ->where('unit_id', $this->peminjaman->unit->id) // Cari Kepala Unit di unit utama
                ->first();
        }
        // 3. Jika pemohon BUKAN Kepala Unit dan ada sub unit → Cari Kepala Subbagian di sub unit
        elseif ($this->peminjaman->sub_unit_id) {
            $this->atasanLangsung = User::role('Kepala Subbagian')
                ->where('unit_id', $this->peminjaman->sub_unit_id)
                ->first();
        }
        if ($this->atasanLangsung) {
            // Kirim notifikasi ke atasan langsung jika ditemukan
            Notification::send($this->atasanLangsung, new UserNotification($messageAtasan, "/permintaan/peminjaman/{$detailPeminjaman->id}"));
        }

        return redirect()->to('permintaan/peminjaman/' . $this->peminjaman->id)->with('tanya', 'berhasil');
    }

    public function mount()
    {
        $this->showNew = Request::is('permintaan/add/peminjaman*');
        if ($this->last) {


            $this->keterangan = $this->last->keterangan;
            $this->dispatch('keterangan', keterangan: $this->keterangan);

            $this->sub_unit_id = $this->last->sub_unit_id;
            $this->dispatch('sub_unit_id', sub_unit_id: $this->sub_unit_id);

            $this->fillTipe(Kategori::find($this->last->kategori_id)->nama);
        }
        if ($this->peminjaman) {
            $this->fillTipe($this->peminjaman->kategori->nama);
            $this->tanggal_peminjaman = $this->peminjaman->tanggal_peminjaman;
            $this->keterangan = $this->peminjaman->keterangan;
            $this->unit_id = $this->peminjaman->unit_id;
            $this->sub_unit_id = $this->peminjaman->sub_unit_id;
            $this->tipe = Kategori::find($this->peminjaman->kategori_id)->nama;
            foreach ($this->peminjaman->peminjamanAset as $key => $value) {
                $this->list[] = [
                    'id' => $value->id,
                    'user_id' => $value->user_id,
                    'detail_peminjaman_id' => $value->detail_peminjaman_id,
                    'aset_id' => $value->aset_id,
                    'approved_aset_id' => $value->approved_aset_id ?? null,
                    'aset_name' => $this->tipe == 'Ruangan'
                        ? Ruang::find($value->aset_id)?->nama
                        : Aset::find($value->aset_id)?->nama,
                    'aset_merk' => Aset::find($value->aset_id)->merk->nama,
                    'aset_noseri' => Aset::find($value->aset_id)->noseri,
                    'approved_aset_name' => $this->tipe == 'Ruangan'
                        ? Ruang::find($value->approved_aset_id)?->nama
                        : Aset::find($value->approved_aset_id)?->nama,
                    'waktu_id' => $value->waktu_id,
                    'approved_waktu_id' => $value->approved_waktu_id ?? null,
                    'waktu' => WaktuPeminjaman::find($value->waktu_id),
                    'approved_waktu' => WaktuPeminjaman::find($value->approved_waktu_id) ?? null,
                    'jumlah' => $value->jumlah,
                    'avilable_jumlah' => Aset::find($value->approved_aset_id)?->jumlah,
                    'approved_jumlah' => $value->jumlah_approve ?? null,
                    'jumlah_peserta' => $value->jumlah_orang,
                    'img_pengembalian' => $value->img_pengembalian,
                    'keterangan_pengembalian' => $value->keterangan_pengembalian,
                    'keterangan' => $value->deskripsi,
                    'img' => $value->img,
                    'foto' => Aset::find($value->aset_id)?->foto
                        ? asset('storage/asetImg/' . Aset::find($value->aset_id)?->foto)
                        : asset('img/default-pic.png'),
                    'fix' => $this->tipe == 'Ruangan' ? $value->approved_aset_id && $value->approved_waktu_id : ($this->tipe == 'KDO' ? $value->approved_aset_id : $value->approved_aset_id  && $value->jumlah_approve)
                ];
                // && $value->approved_waktu_id
            }
            $approve_after = $this->approve_after = $this->peminjaman->opsiPersetujuan->jabatanPersetujuan->pluck('jabatan.name')->toArray()[$this->peminjaman->opsiPersetujuan->urutan_persetujuan - 1];

            $this->approvals = PersetujuanPeminjamanAset::where('status', true)->where('detail_peminjaman_id', $this->peminjaman->id)
                ->whereHas('user', function ($query) use ($approve_after) {
                    $query->role($approve_after); // Muat hanya persetujuan dari kepala_seksi
                })
                ->pluck('detail_peminjaman_id') // Ambil hanya detail_permintaan_id yang sudah disetujui
                ->toArray();
        } else {
            $this->fillTipe($this->tipe);
        };
        $this->waktus = WaktuPeminjaman::all();
        $this->tanggal_peminjaman = Carbon::now()->format('Y-m-d');
    }


    public $availHours;

    public function updatedNewAsetId()
    {
        $selectedAset = Aset::find($this->newAsetId);

        // Pastikan aset tidak null sebelum mengakses properti 'foto'
        $this->newFoto = $selectedAset?->foto
            ? asset('storage/asetImg/' . $selectedAset->foto)
            : asset('img/default-pic-thumb.png');

        $this->availableJumlah = $selectedAset?->jumlah ?? 0;
        // Ambil waktu yang telah di-booking untuk aset yang dipilih pada hari ini
        // $bookedTimes = PeminjamanAset::where('aset_id', $selectedAsetId)
        //     ->whereHas('detailPeminjaman', function ($query) {
        //         $todayStart = strtotime($this->tanggal_peminjaman); // Waktu mulai (00:00:00)
        //         $todayEnd = strtotime($this->tanggal_peminjaman . ' 23:59:59'); // Waktu akhir (23:59:59)


        //         $query->whereBetween('tanggal_peminjaman', [$todayStart, $todayEnd])
        //             ->where(function ($query) {
        //                 $query->whereNull('status')->orWhere('status', '!=', 0);
        //             });
        //     })
        //     ->pluck('waktu_id')
        //     ->toArray();
        // $waktus = WaktuPeminjaman::all();
        // $this->waktus = $waktus->reject(function ($item) use ($bookedTimes) {
        //     return in_array($item->id, $bookedTimes);
        // });
    }

    public function approveItem($index, $message)
    {
        // // Validasi input
        // $this->validate([
        //     "list.$index.approved_aset_id" => 'required',
        //     "list.$index.approved_waktu_id" => 'required',
        // ], [
        //     "list.$index.approved_aset_id.required" => "Layanan untuk item ke-{$index} harus dipilih.",
        //     "list.$index.approved_waktu_id.required" => "Waktu untuk item ke-{$index} harus dipilih.",
        // ]);

        // Tandai item sebagai "fix"
        $this->list[$index]['fix'] = true;


        // Simpan perubahan ke database (misalnya, tabel PeminjamanAset)
        $peminjamanAset = PeminjamanAset::find($this->list[$index]['id']);
        // dd($peminjamanAset, $message);
        if ($peminjamanAset) {
            $approvedAsetId = $this->list[$index]['approved_aset_id'];
            $approvedJumlah = $this->list[$index]['approved_jumlah'] ?? 1;
            if ($this->tipe == 'Peralatan Kantor') {
                // Cek stok terlebih dahulu
                $aset = Aset::find($approvedAsetId);
                if ($aset && $aset->jumlah >= $approvedJumlah) {
                    $aset->decrement('jumlah', $approvedJumlah); // Kurangi stok
                } else {
                    $this->dispatch('error', ['Stok aset tidak mencukupi.']);
                    return;
                }

                // Siapkan data untuk update
                $data = [
                    'approved_aset_id' => $approvedAsetId,
                    'approved_waktu_id' => $this->list[$index]['approved_waktu_id'],
                    'jumlah_approve' => $approvedJumlah,
                    'catatan_approved' => $message,
                ];
            } else {
                // Untuk tipe selain Peralatan Kantor
                $data = [
                    'approved_aset_id' => $approvedAsetId,
                    'approved_waktu_id' => $this->list[$index]['approved_waktu_id'],
                    'catatan_approved' => $message,
                ];
            }
            $peminjamanAset->update($data);
        }
        $this->dispatch('success', "Peminjaman disetujui!");

        // session()->flash('message', "Item pada baris ke-{$index} berhasil disetujui.");
    }

    private function generateQRCode()
    {
        $userId = Auth::id(); // Dapatkan ID pengguna yang login
        $qrName = strtoupper(Str::random(16)); // Buat nama file acak untuk QR code

        // Tentukan folder dan path target file
        $qrFolder = "qr_peminjaman";
        $qrTarget = "{$qrFolder}/{$qrName}.png";

        // Konten QR Code (contohnya URL)
        $qrContent = url("/qr/peminjaman/{$userId}/{$qrName}");

        // Pastikan direktori untuk QR Code tersedia
        if (!Storage::disk('public')->exists($qrFolder)) {
            Storage::disk('public')->makeDirectory($qrFolder);
        }

        // Konfigurasi renderer untuk menggunakan GD dengan ukuran 400x400
        $renderer = new GDLibRenderer(500);
        $writer = new Writer($renderer);

        // Path absolut untuk menyimpan file
        $filePath = Storage::disk('public')->path($qrTarget);

        // Hasilkan QR Code ke file
        $writer->writeFile($qrContent, $filePath);

        // Periksa apakah file berhasil dibuat
        if (Storage::disk('public')->exists($qrTarget)) {
            return $qrName; // Kembalikan nama file QR
        } else {
            return "0"; // Kembalikan "0" jika gagal
        }
    }

    public function backItem($index)
    {
        $peminjamanAset = PeminjamanAset::find($this->list[$index]['id']);

        if (!$peminjamanAset) {
            $this->dispatch('error', ['Data peminjaman tidak ditemukan.']);
            return;
        }

        $this->validate([
            'fotoPengembalian' => 'required|image|max:2048',
        ]);

        $file = $this->fotoPengembalian;
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('pengembalianUmum', $fileName, 'public');
        $fileNameOnly = basename($path);

        $peminjamanAset->update([
            'img_pengembalian' => $fileNameOnly,
            'keterangan_pengembalian' => $this->keteranganPengembalian,
        ]);

        // Kembalikan stok ke aset sesuai approved_jumlah
        $aset = Aset::find($peminjamanAset->approved_aset_id);
        if ($aset && $peminjamanAset->jumlah_approve) {
            $aset->increment('jumlah', $peminjamanAset->jumlah_approve);
        }

        // Optional: update tampilan di Livewire list
        $this->list[$index]['fix'] = false;

        $kategori = $this->peminjaman->kategori;
        $unitId = $this->peminjaman->sub_unit_id;
        $users = User::role('Customer Services')
            ->where('unit_id', $unitId)
            ->get();
        $alert = 'Peminjaman dengan kode <span class="font-bold">' .  $this->peminjaman->kode_peminjaman .
            '</span> Sudah Mengembalikan Peminjaman <span class="font-bold">' .  $kategori->nama .
            '</span> dengan Keterangan <span class="font-bold">' . $this->keteranganPengembalian . '</span>';

        Notification::send($users, new UserNotification($alert, "/permintaan/peminjaman/{$this->peminjaman->id}"));

        if ($index === count($this->list) - 1) {
            $this->peminjaman->update([
                'proses' => 1
            ]);
        }

        $this->fotoPengembalian = null;
        $this->keteranganPengembalian = null;
        $this->dispatch('success', 'Item berhasil dikembalikan dan stok telah diperbarui.');
    }


    public function render()
    {
        return view('livewire.list-peminjaman-form');
    }
}
