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
    public $penanggungjawab;
    public $penulis;
    public $user;
    public $date;

    public $pjList;
    public $lastPj;
    public $pjGudangList;
    public $lastPjGudang;


    public $tuList;
    public $lasttu;

    public $kaunitList;
    public $lastkaunit;

    public $pemeliharaanList;
    public $lastpemeliharaan;

    public $kasudinList;
    public $lastkasudin;

    public $kepalaseksiList;
    public $lastKepalaseksi;
    public $kasubagList;
    public $lastKasubag;
    public $ppkList;
    public $lastPpk;
    public $pptkList;
    public $lastPptk;
    public $listApproval;
    public $roles;
    public $showButton;
    public $status;
    public $isLastUser;
    public $newApprovalFiles = []; // To hold multiple uploaded files
    public $approvalFiles = []; // To hold multiple uploaded files
    public $files = []; // To hold multiple uploaded files
    public $isPenulis = false; // Untuk menandai apakah pengguna adalah penulis

    public $lastKasubagDone = false;
    public $lasttuDone = false;
    public $lastkaunitDone = false;
    public $lastkasudinDone = false;

    public function updatedNewApprovalFiles()
    {
        // Validate each uploaded file
        $this->validate([
            'approvalFiles.*' => 'file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar',
        ]);
        foreach ($this->newApprovalFiles as $file) {
            // $this->attachments[] = $file->store('attachments', 'public');
            $this->approvalFiles[] = $file;
        }

        $this->dispatch('file_approval', count: count($this->approvalFiles));
        // Clear the newAttachments to make ready for next files
        $this->reset('newApprovalFiles');
    }

    public function markAsCompleted()
    {
        $this->permintaan->update(['cancel' => false]);
        session()->flash('message', 'Permintaan telah selesai.');
    }


    public function cancelRequest()
    {
        // Logika untuk membatalkan permintaan
        $this->permintaan->update(['cancel' => true]);
        session()->flash('message', 'Permintaan telah dibatalkan.');
    }


    public function mount()
    {

        $this->isPenulis = $this->permintaan->user_id === Auth::id();

        if ($this->permintaan->persetujuan->where('file')) {
            $this->files = $this->permintaan->persetujuan->filter(fn($persetujuan) => $persetujuan->file !== null)->pluck('file');
        } else {
            $this->files = [];
        }

        $this->user = Auth::user();
        $this->roles = 'penanggungjawab|ppk|pptk';
        $this->penulis = $this->permintaan->user;

        $date = Carbon::parse($this->permintaan->created_at);
        $unit = UnitKerja::find($this->unit_id);
        $roleMapping = [
            'Penanggung Jawab' => 'pjList',
            'Pejabat Pembuat Komitmen' => 'ppkList',
            'Pejabat Pelaksana Teknis Kegiatan' => 'pptkList',
            'Penjaga Gudang' => 'pjGudangList',
            // 'Kepala Subbagian' => 'tuList',
            'Kepala Unit' => 'kaunitList',
            // 'Kepala Seksi Pemeliharaan' => 'pemeliharaanList',
            'Kepala Suku Dinas' => 'kasudinList',
            'Kepala Seksi' => 'kepalaseksiList',
            'Kepala Subbagian' => 'kasubagList',
        ];

        foreach ($roleMapping as $role => $property) {
            $users =
                // User::role($role)
                User::whereHas('roles', function ($query) use ($role) {
                    $query->where('name', 'LIKE', '%' . $role . '%');
                })
                ->where(function ($query) use ($date) {
                    // Prioritaskan unit anak
                    $query->whereHas('unitKerja', function ($subQuery) {
                        $subQuery->where('parent_id', $this->permintaan->unit_id); // Unit child
                    })
                        ->orWhere('unit_id', $this->permintaan->unit_id); // Unit ID yang sama
                })
                ->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))
                ->limit(1)
                ->get();

            $this->{$property} = $users;
            $this->{'last' . Str::studly($property)} = $users->search(fn($user) => $user->id == Auth::id()) === $users->count() - 1;
        }
        $roleSpecialMapping = [
            'Kepala Subbagian' => 'tuList',
            'Kepala Seksi' => 'pemeliharaanList',
        ];
        foreach ($roleSpecialMapping as $role => $property) {

            $users =
                User::whereHas('roles', function ($query) use ($role) {
                    return $query->where('name', 'LIKE', '%' . $role . '%');
                })
                ->where(function ($query) use ($date, $property) {
                    // Prioritaskan unit anak
                    $query->whereHas('unitKerja', function ($subQuery) use ($date, $property) {
                        return $subQuery->where('parent_id', $this->permintaan->unit->parent_id ? $this->permintaan->unit->parent_id : $this->permintaan->unit->id)->where('nama', 'like', '%' . ($property == 'pemeliharaanList' ? 'Seksi Pemeliharaan' : 'Subbagian Tata Usaha') . '%'); // Unit child
                    });
                })
                ->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))
                ->limit(1)
                ->get();

            $this->{$property} = $users;
            $this->{'last' . Str::studly($property)} = $users->search(fn($user) => $user->id == Auth::id()) === $users->count() - 1;
        }

        // Check approval states dynamically
        $this->lastKasubagDone = $this->checkApprovalDone('Kepala Subbagian');
        $this->lasttuDone = $this->checkApprovalDone('Kepala Subbagian', 'tu');
        $this->lastkaunitDone = $this->checkApprovalDone('Kepala Unit');
        $this->lastkasudinDone = $this->checkApprovalDone('Kepala Suku Dinas');

        $tipe = $this->permintaan->jenisStok->nama;

        if ($tipe == 'Umum') {
            $this->listApproval = $this->kepalaseksiList->merge($this->kasubagList)->count();
            $allApproval = $this->kepalaseksiList->merge($this->kasubagList)->merge($this->pjGudangList);
        } elseif ($tipe == 'Spare Part') {
            $this->listApproval = ($this->tuList)->merge(Str::contains($unit->nama, 'Suku Dinas') ? $this->kasudinList : $this->kaunitList)->count();
            $allApproval = $this->tuList->merge(Str::contains($unit->nama, 'Suku Dinas') ? $this->kasudinList : $this->kaunitList)->merge($this->pjGudangList);
        } elseif ($tipe == 'Material') {
            $this->listApproval = (Str::contains($unit->nama, 'Suku Dinas') ? $this->kasudinList : $this->kaunitList)->merge($this->pemeliharaanList)->count();
            $allApproval = (Str::contains($unit->nama, 'Suku Dinas') ? $this->kasudinList : $this->kaunitList)->merge($this->pemeliharaanList)->merge($this->pjGudangList);
        }

        $index = $allApproval->search(fn($user) => $user->id == Auth::id());

        if ($index === 0) {
            $currentUser = $allApproval[$index];
            $this->showButton = !$currentUser->persetujuanPermintaan()->where('detail_permintaan_id', $this->permintaan->id ?? 0)->exists();
        } else {
            $previousUser = $index > 0 ? $allApproval[$index - 1] : null;
            $currentUser = $allApproval[$index];
            $this->showButton =
                $previousUser && !$currentUser->persetujuanPermintaan()->where('detail_permintaan_id', $this->permintaan->id ?? 0)->exists() &&
                $previousUser->persetujuanPermintaan()->where('detail_permintaan_id', $this->permintaan->id ?? 0)->exists();
        }
    }

    protected function checkApprovalDone($role, $tu = null)
    {
        if ($tu == 'tu') {
            return PersetujuanPermintaanStok::where('detail_permintaan_id', $this->permintaan->id)
                ->where('status', true)
                ->whereHas('user', function ($query) use ($role) {
                    $query->role($role)->where(function ($query) {
                        // Prioritaskan unit anak
                        $query->whereHas('unitKerja', function ($subQuery) {
                            return $subQuery->where('parent_id', $this->permintaan->unit->parent_id ? $this->permintaan->unit->parent_id : $this->permintaan->unit->id)->where('nama', 'like', '%' . 'Subbagian Tata Usaha' . '%'); // Unit child
                        });
                    });
                })
                ->exists();
        } else {

            return PersetujuanPermintaanStok::where('detail_permintaan_id', $this->permintaan->id)
                ->where('status', true)
                ->whereHas('user', fn($query) => $query->role($role))
                ->exists();
        }
    }


    public function finishApproval()
    {
        $permintaan = $this->permintaan;
        foreach ($permintaan->permintaanStok as  $value) {
            $stokDisetujui = StokDisetujui::where('permintaan_id', $value->id)->get();
            foreach ($stokDisetujui as $stok) {
                // Cari stok berdasarkan lokasi, bagian, dan posisi
                $stokModel = Stok::where('merk_id', $stok->merk_id)->where('lokasi_id', $stok->lokasi_id)
                    ->when($stok->bagian_id, function ($query) use ($stok) {
                        return $query->where('bagian_id', $stok->bagian_id);
                    })
                    ->when($stok->posisi_id, function ($query) use ($stok) {
                        return $query->where('posisi_id', $stok->posisi_id);
                    })
                    ->first();

                if ($stokModel) {
                    // Kurangi jumlah stok
                    if ($stokModel->jumlah >= $stok->jumlah_disetujui) {
                        $stokModel->jumlah -= $stok->jumlah_disetujui;
                        $stokModel->save();
                        $permintaan->proses = true;
                        $permintaan->save();
                    } else {
                        $this->dispatch('swal:error', [
                            'message' => "Jumlah stok di lokasi {$stok->lokasi_id} tidak mencukupi.",
                        ]);
                        return; // Berhenti jika stok tidak cukup
                    }
                } else {
                    $this->dispatch('swal:error', [
                        'message' => "Stok tidak ditemukan untuk lokasi {$stok->lokasi_id}.",
                    ]);
                    return; // Berhenti jika stok tidak ditemukan
                }
            }
        }
    }

    public function approveConfirmed()
    {
        if (
            $this->lastPj || $this->lastPpk || $this->lastPptk || $this->lastPjGudang
            // || $this->lastKasubag
        ) {
            if ($this->lastPjGudang) {
                $this->finishApproval();
            }
            foreach ($this->approvalFiles as $file) {
                $path = str_replace('dokumen-persetujuan-permintaan/', '', $file->storeAs('dokumen-persetujuan-permintaan', $file->getClientOriginalName(), 'public'));
                $this->permintaan->persetujuan()->create([
                    'detail_permintaan_id' => $this->permintaan->id,
                    'user_id' => Auth::id(),
                    'status' => true,
                    'file' => $path
                ]);
            }
            $list = $this->permintaan->persetujuan;
            $filteredList = $list->filter(function ($approval) {
                return $approval->status;
            })->unique('user_id');
            if ($filteredList->count() == $this->listApproval) {
                $this->permintaan->status = true;
                $this->permintaan->save();
                $permintaanItems = $this->permintaan->permintaanStok;
                foreach ($permintaanItems as $item) {
                    $this->adjustStockForApproval($item->merk_id, $item->jumlah_approve);
                }
            }
        } else {
            $this->permintaan->persetujuan()->create([
                'detail_permintaan_id' => $this->permintaan->id,
                'user_id' => Auth::id(),
                'status' => true,
            ]);

            $list = $this->permintaan->persetujuan;

            $filteredList = $list->filter(function ($approval) {
                return $approval->status;
            })->unique('user_id');
            if ($filteredList->count() == $this->listApproval) {
                $this->permintaan->status = true;
                $this->permintaan->save();
                $permintaanItems = $this->permintaan->permintaanStok;
                // Retrieve the items linked to the request
                $permintaanItems = $this->permintaan->permintaanStok;
                foreach ($permintaanItems as $item) {
                    $this->adjustStockForApproval($item->merk_id, $item->jumlah_approve);
                }
            }
        }



        return redirect()->route('permintaan-stok.show', ['permintaan_stok' => $this->permintaan->id]);

        // $this->emit('actionSuccess', 'Kontrak berhasil disetujui.');
    }

    protected function adjustStockForApproval($merkId, $jumlahApprove)
    {
        // Fetch all stock entries for the specified merk_id, sorted by some logical order
        $stocks = Stok::where('merk_id', $merkId)
            ->orderBy('lokasi_id')  // or any other order logic you prefer
            ->get();

        $remaining = $jumlahApprove;

        foreach ($stocks as $stock) {
            if ($remaining <= 0) break;

            if ($stock->jumlah >= $remaining) {
                // If the stock at this location is more than or equal to the remaining amount needed
                $stock->jumlah -= $remaining;
                $remaining = 0;
            } else {
                // If the stock at this location is less than what is needed
                $remaining -= $stock->jumlah;
                $stock->jumlah = 0;
            }

            $stock->save();  // Save updated stock levels

            // Optionally, log this action or handle cases where stock goes to zero
        }


        // Handle cases where the total stock was insufficient
        if ($remaining > 0) {
            // Implement your logic: send alert, log error, etc.
            Log::warning("Insufficient stock for merk_id {$merkId}. Needed {$jumlahApprove}, but couldn't fulfill.");
        }
    }



    public function rejectConfirmed($reason)
    {
        $this->permintaan->persetujuan()->create([
            'detail_permintaan_id' => $this->permintaan->id,
            'user_id' => Auth::id(),
            'status' => false,
            'keterangan' => $reason
        ]);
        $this->permintaan->status = false;
        $this->permintaan->save();

        return redirect()->route('permintaan-stok.show', ['permintaan_stok' => $this->permintaan->id]);

        // $this->emit('actionSuccess', 'Kontrak berhasil disetujui.');
    }

    public function removeApprovalFile($index)
    {
        // Remove file from the list
        if (isset($this->approvalFiles[$index])) {
            // Delete persisted file if needed
            if (!($this->approvalFiles[$index] instanceof \Illuminate\Http\UploadedFile)) {
                Storage::delete('dokumen-persetujuan-permintaan/' . $this->approvalFiles[$index]);
            }
            unset($this->approvalFiles[$index]);
            $this->approvalFiles = array_values($this->approvalFiles); // Reindex array
            $this->dispatch('file_approval', count: count($this->approvalFiles));
        }
    }
    public function render()
    {
        return view('livewire.approval-permintaan');
    }
}
