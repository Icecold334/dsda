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

        try {
            $this->form->authenticate();
            Session::regenerate();
            redirect()->to('/dashboard');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Clear form ketika login gagal
            $this->form->username = '';
            $this->form->password = '';
            throw $e;
        }
    }
};
?>

<div>
    <!-- Add Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <div class="forms-wrap">
        <!-- Login Form -->
        <form wire:submit="login" class="form sign-in-form">
            <a class="logo" href="/" style="text-decoration: none">
                <h2>{{ env('APP_NAME') }}</h2>
            </a>

            <div class="heading">
                <h2>Login</h2>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="error-messages"
                    style="background-color: #fee2e2; border: 1px solid #fecaca; color: #dc2626; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="actual-form">
                <div class="input-wrap">
                    <input class="input-field @error('form.username') error @enderror" type="text"
                        wire:model="form.username" name="username" id="username-login" autocomplete="off">
                    <label>Username</label>
                    @error('form.username')
                        <span class="error-text"
                            style="color: #dc2626; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-wrap">
                    <input class="input-field @error('form.password') error @enderror" wire:model="form.password"
                        type="password" name="password" autocomplete="off" id="password-login">
                    <label>Password</label>
                    <span class="password-toggle" onclick="togglePassword('password-login', this)">
                        <i class="fas fa-eye eye-icon"></i>
                    </span>
                    @error('form.password')
                        <span class="error-text"
                            style="color: #dc2626; font-size: 12px; margin-top: 5px; display: block;">{{ $message }}</span>
                    @enderror
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

            <!-- Error Messages for Registration -->
            @if (
                    $errors->any() && (
                        $errors->has('registerForm.name') ||
                        $errors->has('registerForm.email') ||
                        $errors->has('registerForm.nomor') ||
                        $errors->has('registerForm.password') ||
                        $errors->has('registerForm.password_confirmation') ||
                        $errors->has('registerForm.parent_id')
                    )
                )
                <div class="error-messages"
                    style="background-color: #fee2e2; border: 1px solid #fecaca; color: #dc2626; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @if ($errors->has('registerForm.name'))
                            <li>{{ $errors->first('registerForm.name') }}</li>
                        @endif
                        @if ($errors->has('registerForm.email'))
                            <li>{{ $errors->first('registerForm.email') }}</li>
                        @endif
                        @if ($errors->has('registerForm.nomor'))
                            <li>{{ $errors->first('registerForm.nomor') }}</li>
                        @endif
                        @if ($errors->has('registerForm.password'))
                            <li>{{ $errors->first('registerForm.password') }}</li>
                        @endif
                        @if ($errors->has('registerForm.password_confirmation'))
                            <li>{{ $errors->first('registerForm.password_confirmation') }}</li>
                        @endif
                        @if ($errors->has('registerForm.parent_id'))
                            <li>{{ $errors->first('registerForm.parent_id') }}</li>
                        @endif
                    </ul>
                </div>
            @endif

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
                        type="password" wire:model="registerForm.password" autocomplete="off" id="password-register">
                    <label>Password</label>
                    <span class="password-toggle" onclick="togglePassword('password-register', this)">
                        <i class="fas fa-eye eye-icon"></i>
                    </span>
                </div>

                <div class="input-wrap">
                    <input
                        class="input-field {{ $errors->any() && strlen($registerForm->password_confirmation) ? 'active' : '' }}"
                        type="password" wire:model="registerForm.password_confirmation" autocomplete="off"
                        id="password-confirm">
                    <label>Konfirmasi Password</label>
                    <span class="password-toggle" onclick="togglePassword('password-confirm', this)">
                        <i class="fas fa-eye eye-icon"></i>
                    </span>
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

                inp.addEventListener("focus", () => {
                    inp.classList.add("active");
                    // Remove error styling when user starts typing
                    inp.classList.remove("error");
                    const errorMessage = inp.parentNode.querySelector(".error-text");
                    if (errorMessage) {
                        errorMessage.style.display = "none";
                    }
                });

                inp.addEventListener("input", () => {
                    // Remove error styling when user starts typing
                    inp.classList.remove("error");
                    const errorMessage = inp.parentNode.querySelector(".error-text");
                    if (errorMessage) {
                        errorMessage.style.display = "none";
                    }
                });

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

            // Hide error messages when user starts typing
            inputs.forEach((inp) => {
                inp.addEventListener("input", () => {
                    const errorContainer = document.querySelector(".error-messages");
                    if (errorContainer) {
                        errorContainer.style.display = "none";
                    }
                });
            });
        });

        function togglePassword(inputId, toggleElement) {
            const input = document.getElementById(inputId);
            const eyeIcon = toggleElement.querySelector('.eye-icon');

            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>

    <style>
        .input-wrap {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            user-select: none;
            z-index: 10;
        }

        .eye-icon {
            font-size: 16px;
            color: #666;
        }

        .password-toggle:hover .eye-icon {
            color: #333;
        }

        .error-messages {
            background-color: #fee2e2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .error-messages ul {
            margin: 0;
            padding-left: 20px;
        }

        .error-text {
            color: #dc2626;
            font-size: 12px;
            margin-top: 5px;
            display: block;
            font-weight: 500;
        }

        .input-field.error {
            border-color: #dc2626;
            background-color: #fef2f2;
        }

        .input-field.error:focus {
            outline: none;
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }
    </style>
</div>