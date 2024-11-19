<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class AprovalKontrak extends Component
{
    use WithFileUploads;
    public $kontrak;
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

        if ($this->kontrak && $this->kontrak->persetujuan->where('file')) {
            // Fetch files where the status is true and the file exists
            $this->files = $this->kontrak->persetujuan->filter(function ($persetujuan) {
                return $persetujuan->file !== null;
            })->pluck('file');
        } else {
            $this->files = [];
        }

        $this->user = Auth::user();
        if ($this->kontrak) {
            if ($this->kontrak->type) {
                $this->roles = 'penanggungjawab';
                $date = Carbon::parse($this->date);
                $users = User::role('penanggungjawab')
                    ->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))
                    ->get();

                $index = $users->search(function ($user) {
                    return $user->id == Auth::id();
                });
                $previousUser = $index > 0 ? $users[$index - 1] : null;
                $users = User::role('penanggungjawab')
                    ->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))
                    ->get();
                $index = $users->search(function ($user) {
                    return $user->id == Auth::id();
                });
                $this->isLastUser = $index === $users->count() - 1; // Check if current user is the last user
                if ($index === 0) {
                    $currentUser = $users[$index];
                    $this->showButton = !$currentUser->persetujuanKontrak()->where('kontrak_id', $this->kontrak->id ?? 0)->exists();
                } else {
                    $previousUser = $index > 0 ? $users[$index - 1] : null;
                    $currentUser = $users[$index];
                    $this->showButton = $this->kontrak &&
                        $previousUser && !$currentUser->persetujuanKontrak()->where('kontrak_id', $this->kontrak->id ?? 0)->exists() &&
                        $previousUser->persetujuanKontrak()->where('kontrak_id', $this->kontrak->id ?? 0)->exists();
                }
                $this->pjList = $users;
            } else {
                $this->roles = 'penanggungjawab|ppk|pptk';

                $this->penulis = $this->kontrak->transaksiStok->unique('user_id');
                $date = Carbon::parse($this->date);
                $pj = User::role('penanggungjawab')->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->get();
                $indexPj = $pj->search(function ($user) {
                    return $user->id == Auth::id();
                });
                $this->lastPj = $indexPj === $pj->count() - 1;
                $this->pjList = $pj;
                $ppk = User::role('ppk')->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->get();
                $indexPpk = $ppk->search(function ($user) {
                    return $user->id == Auth::id();
                });
                $this->lastPpk = $indexPpk === $ppk->count() - 1; // Check if current user is the last user

                $this->ppkList = $ppk;


                $pptk = User::role('pptk')->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->get();
                $indexPptk = $pptk->search(function ($user) {
                    return $user->id == Auth::id();
                });
                $this->lastPptk = $indexPptk === $pptk->count() - 1; // Check if current user is the last user

                $this->pptkList = $pptk;

                $this->listApproval = $this->pjList->merge($this->ppkList)->merge($this->pptkList)->count();
                $allApproval = $this->pjList->merge($this->ppkList)->merge($this->pptkList);
                $index = $allApproval->search(function ($user) {
                    return $user->id == Auth::id();
                });
                if ($index === 0) {
                    $currentUser = $allApproval[$index];
                    $this->showButton = !$currentUser->persetujuanKontrak()->where('kontrak_id', $this->kontrak->id ?? 0)->exists();
                } else {
                    $previousUser = $index > 0 ? $allApproval[$index - 1] : null;
                    $currentUser = $allApproval[$index];
                    $this->showButton =
                        $previousUser && !$currentUser->persetujuanKontrak()->where('kontrak_id', $this->kontrak->id ?? 0)->exists() &&
                        $previousUser->persetujuanKontrak()->where('kontrak_id', $this->kontrak->id ?? 0)->exists();
                }
            }
        } else {
            $this->roles = '';
        }

        // }
    }

    public function approveConfirmed()
    {



        if ($this->kontrak->type) {
            if ($this->isLastUser) {
                foreach ($this->approvalFiles as $file) {
                    $path = str_replace('dokumen-persetujuan-kontrak/', '', $file->storeAs('dokumen-persetujuan-kontrak', $file->getClientOriginalName(), 'public'));
                    $this->kontrak->persetujuan()->create([
                        'kontrak_id' => $this->kontrak->id,
                        'user_id' => Auth::id(),
                        'status' => true,
                        'file' => $path
                    ]);
                }
                $list = $this->kontrak->persetujuan;
                $filteredList = $list->filter(function ($approval) {
                    return $approval->status;
                })->unique('user_id');
                if ($filteredList->count() == $this->pjList->count()) {
                    $this->kontrak->status = true;
                    $this->kontrak->save();
                }
            } else {
                $this->kontrak->persetujuan()->create([
                    'kontrak_id' => $this->kontrak->id,
                    'user_id' => Auth::id(),
                    'status' => true,
                ]);

                $list = $this->kontrak->persetujuan;

                $filteredList = $list->filter(function ($approval) {
                    return $approval->status;
                })->unique('user_id');
                if ($filteredList->count() == $this->pjList->count()) {
                    $this->kontrak->status = true;
                    $this->kontrak->save();
                }
            }
        } else {
            if ($this->lastPj || $this->lastPpk || $this->lastPptk) {
                foreach ($this->approvalFiles as $file) {
                    $path = str_replace('dokumen-persetujuan-kontrak/', '', $file->storeAs('dokumen-persetujuan-kontrak', $file->getClientOriginalName(), 'public'));
                    $this->kontrak->persetujuan()->create([
                        'kontrak_id' => $this->kontrak->id,
                        'user_id' => Auth::id(),
                        'status' => true,
                        'file' => $path
                    ]);
                }
                $list = $this->kontrak->persetujuan;
                $filteredList = $list->filter(function ($approval) {
                    return $approval->status;
                })->unique('user_id');
                if ($filteredList->count() == $this->listApproval) {
                    $this->kontrak->status = true;
                    $this->kontrak->save();
                }
            } else {
                $this->kontrak->persetujuan()->create([
                    'kontrak_id' => $this->kontrak->id,
                    'user_id' => Auth::id(),
                    'status' => true,
                ]);

                $list = $this->kontrak->persetujuan;

                $filteredList = $list->filter(function ($approval) {
                    return $approval->status;
                })->unique('user_id');
                if ($filteredList->count() == $this->pjList->count()) {
                    $this->kontrak->status = true;
                    $this->kontrak->save();
                }
            }
            // $this->kontrak->persetujuan()->create([
            //     'kontrak_id' => $this->kontrak->id,
            //     'user_id' => Auth::id(),
            //     'status' => true,
            // ]);

            // $list = $this->kontrak->persetujuan;

            // $filteredList = $list->filter(function ($approval) {
            //     return $approval->status;
            // });
            // if ($filteredList->count() == $this->listApproval) {
            //     $this->kontrak->status = true;
            //     $this->kontrak->save();
            // }
        }
        return redirect()->route('kontrak-vendor-stok.show', ['kontrak_vendor_stok' => $this->kontrak->id]);

        // $this->emit('actionSuccess', 'Kontrak berhasil disetujui.');
    }
    public function approveWithFile($file)
    {
        // Handle file upload and approval logic
        $filePath = $file->store('approvals', 'public'); // Store the file in a public directory
        $this->approveConfirmed(); // Call the existing approve logic

        // Save file path to the contract or other appropriate table
        $this->kontrak->update(['approval_file' => $filePath]);
    }

    public function rejectConfirmed($reason)
    {
        $this->kontrak->persetujuan()->create([
            'kontrak_id' => $this->kontrak->id,
            'user_id' => Auth::id(),
            'status' => false,
            'keterangan' => $reason
        ]);
        $this->kontrak->status = false;
        $this->kontrak->save();

        return redirect()->route('kontrak-vendor-stok.show', ['kontrak_vendor_stok' => $this->kontrak->id]);

        // $this->emit('actionSuccess', 'Kontrak berhasil disetujui.');
    }
    public function saveApprovalFiles()
    {
        foreach ($this->approvalFiles as $file) {
            if ($file instanceof \Illuminate\Http\UploadedFile) {
                $filePath = $file->storeAs(
                    'approvals',
                    $file->getClientOriginalName(),
                    'public'
                );
                // Save $filePath to the database if needed
            }
        }

        // Reset after saving
        $this->approvalFiles = [];
        session()->flash('success', 'Files uploaded successfully.');
    }

    public function removeApprovalFile($index)
    {
        // Remove file from the list
        if (isset($this->approvalFiles[$index])) {
            // Delete persisted file if needed
            if (!($this->approvalFiles[$index] instanceof \Illuminate\Http\UploadedFile)) {
                Storage::delete('dokumen-persetujuan-kontrak/' . $this->approvalFiles[$index]);
            }
            unset($this->approvalFiles[$index]);
            $this->approvalFiles = array_values($this->approvalFiles); // Reindex array
            $this->dispatch('file_approval', count: count($this->approvalFiles));
        }
    }

    public function render()
    {
        return view('livewire.aproval-kontrak');
    }
}
