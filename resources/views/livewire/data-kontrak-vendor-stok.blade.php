<div>
    <div class="flex justify-between py-2 mb-3">

        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    Swal.fire({
                        title: 'Success!',
                        text: "{{ session('success') }}",
                        icon: 'success',
                        confirmButtonText: 'Okay'
                    });
                });
            </script>
        @endif

        <h1 class="text-2xl font-bold text-primary-900 ">Daftar Kontrak
            @if (auth()->user()->unitKerja)
                @if (!(auth()->user()->unitKerja->hak ?? 0))
                    {{ auth()->user()->unitKerja->nama ?? 'Unit Kerja Tidak Diketahui' }}
                @else
                    {{ auth()->user()->unitKerja->nama }}
                @endif
            @else
                Semua Unit Kerja
            @endif
        </h1>

        <div class="flex gap-4">
            <input type="date" wire:model.live="tanggal" class="border rounded-lg px-4 py-2" />
            <input type="text" wire:model.live="search" class="border rounded-lg px-4 py-2"
                placeholder="Cari Kode / Vendor" />

            <select wire:model.live="jenis"
                class="border rounded-lg px-4 py-2 {{ !(auth()->user()->unitKerja->hak ?? 0) ? 'hidden' : '' }}">
                <option value="">Pilih Jenis</option>
                @if(isset($jenisOptions))
                    @foreach ($jenisOptions as $jenis)
                        <option value="{{ $jenis }}">{{ $jenis }}</option>
                    @endforeach
                @endif
            </select>

            <select wire:model.live="metode" class="border rounded-lg px-4 py-2">
                <option value="">Pilih Metode</option>
                @if(isset($metodeOptions))
                    @foreach ($metodeOptions as $metode)
                        <option value="{{ $metode }}">{{ $metode }}</option>
                    @endforeach
                @endif
            </select>

            @can('kontrak.read')
                <button wire:click="applyFilters" class="bg-blue-500 text-white px-4 py-2 rounded-lg">
                    <i class="fa fa-sync-alt"></i>
                </button>
            @endcan

            @can('kontrak.create')
                <a href="{{ route('kontrak-vendor-stok.create') }}"
                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                    + Rekam Kontrak Baru
                </a>
            @endcan
        </div>
    </div>

    <table class="w-full border-3 border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">NOMOR KONTRAK</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">NAMA PAKET</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">NAMA PENYEDIA</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">TAHUN PENGADAAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">TANGGAL KONTRAK</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">JENIS PENGADAAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">JENIS KONTRAK</th>
                <th class="py-3 px-6 bg-primary-950 text-center w-1/5 font-semibold">DETAIL TRANSAKSI</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($groupedTransactions as $index => $transaction)
                <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                    <td class="py-3 px-6"></td>
                    <td class="py-3 px-6 font-semibold text-gray-800">{{ $transaction->nomor_kontrak }}</td>
                    <td class="py-3 px-6 font-semibold text-gray-800">{{ $transaction->nama_paket }}</td>
                    <td class="py-3 px-6 font-semibold text-gray-800">{{ $transaction->vendorStok->nama ?? 'Unknown Vendor'
                            }}</td>
                    <td class="py-3 px-6 font-semibold text-gray-800">{{ $transaction->tahun_anggaran }}</td>
                    <td class="py-3 px-6 font-semibold text-gray-800">
                        {{ $transaction->tanggal_kontrak ? date('j F Y', $transaction->tanggal_kontrak) : '---' }}
                    </td>
                    <td class="py-3 px-6 font-semibold text-center text-gray-800">{{ $transaction->jenis_pengadaan }}</td>
                    <td class="py-3 px-6 font-semibold text-center text-gray-800">
                        {{ $transaction->parent ? 'Adendum' : 'Baru' }}
                    </td>
                    <td class="py-3 px-6">
                        <table class="w-full text-sm border-spacing-y-2">
                            <thead class="text-primary-800">
                                <tr>
                                    <th class="w-1/3">Barang</th>
                                    <th class="w-1/3">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaction->listKontrak->take(3) as $tran)
                                    <tr class="border-b-[1px] border-primary-800">
                                        <td class="border-r-4 px-2">
                                            {{ $tran->merkStok->barangStok->nama }}
                                        </td>
                                        <td class="px-2">{{ $tran->jumlah }} {{ $tran->merkStok->barangStok->satuanBesar->nama
                                                    }}</td>
                                    </tr>
                                @endforeach
                                @if ($transaction->listKontrak->count() > 3)
                                    <tr class="border-b-[1px] border-primary-800">
                                        <td colspan="2" class="text-center font-semibold px-2">
                                            {{ $transaction->listKontrak->count() - 3 }} Transaksi Lain
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </td>
                    <td class="py-3 px-6 text-center flex gap-2">
                        <a href="{{ route('kontrak-vendor-stok.show', ['kontrak_vendor_stok' => $transaction->id]) }}"
                            class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300"
                            data-tooltip-target="tooltip-kontrak-{{ $transaction->id }}">
                            <i class="fa-solid fa-eye"></i>
                        </a>

                        @can('kontrak.update')
                            <a href="{{ route('kontrak-vendor-stok.edit', ['kontrak_vendor_stok' => $transaction->id]) }}"
                                class="text-warning-950 px-3 py-3 rounded-md border hover:bg-slate-300"
                                data-tooltip-target="tooltip-edit-{{ $transaction->id }}">
                                <i class="fa-solid fa-pencil"></i>
                            </a>
                        @endcan

                        <div id="tooltip-kontrak-{{ $transaction->id }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Lihat Detail Kontrak
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>