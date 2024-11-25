<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Stok;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    public function mount()
    {
        // $this->adjustStockForApproval(3, 40);

        if ($this->permintaan->persetujuan->where('file')) {
            // Fetch files where the status is true and the file exists
            $this->files = $this->permintaan->persetujuan->filter(function ($persetujuan) {
                return $persetujuan->file !== null;
            })->pluck('file');
        } else {
            $this->files = [];
        }

        $this->user = Auth::user();
        $this->roles = 'penanggungjawab|ppk|pptk';
        $this->penulis = $this->permintaan->user;
        $date = Carbon::createFromTimestamp($this->permintaan->tanggal_permintaan);
        $pj = User::role('penanggungjawab')->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->get();
        $indexPj = $pj->search(function ($user) {
            return $user->id == Auth::id();
        });
        $this->lastPj = $indexPj === $pj->count() - 1;
        $this->pjList = $pj;
        $ppk = User::role('ppk')->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->limit(1)->get();
        $indexPpk = $ppk->search(function ($user) {
            return $user->id == Auth::id();
        });
        $this->lastPpk = $indexPpk === $ppk->count() - 1; // Check if current user is the last user
        $this->ppkList = $ppk;
        $pptk = User::role('pptk')->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->limit(1)->get();
        $indexPptk = $pptk->search(function ($user) {
            return $user->id == Auth::id();
        });
        $this->lastPptk = $indexPptk === $pptk->count() - 1; // Check if current user is the last user
        $this->pptkList = $pptk;
        $this->listApproval = $this->pptkList->merge($this->ppkList)->count();

        $allApproval = $this->pptkList->merge($this->ppkList);
        $index = $allApproval->search(function ($user) {
            return $user->id == Auth::id();
        });
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

    public function approveConfirmed()
    {

        if ($this->lastPj || $this->lastPpk || $this->lastPptk) {
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


        // $this->permintaan->persetujuan()->create([
        //     'detail_permintaan_id' => $this->permintaan->id,
        //     'user_id' => Auth::id(),
        //     'status' => true,
        // ]);

        // $list = $this->permintaan->persetujuan;

        // $filteredList = $list->filter(function ($approval) {
        //     return $approval->status;
        // });
        // if ($filteredList->count() == $this->listApproval) {
        //     $this->permintaan->status = true;
        //     $this->permintaan->save();

        //     $permintaanItems = $this->permintaan->permintaanStok;
        //     foreach ($permintaanItems as $permintaan) {
        //         $stok = Stok::firstOrCreate(
        //             [
        //                 'merk_id' => $permintaan->merk_id,
        //                 'lokasi_id' => $permintaan->lokasi_id,
        //                 'bagian_id' => $permintaan->bagian_id,
        //                 'posisi_id' => $permintaan->posisi_id,
        //             ],
        //             ['jumlah' => 0]  // Atur stok awal jika belum ada
        //         );

        //         $stok->jumlah += $permintaan->jumlah;
        //         $stok->save();
        //     }
        // }


        return redirect()->route('permintaan-stok.edit', ['permintaan_stok' => $this->permintaan->id]);

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

        return redirect()->route('permintaan-stok.edit', ['permintaan_stok' => $this->permintaan->id]);

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
