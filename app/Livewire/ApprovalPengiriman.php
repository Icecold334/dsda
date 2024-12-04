<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\Stok;
use App\Models\User;
use Livewire\Component;
use App\Models\PengirimanStok;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class ApprovalPengiriman extends Component
{

    use WithFileUploads;
    public $pengiriman;
    public $penanggungjawab;
    public $penulis;
    public $user;
    public $date;
    public $pjList;
    public $lastPj;
    public $ppkList;
    public $lastPpk;
    public $penerimaList;
    public $lastPenerima;
    public $pemeriksaList;
    public $lastPemeriksa;
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
        if ($this->pengiriman->persetujuan->where('file')) {
            // Fetch files where the status is true and the file exists
            $this->files = $this->pengiriman->persetujuan->filter(function ($persetujuan) {
                return $persetujuan->file !== null;
            })->pluck('file');
        } else {
            $this->files = [];
        }

        $this->user = Auth::user();
        $this->roles = 'penanggungjawab|ppk|pptk|';
        $this->penulis = $this->pengiriman->user;
        $date = Carbon::createFromTimestamp($this->pengiriman->tanggal);

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
        // dd(PengirimanStok::where('detail_pengiriman_id', $this->pengiriman->id)->pluck('lokasi_id'));
        $penerima = User::role('penerima_barang')
            ->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))
            ->whereHas('lokasiStok', function ($query) {
                $query->whereIn('lokasi_id', PengirimanStok::where('detail_pengiriman_id', $this->pengiriman->id)->pluck('lokasi_id'));
            })
            ->get();

        $indexPenerima = $penerima->search(function ($user) {
            return $user->id == Auth::id();
        });

        $this->lastPenerima = $indexPenerima === $penerima->count() - 1; // Check if current user is the last user
        $this->penerimaList = $penerima;


        $pemeriksa = User::role('pemeriksa_barang')->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->limit(1)->get();
        $indexPemeriksa = $pemeriksa->search(function ($user) {
            return $user->id == Auth::id();
        });
        $this->lastPemeriksa = $indexPemeriksa === $pemeriksa->count() - 1; // Check if current user is the last user
        $this->pemeriksaList = $pemeriksa;


        $pptk = User::role('pptk')->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->limit(1)->get();
        $indexPptk = $pptk->search(function ($user) {
            return $user->id == Auth::id();
        });
        $this->lastPptk = $indexPptk === $pptk->count() - 1; // Check if current user is the last user
        $this->pptkList = $pptk;

        // Menentukan urutan approval yang benar
        $this->listApproval = $this->pemeriksaList
            // ->merge($this->pemeriksaList)
            ->merge($this->pptkList)
            ->merge($this->ppkList)
            ->count();

        // Menggabungkan list approval sesuai urutan yang benar
        $allApproval = $this->pemeriksaList
            // ->merge($this->pemeriksaList)
            ->merge($this->pptkList)
            ->merge($this->ppkList);
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

        if ($this->lastPj || $this->lastPpk || $this->lastPptk || $this->lastPenerima || $this->lastPemeriksa) {
            foreach ($this->approvalFiles as $file) {
                $path = str_replace('dokumen-persetujuan-pengiriman/', '', $file->storeAs('dokumen-persetujuan-pengiriman', $file->getClientOriginalName(), 'public'));
                $this->pengiriman->persetujuan()->create([
                    'detail_pengiriman_id' => $this->pengiriman->id,
                    'user_id' => Auth::id(),
                    'status' => true,
                    'file' => $path
                ]);
            }
            $list = $this->pengiriman->persetujuan;
            $filteredList = $list->filter(function ($approval) {
                return $approval->status;
            })->unique('user_id');
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
        } else {
            $this->pengiriman->persetujuan()->create([
                'detail_pengiriman_id' => $this->pengiriman->id,
                'user_id' => Auth::id(),
                'status' => true,
            ]);

            $list = $this->pengiriman->persetujuan;

            $filteredList = $list->filter(function ($approval) {
                return $approval->status;
            })->unique('user_id');
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

    public function removeApprovalFile($index)
    {
        // Remove file from the list
        if (isset($this->approvalFiles[$index])) {
            // Delete persisted file if needed
            if (!($this->approvalFiles[$index] instanceof \Illuminate\Http\UploadedFile)) {
                Storage::delete('dokumen-persetujuan-pengiriman/' . $this->approvalFiles[$index]);
            }
            unset($this->approvalFiles[$index]);
            $this->approvalFiles = array_values($this->approvalFiles); // Reindex array
            $this->dispatch('file_approval', count: count($this->approvalFiles));
        }
    }


    public function render()
    {
        return view('livewire.approval-pengiriman');
    }
}
