<?php

use App\Livewire\Forms\LoginForm;
use App\Livewire\Forms\RegisterForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use App\Models\UnitKerja;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public $unitkerjas;
    // public function __construct()
    // {
    //     // Ambil data Unit dan Lokasi
    //     $this->lokasis = LokasiStok::all();
    //     $this->unitkerjas = UnitKerja::whereNull('parent_id')->get();
    //     // dd($this->unitkerjas);
    //     // $this->subunitkerjas = UnitKerja::all()->toArray();
    // }

    public LoginForm $form;
    public RegisterForm $registerForm;

    public function mount()
    {
        // $this->form = new LoginForm();
        // $this->registerForm = new RegisterForm();
        // Ambil data Unit dan Lokasi
        $this->unitkerjas = UnitKerja::whereNull('parent_id')->get();
        // dd($this->unitkerjas);
        // $this->subunitkerjas = UnitKerja::all()->toArray();
    }

    public function register()
    {
        $this->registerForm->register();
    }

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        redirect()->to('/dashboard');
        // $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>
<div>
    <div class="forms-wrap">
        <!-- Sign In Form -->
        <form wire:submit="login" class="form sign-in-form">
            <a class="logo" href="/" style="text-decoration: none">
                {{-- <img src="{{ asset('dashboard/img/logo.png') }}" alt="easyclass" /> --}}
                <h2>{{ env('APP_NAME') }}</h2>
            </a>

            <div class="heading">
                <h2>Login</h2>
                {{-- <h6 class="heading">Belum punya akun?</h6>
                <a href="" class="toggle">Daftar disini</a> --}}
            </div>

            {{-- @dump($form) --}}
            <div class="actual-form">
                <div class="input-wrap">
                    <input class="input-field " type="text" wire:model="form.email" name="email" id="email-login"
                        autocomplete="off">
                    <label>Email</label>
                </div>

                <div class="input-wrap">
                    <input class="input-field " wire:model="form.password" type="password" name="password"
                        autocomplete="off">
                    <label>Password</label>
                </div>

                <button type="submit" class="sign-btn">Login</button>
                <button type="button" class="sign-google" id="google">Registrasi</button>
            </div>
        </form>
        <!-- Sign Up Form -->
        <form wire:submit="register" class="form sign-up-form">
            <div class="heading">
                <h2>Registrasi</h2>
                <h6 class="heading">Sudah Punya Akun?</h6>
                <a href="" class="toggle">Login disini</a>
            </div>

            <div class="actual-form">
                <div class="input-wrap">
                    <select class="input-field {{ $errors->any() && strlen($registerForm->name) ? 'active' : '' }} "
                        type="text" wire:model="registerForm.parent_id" autocomplete="off">
                        <label>Nama</label>
                        <option value="">Pilih Unit Kerja</option>
                        @foreach ($unitkerjas as $parent)
                            <option value="{{ $parent->id }}">{{ $parent->nama }}</option>
                            @foreach ($parent->children as $child)
                                <option value="{{ $child->id }}">--- {{ $child->nama }}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div class="input-wrap">
                    <input class="input-field {{ $errors->any() && strlen($registerForm->name) ? 'active' : '' }} "
                        type="text" wire:model="registerForm.name" autocomplete="off">
                    <label>Nama</label>
                </div>
                <div class="input-wrap">
                    <input class="input-field {{ $errors->any() && strlen($registerForm->email) ? 'active' : '' }} "
                        type="text" wire:model="registerForm.email" autocomplete="off">
                    <label>Email</label>
                </div>
                {{-- 
                <div class="input-wrap">
                    <input class="input-field {{ $errors->any() && strlen($registerForm->name) }} " type="text" name="username"
                        @if (session('register')) value="{{ old('username') }}" @endif autocomplete="off">
                    <label>Username</label>
                </div>

                <div class="input-wrap">
                    <input class="input-field {{ $errors->any() && strlen($registerForm->name) }} " type="text" name="email" value="{{ old('email') }}"
                        autocomplete="off">
                    <label>Email</label>
                </div> --}}

                <div class="input-wrap">
                    <input class="input-field {{ $errors->any() && strlen($registerForm->nomor) ? 'active' : '' }} "
                        type="text" wire:model="registerForm.nomor" autocomplete="off">
                    <label>Nomor Telpon</label>
                </div>

                <div class="input-wrap">
                    <input class="input-field {{ $errors->any() && strlen($registerForm->password) ? 'active' : '' }} "
                        type="password" wire:model="registerForm.password" autocomplete="off">
                    <label>Password</label>
                </div>

                <div class="input-wrap">
                    <input
                        class="input-field {{ $errors->any() && strlen($registerForm->password_confirmation) ? 'active' : '' }} "
                        type="password" wire:model="registerForm.password_confirmation" autocomplete="off">
                    <label>Konfirmasi Password</label>
                </div>

                <button type="submit" class="sign-btn">Daftar</button>
            </div>
        </form>
    </div>

    <div class="carousel">
        <div class="images-wrapper">
            <img src="{{ asset('img/img-1.png') }}" class="image img-1 show" />
            <img src="{{ asset('img/img-2.png') }}" class="image img-2" />
        </div>

        <div class="text-slider">
            <div class="text-wrap">
                <div class="text-group" style="color: white;">
                    <h2>Selamat Datang</h2>
                    <h2>Login Untuk Melanjutkan</h2>
                </div>
            </div>

            <div class="bullets">
                <span class="active" data-value="1"></span>
                <span data-value="2"></span>
            </div>
        </div>
    </div>

    {{-- @if (strlen($errors->first() > 0)) --}}

    {{-- @endif --}}
    <script>
        // let timerInterval;
        // Swal.fire({
        //     title: 'asd',
        //     html: 'asd',
        //     icon: 'error',
        //     timer: 1500,
        //     timerProgressBar: true,
        //     showConfirmButton: false,
        //     willClose: () => {
        //         clearInterval(timerInterval);
        //     },
        // }).then((result) => {
        //     /* Read more about handling dismissals below */
        //     if (result.dismiss === Swal.DismissReason.timer) {
        //         // console.log("I was closed by the timer");
        //     }
        // });
    </script>
</div>

{{-- <div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input wire:model="form.email" id="email" class="block mt-1 w-full" type="email" name="email"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full" type="password"
                name="password" required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember" class="inline-flex items-center">
                <input wire:model="form.remember" id="remember" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>


        <div class="flex items-center justify-between mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('register') }}" wire:navigate>
                {{ __('Register?') }}
            </a>
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}" wire:navigate>
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

    </form>
</div> --}}
