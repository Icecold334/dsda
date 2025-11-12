<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900">DETAIL KONTRAK</h1>
        <div class="flex gap-2">
            @if (count($riwayatKontrak) > 1)
            {{-- @dump($kontrakId) --}}
            <div>
                <label for="riwayatSelect" class="text-sm font-semibold text-gray-600">Versi Kontrak:</label>
                <select id="riwayatSelect" wire:model.live.debounce.500ms="kontrakId"
                    class="border border-gray-300 rounded-md px-2 py-1">
                    <option value="" disabled>Pilih Versi</option>
                    @foreach ($riwayatKontrak as $k)
                    <option value="{{ $k->id }}">
                        #{{ $k->nomor_kontrak }} - {{ date('d M Y', $k->tanggal_kontrak) }}
                    </option>
                    @endforeach
                </select>
            </div>
            @endif

            @can('kontrak.create')
            <a href="{{ route('kontrak-vendor-stok.edit',['kontrak_vendor_stok'=>$kontrak->id]) }}"
                class="text-warning-900 bg-warning-100 hover:bg-warning-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition">
                Adendum
            </a>
            @endcan
            <a href="{{ route('kontrak-vendor-stok.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition">
                Kembali
            </a>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <x-card title="Data Umum">
                <table class="w-full font-semibold">
                    <tr>
                        <td>Nama Vendor</td>
                        <td>{{ $kontrak->nama_penyedia }}</td>
                    </tr>
                    <tr>
                        <td>Nomor Kontrak</td>
                        <td>{{ $kontrak->nomor_kontrak }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Barang</td>
                        <td>{{ $kontrak->listKontrak->first()->merkStok->barangStok->jenisStok->nama ?? '-' }}</td>
                    </tr>
                    {{-- <tr>
                        <td>Metode Pengadaan</td>
                        <td>{{ $kontrak->metodePengadaan->nama ?? '-' }}</td>
                    </tr> --}}
                    <tr>
                        <td>Tanggal Kontrak</td>
                        <td>{{ date('j F Y', $kontrak->tanggal_kontrak) }}</td>
                    </tr>

                    {{-- Informasi Tambahan --}}
                    <tr>
                        <td colspan="2" class="py-2">
                            <hr>
                        </td>
                    </tr>
                    <tr>
                        <td>Tahun Anggaran</td>
                        <td>{{ $kontrak->tahun_anggaran ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Dinas/Sudin</td>
                        <td>{{ $kontrak->dinas_sudin ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Bidang/Seksi</td>
                        <td>{{ $kontrak->nama_bidang_seksi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Program</td>
                        <td>{{ $kontrak->program ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Kegiatan</td>
                        <td>{{ $kontrak->kegiatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Sub Kegiatan</td>
                        <td>{{ $kontrak->sub_kegiatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Aktivitas Sub Kegiatan</td>
                        <td>{{ $kontrak->aktivitas_sub_kegiatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Rekening</td>
                        <td>{{ $kontrak->rekening ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Nama Paket</td>
                        <td>{{ $kontrak->nama_paket ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Pengadaan</td>
                        <td>{{ $kontrak->jenis_pengadaan ?? '-' }}</td>
                    </tr>
                </table>
            </x-card>
        </div>
        <div>
            <x-card title="Dokumen kontrak">
                @foreach ($kontrak->dokumen as $attachment)
                <div class="flex items-center justify-between border-b-4 p-2 rounded my-1">
                    <span class="flex items-center space-x-3">
                        @php
                        $fileType = pathinfo($attachment->file, PATHINFO_EXTENSION);
                        @endphp
                        <span class="text-primary-600">
                            @if (in_array($fileType, ['png', 'jpg', 'jpeg', 'gif']))
                            <i class="fa-solid fa-image text-green-500"></i>
                            @elseif($fileType == 'pdf')
                            <i class="fa-solid fa-file-pdf text-red-500"></i>
                            @elseif(in_array($fileType, ['doc', 'docx']))
                            <i class="fa-solid fa-file-word text-blue-500"></i>
                            @else
                            <i class="fa-solid fa-file text-gray-500"></i>
                            @endif
                        </span>

                        <!-- File name with underline on hover and a link to the saved file -->
                        <span>
                            <a href="{{ asset('storage/dokumenKontrak/' . $attachment->file) }}" target="_blank"
                                class="text-gray-800 hover:underline">
                                {{ basename($attachment->file) }}
                            </a>
                        </span>
                    </span>
                </div>
                @endforeach

            </x-card>
        </div>
    </div>
    <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
        <thead>
            <tr class="text-white">

                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6 rounded-l-lg">BARANG</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">SPESIFIKASI (MERK/TIPE/UKURAN)</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/12">JUMLAH</th>
                @if ($kontrak->type)
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/12">JUMLAH TERKIRIM</th>
                @else
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">LOKASI PENERIMAAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">KETERANGAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">DOKUMEN PENDUKUNG</th>
                @endif
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold ">HARGA SATUAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/12">PPN</th>
                <th class="py-3 px-6 bg-primary-950 text-left w-1/6 font-semibold ">HARGA TOTAL</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody class="">
            @foreach ($kontrak->listKontrak as $transaksi)
            <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                <td class="py-3 px-6 font-semibold">
                    {{ $transaksi->merkStok->barangStok->nama }}
                </td>
                <td class="py-3 px-6 font-semibold">
                    <table class="w-full">
                        <tr>
                            <td class="w-1/3 px-3  {{ $transaksi->merkStok->nama ?? 'text-center' }}">
                                {{ $transaksi->merkStok->nama ?? '-' }}</td>
                            <td
                                class="w-1/3 px-3 border-x-2 border-primary-500 {{ $transaksi->merkStok->tipe ?? 'text-center' }}">
                                {{ $transaksi->merkStok->tipe ?? '-' }}</td>
                            <td class="w-1/3 px-3 {{ $transaksi->merkStok->ukuran ?? 'text-center' }}">
                                {{ $transaksi->merkStok->ukuran ?? '-' }}</td>
                        </tr>
                    </table>
                </td>
                <td class="py-3 px-6 font-semibold">
                    {{ $transaksi->jumlah }}
                    {{ $transaksi->merkStok->barangStok->satuanBesar->nama }}
                </td>
                @if ($kontrak->type)
                <td class="text-center py-3 font-semibold ">
                    @php
                    $parent = $kontrak->parent_kontrak_id ? $kontrak->parent_kontrak_id : $kontrak->id;
                    $kontrakIds = \App\Models\KontrakVendorStok::where('id', $parent)
                    ->orWhere('parent_kontrak_id', $parent)
                    ->pluck('id');

                    $jumlahTerkirim = \App\Models\PengirimanStok::whereIn('kontrak_id', $kontrakIds)
                    ->where('merk_id', $transaksi->merk_id)
                    ->sum('jumlah');

                    $sisa = max(0, $transaksi->jumlah - $jumlahTerkirim);
                    @endphp

                    {{ $jumlahTerkirim }} {{ $transaksi->merkStok->barangStok->satuanBesar->nama }}

                </td>
                @else
                <td class="text-center py-3 font-semibold ">
                    {{ $transaksi->lokasi_penerimaan }}</td>
                <td class="text-center py-3 font-semibold ">{{ $transaksi->deskripsi }}</td>
                <td class="flex justify-center py-3 font-semibold "> <a class="text-center"
                        href="{{ asset('storage/buktiTransaksi/' . $transaksi->img) }}" target="_blank">
                        <img src="{{ asset('storage/buktiTransaksi/' . $transaksi->img) }}" alt="Preview Bukti"
                            class="w-16 h-16 rounded-md text-center">
                    </a></td>
                @endif
                <td class="text-center py-3 font-semibold ">
                    Rp {{ number_format($transaksi->harga, 0, ',', '.') }}
                </td>

                <td class="text-center px-3 py-3 text-sm font-semibold ">
                    {{ $transaksi->ppn ? $transaksi->ppn . '%' : 'Sudah Termasuk PPN' }}</td>

                <td class="text-left py-3 font-semibold ">
                    {{-- Harga total --}}
                    Rp
                    {{ number_format($transaksi->harga * $transaksi->jumlah + ($transaksi->harga *
                    $transaksi->jumlah *
                    $transaksi->ppn) / 100, 0, ',', '.') }}
                </td>


                <td class="text-center py-3 ">
                </td>
            </tr>
            @endforeach
            <tr class="...">
                <td colspan="{{ $kontrak->type ? '6' : '8' }}" class="px-6 py-3 font-semibold text-right">
                    Total
                </td>
                <td class="text-left py-3 font-semibold ">
                    Rp {{ number_format($total, 0, ',', '.') }}
                </td>
                <td class="py-3 px-6  font-semibold text-center"></td>
            </tr>
        </tbody>
    </table>
    <!-- Penulis -->
    <livewire:aproval-kontrak :date="$kontrak->created_at" :kontrak="$kontrak" :status="$kontrak->status">
</div>