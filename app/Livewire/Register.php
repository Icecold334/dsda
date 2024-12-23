<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\UnitKerja;
use App\Models\LokasiStok;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Role;

#[Layout('layouts.guest')]
class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public $unitkerjas;
    public array $subunitkerjas;
    public array $lokasis = [];
    public $lokasi_id = '';
    public $parent_id;
    public $sub_unit;
    public $subUnits;

    public function updatedParentId()
    {

        if ($this->parent_id) {
            $this->subUnits = UnitKerja::where('parent_id', $this->parent_id)->get();
        }
    }

    public function mount()
    {
        // Ambil data Unit dan Lokasi
        $this->lokasis = LokasiStok::all()->toArray();
        $this->unitkerjas = UnitKerja::whereNull('parent_id')->get();
        // $this->parent_id = $unitkerjas->parent_id;
        // dd($this->unitkerjas);
        // $this->subunitkerjas = UnitKerja::all()->toArray();
    }

    public function register()
    {
        // Validasi Input
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'parent_id' => ['required', 'exists:unit_kerja,id'],
            'lokasi_id' => ['required', 'exists:lokasi_stok,id'],
        ]);

        // Buat User Baru
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'unit_id' => $this->sub_unit ?? $this->parent_id,
            'lokasi_id' => $validated['lokasi_id'],
        ]);

        // Berikan Role 'Anggota' dari Spatie
        $user->assignRole('Anggota');

        // Trigger Event Registered
        event(new Registered($user));

        // Login User
        Auth::login($user);

        // Redirect ke Dashboard
        return redirect()->route('dashboard');
    }
    public function render()
    {
        return view('livewire.register');
    }
}
