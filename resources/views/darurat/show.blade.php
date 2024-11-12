<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">DETAIL TRANSAKSI</h1>
        <div>
            <a href="{{ route('transaksi-darurat-stok.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-3">
        <div>
            <x-card title="Data Umum">
                <table class="w-full font-semibold">
                    <tr>
                        <td>Nama Vendor</td>
                        <td>{{ $transaksi->first()->vendorStok->nama }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Transaksi</td>
                        <td>{{ $transaksi->first()->merkStok->barangStok->jenisStok->nama }}</td>
                    </tr>
                    {{-- <tr>
                        <td>Nomor Kontrak</td>
                        <td>{{ $transaksi->first()->nomor_kontrak ?? '---' }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Kontrak</td>
                        <td>{{ $transaksi->first()->tanggal_kontrak ? date('j F Y', $transaksi->first()->tanggal_kontrak) : '---' }}
                        </td>
                    </tr>
                    <tr>
                        <td>Penulis</td>
                        <td>{{ $transaksi->first()->user->name }}</td>
                    </tr> --}}

                </table>
            </x-card>
        </div>
        <div>
            @if ($transaksi->first()->kontrak_id !== null)
                <x-card title="Dokumen kontrak">
                    @foreach ($transaksi->first()->kontrakStok->dokumen as $attachment)
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
            @endif
        </div>
    </div>
    <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold  rounded-l-lg">BARANG</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">MERK</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/12">JUMLAH</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold ">TANGGAL PENGIRIMAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/5 ">KETERANGAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold  w-1/5">LOKASI PENERIMAAN
                </th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/5  rounded-r-lg">BUKTI</th>
                {{-- <th class="py-3 px-6 bg-primary-950 text-center font-semibold "></th> --}}
            </tr>
        </thead>
        <tbody class="">
            @foreach ($transaksi as $transaksi)
                <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                    <td class="py-3 px-6 font-semibold">
                        {{ $transaksi->merkStok->barangStok->nama }}
                    </td>
                    <td class="py-3 px-6 font-semibold">
                        {{ $transaksi->merkStok->nama }}
                    </td>
                    <td class="py-3 px-6 font-semibold">
                        {{ $transaksi->jumlah }}
                        {{ $transaksi->merkStok->barangStok->satuanBesar->nama }}
                    </td>
                    <td class="py-3 px-6 font-semibold">
                        {{ date('j F Y', $transaksi->tanggal) }}
                    </td>
                    <td class="py-3 px-6 font-semibold">
                        {{ $transaksi->deskripsi }}
                    </td>
                    <td class="py-3 px-6 font-semibold">
                        {{ $transaksi->lokasi_penerimaan }}
                    </td>
                    <td class="py-3 px-6 font-semibold text-center">
                        @if ($transaksi->img)
                            <a href="{{ asset('storage/buktiTransaksi/' . $transaksi->img) }}" target="_blank"
                                download="{{ basename($transaksi->img) }}">
                                <img src="{{ asset('storage/buktiTransaksi/' . $transaksi->img) }}" alt="Bukti"
                                    class="w-16 h-16 rounded-md">
                            </a>
                        @else
                            <span class="text-gray-500">Belum ada unggahan</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-body>
