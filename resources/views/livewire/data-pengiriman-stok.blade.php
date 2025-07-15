<div>
    <div class="flex justify-between py-1 mb-3">
        <div class="text-2xl font-bold text-primary-900">
            Pengiriman Material
        </div>
        <div class="flex gap-4 ">
            <input type="date" wire:model.live="tanggal" class="border rounded-lg px-4 py-2 w-full" />
            <input type="text" wire:model.live="search" class="border rounded-lg px-4 py-2 w-full"
                placeholder="Cari Kode / Vendor" />
            <select wire:model.live="jenis" class="border rounded-lg px-4 py-2 w-full">
                <option value="">Pilih Jenis</option>
                @foreach ($jenisOptions as $jenis)
                <option value="{{ $jenis }}">{{ $jenis }}</option>
                @endforeach
            </select>
            <button wire:click="applyFilters" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                <i class="fa fa-sync-alt"></i>
            </button>
            @can('penerimaan.create')
            <a href="{{ route('pengiriman-stok.create') }}"
                class="text-primary-900 bg-primary-100 w-full hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Tambah Pengiriman
            </a>
            @endcan
        </div>
    </div>

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
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">NAMA PENYEDIA</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">JENIS PENGIRIMAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">KODE PENGIRIMAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">TANGGAL</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">STATUS</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($datangs as $datang)
            <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                <td class="px-6 py-3"></td>
                <td class="px-6 py-3 font-semibold">
                    {{ $datang->pengirimanStok->first()->kontrakVendorStok->nama_penyedia ?? 'Tidak Diketahui' }}
                </td>
                <td class="px-6 py-3 font-semibold">
                    {{ $datang->pengirimanStok->first()->merkStok->barangStok->jenisStok->nama }}
                </td>
                <td class="px-6 py-3 font-semibold">
                    {{ $datang->kode_pengiriman_stok }}
                </td>
                <td class="px-6 py-3 font-semibold">
                    {{ date('j F Y', $datang->tanggal) }}
                </td>
                <td class="px-6 py-3 text-center">
                    <span
                        class="bg-{{ $datang->status === null ? 'warning' : ($datang->status ? 'success' : 'danger') }}-400 text-{{ $datang->status === null ? 'black' : 'white' }} text-xs font-medium me-2 px-2.5 py-0.5 rounded-full dark:bg-{{ $datang->status === null ? 'warning' : ($datang->status ? 'success' : 'danger') }}-900 dark:text-{{ $datang->status === null ? 'warning' : ($datang->status ? 'success' : 'danger') }}-300">
                        {{ $datang->status === null ? 'diproses' : ($datang->status ? 'disetujui' : 'ditolak') }}
                    </span>
                </td>
                <td class="py-3 px-6 text-center">
                    @can('penerimaan.read')
                    <a href="{{ route('pengiriman-stok.show', ['pengiriman_stok' => $datang->id]) }}"
                        class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300"
                        data-tooltip-target="tooltip-aset-{{ $datang->id }}">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    @endcan
                    <div id="tooltip-aset-{{ $datang->id }}" role="tooltip"
                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Ubah Detail Kedatangan Barang
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-6">Tidak Ada Data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>