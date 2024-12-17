<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INVENTA Manajemen Aset</title>
    <script type="importmap">
    {
        "imports": {
            "https://esm.sh/v135/prosemirror-model@1.22.3/es2022/prosemirror-model.mjs": "https://esm.sh/v135/prosemirror-model@1.19.3/es2022/prosemirror-model.mjs", 
            "https://esm.sh/v135/prosemirror-model@1.22.1/es2022/prosemirror-model.mjs": "https://esm.sh/v135/prosemirror-model@1.19.3/es2022/prosemirror-model.mjs"
        }
    }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/5fd2369345.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/2.0.6/css/dataTables.bootstrap5.css"> --}}
    <link rel="stylesheet"
        href="https://gistcdn.githack.com/mfd/09b70eb47474836f25a21660282ce0fd/raw/e06a670afcb2b861ed2ac4a1ef752d062ef6b46b/Gilroy.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body>
    {{-- @dd(session()->get('alert')) --}}
    {{-- @dd('alert') --}}
    @if (session('alert') && !request()->is('profil/profile'))
        <script type="module">
            Swal.fire({
                title: "Lengkapi Data!",
                text: "Harap lengkapi NIP dan Tanda Tangan Anda sebelum melanjutkan.",
                icon: "warning",
                confirmButtonText: "Lengkapi Sekarang",
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/profil/profile";
                }
            });
        </script>
    @endif
    <livewire:navbar />
    <div class="mx-[3%] px-1 py-10">
        {{ $slot }}
    </div>
    @stack('html')
</body>
@if (session('success'))
    <script type="module">
        feedback('Berhasil', "{{ session('success') }}", 'success');
    </script>
@endif

@if (session('tanya'))
    <script type="module">
        // SweetAlert pertama: Konfirmasi tambah permintaan
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Tambah Permintaan?',
                text: "Apakah Anda ingin membuat permintaan lagi?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika pilih "Ya", munculkan alert kedua
                    Swal.fire({
                        title: 'Layanan Apa yang Dipilih?',
                        text: "Silakan pilih jenis layanan:",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Permintaan',
                        cancelButtonText: 'Peminjaman'
                    }).then((choice) => {
                        if (choice.isConfirmed) {
                            // Jika pilih "Permintaan", redirect ke halaman permintaan umum
                            window.location.href = "/permintaan/add/permintaan";
                        } else {
                            // Jika pilih "Peminjaman", redirect ke halaman peminjaman
                            window.location.href = "/permintaan/add/peminjaman";
                        }
                    });
                }
            });
        });
    </script>
@endif
@stack('scripts')

</html>
