<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem dalam Perbaikan</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.1/dist/dotlottie-wc.js" type="module"></script>
    
    <style>
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #F8F9FA;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #212529;
            padding: 20px;
            box-sizing: border-box;
        }

        .container {
            max-width: 850px; /* Lebarkan container utama */
            width: 100%;
        }

        .lottie-container {
            width: 100%;
            /* UKURAN ANIMASI DIPERBESAR */
            max-width: 1200px; 
            margin: 0 auto 30px auto; /* Kurangi sedikit jarak ke teks */
        }
        
        h1 {
            font-size: 36px; /* Ukuran judul disesuaikan */
            font-weight: 700;
            color: #2c3e50; /* Warna lebih soft black */
            margin-bottom: 15px;
        }

        p {
            font-size: 18px; /* Ukuran deskripsi disesuaikan */
            color: #7f8c8d; /* Warna abu-abu yang lebih lembut */
            line-height: 1.7;
            max-width: 550px;
            margin: 0 auto;
        }

        .footer {
            position: absolute;
            bottom: 20px;
            font-size: 14px; /* Sedikit diperbesar agar terbaca */
            color: #bdc3c7; /* Warna lebih soft */
        }

        /* Penyesuaian untuk layar kecil (Mobile) */
        @media (max-width: 600px) {
            .lottie-container {
                /* UKURAN ANIMASI MOBILE DIPERBESAR */
                max-width: 350px; 
            }
            h1 {
                font-size: 26px;
            }
            p {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        
        <div class="lottie-container">
            <dotlottie-wc
                src="https://lottie.host/997c0953-7784-43bd-a8f0-ad713dfcfd7d/C2csthjaRW.lottie"
                background="transparent"
                speed="1"
                autoplay
                loop>
            </dotlottie-wc>
        </div>

        <h1>Aplikasi Sedang dalam Perbaikan</h1>
        <p>
            Saat ini kami sedang melakukan pemeliharaan. Akan segera kembali dalam beberapa saat. Terima kasih atas kesabaran Anda.
        </p>

    </div>

    <footer class="footer">
        &copy; {{ date('Y') }} Dinas Sumber Daya Air Provinsi DKI Jakarta
    </footer>
</body>
</html>