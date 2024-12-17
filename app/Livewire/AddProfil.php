<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\UnitKerja;
use App\Models\LokasiStok;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;

class AddProfil extends Component
{
    use WithFileUploads;
    public $id;
    public $tipe;
    public $users;
    public $name;
    public $unitkerjas;
    public $lokasistoks;
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
    public $lokasi_stok;
    public $unit_kerja;
    public $nip;
    public $img;
    public $ttd;

    // public function updatedSelectedRoles()
    // {
    //     // Jika role admin dipilih
    //     if (in_array('Kepala Suku Dinas', $this->selectedRoles)) {
    //         $this->unitkerjas = UnitKerja::whereNull('parent_id')->get(); // Ambil hanya unit parent
    //     } else {
    //         $this->unitkerjas = UnitKerja::all(); // Ambil semua unit
    //     }
    // }

    public function mount()
    {
        $user = Auth::user(); // Mendapatkan data pengguna yang login
        // Ambil unit_id user yang sedang login
        $userUnitId = Auth::user()->unit_id;

        // Cari unit berdasarkan unit_id user
        $unit = UnitKerja::find($userUnitId);

        if ($user->id != 1) {
            // Jika user memiliki parent_id null (berarti parent unit)
            if ($unit && $unit->parent_id === null) {
                // Ambil child unit dari unit yang dimiliki user
                $this->unitkerjas = UnitKerja::where('parent_id', $userUnitId)->get();
            }
        } else {
            $this->unitkerjas = UnitKerja::all();
        }
        $this->lokasistoks = LokasiStok::all();
        if ($this->tipe == 'user') {
            // $this->users = User::all();
            $this->roles = Role::whereNotIn('id', [1, 2])
                ->whereNotIn('id', $user->roles->pluck('id')->toArray())
                ->when($user->roles->contains('name', 'Kepala Unit') || $user->roles->contains('name', 'Kepala Suku Dinas'), function ($query) {
                    // Menambahkan filter agar roles "kepala unit" dan "kepala seksi" tidak muncul
                    $query->whereNotIn('name', ['Kepala Unit', 'Kepala Suku Dinas']);
                })
                ->get();
            if ($this->id) {
                $user = User::find($this->id);
                $this->user_id = $user->user_id;
                $this->name = $user->name;
                $this->keterangan = $user->keterangan;
                $this->username = $user->username;
                $this->email = $user->email;
                $this->unit_kerja = $user->unit_id;
                $this->lokasi_stok = $user->lokasi_id;
                $this->nip = $user->nip;
                // Ambil roles yang sudah dimiliki user
                $this->selectedRoles = $user->roles->pluck('name')->toArray();
                // $this->role = $user->hak;
            }
        } elseif ($this->tipe == 'phone') {
            $this->users = User::all();
            if ($this->id) {
                // $no_wa = User::find($this->id);
                $this->user_id = $user->user_id;
                $this->no_wa = $user->no_wa;
            }
        } elseif ($this->tipe == 'email') {
            $this->users = User::all();
            if ($this->id) {
                // $email = User::find($this->id);
                $this->user_id = $user->user_id;
                $this->email = $user->email;
            }
        } elseif ($this->tipe == 'password') {
            $this->users = User::all();
            if ($this->id) {
                // $password = User::find($this->id);
                $this->user_id = $user->user_id;
                // $this->old_password = $password->password;
            }
        } else {
            // if ($this->id) {
            $this->name = $user->name;
            // $this->perusahaan = $user->perusahaan;
            // $this->alamat = $user->alamat;
            // $this->provinsi = $user->provinsi;
            // $this->kota = $user->kota;
            $this->nip = $user->nip;
            $this->img = $user->foto;
            $this->ttd = $user->ttd;
            // $this->unit_kerja = $user->unit_id;
            // $this->lokasi_stok = $profil->lokasi_id;
            // }
        }
    }
    public function removeImg()
    {
        $this->img = null;
    }
    public function removeProfil()
    {
        if ($this->tipe == 'user') {
            User::destroy($this->id);
        }

        return redirect()->route('profil.index');
    }

    public function messages()
    {
        return [
            'img.image' => 'File harus berupa gambar!',
            'img.max' => 'Ukuran gambar maksimal 2MB!',
            'img.mimes' => 'Format gambar harus jpeg, png, jpg, gif!',
        ];
    }

    public function saveProfil()
    {
        if ($this->tipe == 'user') {
            $rules = $this->id
                ? ['nullable', 'min:8']
                : ['required', 'min:8', 'confirmed'];
            $imgRule = is_string($this->img) ? '' : 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif';

            $this->validate([
                'password' => $rules,
                'img' => $imgRule,
            ]);
            $user = User::updateOrCreate(
                ['id' => $this->id ?? 0], // Unique field to check for existing record
                [
                    'name' => $this->name,
                    'username' => $this->username,
                    'email' => $this->email,
                    'password' => $this->password,
                    'keterangan' => $this->keterangan,
                    'unit_id' => $this->unit_kerja,
                    'lokasi_id' => $this->lokasi_stok,
                    // 'nip' => $this->nip,
                    // 'hak' => $this->selectedRoles,
                ]
            );
            $user->assignRole($this->selectedRoles);

            if ($user->wasRecentlyCreated && $this->name) {
                return redirect()->route('profil.index')->with('success', 'Berhasil Menambah User');
            } else {
                return redirect()->route('profil.index')->with('success', 'Berhasil Mengubah Data User');
            }
        } elseif ($this->tipe == 'phone') {
            $user = User::find($this->id);
            $user->update( // Unique fields to check
                [
                    'no_wa' => $this->new_wa,
                ]
            );
            return redirect()->route('profil.index')->with('success', 'Berhasil Mengubah No WhatsApp');
        } elseif ($this->tipe == 'email') {
            $user = User::find($this->id);
            $user->update(
                [
                    'email' => $this->new_email,
                ]
            );
            return redirect()->route('profil.index')->with('success', 'Berhasil Mengubah Email');
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
            return redirect()->route('profil.index')->with('success', 'Berhasil Mengubah Password');
        } else {
            $user = User::find(Auth::user()->id);
            $imgRule = is_string($this->img) ? '' : 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif';
            $this->validate([
                'img' => $imgRule,
            ]);

            // Proses penyimpanan tanda tangan
            // dd($this->ttd);

            // Proses penyimpanan tanda tangan
            $ttdFileName = $user->ttd; // Gunakan TTD lama sebagai default
            if ($this->ttd && $this->ttd !== $ttdFileName) { // Jika ada TTD baru dan berbeda
                // Decode Base64 Image
                $image = str_replace('data:image/png;base64,', '', $this->ttd);
                $image = str_replace(' ', '+', $image);
                $imageData = base64_decode($image);

                // Simpan ke storage
                $fileName = 'usersTTD/' . uniqid() . '.png';
                Storage::disk('public')->put($fileName, $imageData);

                // Hapus TTD lama jika perlu
                if ($ttdFileName && Storage::disk('public')->exists('usersTTD/' . $ttdFileName)) {
                    Storage::disk('public')->delete('usersTTD/' . $ttdFileName);
                }

                // Simpan nama file TTD baru
                $ttdFileName = str_replace('usersTTD/', '', $fileName);
            }


            // dd($user);
            $user->update( // Unique fields to check
                [
                    'name' => $this->name,
                    // 'unit_id' => $this->unit_kerja,
                    // 'lokasi_id' => $this->lokasi_stok,
                    // 'perusahaan' => $this->perusahaan,
                    // 'alamat' => $this->alamat,
                    // 'provinsi' => $this->provinsi,
                    // 'kota' => $this->kota,
                    'nip' => $this->nip,
                    'ttd' => $ttdFileName,
                    'foto' => $this->img
                        ? (is_object($this->img)
                            ? str_replace('usersFoto/', '', $this->img->store('usersFoto', 'public'))
                            : $this->img)
                        : null,
                ]
            );
            // dd($user);
            return redirect()->route('profil.index')->with('success', 'Berhasil Mengubah Profil');
        }
    }

    #[On('upload')]
    public function generateTTD($detail)
    {
        $this->ttd = $detail;
        $this->saveProfil();
    }

    public function removeTTD()
    {
        $this->ttd = null;
        $this->dispatch('resetCanvas');
    }

    public function render()
    {
        return view('livewire.add-profil');
    }
}
