<x-body>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900">Master Data User</h1>
        <div class="flex space-x-2">
            <a href="{{ route('users.export', request()->query()) }}"
                class="text-green-700 bg-green-100 hover:bg-green-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                <i class="fas fa-download mr-1"></i> Export Excel
            </a>
            <a href="{{ route('users.create') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                <i class="fas fa-plus mr-1"></i> Tambah User
            </a>
        </div>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded'
                    }
                });
            });
        </script>
    @endif

    @if (session('info'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Info!',
                    text: "{{ session('info') }}",
                    icon: 'info',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded'
                    }
                });
            });
        </script>
    @endif

    <!-- Livewire Users Index Component -->
    <livewire:users-index />
</x-body>