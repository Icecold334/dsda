<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900 ">
            Pelayanan Umum
            @if (auth()->user()->unitKerja)
                {{ auth()->user()->unitKerja->parent ? auth()->user()->unitKerja->parent->nama : auth()->user()->unitKerja->nama }}
            @endif
        </h1>
        <div>
            <div class="flex gap-4">
                <!-- Search Input -->
                <input type="date" wire:model.live="tanggal" class="border rounded-lg px-4 py-2 w-full" />

                <input type="text" wire:model.live="search" class="border rounded-lg px-4 py-2 w-full"
                    placeholder="Cari Kode" />
                <select wire:model.live="jenis" class="border rounded-lg px-4 py-2 w-full">
                    <option value="">Pilih Jenis</option>
                    <option value="permintaan">Permintaan</option>
                    <option value="peminjaman">Peminjaman</option>
                </select>
                <select wire:model.live="unit_id" class="border rounded-lg px-4 py-2 w-full">
                    <option value="">Pilih sub-unit</option>
                    @foreach ($unitOptions as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                    @endforeach
                </select>
                <select wire:model.live="status" class="border rounded-lg px-4 py-2 w-full">
                    <option value="">Pilih status</option>
                    <option value="diproses">diproses</option>
                    <option value="ditolak">ditolak</option>
                    <option value="dibatalkan">dibatalkan</option>
                    <option value="disetujui">disetujui</option>
                    <option value="siap diambil">siap diambil</option>
                    <option value="selesai">selesai</option>
                </select>

                <div id="tooltip-excel" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Download dalam format excel
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            </div>

        </div>

    </div>
    <table class="w-full border-3 border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">KODE
                    PERMINTAAN/PEMINJAMAN
                </th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">JENIS LAYANAN</th>
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
                        {{ $permintaan->kode }}</td>
                    <td class="px-6 py-3 font-semibold">
                        <div>
                            {{ $permintaan->tipe == 'permintaan' ? 'Permintaan' : 'Peminjaman' }}
                        </div>
                        <div class="text-gray-500 text-sm">
                            {{ $permintaan->tipe == 'permintaan' ? $permintaan->kategoriStok->nama : $permintaan->kategori->nama }}
                        </div>
                    </td>
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

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
