<div>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">Riwayat Keluar Masuk Barang
            @if (auth()->user()->unitKerja)
            {{-- {{ auth()->user()->unitKerja->parent ? auth()->user()->unitKerja->parent->nama :
            auth()->user()->unitKerja->nama }} --}}
            @if (!auth()->user()->unitKerja->hak)
            {{ $sudin }}
            @else
            {{ auth()->user()->unitKerja->nama }}
            @endif
            @endif


        </h1>
        <div>
            {{-- <a href="{{ route('aset.create') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                Tambah Aset</a> --}}
            <div class="flex gap-4">
                <!-- Search Input -->
                <input type="text" wire:model.live="search" class="border rounded-lg px-4 py-2 w-full"
                    placeholder="Cari Kode / Barang" />

                <!-- Dropdown untuk Memilih Jenis -->
                {{-- <select wire:model.live="jenis"
                    class="border rounded-lg px-4 py-2 w-full {{ $isSeribu ? 'hidden':'' }}">
                    <option value="">Pilih Jenis</option>
                    @foreach ($jenisOptions as $jenis)
                    <option value="{{ $jenis }}">{{ $jenis }}</option>
                    @endforeach
                </select>
                <select wire:model.live="lokasi" wire:change="applyFilters" class="border rounded-lg px-4 py-2 w-full">
                    <option value="">Pilih Lokasi</option>
                    @foreach ($lokasiOptions as $lokasi)
                    <option value="{{ $lokasi }}">{{ $lokasi }}</option>
                    @endforeach
                </select> --}}
            </div>
        </div>
    </div>

    <table class="w-full  border-3 border-separate border-spacing-y-4 ">
        <thead>
            <tr class="text-white uppercase">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Tanggal</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">JENIS</th>
                {{-- <th class="py-3 px-6 bg-primary-950 text-center font-semibold">SPESIFIKASI (MERK/TIPE/UKURAN)</th>
                --}}
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold ">volume</th>
                {{-- <th class="py-3 px-6 bg-primary-950 text-center font-semibold">LOKASI </th> --}}
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/12 rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($list as $barang)
            <tr class="bg-gray-50  hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                <td class="py-3 px-6"></td>
                <td class="py-3 px-6 font-semibold">
                    <div>{{ $barang['tanggal'] }}</div>
                </td>
                <td class="py-3 px-6 font-semibold text-center">
                    <span
                        class="bg-{{ $barang['jenis'] ? 'primary':'secondary' }}-600 text-{{ $barang['jenis'] ? 'primary':'secondary' }}-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                        {{ $barang['jenis'] ?'Masuk':'Keluar' }}
                    </span>
                </td>
                <td class="py-3 px-6 font-semibold text-center">
                    {{ $barang['jumlah'] }}
                </td>
                <td class="py-3 px-6">
                    <button class=" text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300 "
                        data-tooltip-target="tooltip-stok-{{ $barang['uuid'] }}">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                    <div id="tooltip-stok-{{ $barang['uuid'] }}" role="tooltip"
                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Lihat Riwayat Stok
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Tidak ada data riwayat ditemukan</td>
            </tr>
            @endforelse

        </tbody>
    </table>
</div>