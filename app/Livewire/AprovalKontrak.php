<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AprovalKontrak extends Component
{
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

    public function mount()
    {

        $this->user = Auth::user();
        if ($this->kontrak) {
            if ($this->kontrak->type) {
                $this->roles = 'penanggungjawab';
                $date = Carbon::parse($this->date);
                $users = User::role('penanggungjawab')->whereDate('created_at', '<', $date->format('Y-m-d H:i:s'))->get();

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


    public function render()
    {
        return view('livewire.aproval-kontrak');
    }
}
