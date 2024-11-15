<?php

namespace App\Livewire;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AprovalKontrak extends Component
{
    public $penanggungjawab;
    public $date;
    public $pjList;

    public function mount()
    {
        dd(date('Y-m-d H:i:s'));
        if (Auth::user()->hasRole('penanggungjawab')) {
            $timestamp = 1731628800;
            $date = Carbon::createFromTimestamp($this->date);
            dd($date->format('Y-m-d H:i:s'));
            // Get users with 'penanggungjawab' role created before the specific date
            $users = User::role('penanggungjawab')->where('created_at', '<', $date->format('Y-m-d H:i:s'))->get();

            // Debug the result
            dd($users);
        }
    }


    public function render()
    {
        return view('livewire.aproval-kontrak');
    }
}
