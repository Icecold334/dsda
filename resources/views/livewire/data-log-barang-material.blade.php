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
            <div class="flex flex-wrap gap-4 mb-4 items-end">
                {{-- Filter Jenis --}}
                <div class="w-32">
                    {{-- <label class="block text-sm font-semibold mb-1">Jenis:</label> --}}
                    <select wire:model.live="filterJenis" class="border rounded-lg px-4 py-2 w-full">
                        <option value="">-- Semua Jenis --</option>
                        <option value="0">Keluar</option>
                        <option value="1">Masuk</option>
                    </select>
                </div>
                {{-- Filter Tanggal --}}
                <div class="w-32">
                    {{-- <label class="block text-sm font-semibold mb-1">Dari Tanggal:</label> --}}
                    <input type="date" wire:model.live="filterFromDate" class="border rounded-lg px-4 py-2 w-full" />
                </div>
                <div class="w-32">
                    {{-- <label class="block text-sm font-semibold mb-1">Sampai Tanggal:</label> --}}
                    <input type="date" wire:model.live="filterToDate" class="border rounded-lg px-4 py-2 w-full" />
                </div>

                {{-- Filter Bulan --}}
                <div class="w-32">
                    {{-- <label class="block text-sm font-semibold mb-1">Bulan:</label> --}}
                    <select wire:model.live="filterMonth" class="border rounded-lg px-4 py-2 w-full">
                        <option value="">-- Semua Bulan --</option>
                        @foreach(range(1,12) as $m)
                        <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Tahun --}}
                <div class="w-32">
                    {{-- <label class="block text-sm font-semibold mb-1">Tahun:</label> --}}
                    <select wire:model.live="filterYear" class="border rounded-lg px-4 py-2 w-full">
                        <option value="">-- Semua Tahun --</option>
                        @foreach(range(now()->year, now()->year - 5) as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol Reset --}}
                <div>
                    <button type="button" wire:click="resetFilters"
                        class="px-4 py-2 bg-blue-700 text-white rounded hover:bg-blue-400 transition">
                        <i class="fa fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <table class="w-full  border-3 border-separate border-spacing-y-4 ">
        <thead>
            <tr class="text-white uppercase">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Tanggal</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Nomor SPPB / Surat Jalan</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Gudang</th>
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
                <td class="py-3 px-6 text-center">{{ $barang['nomor'] }}</td>
                <td class="py-3 px-6 text-center">{{ $barang['gudang_nama'] }}</td>
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
                    <button class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300"
                        wire:click="selectedTanggal('{{ \Carbon\Carbon::parse($barang['tanggal'])->format('Y-m-d') }}', {{ $barang['jenis'] }}, {{ $barang['gudang_id'] }})"
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

    {{-- Modal Riwayat Barang --}}
    @if($modalVisible)
    {{-- @foreach ($detailList as $entry)
    @dd($entry)
    @endforeach --}}
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white w-full max-w-4xl rounded-lg shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h2 class="text-lg font-semibold">
                    Riwayat Barang {{ $jenisDipilih ? 'Masuk' : 'Keluar' }} {{ $tanggalDipilih }}
                </h2>
                <button wire:click="$set('modalVisible', false)" class="text-gray-500 hover:text-gray-800">
                    ✕
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 overflow-y-auto max-h-[70vh]">
                <div class="flex my-3">
                    @if (!$jenisDipilih)

                    @if ($dataSelected->spb_path)
                    <a class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"
                        href="{{ asset('storage/spb/' . $dataSelected->spb_path) }}" target="_blank" class="...">Unduh
                        SPB</a>
                    @else
                    <a class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"
                        onclick="confirmDownload('spb','{{ $dataSelected->nodin }}')" class="...">Unduh SPB</a>

                    @endif

                    @if ($dataSelected->sppb_path)
                    <a class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"
                        href="{{ asset('storage/sppb/' . $dataSelected->sppb_path) }}" target="_blank" class="...">Unduh
                        SPPB</a>
                    @else
                    <a class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"
                        onclick="confirmDownload('sppb','{{ $dataSelected->nodin }}')" class="...">Unduh SPPB</a>
                    @endif
                    @if ($dataSelected->suratJalan_path)
                    <a class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"
                        href="{{ asset('storage/suratJalan/' . $dataSelected->suratJalan_path) }}" target="_blank"
                        class="...">Unduh Surat
                        Jalan</a>
                    @else
                    <a class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"
                        onclick="confirmDownload('suratJalan','{{ $dataSelected->nodin }}')" class="...">Unduh Surat
                        Jalan</a>
                    @endif
                    @endif
                </div>
                <table class="w-full table-auto text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 text-left">Barang</th>
                            <th class="px-3 py-2 text-left">Merk</th>
                            <th class="px-3 py-2 text-left">Tipe</th>
                            <th class="px-3 py-2 text-left">Ukuran</th>
                            <th class="px-3 py-2 text-center">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($detailList as $item)
                        {{-- @foreach ($jenisDipilih == 0 ? $entry->detailPermintaan : $entry->detailPengirimanStok as
                        $item) --}}
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-3 py-2">{{ $item->merkStok->barangStok->nama ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $item->merkStok->nama ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $item->merkStok->tipe ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $item->merkStok->ukuran ?? '-' }}</td>
                            <td class="px-3 py-2 text-center">{{ $item->jumlah }} {{
                                $item->merkStok->barangStok->satuanBesar->nama }}</td>
                        </tr>
                        {{-- @endforeach --}}
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 py-4">Tidak ada data ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t text-right">
                <button wire:click="$set('modalVisible', false)"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

@pushOnce('scripts')
<script>
    function confirmDownload(docType,nomor) {
        Swal.fire({
            title: 'Gunakan TTD Elektronik (e-TTD)?',
            text: "Apakah Anda ingin menyertakan tanda tangan elektronik pada dokumen?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, gunakan TTD',
            cancelButtonText: 'Tanpa TTD',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('downloadDoc',{ type: docType, withSign: true,no:nomor });
                // Livewire.dispatch('downloadDoc', { type: docType, withSign: true });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                @this.call('downloadDoc',{ type: docType, withSign: false,no:nomor });
            }
        });
    }
</script>
@endPushOnce