<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900 ">
            {{ $tipe === null ? 'Pelayanan Umum' : ($tipe == 'spare-part' ? 'Permintaan Spare Part' : 'Permintaan Material') }}
            @if (auth()->user()->unitKerja)
                {{-- {{ auth()->user()->unitKerja->parent ? auth()->user()->unitKerja->parent->nama : auth()->user()->unitKerja->nama }} --}}
                {{ auth()->user()->unitKerja->nama }}
            @endif
        </h1>
        <div>
            <div class="flex gap-4">
                <!-- Search Input -->
                <input type="date" wire:model.live="tanggal" class="border rounded-lg px-4 py-2 w-40" />

                <input type="text" wire:model.live="search" class="border rounded-lg px-4 py-2 w-40"
                    placeholder="Cari Kode" />
                @if (!$tipe)
                    <select wire:model.live="jenis" class="border rounded-lg px-4 py-2 w-40">
                        <option value="">Semua Jenis</option>
                        <option value="permintaan">Permintaan</option>
                        <option value="peminjaman">Peminjaman</option>
                    </select>
                @endif
                <select wire:model.live="selected_unit_id" class="border rounded-lg px-4 py-2 w-40">
                    <option value="">Semua Unit</option>
                    @foreach ($unitOptions as $item)
                        {{-- @dd($item) --}}
                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                        @foreach ($item->children as $unit)
                            <option value="{{ $unit->id }}">--- {{ $unit->nama }}</option>
                        @endforeach
                    @endforeach
                </select>
                <select wire:model.live="status" class="border rounded-lg px-4 py-2 w-40">
                    <option value="">Semua Status</option>
                    <option value="diproses">diproses</option>
                    <option value="ditolak">ditolak</option>
                    <option value="dibatalkan">dibatalkan</option>
                    <option value="disetujui">disetujui</option>
                    <option value="siap diambil">siap diambil</option>
                    <option value="selesai">selesai</option>
                </select>
                <div wire:loading wire:target='downloadExcel'>
                    <livewire:loading>
                </div>
                @if ($permintaans->count())
                    @can('pelayanan_xls')
                        <button data-tooltip-target="tooltip-excel" wire:click="downloadExcel"
                            class="bg-white text-blue-500 h-10 border border-blue-500 rounded-lg px-4 py-2 flex items-center hover:bg-blue-500 hover:text-white transition-colors"><i
                                class="fa-solid fa-file-excel"></i></button>
                        <div id="tooltip-excel" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Unduh dalam format excel
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    @endcan
                @endif
                @if ($nonUmum)
                    <a href="/permintaan/add/{{ request()->segment(2) }}/{{ request()->segment(2) }}"
                        class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 transition duration-200">
                        + Tambah Permintaan
                    </a>
                @endif
            </div>

        </div>

    </div>
    <table class="w-full border-3 border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">KODE
                    PERMINTAAN<span class="{{ $tipe ? 'hidden' : '' }}">/PEMINJAMAN</span>
                </th>
                <th class="{{ $tipe ? 'hidden' : '' }} py-3 px-6 bg-primary-950 text-center font-semibold">JENIS LAYANAN
                </th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">TANGGAL PENGGUNAAN</th>
                {{-- <th class="py-3 px-6 bg-primary-950 text-center font-semibold">BARANG</th> --}}
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">UNIT KERJA</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">STATUS</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($permintaans as $permintaan)
                <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                    <td class="px-6 py-3"></td>
                    <td class="px-6 py-3 font-semibold">
                        {{ $permintaan['kode'] }}
                    </td>
                    <td class="px-6 py-3 font-semibold {{ $tipe ? 'hidden' : '' }}">
                        <div>
                            {{ Str::ucfirst($permintaan['tipe']) }}
                        </div>
                        <div class="text-gray-500 text-sm">
                            {{ $permintaan['kategori']?->nama }}
                        </div>
                    </td>
                    <td class="px-6 py-3 font-semibold">{{ date('j F Y', $permintaan['tanggal']) }}</td>
                    <td class="px-6 py-3 font-semibold">
                        <div class="text-gray-600 text-sm">
                            {{ $permintaan['sub_unit']?->nama ?? $permintaan['unit']?->nama }}
                        </div>
                    </td>
                    <td class="py-3 px-6">
                        <p class="font-semibold text-gray-800 text-center">
                            <span
                                class="
    bg-{{ $permintaan['cancel'] === 1
        ? 'secondary'
        : ($permintaan['cancel'] === 0 && $permintaan['proses'] === 1
            ? 'primary'
            : ($permintaan['cancel'] === 0 && $permintaan['proses'] === null
                ? 'info'
                : ($permintaan['cancel'] === null && $permintaan['proses'] === null && $permintaan['status'] === null
                    ? 'warning'
                    : ($permintaan['cancel'] === null && $permintaan['proses'] === null && $permintaan['status'] === 1
                        ? 'success'
                        : 'danger')))) }}-600
    text-{{ $permintaan['cancel'] === 1
        ? 'secondary'
        : ($permintaan['cancel'] === 0 && $permintaan['proses'] === 1
            ? 'primary'
            : ($permintaan['cancel'] === 0 && $permintaan['proses'] === null
                ? 'info'
                : ($permintaan['cancel'] === null && $permintaan['proses'] === null && $permintaan['status'] === null
                    ? 'warning'
                    : ($permintaan['cancel'] === null && $permintaan['proses'] === null && $permintaan['status'] === 1
                        ? 'success'
                        : 'danger')))) }}-100
    text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                                {{ $permintaan['cancel'] === 1
                                    ? 'dibatalkan'
                                    : ($permintaan['cancel'] === 0 && $permintaan['proses'] === 1
                                        ? 'selesai'
                                        : ($permintaan['cancel'] === 0 && $permintaan['proses'] === null
                                            ? 'siap diambil'
                                            : ($permintaan['cancel'] === null && $permintaan['proses'] === null && $permintaan['status'] === null
                                                ? 'diproses'
                                                : ($permintaan['cancel'] === null && $permintaan['proses'] === null && $permintaan['status'] === 1
                                                    ? 'disetujui'
                                                    : 'ditolak')))) }}
                            </span>
                        </p>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <a href="/permintaan/{{ $permintaan['tipe'] === 'peminjaman' ? 'peminjaman' : 'permintaan' }}/{{ $permintaan['id'] }}"
                            class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300"
                            data-tooltip-target="tooltip-permintaan-{{ $permintaan['id'] }}">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <div id="tooltip-permintaan-{{ $permintaan['id'] }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Lihat Detail Permintaan
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">
                        <div class="text-center text-gray-600 text-lg">Tidak ada data yang ditemukan.</div>
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
    {{-- {{ $permintaans->onEachSide(1)->links() }} --}}
</div>
