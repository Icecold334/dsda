<x-body>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900 ">
            {{ request()->routeIs('permintaan-stok.index') || request()->is('permintaan/umum') ? 'Pelayanan Umum' : (request()->is('permintaan/spare-part') ? 'Permintaan Spare Part' : 'Permintaan Material') }}
            @if (auth()->user()->unitKerja)
                {{ auth()->user()->unitKerja->parent ? auth()->user()->unitKerja->parent->nama : auth()->user()->unitKerja->nama }}
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
            @elseif(request()->is('permintaan/spare-part') || request()->is('permintaan/material'))
                <a href="/permintaan/add/{{ request()->segment(2) }}/{{ request()->segment(2) }}"
                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                    + Tambah Permintaan
                </a>
            @endif
        </div>

    </div>
    @if ($tipe == 'umum' && true)
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
        {{-- <div class="flex justify-between py-2 mb-3">
            <h1 class="text-2xl font-bold text-primary-900 ">
                {{ request()->routeIs('permintaan-stok.index') || request()->is('permintaan/umum') ? 'Pelayanan Umum' : (request()->is('permintaan/spare-part') ? 'Permintaan Spare Part' : 'Permintaan Material') }}
                @if (auth()->user()->unitKerja)
                    {{ auth()->user()->unitKerja->parent ? auth()->user()->unitKerja->parent->nama : auth()->user()->unitKerja->nama }}
                @endif
            </h1>
            <div>
                @if (request()->routeIs('permintaan-stok.index') || request()->is('permintaan/umum'))
                    <a href="/permintaan/add/permintaan"
                        class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                        + Tambah Permintaan
                    </a>
                    <a href="/permintaan/add/peminjaman"
                        class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                        + Tambah Peminjaman
                    </a>
                @elseif(request()->is('permintaan/spare-part') || request()->is('permintaan/material'))
                    <a href="/permintaan/add/{{ request()->segment(2) }}"
                        class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                        + Tambah Permintaan
                    </a>
                @endif
            </div>

        </div> --}}

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

        <table class="w-full border-3 border-separate border-spacing-y-4">
            <thead>
                <tr class="text-white">
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">KODE
                        PERMINTAAN{{ request()->routeIs('permintaan-stok.index') || request()->is('permintaan/umum') ? '/PEMINJAMAN' : '' }}
                    </th>
                    @if (request()->routeIs('permintaan-stok.index') || request()->is('permintaan/umum'))
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Jenis layanan</th>
                    @endif
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">TANGGAL PENGGUNAAN</th>
                    {{-- <th class="py-3 px-6 bg-primary-950 text-center font-semibold">BARANG</th> --}}
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">UNIT KERJA</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">STATUS</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permintaans as $permintaan)
                    <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                        <td class="px-6 py-3"></td>
                        <td class="px-6 py-3 font-semibold">
                            {{ $permintaan->kode_permintaan ?? $permintaan->kode_peminjaman }}</td>
                        @if (request()->routeIs('permintaan-stok.index') || request()->is('permintaan/umum'))
                            <td class="px-6 py-3 font-semibold">
                                <div>
                                    {{ $permintaan->getTable() == 'detail_permintaan_stok' ? 'Permintaan' : 'Peminjaman' }}
                                </div>
                                <div class="text-gray-500 text-sm">
                                    {{ $permintaan->getTable() == 'detail_permintaan_stok' ? $permintaan->kategoriStok->nama : $permintaan->kategori->nama }}
                                </div>
                            </td>
                        @endif
                        <td class="px-6 py-3 font-semibold">{{ date('j F Y', $permintaan->tanggal_permintaan) }}</td>
                        {{-- <td class="px-6 py-3 font-semibold">{{ $permintaan->kode_permintaan }}</td> --}}
                        <td class="px-6 py-3 font-semibold">
                            <div class="text-gray-600 text-sm">
                                {{ $permintaan->subUnit->nama ?? $permintaan->unit->nama }}
                            </div>
                        </td>
                        <td class="py-3 px-6">
                            <p class="font-semibold text-gray-800 text-center">
                                <span
                                    class="
        bg-{{ $permintaan->cancel === 1
            ? 'secondary'
            : ($permintaan->cancel === 0 && $permintaan->proses === 1
                ? 'primary'
                : ($permintaan->cancel === 0 && $permintaan->proses === null
                    ? 'info'
                    : ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status === null
                        ? 'warning'
                        : ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status === 1
                            ? 'success'
                            : 'danger')))) }}-600
        text-{{ $permintaan->cancel === 1
            ? 'secondary'
            : ($permintaan->cancel === 0 && $permintaan->proses === 1
                ? 'primary'
                : ($permintaan->cancel === 0 && $permintaan->proses === null
                    ? 'info'
                    : ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status === null
                        ? 'warning'
                        : ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status === 1
                            ? 'success'
                            : 'danger')))) }}-100
        text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                                    {{ $permintaan->cancel === 1
                                        ? 'dibatalkan'
                                        : ($permintaan->cancel === 0 && $permintaan->proses === 1
                                            ? 'selesai'
                                            : ($permintaan->cancel === 0 && $permintaan->proses === null
                                                ? 'siap diambil'
                                                : ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status === null
                                                    ? 'diproses'
                                                    : ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status === 1
                                                        ? 'disetujui'
                                                        : 'ditolak')))) }}
                                </span>


                            </p>
                        </td>
                        <td class="py-3 px-6 text-center">
                            {{-- @dump($permintaan->getTable()) --}}
                            <a href="/permintaan/{{ $permintaan->getTable() === 'detail_peminjaman_aset' ? 'peminjaman' : 'permintaan' }}/{{ $permintaan->id }}"
                                class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300"
                                data-tooltip-target="tooltip-permintaan-{{ $permintaan->id }}">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <div id="tooltip-permintaan-{{ $permintaan->id }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Lihat Detail Permintaan
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                            {{-- <a href="{{ route('permintaan-stok.edit', ['permintaan_stok' => $permintaan->id]) }}"
                            class="text-primary-950 px-3 py-3 mx-2 rounded-md border hover:bg-slate-300"
                            data-tooltip-target="tooltip-edit-kontrak-{{ $permintaan->id }}">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <div id="tooltip-edit-kontrak-{{ $permintaan->id }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Perbarui Permintaan
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</x-body>
