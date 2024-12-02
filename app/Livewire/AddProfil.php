<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class AddProfil extends Component
{
    public $id;
    public $tipe;
    public $users;
    public $name;
    public $role;
    public $email;
    public $password;
    public $alamat;
    public $perusahaan;
    public $provinsi;
    public $kota;
    public $no_wa;
    public $username;
    public $keterangan;
    public $user_id;
    public $user;

    public function mount()
    {
        if ($this->tipe == 'user') {
            $this->users = User::all();
            if ($this->id) {
                $user = User::find($this->id);
                $this->user_id = $user->user_id;
                $this->name = $user->name;
                $this->keterangan = $user->keterangan;
                $this->username = $user->username;
                $this->email = $user->email;
                $this->password = $user->password;
                $this->role = $user->role;
            }
        } elseif ($this->tipe == 'phone') {
            $this->users = User::all();
            if ($this->id) {
                $no_wa = User::find($this->id);
                $this->user_id = $no_wa->user_id;
                $this->no_wa = $no_wa->no_wa;
            }
        }elseif ($this->tipe == 'email') {
            $this->users = User::all();
            if ($this->id) {
                $email = User::find($this->id);
                $this->user_id = $email->user_id;
                $this->email = $email->email;
            }
        }elseif ($this->tipe == 'password') {
            $this->users = User::all();
            if ($this->id) {
                $password = User::find($this->id);
                $this->user_id = $password->user_id;
                $this->password = $password->password;
            }
        } else {
            if ($this->id) {
                $user = User::find($this->id);
                $this->name = $user->name;
                $this->perusahaan = $user->perusahaan;
                $this->alamat = $user->alamat;
                $this->provinsi = $user->provinsi;
                $this->kota = $user->kota;
            }
        }
    }
    public function removeUser()
    {
        if ($this->tipe == 'user') {
            User::destroy($this->id);
        } 

        return redirect()->route('profil.index');
    }

    public function saveLokasi()
    {
        if ($this->tipe == 'user') {
            User::updateOrCreate(
                ['id' => $this->id], // Unique field to check for existing record
                [
                    'name' => $this->name,
                    'username' => $this->username,
                    'email' => $this->email,
                    'password' => $this->password,
                    'keterangan' => $this->keterangan,
                    'hak' => $this->role,
                ]
            );
        } elseif ($this->tipe == 'phone') {
            User::updateOrCreate(
                ['id' => $this->id], // Unique fields to check
                [
                    'no_wa' => $this->no_wa,
                ]
            );
        } elseif ($this->tipe == 'email') {
            User::updateOrCreate(
                ['id' => $this->id], // Unique fields to check
                [
                    'email' => $this->email,
                ]
            );
        } elseif ($this->tipe == 'password') {
            User::updateOrCreate(
                ['id' => $this->id], // Unique fields to check
                [
                    'password' => $this->password,
                ]
            );
        } else {
            User::updateOrCreate(
                ['id' => $this->id], // Unique fields to check
                [
                    'nama' => $this->nama,
                    'perusahaan' => $this->perusahaan,
                    'alamat' => $this->alamat,
                    'provinsi' => $this->provinsi,
                    'kota' => $this->kota,
                ]
            );
        }
        return redirect()->route('profil.index');
    }

    public function render()
    {
        return view('livewire.add-profil');
    }
}
