<div>
    <div wire:loading wire:target='downloadExcel'>
        <livewire:loading>
    </div>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">Daftar Stok

            {{ $sudin }}



        </h1>
        <div>
            {{-- <a href="{{ route('aset.create') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                Tambah Aset</a> --}}
            <div class="flex gap-4">
                @if ($all)
                <div class=" w-full max-w-lg">

                    <select id="unitKerjaSelect" wire:model.live="unit_id"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">-- Pilih Unit --</option>
                        @foreach ($sudins as $sudin)
                        <option value="{{ $sudin->id }}">{{ $sudin->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <!-- Search Input -->
                {{-- <input type="text" wire:model.live="search" class="border rounded-lg px-4 py-2 w-full"
                    placeholder="Cari Kode / Barang" />


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
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg">LOKASI GUDANG</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">NAMA BARANG</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($gudangs as $gudang)

            <tr class="bg-gray-50 hover:bg-gray-200">
                <td class="py-3 px-6 font-semibold text-center">
                    {{ $gudang->nama }}
                </td>
                <td class="py-3 px-6">
                    <div class="grid grid-cols-1 gap-2">
                        @foreach ($gudang->barangStokSisa->take(2) as $barangId => $jumlah)
                        @php
                        $barang = \App\Models\BarangStok::find($barangId);
                        @endphp

                        <div class="flex items-center justify-between border-b pb-1 text-sm">
                            <div class="font-semibold">{{ $barang->nama }}</div>
                            <div>{{ $jumlah }} {{ $barang->satuanBesar->nama }}</div>
                        </div>
                        @endforeach
                        @if ($gudang->barangStokSisa->count() > 2)
                        <div class="flex items-center justify-center border-b pb-1 text-sm">
                            <div class="font-semibold">{{ $gudang->barangStokSisa->count() - 2 }} Barang Lain</div>
                        </div>
                        @endif
                    </div>
                </td>
                <td class="py-3 px-6 text-center">
                    <a href="{{ route('stok.show', ['stok' => $gudang->id]) }}"
                        class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                </td>
            </tr>

            @endforeach
        </tbody>
    </table>

</div>