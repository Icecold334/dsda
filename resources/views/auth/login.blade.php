<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link href="/dashboard/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <script src="https://kit.fontawesome.com/5fd2369345.js" crossorigin="anonymous"></script>
    <style>
        /* (Semua CSS tetap sama seperti sebelumnya — tidak diubah) */
    </style>
</head>

<body>
    <main class="@if (session('register')) sign-up-mode @endif">
        <div class="box">
            <div class="inner-box">
                <div class="forms-wrap">
                    <!-- Login Form -->
                    <form wire:submit="loginCheck">
                        <a class="logo" href="/" style="text-decoration: none">
                            <h2>{{ env('APP_NAME') }}</h2>
                        </a>
                        <div class="heading">
                            <h2>Login</h2>
                            <h6 class="heading">Belum punya akun?</h6>
                            <a href="" class="toggle">Daftar disini</a>
                        </div>
                        <div class="actual-form">
                            <div class="input-wrap">
                                <input class="input-field" type="text" wire:model="form.email" name="email"
                                    id="email-login" autocomplete="off">
                                <label>Email</label>
                            </div>
                            <div class="input-wrap">
                                <input class="input-field" wire:model="form.password" type="password" name="password"
                                    autocomplete="off">
                                <label>Password</label>
                            </div>
                            <button type="submit" class="sign-btn">Login</button>
                            <button type="button" class="sign-google" id="google">
                                <i class="fa-brands fa-google"></i> Login Dengan Google
                            </button>
                        </div>
                    </form>

                    <!-- Register Form -->
                    <form action="/register" method="post" class="form sign-up-form">
                        @csrf
                        <div class="heading">
                            <h2>Registrasi</h2>
                            <h6 class="heading">Sudah Punya Akun?</h6>
                            <a href="" class="toggle">Login disini</a>
                        </div>
                        <div class="actual-form">
                            <div class="input-wrap">
                                <input class="input-field" type="text" name="name" value="{{ old('name') }}"
                                    autocomplete="off">
                                <label>Nama</label>
                            </div>
                            <div class="input-wrap">
                                <input class="input-field" type="text" name="username" @if (session('register'))
                                    value="{{ old('username') }}" @endif autocomplete="off">
                                <label>Username</label>
                            </div>
                            <div class="input-wrap">
                                <input class="input-field" type="text" name="email" value="{{ old('email') }}"
                                    autocomplete="off">
                                <label>Email</label>
                            </div>
                            <div class="input-wrap">
                                <input class="input-field" type="text" name="phone" value="{{ old('phone') }}"
                                    autocomplete="off">
                                <label>Nomor Telpon</label>
                            </div>
                            <div class="input-wrap">
                                <input class="input-field" type="password" name="password" autocomplete="off">
                                <label>Password</label>
                            </div>
                            <div class="input-wrap">
                                <input class="input-field" type="password" name="password_confirmation"
                                    autocomplete="off">
                                <label>Konfirmasi Password</label>
                            </div>
                            <input type="submit" value="Daftar" class="sign-btn" />
                        </div>
                    </form>
                </div>

                <!-- Carousel (hanya img-1 tanpa bullets) -->
                <div class="carousel">
                    <div class="images-wrapper">
                        <img src="{{ asset('img/img-1.png') }}" class="image img-1 show" />
                    </div>
                    <div class="text-slider">
                        <div class="text-wrap">
                            <div class="text-group" style="color: white;">
                                <h2>Selamat Datang</h2>
                                <h2>Login Untuk Melanjutkan</h2>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- SweetAlert notifications -->
    @if (session('login'))
    <script>
        Swal.fire({
                toast: true,
                icon: "error",
                title: "Login gagal!",
                position: "top-start",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
    </script>
    @endif
    @if (session('register'))
    <script>
        Swal.fire({
                toast: true,
                icon: "error",
                title: "Registrasi gagal!",
                position: "top-start",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
    </script>
    @endif
    @if (session('daftar'))
    <script>
        Swal.fire({
                toast: true,
                icon: "success",
                title: "Registrasi berhasil!",
                position: "top-start",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
    </script>
    @endif

    <!-- JS logic -->
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
    <script>
        $('#google').click(() => {
            location.href = '{{ url('/auth/google') }}';
        });
    </script>
</body>

</html>