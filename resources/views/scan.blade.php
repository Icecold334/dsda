<x-body>
    <div class="max-w-12xl mx-auto bg-white p-6 rounded-md shadow-md">
        <!-- Title -->
        <h1 class="text-xl font-semibold text-primary-600 mb-6 text-center">Dinas Sumber Daya Air (DSDA)</h1>

        <!-- Foto Section -->
        <h2 class="text-md text-center font-semibold text-primary-600 mb-4 bg-[#d9faff] p-4 rounded-lg">
            FOTO
        </h2>
        <div class="flex justify-center">
            @if ($aset->foto)
                <img src="{{ asset('storage/' . $aset->foto) }}" alt="{{ $aset->nama }}"
                    class="max-w-sm rounded-md shadow-md">
            @else
                <p class="text-gray-500">Foto tidak tersedia</p>
            @endif
        </div>

        <!-- Aset Info Section -->
        <h2 class="text-md text-center font-semibold text-primary-600 mb-4 bg-[#d9faff] p-4 rounded-lg">
            ASET
        </h2>
        <table class="text-sm text-left text-gray-500">
            <tbody>
                @if ($user->can('nama'))
                    <tr>
                        <td class="px-4 py-2 font-bold text-gray-700">Nama Aset</td>
                        <td class="px-4 py-2 font-bold text-gray-900">{{ $aset->nama }}</td>
                    </tr>
                @endif
                @if ($user->can('kode'))
                    <tr>
                        <td class="px-4 py-2 font-medium text-gray-700">Kode Aset</td>
                        <td class="px-4 py-2 text-gray-900">{{ $aset->kode }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Kode Sistem</td>
                    <td class="px-4 py-2 text-gray-900">{{ $aset->systemcode }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Kategori</td>
                    <td class="px-4 py-2 text-gray-900">{{ $aset->kategori->nama }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Status</td>
                    <td class="px-4 py-2 text-gray-900">{{ $aset->status == 1 ? 'Aktif' : 'Non Aktif' }}</td>
                </tr>
                @if ($aset->status != 1)
                    <tr>
                        <td class="px-4 py-2 font-medium text-gray-700">Tanggal Non Aktif</td>
                        <td class="px-4 py-2 text-gray-900">{{ date('d-m-Y', $aset->tglnonaktif) ?? 'Tidak Tersedia' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-medium text-gray-700">Sebab</td>
                        <td class="px-4 py-2 text-gray-900">{{ $aset->alasannonaktif ?? 'Tidak Tersedia' }}</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2 font-medium text-gray-700">Keterangan Non Aktif</td>
                        <td class="px-4 py-2 text-gray-900">{{ $aset->ketnonaktif ?? 'Tidak Tersedia' }}</td>
                    </tr>
                @endif
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Keterangan</td>
                    <td class="px-4 py-2 text-gray-900"> {{ $aset->deskripsi ?? '---' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- DETAIL ASET -->
        <h2 class="text-md text-center font-semibold text-primary-600 mb-4 bg-[#d9faff] p-4 rounded-lg">
            DETAIL ASET
        </h2>
        <table class="text-sm text-left text-gray-500">
            <tbody>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Merk</td>
                    <td class="px-4 py-2 text-gray-900">{{ $aset->merk->nama }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Tipe</td>
                    <td class="px-4 py-2 text-gray-900">{{ $aset->tipe ?? '---' }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Produsen</td>
                    <td class="px-4 py-2 text-gray-900">{{ $aset->produsen ?? '---' }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Kode Produsen</td>
                    <td class="px-4 py-2 text-gray-900">{{ $aset->noseri ?? '---' }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Tahun Produksi</td>
                    <td class="px-4 py-2 text-gray-900">{{ $aset->thnproduksi ?? '---' }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Keterangan</td>
                    <td class="px-4 py-2 text-gray-900">{{ $aset->deskripsi ?? '---' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- PEMBELIAN -->
        <h2 class="text-md text-center font-semibold text-primary-600 mb-4 bg-[#d9faff] p-4 rounded-md">
            PEMBELIAN
        </h2>
        <table class="text-sm text-left text-gray-500">
            <tbody>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Tanggal </td>
                    <td class="px-4 py-2 text-gray-900">{{ date('d M Y', $aset->tanggalbeli) }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Distributor</td>
                    <td class="px-4 py-2 text-gray-900">{{ $aset->toko->nama }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">No. Invoice</td>
                    <td class="px-4 py-2 text-gray-900">{{ $aset->invoice ?? '---' }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Jumlah</td>
                    <td class="px-4 py-2 text-gray-900">{{ $aset->jumlah }} Unit</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Harga Satuan</td>
                    <td class="px-4 py-2 text-gray-900">{{ rupiah($aset->hargasatuan) }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Harga Total</td>
                    <td class="px-4 py-2 text-gray-900">{{ rupiah($aset->hargatotal) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- LAMPIRAN -->
        <h2 class="text-md text-center font-semibold text-primary-600 mb-4 bg-[#d9faff] p-4 rounded-md">
            LAMPIRAN
        </h2>
        <table class="text-sm text-left text-gray-500">
            <tbody>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">---</td>
                </tr>
            </tbody>
        </table>

        <!-- UMUR & PENYUSUTAN -->
        <h2 class="text-md text-center font-semibold text-primary-600 mb-4 bg-[#d9faff] p-4 rounded-md">
            UMUR & PENYUSUTAN
        </h2>
        <table class="text-sm text-left text-gray-500">
            <tbody>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Umur Ekonomi </td>
                    <td class="px-4 py-2 text-gray-900">{{ $aset->umur * 1 }} Tahun</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Penyusutan</td>
                    <td class="px-4 py-2 text-gray-900">{{ rupiah($aset->penyusutan) }} /bulan</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Usia Aset</td>
                    <td class="px-4 py-2 text-gray-900">{{ usia_aset($aset->tanggalbeli) }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Jumlah</td>
                    <td class="px-4 py-2 text-gray-900">
                        {{ rupiah(nilaisekarang($aset->hargatotal, $aset->tanggalbeli, $aset->umur)) }}</td>
                </tr>
            </tbody>
        </table>

        <h2 class="text-md text-center font-semibold text-primary-600 mb-4 bg-[#d9faff] p-4 rounded-md">
            RIWAYAT TERAKHIR
        </h2>
        <table class="text-sm text-left text-gray-500">
            <tbody>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Sejak Tanggal </td>
                    <td class="px-4 py-2 text-gray-900">{{ date('d M Y', $aset->histories->last()->tanggal) }}
                    </td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Penanggung Jawab</td>
                    <td class="px-4 py-2 text-gray-900">{{ $aset->histories->last()->person->nama }} /bulan</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Lokasi</td>
                    <td class="px-4 py-2 text-gray-900">{{ $aset->histories->last()->lokasi->nama }}</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Jumlah</td>
                    <td class="px-4 py-2 text-gray-900">
                        {{ $aset->histories->last()->jumlah * 1 }} Unit</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Kondisi</td>
                    <td class="px-4 py-2 text-gray-900">{{ $aset->histories->last()->kondisi }} %</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Kelengkapan</td>
                    <td class="px-4 py-2 text-gray-900">{{ $aset->histories->last()->kelengkapan }} %</td>
                </tr>
                <tr>
                    <td class="px-4 py-2 font-medium text-gray-700">Keterangan</td>
                    <td class="px-4 py-2 text-gray-900">{{ $aset->histories->last()->keterangan }}</td>
                </tr>
            </tbody>
        </table>

        <!-- RIWAYAT -->
        @if ($aset->histories && $aset->histories->count() > 0)
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-primary-600 mb-2">Riwayat</h2>
                <table class="table-auto w-full text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">Tanggal</th>
                            <th class="px-4 py-2">Penanggung Jawab</th>
                            <th class="px-4 py-2">Lokasi</th>
                            <th class="px-4 py-2">Jumlah</th>
                            <th class="px-4 py-2">Kondisi</th>
                            <th class="px-4 py-2">Kelengkapan</th>
                            <th class="px-4 py-2">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($aset->histories as $history)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ date('d M Y', $history->tanggal) }}</td>
                                <td class="px-4 py-2">{{ $history->person->nama }}</td>
                                <td class="px-4 py-2">{{ $history->lokasi->nama }}</td>
                                <td class="px-4 py-2">{{ $history->jumlah }}</td>
                                <td class="px-4 py-2">{{ $history->kondisi }}%</td>
                                <td class="px-4 py-2">{{ $history->kelengkapan }}%</td>
                                <td class="px-4 py-2">{{ $history->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">Tidak ada riwayat tersedia.</p>
        @endif

        <!-- KEUANGAN -->
        @if ($aset->keuangan && $aset->keuangan->count() > 0)
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-primary-600 mb-2">Keuangan</h2>
                <table class="table-auto w-full text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">Tanggal</th>
                            <th class="px-4 py-2">Jenis</th>
                            <th class="px-4 py-2">Nominal</th>
                            <th class="px-4 py-2">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalPemasukan = 0;
                            $totalPengeluaran = 0;
                        @endphp
                        @foreach ($aset->keuangan as $finance)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ date('d M Y', strtotime($finance->tanggal)) }}</td>
                                <td class="px-4 py-2">{{ $finance->tipe == 'in' ? 'Pemasukan' : 'Pengeluaran' }}</td>
                                <td class="px-4 py-2">{{ rupiah($finance->nominal) }}</td>
                                <td class="px-4 py-2">{{ $finance->keterangan }}</td>
                            </tr>
                            @php
                                if ($finance->tipe == 'in') {
                                    $totalPemasukan += $finance->nominal;
                                } else {
                                    $totalPengeluaran += $finance->nominal;
                                }
                            @endphp
                        @endforeach
                        <tr class="font-bold bg-gray-200">
                            <td colspan="2" class="px-4 py-2">Total</td>
                            <td class="px-4 py-2">
                                Pemasukan: {{ rupiah($totalPemasukan) }}<br>
                                Pengeluaran: {{ rupiah($totalPengeluaran) }}<br>
                                Selisih: {{ rupiah($totalPemasukan - $totalPengeluaran) }}
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">Tidak ada data keuangan tersedia.</p>
        @endif

        <!-- JURNAL -->
        @if ($aset->jurnal && $aset->jurnal->count() > 0)
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-primary-600 mb-2">Jurnal</h2>
                <table class="table-auto w-full text-left">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2">Tanggal</th>
                            <th class="px-4 py-2">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($aset->jurnal as $entry)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ date('d M Y', strtotime($entry->tanggal)) }}</td>
                                <td class="px-4 py-2">{{ $entry->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">Tidak ada data jurnal tersedia.</p>
        @endif

    </div>
</x-body>
