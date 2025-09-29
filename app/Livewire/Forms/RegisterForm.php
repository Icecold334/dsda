<?php

namespace App\Livewire\Forms;

use Carbon\Carbon;
use Livewire\Form;
use App\Models\User;
use App\Models\UnitKerja;
use App\Models\LokasiStok;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class RegisterForm extends Form
{
    public string $name = '';
    public string $email = '';
    public $nomor;
    public string $password = '';
    public string $password_confirmation = '';
    public $unitkerjas;
    public array $subunitkerjas;
    public $lokasis;
    public $lokasi_id = '';
    public $parent_id;
    public $sub_unit;
    public $subUnits;


    // public function __construct()
    // {
    //     // Ambil data Unit dan Lokasi
    //     $this->lokasis = LokasiStok::all();
    //     $this->unitkerjas = UnitKerja::whereNull('parent_id')->get();
    //     // dd($this->unitkerjas);
    //     // $this->subunitkerjas = UnitKerja::all()->toArray();
    // }

    public function register()
    {
        // Validasi Input
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed'],
            'parent_id' => ['required', 'exists:unit_kerja,id'],
            // 'lokasi_id' => ['required', 'exists:lokasi_stok,id'],
        ]);

        // Buat username dari email (bagian sebelum @)
        $username = strstr($validated['email'], '@', true);

        // Pastikan username unik
        $originalUsername = $username;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . $counter;
            $counter++;
        }

        // Buat User Baru
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'username' => $username,
            'password' => Hash::make($validated['password']),
            'unit_id' => $this->sub_unit ?? $this->parent_id,
            'lokasi_id' => $validated['lokasi_id'] ?? null,
            'email_verified_at' => Carbon::now(),
        ]);

        // Berikan Role 'Anggota' dari Spatie
        $user->assignRole('Anggota');

        // Trigger Event Registered
        event(new Registered($user));

        // Login User
        Auth::login($user);

        RateLimiter::clear($this->throttleKey());
    }



    public function updatedParentId()
    {

        if ($this->parent_id) {
            $this->subUnits = UnitKerja::where('parent_id', $this->parent_id)->get();
        }
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}
