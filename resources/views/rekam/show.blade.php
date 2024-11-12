<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">DETAIL KONTRAK</h1>
        <div>
            <a href="{{ route('kontrak-vendor-stok.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <x-card title="Data Umum">
                <table class="w-full font-semibold">
                    <tr>
                        <td>Nama Vendor</td>
                        <td>{{ $kontrak->vendorStok->nama }}</td>
                    </tr>
                    <tr>
                        <td>Nomor Kontrak</td>
                        <td>{{ $kontrak->nomor_kontrak }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Barang</td>
                        <td>{{ $kontrak->transaksiStok->first()->merkStok->barangStok->jenisStok->nama }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Kontrak</td>
                        <td>{{ date('j F Y', $kontrak->tanggal_kontrak) }}</td>
                    </tr>
                    {{-- <tr>
                        <td>Penulis</td>
                        <td>{{ $kontrak->user->name }}</td>
                    </tr> --}}

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

                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-2/5 rounded-l-lg">BARANG</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">MERK</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">JUMLAH</th>
                @if ($kontrak->type)
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">SISA</th>
                @endif
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody class="">
            @foreach ($kontrak->transaksiStok as $transaksi)
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
                    @if ($kontrak->type)
                        <td class="text-center py-3 font-semibold ">
                            {{ max(0, $transaksi->jumlah - $kontrak->pengirimanStok->where('merk_id', $transaksi->merk_id)->sum('jumlah')) }}

                            {{ $transaksi->merkStok->barangStok->satuanBesar->nama }}

                        </td>
                    @endif

                    <td class="text-center py-3 ">
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="flex w-full justify-evenly">
        <!-- Penulis -->
        <div class="flex items-center space-x-2">
            <label for="penulis" class="block text-sm font-medium text-gray-900">Penulis</label>
            <input type="text" id="penulis" value="{{ $kontrak->penulis }}" disabled
                class="border-gray-300 rounded-lg p-2.5 focus:ring-primary-500 focus:border-primary-500 w-full" />
            <button type="button" class="bg-gray-200 rounded-full p-2">
                <i class="fa-solid fa-check text-primary-600"></i>
            </button>
        </div>

        <!-- PJ1 -->
        <div class="flex items-center space-x-2">
            <label for="pj1" class="block text-sm font-medium text-gray-900">Persetujuan 1</label>
            <input type="text" id="pj1" value="{{ $kontrak->pj1 }}" disabled
                class="border-gray-300 rounded-lg p-2.5 focus:ring-primary-500 focus:border-primary-500 w-full" />
            <button type="button" class="bg-gray-200 rounded-full p-2">
                <i class="fa-solid fa-check text-primary-600"></i>
            </button>
        </div>

        <!-- PJ2 -->
        <div class="flex items-center space-x-2">
            <label for="pj2" class="block text-sm font-medium text-gray-900">Persetujuan 2</label>
            <input type="text" id="pj2" value="{{ $kontrak->pj2 }}" disabled
                class="border-gray-300 rounded-lg p-2.5 focus:ring-primary-500 focus:border-primary-500 w-full" />
            <button type="button" class="bg-gray-200 rounded-full p-2">
                <i class="fa-solid fa-check text-primary-600"></i>
            </button>
        </div>
    </div>
</x-body>
