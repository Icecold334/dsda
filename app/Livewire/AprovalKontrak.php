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
    public $ppkList;
    public $pptkList;
    public $listApproval;
    public $roles;
    public $showButton;
    public $isLastUser;
    public $newApprovalFiles = []; // To hold multiple uploaded files
    public $approvalFiles = []; // To hold multiple uploaded files
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
                $pptk = User::role('pptk')->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->get();
                $ppk = User::role('ppk')->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->get();
                $pj = User::role('penanggungjawab')->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->get();

                $this->pptkList = $pptk;
                $this->ppkList = $ppk;
                $this->pjList = $pj;

                $this->listApproval = $this->pptkList->merge($this->ppkList)->merge($this->pjList)->count();
            }
        } else {
            $this->roles = '';
        }

        // }
    }

    public function approveConfirmed()
    {



        if ($this->kontrak->type) {
            $this->kontrak->persetujuan()->create([
                'kontrak_id' => $this->kontrak->id,
                'user_id' => Auth::id(),
                'status' => true,
            ]);

            $list = $this->kontrak->persetujuan;

            $filteredList = $list->filter(function ($approval) {
                return $approval->status;
            });
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
            });
            if ($filteredList->count() == $this->listApproval) {
                $this->kontrak->status = true;
                $this->kontrak->save();
            }
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
                Storage::delete('approvals/' . $this->approvalFiles[$index]);
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
