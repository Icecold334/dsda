<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AddProfil extends Component
{
    public $id;
    public $tipe;
    public $users;
    public $name;
    public $roles;
    public $selectedRoles = [];
    public $email;
    public $new_email;
    public $old_password;
    public $password_confirmation;
    public $password;
    public $alamat;
    public $perusahaan;
    public $provinsi;
    public $kota;
    public $no_wa;
    public $new_wa;
    public $username;
    public $keterangan;
    public $user_id;
    public $user;

    public function mount()
    {
        if ($this->tipe == 'user') {
            $this->users = User::all();
            $this->roles = Role::where('id', '>', '1')->get();
            if ($this->id) {
                $user = User::find($this->id);
                $this->user_id = $user->user_id;
                $this->name = $user->name;
                $this->keterangan = $user->keterangan;
                $this->username = $user->username;
                $this->email = $user->email;
                // Ambil roles yang sudah dimiliki user
                $this->selectedRoles = $user->roles->pluck('name')->toArray();
                // $this->role = $user->hak;
            }
        } elseif ($this->tipe == 'phone') {
            $this->users = User::all();
            if ($this->id) {
                $no_wa = User::find($this->id);
                $this->user_id = $no_wa->user_id;
                $this->no_wa = $no_wa->no_wa;
            }
        } elseif ($this->tipe == 'email') {
            $this->users = User::all();
            if ($this->id) {
                $email = User::find($this->id);
                $this->user_id = $email->user_id;
                $this->email = $email->email;
            }
        } elseif ($this->tipe == 'password') {
            $this->users = User::all();
            if ($this->id) {
                $password = User::find($this->id);
                $this->user_id = $password->user_id;
                // $this->old_password = $password->password;
            }
        } else {
            if ($this->id) {
                $profil = User::find($this->id);
                $this->name = $profil->name;
                $this->perusahaan = $profil->perusahaan;
                $this->alamat = $profil->alamat;
                $this->provinsi = $profil->provinsi;
                $this->kota = $profil->kota;
            }
        }
    }
    public function removeProfil()
    {
        if ($this->tipe == 'user') {
            User::destroy($this->id);
        }

        return redirect()->route('profil.index');
    }

    public function saveProfil()
    {
        if ($this->tipe == 'user') {
            // dd($this->selectedRoles);
            $rules = $this->id
                ? ['nullable', 'min:8']
                : ['required', 'min:8', 'confirmed'];
            $this->validate([
                'password' => $rules,
            ]);

            $user = User::updateOrCreate(
                ['id' => $this->id ?? 0], // Unique field to check for existing record
                [
                    'name' => $this->name,
                    'username' => $this->username,
                    'email' => $this->email,
                    'password' => $this->password,
                    'keterangan' => $this->keterangan,
                    // 'hak' => $this->selectedRoles,
                ]
            );
            $user->assignRole($this->selectedRoles);
        } elseif ($this->tipe == 'phone') {
            $user = User::find($this->id);
            $user->update( // Unique fields to check
                [
                    'no_wa' => $this->new_wa,
                ]
            );
        } elseif ($this->tipe == 'email') {
            $user = User::find($this->id);
            $user->update( // Unique fields to check
                [
                    'email' => $this->new_email,
                ]
            );
        } elseif ($this->tipe == 'password') {
            $this->validate([
                'old_password' => 'required',
                'password' => [
                    'required',
                    'min:8',
                    'confirmed', // Ensures password matches password_confirmation
                    // 'regex:/[a-z]/', // At least one lowercase letter
                    // 'regex:/[A-Z]/', // At least one uppercase letter
                    // 'regex:/[0-9]/', // At least one digit
                ],
            ]);

            $user = User::find($this->id);
            $old_password   = $user->password;

            // Check if the old password matches the current password
            if (!Hash::check($this->old_password, $old_password)) {
                $this->addError('old_password', 'Password lama tidak sesuai.');
                return;
            }

            $user->update( // Unique fields to check
                [
                    'password' => Hash::make($this->password),
                ]
            );
        } else {
            $user = User::find($this->id);
            $user->update( // Unique fields to check
                [
                    'name' => $this->name,
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
