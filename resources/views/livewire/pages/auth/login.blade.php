<?php

use App\Livewire\Forms\LoginForm;
use App\Livewire\Forms\RegisterForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use App\Models\UnitKerja;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public $unitkerjas;
    public LoginForm $form;
    public RegisterForm $registerForm;

    public function mount()
    {
        $this->unitkerjas = UnitKerja::whereNull('parent_id')->get();
    }

    public function register()
    {
        $this->registerForm->register();
    }

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        redirect()->to('/dashboard');
    }
};
?>

<div>
    <div class="forms-wrap">
        <!-- Login Form -->
        <form wire:submit="login" class="form sign-in-form">
            <a class="logo" href="/" style="text-decoration: none">
                <h2>{{ env('APP_NAME') }}</h2>
            </a>

            <div class="heading">
                <h2>Login</h2>
            </div>

            <div class="actual-form">
                <div class="input-wrap">
                    <input class="input-field" type="text" wire:model="form.email" name="email" id="email-login"
                        autocomplete="off">
                    <label>Email</label>
                </div>

                <div class="input-wrap">
                    <input class="input-field" wire:model="form.password" type="password" name="password"
                        autocomplete="off">
                    <label>Password</label>
                </div>

                <button type="submit" class="sign-btn">Login</button>
            </div>
        </form>

        <!-- Register Form -->
        <form wire:submit="register" class="form sign-up-form">
            <div class="heading">
                <h2>Registrasi</h2>
                <h6 class="heading">Sudah Punya Akun?</h6>
                <a href="" class="toggle">Login disini</a>
            </div>

            <div class="actual-form">
                <div class="input-wrap">
                    <select class="input-field {{ $errors->any() && strlen($registerForm->name) ? 'active' : '' }}"
                        wire:model="registerForm.parent_id">
                        <option value="">Pilih Unit Kerja</option>
                        @foreach ($unitkerjas as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->nama }}</option>
                        @foreach ($parent->children as $child)
                        <option value="{{ $child->id }}">--- {{ $child->nama }}</option>
                        @endforeach
                        @endforeach
                    </select>
                    <label>Unit Kerja</label>
                </div>

                <div class="input-wrap">
                    <input class="input-field {{ $errors->any() && strlen($registerForm->name) ? 'active' : '' }}"
                        type="text" wire:model="registerForm.name" autocomplete="off">
                    <label>Nama</label>
                </div>

                <div class="input-wrap">
                    <input class="input-field {{ $errors->any() && strlen($registerForm->email) ? 'active' : '' }}"
                        type="text" wire:model="registerForm.email" autocomplete="off">
                    <label>Email</label>
                </div>

                <div class="input-wrap">
                    <input class="input-field {{ $errors->any() && strlen($registerForm->nomor) ? 'active' : '' }}"
                        type="text" wire:model="registerForm.nomor" autocomplete="off">
                    <label>Nomor Telpon</label>
                </div>

                <div class="input-wrap">
                    <input class="input-field {{ $errors->any() && strlen($registerForm->password) ? 'active' : '' }}"
                        type="password" wire:model="registerForm.password" autocomplete="off">
                    <label>Password</label>
                </div>

                <div class="input-wrap">
                    <input
                        class="input-field {{ $errors->any() && strlen($registerForm->password_confirmation) ? 'active' : '' }}"
                        type="password" wire:model="registerForm.password_confirmation" autocomplete="off">
                    <label>Konfirmasi Password</label>
                </div>

                <button type="submit" class="sign-btn">Daftar</button>
            </div>
        </form>
    </div>

    <!-- Carousel Section with only one image -->
    <div class="carousel">
        <div class="carousel-content">
            <img src="{{ asset('img/logo-login.png') }}" alt="Logo DSDA" class="image" />
            {{-- <h2 class="welcome-text">Selamat Datang</h2>
            <h2 class="welcome-text">Login Untuk Melanjutkan</h2> --}}
        </div>
    </div>

    <!-- Optional: You can remove this if not needed -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const inputs = document.querySelectorAll(".input-field");
            const toggle_btns = document.querySelectorAll(".toggle");
            const main = document.querySelector("main");

            inputs.forEach((inp) => {
                if (inp.value !== "") {
                    inp.classList.add("active");
                }

                inp.addEventListener("focus", () => inp.classList.add("active"));
                inp.addEventListener("blur", () => {
                    if (inp.value === "") inp.classList.remove("active");

                    if (inp.value.trim() === "" || !inp.checkValidity()) {
                        inp.classList.add("error");
                        inp.nextElementSibling.classList.add("error");
                    } else {
                        inp.classList.remove("error");
                        inp.nextElementSibling.classList.remove("error");
                    }
                });
            });

            toggle_btns.forEach((btn) => {
                btn.addEventListener("click", (e) => {
                    e.preventDefault();
                    main.classList.toggle("sign-up-mode");
                });
            });
        });
    </script>
</div>