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
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");

        *,
        *::before,
        *::after {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body,
        input {
            font-family: "Poppins", sans-serif;
        }

        main {
            width: 100%;
            min-height: 100vh;
            overflow: hidden;
            background-color: #0d6efd;
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .box {
            position: relative;
            width: 100%;
            max-width: 1020px;
            height: 640px;
            background-color: #fff;
            border-radius: 3.3rem;
            box-shadow: 0 60px 40px -30px rgba(0, 0, 0, 0.27);
        }

        .inner-box {
            position: absolute;
            width: calc(100% - 4.1rem);
            height: calc(100% - 4.1rem);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .forms-wrap {
            position: absolute;
            height: 100%;
            width: 45%;
            top: 0;
            left: 0;
            display: grid;
            grid-template-columns: 1fr;
            grid-template-rows: 1fr;
            transition: 0.8s ease-in-out;
        }

        form {
            max-width: 260px;
            width: 100%;
            margin: 0 auto;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-evenly;
            grid-column: 1 / 2;
            grid-row: 1 / 2;
            transition: opacity 0.02s 0.4s;
        }

        form.sign-up-form {
            opacity: 0;
            pointer-events: none;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            width: 37px;
            margin-right: 0.3rem;
        }

        .logo h4 {
            font-size: 1.1rem;
            margin-top: -9px;
            letter-spacing: -0.5px;
            color: #151111;
        }

        .heading h2 {
            font-size: 2.1rem;
            font-weight: 600;
            color: #151111;
        }

        h2 {
            font-size: 2.1rem;
            font-weight: 600;
            color: #2b2b2b;
        }

        .heading h6 {
            color: #151111;
            font-weight: 400;
            font-size: 0.75rem;
            display: inline;
        }

        .toggle {
            color: #151111;
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 500;
            transition: 0.3s;
            margin-left: 1rem;
        }

        .toggle:hover {
            color: #8371fd;
        }

        .input-wrap {
            position: relative;
            height: 37px;
            margin-bottom: 2rem;
        }

        .input-field {
            position: absolute;
            width: 100%;
            height: 100%;
            background: none;
            border: none;
            outline: none;
            border-bottom: 1px solid #bbb;
            padding: 0;
            font-size: 0.95rem;
            color: #151111;
            transition: 0.4s;
        }

        label {
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.95rem;
            color: #616161;
            pointer-events: none;
            transition: 0.4s;
        }

        .input-field.active {
            border-bottom-color: #151111;
        }

        .input-field.active+label {
            font-size: 0.75rem;
            top: -2px;
        }

        .input-field.error {
            border-bottom-color: red;
        }

        .label.error {
            color: red;
        }

        .sign-btn {
            display: inline-block;
            width: 100%;
            height: 43px;
            background-color: #0d6efd;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 0.8rem;
            font-size: 0.8rem;
            margin-bottom: 2rem;
            transition: 0.3s;
        }

        .sign-btn:hover {
            background-color: #0045ac;
        }

        .sign-google {
            display: inline-block;
            width: 100%;
            height: 43px;
            background-color: #383838;
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 0.8rem;
            font-size: 0.8rem;
            margin-bottom: 2rem;
            transition: 0.3s;
        }

        .sign-google:hover {
            background-color: #0045ac;
        }

        .text {
            color: #bbb;
            font-size: 0.7rem;
        }

        .text a {
            color: #bbb;
            transition: 0.3s;
        }

        .text a:hover {
            color: #8371fd;
        }

        main.sign-up-mode form.sign-in-form {
            opacity: 0;
            pointer-events: none;
        }

        main.sign-up-mode form.sign-up-form {
            opacity: 1;
            pointer-events: all;
        }

        main.sign-up-mode .forms-wrap {
            left: 55%;
        }

        main.sign-up-mode .carousel {
            left: 0%;
        }

        .carousel {
            position: absolute;
            height: 100%;
            width: 55%;
            left: 45%;
            top: 0;
            background-color: #0066ff;
            border-radius: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            overflow: hidden;
            transition: 0.8s ease-in-out;
            flex-direction: column;
            text-align: center;
        }

        .carousel .image {
            max-width: 500px;
            width: 100%;
            height: auto;
            object-fit: contain;
            margin-bottom: 2rem;
        }

        .welcome-text {
            font-size: 1.6rem;
            font-weight: 600;
            color: #222;
        }

        .images-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            padding: 2rem;
        }

        .image {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        /* .img-1 {
            transform: translate(0, -50px);
        } */

        .img-2 {
            transform: scale(0.4, 0.5);
        }

        .image.show {
            opacity: 1;
            transform: none;
        }

        .text-slider {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .text-wrap {
            max-height: 2.2rem;
            overflow: hidden;
            margin-bottom: 2.5rem;
        }

        .text-group {
            display: flex;
            flex-direction: column;
            text-align: center;
            transform: translateY(0);
            transition: 0.5s;
        }

        .text-group h2 {
            line-height: 2.2rem;
            font-weight: 600;
            font-size: 1.6rem;

        }

        .bullets {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bullets span {
            display: block;
            width: 0.5rem;
            height: 0.5rem;
            background-color: #4b4b4b;
            margin: 0 0.25rem;
            border-radius: 50%;
            cursor: pointer;
            transition: 0.3s;
        }

        .bullets span.active {
            width: 1.1rem;
            background-color: #151111;
            border-radius: 1rem;
        }

        @media (max-width: 850px) {
            .box {
                height: auto;
                max-width: 550px;
                overflow: hidden;
            }

            .inner-box {
                position: static;
                transform: none;
                width: revert;
                height: revert;
                padding: 2rem;
            }

            .forms-wrap {
                position: revert;
                width: 100%;
                height: auto;
            }

            form {
                max-width: revert;
                padding: 1.5rem 2.5rem 2rem;
                transition: transform 0.8s ease-in-out, opacity 0.45s linear;
            }

            .heading {
                margin: 2rem 0;
            }

            form.sign-up-form {
                transform: translateX(100%);
            }

            main.sign-up-mode form.sign-in-form {
                transform: translateX(-100%);
            }

            main.sign-up-mode form.sign-up-form {
                transform: translateX(0%);
            }

            .carousel {
                position: revert;
                height: auto;
                width: 100%;
                padding: 3rem 2rem;
                display: flex;
            }

            .images-wrapper {
                display: none;
            }

            .text-slider {
                width: 100%;
            }
        }

        @media (max-width: 530px) {
            main {
                padding: 1rem;
            }

            .box {
                border-radius: 2rem;
            }

            .inner-box {
                padding: 1rem;
            }

            .carousel {
                padding: 1.5rem 1rem;
                border-radius: 1.6rem;
            }

            .text-wrap {
                margin-bottom: 1rem;
            }

            .text-group h2 {
                font-size: 1.2rem;
            }

            form {
                padding: 1rem 2rem 1.5rem;
            }
        }
    </style>@stack('vite')
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <script src="https://kit.fontawesome.com/5fd2369345.js" crossorigin="anonymous"></script>

</head>


<body>
    <main class="">
        {{-- @if (session('register')) sign-up-mode @endif --}}
        <div class="box">
            <div class="inner-box">
                {{ $slot }}
            </div>
        </div>
    </main>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const inputs = document.querySelectorAll(".input-field");
            const toggle_btns = document.querySelectorAll(".toggle");
            const google_btns = document.querySelectorAll("#google");
            const main = document.querySelector("main");
            const bullets = document.querySelectorAll(".bullets span");
            const images = document.querySelectorAll(".image");
            const textSlider = document.querySelector(".text-group");

            inputs.forEach((inp) => {
                if (inp.value !== "") {
                    inp.classList.add("active");
                }

                inp.addEventListener("focus", () => {
                    inp.classList.add("active");
                });
                inp.addEventListener("blur", () => {
                    if (inp.value != "") return;
                    inp.classList.remove("active");
                });

                inp.addEventListener("blur", function() {
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
            google_btns.forEach((btn) => {
                btn.addEventListener("click", (e) => {
                    e.preventDefault();
                    main.classList.toggle("sign-up-mode");
                });
            });

            function moveSlider() {
                let index = this.dataset.value;

                let currentImage = document.querySelector(`.img-${index}`);
                images.forEach((img) => img.classList.remove("show"));
                currentImage.classList.add("show");

                const textSliderChildren = textSlider.children;
                for (let i = 0; i < textSliderChildren.length; i++) {
                    textSliderChildren[i].style.transform = `translateY(${-(index - 1) * 2.2}rem)`;
                }

                bullets.forEach((bull) => bull.classList.remove("active"));
                this.classList.add("active");
            }

            bullets.forEach((bullet) => {
                bullet.addEventListener("click", moveSlider);
            });

            let currentIndex = 1;
            setInterval(() => {
                currentIndex = currentIndex === 1 ? 2 : 1;
                bullets[currentIndex - 1].click();
            }, 4000);

            // Form validation
            function validateForm(e) {
                let formValid = true;
                inputs.forEach((inp) => {
                    if (inp.value.trim() === "" || !inp.checkValidity()) {
                        inp.classList.add("error");
                        inp.nextElementSibling.classList.add("error");
                        formValid = false;
                    } else {
                        inp.classList.remove("error");
                        inp.nextElementSibling.classList.remove("error");
                    }
                });
                if (!formValid) {
                    e.preventDefault();
                }
            }

            // signInBtn.addEventListener("click", validateForm);
            // signUpBtn.addEventListener("click", validateForm);
        });
    </script>

</body>

</html>