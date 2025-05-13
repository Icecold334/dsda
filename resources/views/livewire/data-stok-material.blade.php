<div>
    <div wire:loading wire:target='downloadExcel'>
        <livewire:loading>
    </div>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">Daftar Stok
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


                <select wire:model.live="lokasi" wire:change="applyFilters" class="border rounded-lg px-4 py-2 w-full">
                    <option value="">Pilih Lokasi</option>
                    @foreach ($lokasiOptions as $lokasi)
                    <option value="{{ $lokasi }}">{{ $lokasi }}</option>
                    @endforeach
                </select>
                @if ($barangs->count())
                <button data-tooltip-target="tooltip-excel" wire:click="downloadExcel"
                    class="bg-white text-blue-500 h-10 border border-blue-500 rounded-lg px-4 py-2 flex items-center hover:bg-blue-500 hover:text-white transition-colors"><i
                        class="fa-solid fa-file-excel"></i></button>
                <div id="tooltip-excel" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Download dalam format excel
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <table class="w-full  border-3 border-separate border-spacing-y-4 ">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">LOKASI GUDANG</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">NAMA BARANG</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gudangs as $gudang)


            <tr class="bg-gray-50 hover:bg-gray-200">
                <td class="py-3 px-6"></td>
                <td class="py-3 px-6 font-semibold text-center">
                    {{ $gudang->nama }}
                </td>
                <td class="py-3 px-6">
                    <div class="grid grid-cols-1 gap-2">
                        @foreach ($gudang->barangStokSisa as $barangId => $jumlah)
                        @php
                        $barang = \App\Models\BarangStok::find($barangId);
                        @endphp

                        <div class="flex items-center justify-between border-b pb-1 text-sm">
                            <div class="font-semibold">{{ $barang->nama }}</div>
                            <div>{{ $jumlah }} {{ $barang->satuanBesar->nama }}</div>
                        </div>
                        @endforeach
                    </div>
                </td>
                <td class="py-3 px-6 text-center">
                    <a href="{{ route('stok.show', ['stok' => 1]) }}"
                        class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                </td>
            </tr>

            @endforeach
        </tbody>
    </table>

    {{ $barangs->onEachSide(1)->links() }}
</div>