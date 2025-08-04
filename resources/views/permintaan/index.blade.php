<x-body>
    @if ($tipe == 'umum' && true)
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900 ">
            {{ request()->routeIs('permintaan-stok.index') || request()->is('permintaan/umum') ? 'Pelayanan Umum' :
            (request()->is('permintaan/spare-part') ? 'Permintaan Spare Part' : 'Permintaan Material') }}
            @if (auth()->user()->unitKerja)
            {{-- {{ auth()->user()->unitKerja->parent ? auth()->user()->unitKerja->parent->nama :
            auth()->user()->unitKerja->nama }} --}}
            {{ auth()->user()->unitKerja->nama }}
            @endif
        </h1>
        <div>
            @if (request()->routeIs('permintaan-stok.index') || request()->is('permintaan/umum'))
            {{-- <a href="/permintaan/add/permintaan"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                + Tambah Permintaan
            </a>
            <a href="/permintaan/add/peminjaman"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                + Tambah Peminjaman
            </a> --}}
            @elseif(request()->is('permintaan/spare-part') || request()->is('permintaan/material') &&
            auth()->user()->can('permintaan_tambah_permintaan'))
            <a href="/permintaan/add/{{ request()->segment(2) }}/{{ request()->segment(2) }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                + Tambah Permintaan
            </a>
            @endif
        </div>

    </div>
    <div class="grid grid-cols-2 gap-6">

        <x-card title='Permintaan'>
            <div class="flex flex-col  gap-3">
                @foreach ($kategoris as $kategori)
                <a href="/permintaan/add/permintaan/{{ Str::slug($kategori->nama) }}"
                    class="block items-center p-4 w-full shadow-lg bg-white border border-gray-200 rounded-lg hover:bg-gray-200 dark:bg-gray-800 transition duration-200 dark:border-gray-700 dark:hover:bg-gray-700 vertical-center">
                    <div class="content">
                        <div
                            class="flex text-2xl justify-between font-bold tracking-tight text-gray-900 dark:text-white">
                            <div>
                                Permintaan {{ $kategori->nama }}
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </x-card>
        <x-card title='Peminjaman'>
            <div class="flex flex-col capitalize  gap-3">
                <a href="/permintaan/add/peminjaman/kdo"
                    class="block items-center p-4 w-full shadow-lg bg-white border border-gray-200 rounded-lg hover:bg-gray-200 dark:bg-gray-800 transition duration-200 dark:border-gray-700 dark:hover:bg-gray-700 vertical-center">
                    <div class="content">
                        <div
                            class="flex text-2xl justify-between font-bold tracking-tight text-gray-900 dark:text-white">
                            <div>
                                Peminjaman KDO
                            </div>
                        </div>
                    </div>
                </a>
                <a href="/permintaan/add/peminjaman/ruangan"
                    class="block items-center p-4 w-full shadow-lg bg-white border border-gray-200 rounded-lg hover:bg-gray-200 dark:bg-gray-800 transition duration-200 dark:border-gray-700 dark:hover:bg-gray-700 vertical-center">
                    <div class="content">
                        <div
                            class="flex text-2xl justify-between font-bold tracking-tight text-gray-900 dark:text-white">
                            <div>
                                Peminjaman ruangan
                            </div>
                        </div>
                    </div>
                </a>
                <a href="/permintaan/add/peminjaman/peralatan-kantor"
                    class="block items-center p-4 w-full shadow-lg bg-white border border-gray-200 rounded-lg hover:bg-gray-200 dark:bg-gray-800 transition duration-200 dark:border-gray-700 dark:hover:bg-gray-700 vertical-center">
                    <div class="content">
                        <div
                            class="flex text-2xl justify-between font-bold tracking-tight text-gray-900 dark:text-white">
                            <div>
                                Peminjaman peralatan kantor
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </x-card>
    </div>
    @else
    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Success!',
                        text: "{{ session('success') }}",
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    });
                });
    </script>
    @endif

    @if (auth()->user()->unitKerja && auth()->user()->unitKerja->hak)
        <livewire:data-permintaan />
    @else
        <livewire:data-permintaan-material />
    @endif
@endif
</x-body>