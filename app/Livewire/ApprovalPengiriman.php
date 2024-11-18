<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Stok;
use App\Models\User;
use Livewire\Component;
use App\Models\PengirimanStok;
use Illuminate\Support\Facades\Auth;

class ApprovalPengiriman extends Component
{

    public $pengiriman;
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
        $this->user = Auth::user();
        $this->roles = 'penanggungjawab|ppk|pptk';
        $this->penulis = $this->pengiriman->user;
        $date = Carbon::createFromTimestamp($this->pengiriman->tanggal);
        $pj = User::role('penanggungjawab')->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->get();
        $indexPj = $pj->search(function ($user) {
            return $user->id == Auth::id();
        });
        $this->lastPj = $indexPj === $pj->count() - 1;
        $this->pjList = $pj;
        $ppk = User::role('ppk')->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->get();
        $this->ppkList = $ppk;
        $pptk = User::role('pptk')->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->get();
        $this->pptkList = $pptk;
        $this->listApproval = $this->pptkList->merge($this->ppkList)->merge($this->pjList)->count();

        $allApproval = $this->pjList->merge($this->ppkList)->merge($this->pptkList);
        $index = $allApproval->search(function ($user) {
            return $user->id == Auth::id();
        });
        if ($index === 0) {
            $currentUser = $allApproval[$index];
            $this->showButton = !$currentUser->persetujuanPengiriman()->where('detail_pengiriman_id', $this->pengiriman->id ?? 0)->exists();
        } else {
            $previousUser = $index > 0 ? $allApproval[$index - 1] : null;
            $currentUser = $allApproval[$index];
            $this->showButton =
                $previousUser && !$currentUser->persetujuanPengiriman()->where('detail_pengiriman_id', $this->pengiriman->id ?? 0)->exists() &&
                $previousUser->persetujuanPengiriman()->where('detail_pengiriman_id', $this->pengiriman->id ?? 0)->exists();
        }
    }

    public function approveConfirmed()
    {
        $this->pengiriman->persetujuan()->create([
            'detail_pengiriman_id' => $this->pengiriman->id,
            'user_id' => Auth::id(),
            'status' => true,
        ]);

        $list = $this->pengiriman->persetujuan;

        $filteredList = $list->filter(function ($approval) {
            return $approval->status;
        });
        if ($filteredList->count() == $this->listApproval) {
            $this->pengiriman->status = true;
            $this->pengiriman->save();

            $pengirimanItems = $this->pengiriman->pengirimanStok;
            foreach ($pengirimanItems as $pengiriman) {
                $stok = Stok::firstOrCreate(
                    [
                        'merk_id' => $pengiriman->merk_id,
                        'lokasi_id' => $pengiriman->lokasi_id,
                        'bagian_id' => $pengiriman->bagian_id,
                        'posisi_id' => $pengiriman->posisi_id,
                    ],
                    ['jumlah' => 0]  // Atur stok awal jika belum ada
                );

                $stok->jumlah += $pengiriman->jumlah;
                $stok->save();
            }
        }


        return redirect()->route('pengiriman-stok.show', ['pengiriman_stok' => $this->pengiriman->id]);

        // $this->emit('actionSuccess', 'Kontrak berhasil disetujui.');
    }

    public function rejectConfirmed($reason)
    {
        $this->pengiriman->persetujuan()->create([
            'detail_pengiriman_id' => $this->pengiriman->id,
            'user_id' => Auth::id(),
            'status' => false,
            'keterangan' => $reason
        ]);
        $this->pengiriman->status = false;
        $this->pengiriman->save();

        return redirect()->route('pengiriman-stok.show', ['pengiriman_stok' => $this->pengiriman->id]);

        // $this->emit('actionSuccess', 'Kontrak berhasil disetujui.');
    }

    public function render()
    {
        return view('livewire.approval-pengiriman');
    }
}
