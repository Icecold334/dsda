<x-body>
    <div class="max-w-12xl mx-auto bg-white p-6 rounded-md shadow-md">
        <!-- Title -->
        <h1 class="text-xl font-semibold text-primary-600 mb-6 text-center">Dinas Sumber Daya Air (DSDA)</h1>

        <!-- Foto Section -->
        @if ($user->can('foto'))
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
        @endif

        <!-- Aset Info Section -->
        @if (
            $user->can('nama') ||
                $user->can('kode') ||
                $user->can('systemcode') ||
                $user->can('kategori') ||
                $user->can('status') ||
                $user->can('aset_keterangan') ||
                $user->can('nonaktif_tanggal') ||
                $user->can('nonaktif_alasan') ||
                $user->can('nonaktif_keterangan'))
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
                    @if ($user->can('systemcode'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Kode Sistem</td>
                            <td class="px-4 py-2 text-gray-900">{{ $aset->systemcode }}</td>
                        </tr>
                    @endif
                    @if ($user->can('kategori'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Kategori</td>
                            <td class="px-4 py-2 text-gray-900">{{ $aset->kategori->nama }}</td>
                        </tr>
                    @endif
                    @if ($user->can('status'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Status</td>
                            <td class="px-4 py-2 text-gray-900">{{ $aset->status == 1 ? 'Aktif' : 'Non Aktif' }}</td>
                        </tr>
                        @if ($aset->status != 1)
                            @if ($user->can('nonaktif_tanggal'))
                                <tr>
                                    <td class="px-4 py-2 font-medium text-gray-700">Tanggal Non Aktif</td>
                                    <td class="px-4 py-2 text-gray-900">
                                        {{ date('d-m-Y', $aset->tglnonaktif) ?? 'Tidak Tersedia' }}
                                    </td>
                                </tr>
                            @endif
                            @if ($user->can('nonaktif_alasan'))
                                <tr>
                                    <td class="px-4 py-2 font-medium text-gray-700">Sebab</td>
                                    <td class="px-4 py-2 text-gray-900">{{ $aset->alasannonaktif ?? 'Tidak Tersedia' }}
                                    </td>
                                </tr>
                            @endif
                            @if ($user->can('nonaktif_keterangan'))
                                <tr>
                                    <td class="px-4 py-2 font-medium text-gray-700">Keterangan Non Aktif</td>
                                    <td class="px-4 py-2 text-gray-900">{{ $aset->ketnonaktif ?? 'Tidak Tersedia' }}
                                    </td>
                                </tr>
                            @endif

                        @endif
                    @endif
                    @if ($user->can('aset_keterangan'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Keterangan</td>
                            <td class="px-4 py-2 text-gray-900"> {{ $aset->keterangan ?? '---' }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endif

        <!-- DETAIL ASET -->
        @if (
            $user->can('detil_merk') ||
                $user->can('detil_tipe') ||
                $user->can('detil_produsen') ||
                $user->can('detil_noseri') ||
                $user->can('detil_thnproduksi') ||
                $user->can('detil_deskripsi'))
            <h2 class="text-md text-center font-semibold text-primary-600 mb-4 bg-[#d9faff] p-4 rounded-lg">
                DETAIL ASET
            </h2>
            <table class="text-sm text-left text-gray-500">
                <tbody>
                    @if ($user->can('detil_merk'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Merk</td>
                            <td class="px-4 py-2 text-gray-900">{{ $aset->merk->nama }}</td>
                        </tr>
                    @endif
                    @if ($user->can('detil_tipe'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Tipe</td>
                            <td class="px-4 py-2 text-gray-900">{{ $aset->tipe ?? '---' }}</td>
                        </tr>
                    @endif
                    @if ($user->can('detil_produsen'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Produsen</td>
                            <td class="px-4 py-2 text-gray-900">{{ $aset->produsen ?? '---' }}</td>
                        </tr>
                    @endif
                    @if ($user->can('detil_noseri'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Kode Produsen</td>
                            <td class="px-4 py-2 text-gray-900">{{ $aset->noseri ?? '---' }}</td>
                        </tr>
                    @endif
                    @if ($user->can('detil_thnproduksi'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Tahun Produksi</td>
                            <td class="px-4 py-2 text-gray-900">{{ $aset->thnproduksi ?? '---' }}</td>
                        </tr>
                    @endif
                    @if ($user->can('detil_deskripsi'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Keterangan</td>
                            <td class="px-4 py-2 text-gray-900">{{ $aset->deskripsi ?? '---' }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endif

        <!-- PEMBELIAN -->
        @if (
            $user->can('tanggalbeli') ||
                $user->can('toko') ||
                $user->can('invoice') ||
                $user->can('jumlah') ||
                $user->can('hargasatuan') ||
                $user->can('hargatotal'))
            <h2 class="text-md text-center font-semibold text-primary-600 mb-4 bg-[#d9faff] p-4 rounded-md">
                PEMBELIAN
            </h2>
            <table class="text-sm text-left text-gray-500">
                <tbody>
                    @if ($user->can('tanggalbeli'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Tanggal </td>
                            <td class="px-4 py-2 text-gray-900">{{ date('d M Y', $aset->tanggalbeli) }}</td>
                        </tr>
                    @endif
                    @if ($user->can('toko'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Distributor</td>
                            <td class="px-4 py-2 text-gray-900">{{ $aset->toko->nama }}</td>
                        </tr>
                    @endif
                    @if ($user->can('invoice'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">No. Invoice</td>
                            <td class="px-4 py-2 text-gray-900">{{ $aset->invoice ?? '---' }}</td>
                        </tr>
                    @endif
                    @if ($user->can('jumlah'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Jumlah</td>
                            <td class="px-4 py-2 text-gray-900">{{ $aset->jumlah }} Unit</td>
                        </tr>
                    @endif
                    @if ($user->can('hargasatuan'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Harga Satuan</td>
                            <td class="px-4 py-2 text-gray-900">{{ rupiah($aset->hargasatuan) }}</td>
                        </tr>
                    @endif
                    @if ($user->can('hargatotal'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Harga Total</td>
                            <td class="px-4 py-2 text-gray-900">{{ rupiah($aset->hargatotal) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endif

        <!-- LAMPIRAN -->

        @if ($user->can('lampiran'))
            <h2 class="text-md text-center font-semibold text-primary-600 mb-4 bg-[#d9faff] p-4 rounded-md">
                LAMPIRAN
            </h2>
            <div class="flex justify-center">
                @if ($aset->lampirans)
                    {{-- <img src="{{ asset('storage/' . $aset->lampirans) }}"
                        alt="{{ $aset->lampirans }}" class="max-w-sm rounded-md shadow-md"> --}}
                    <p class="text-gray-500">---</p>
                @else
                    <p class="text-gray-500">---</p>
                @endif
            </div>
        @endif

        <!-- UMUR & PENYUSUTAN -->
        @if ($user->can('umur') || $user->can('penyusutan') || $user->can('usia') || $user->can('nilaisekarang'))
            <h2 class="text-md text-center font-semibold text-primary-600 mb-4 bg-[#d9faff] p-4 rounded-md">
                UMUR & PENYUSUTAN
            </h2>
            <table class="text-sm text-left text-gray-500">
                <tbody>
                    @if ($user->can('umur'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Umur Ekonomi </td>
                            <td class="px-4 py-2 text-gray-900">{{ $aset->umur * 1 }} Tahun</td>
                        </tr>
                    @endif
                    @if ($user->can('penyusutan'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Penyusutan</td>
                            <td class="px-4 py-2 text-gray-900">{{ rupiah($aset->penyusutan) }} /bulan</td>
                        </tr>
                    @endif
                    @if ($user->can('usia'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Usia Aset</td>
                            <td class="px-4 py-2 text-gray-900">{{ usia_aset($aset->tanggalbeli) }}</td>
                        </tr>
                    @endif
                    @if ($user->can('nilaisekarang'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Jumlah</td>
                            <td class="px-4 py-2 text-gray-900">
                                {{ rupiah(nilaisekarang($aset->hargatotal, $aset->tanggalbeli, $aset->umur)) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endif

        @if ($user->can('riwayat_terakhir'))
            <!-- RIWAYAT Terakhir -->
            <h2 class="text-md text-center font-semibold text-primary-600 mb-4 bg-[#d9faff] p-4 rounded-md">
                RIWAYAT TERAKHIR
            </h2>
            <table class="text-sm text-left text-gray-500">
                <tbody>
                    @if ($user->can('riwayat_tanggal'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Sejak Tanggal </td>
                            <td class="px-4 py-2 text-gray-900">{{ date('d M Y', $aset->histories->last()->tanggal) }}
                            </td>
                        </tr>
                    @endif
                    @if ($user->can('riwayat_person'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Penanggung Jawab</td>
                            <td class="px-4 py-2 text-gray-900">{{ $aset->histories->last()->person->nama }} /bulan
                            </td>
                        </tr>
                    @endif
                    @if ($user->can('riwayat_lokasi'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Lokasi</td>
                            <td class="px-4 py-2 text-gray-900">{{ $aset->histories->last()->lokasi->nama }}</td>
                        </tr>
                    @endif
                    @if ($user->can('riwayat_jumlah'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Jumlah</td>
                            <td class="px-4 py-2 text-gray-900">
                                {{ $aset->histories->last()->jumlah * 1 }} Unit</td>
                        </tr>
                    @endif
                    @if ($user->can('riwayat_kondisi'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Kondisi</td>
                            <td class="px-4 py-2 text-gray-900">{{ $aset->histories->last()->kondisi }} %</td>
                        </tr>
                    @endif
                    @if ($user->can('riwayat_kelengkapan'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Kelengkapan</td>
                            <td class="px-4 py-2 text-gray-900">{{ $aset->histories->last()->kelengkapan }} %</td>
                        </tr>
                    @endif
                    @if ($user->can('riwayat_keterangan'))
                        <tr>
                            <td class="px-4 py-2 font-medium text-gray-700">Keterangan</td>
                            <td class="px-4 py-2 text-gray-900">{{ $aset->histories->last()->keterangan }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        @endif

        @if ($user->can('riwayat_semua'))
            <!-- RIWAYAT -->
            @if ($aset->histories && $aset->histories->count() > 0)
                <div class="mb-6">
                    <h2 class="text-md text-center font-semibold text-primary-600 mb-4 bg-[#d9faff] p-4 rounded-md">
                        RIWAYAT
                    </h2>

                    <div class="space-y-6">
                        @foreach ($aset->histories as $history)
                            <div class="p-4 bg-gray-50 rounded-md shadow-md">
                                <div class="w-full">
                                    <table class="w-full text-sm border-collapse border-spacing-2">
                                        <tbody>
                                            @if ($user->can('riwayat_tanggal'))
                                                <tr>
                                                    <td class="font-semibold w-40">Sejak Tanggal</td>
                                                    <td>{{ date('d M Y', $history->tanggal) }}</td>
                                                </tr>
                                            @endif
                                            @if ($user->can('riwayat_person'))
                                                <tr>
                                                    <td class="font-semibold w-40">Penanggung Jawab</td>
                                                    <td>{{ $history->person->nama }}</td>
                                                </tr>
                                            @endif
                                            @if ($user->can('riwayat_lokasi'))
                                                <tr>
                                                    <td class="font-semibold w-40">Lokasi</td>
                                                    <td>{{ $history->lokasi->nama }}</td>
                                                </tr>
                                            @endif
                                            @if ($user->can('riwayat_jumlah'))
                                                <tr>
                                                    <td class="font-semibold w-40">Jumlah</td>
                                                    <td>{{ $history->jumlah * 1 }} Unit</td>
                                                </tr>
                                            @endif
                                            @if ($user->can('riwayat_kondisi'))
                                                <tr>
                                                    <td class="font-semibold w-40">Kondisi</td>
                                                    <td>{{ $history->kondisi }}%</td>
                                                </tr>
                                            @endif
                                            @if ($user->can('riwayat_kelengkapan'))
                                                <tr>
                                                    <td class="font-semibold w-40">Kelengkapan</td>
                                                    <td>{{ $history->kelengkapan }}%</td>
                                                </tr>
                                            @endif
                                            @if ($user->can('riwayat_keterangan'))
                                                <tr>
                                                    <td class="font-semibold w-40">Keterangan</td>
                                                    <td>{{ $history->keterangan }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-gray-500">Tidak ada riwayat tersedia.</p>
            @endif
        @endif

        @if ($user->can('keuangan'))
            <!-- KEUANGAN -->
            @if ($aset->keuangans && $aset->keuangans->count() > 0)
                <div class="mb-6">
                    <h2 class="text-md text-center font-semibold text-primary-600 mb-4 bg-[#d9faff] p-4 rounded-md">
                        KEUANGAN
                    </h2>
                    <table class="table-auto w-full text-left">
                        <tbody>
                            @php
                                $totalPemasukan = 0;
                                $totalPengeluaran = 0;
                            @endphp
                            @foreach ($aset->keuangans as $finance)
                                <tr class="border-t">
                                    <td
                                        class="flex items-center justify-center w-8 h-8 rounded-full 
                        {{ $finance->tipe === 'out' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                        {!! $finance->tipe === 'out'
                                            ? '<i class="fa-solid fa-arrow-right-from-bracket"></i>'
                                            : '<i class="fa-solid fa-arrow-right-to-bracket"></i>' !!}
                                    </td>
                                    <td class="px-4 py-2">{{ date('d M Y', $finance->tanggal) }}</td>
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
                            <tr class="font-bold">
                                <td colspan="2" class="px-4 py-2">Total</td>
                                <td class="px-4 py-2">
                                    Pengeluaran: {{ rupiah($totalPengeluaran) }}<br>
                                    Pemasukan: {{ rupiah($totalPemasukan) }}<br>
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
        @endif

        @if ($user->can('agenda'))
            <!-- Agenda -->
            @if ($aset->agendas && $aset->agendas->count() > 0)
                <div class="mb-6">
                    <h2 class="text-md text-center font-semibold text-primary-600 mb-4 bg-[#d9faff] p-4 rounded-md">
                        AGENDA
                    </h2>
                    <table class="table-auto w-full text-left">
                        @php
                            $dayMap = [
                                1 => 'Senin',
                                2 => 'Selasa',
                                3 => 'Rabu',
                                4 => 'Kamis',
                                5 => 'Jumat',
                                6 => 'Sabtu',
                                7 => 'Minggu',
                            ];
                        @endphp
                        <tbody>
                            @foreach ($aset->agendas as $agenda)
                                <div class="p-4 bg-gray-50 rounded-md shadow-md mb-4">
                                    @if ($agenda->tipe === 'mingguan')
                                        <div class="text-sm font-semibold text-gray-500">Mingguan</div>
                                        <div class="text-md font-bold text-gray-700">
                                            Setiap Hari {{ $dayMap[$agenda->hari] ?? 'Tidak Diketahui' }}
                                        </div>
                                    @elseif ($agenda->tipe === 'bulanan')
                                        <div class="text-sm font-semibold text-gray-500">Bulanan</div>
                                        <div class="text-md font-bold text-gray-700">
                                            Setiap Tanggal {{ $agenda->hari }}
                                        </div>
                                    @elseif ($agenda->tipe === 'tahunan')
                                        <div class="text-sm font-semibold text-gray-500">Tahunan</div>
                                        <div class="text-md font-bold text-gray-700">
                                            Setiap {{ date('j F', $agenda->tanggal) }}
                                        </div>
                                    @elseif ($agenda->tipe === 'tanggal_tertentu')
                                        <div class="text-sm font-semibold text-gray-500">Tanggal</div>
                                        <div class="text-md font-bold text-gray-700">
                                            {{ date('j F Y', $agenda->tanggal) }}
                                        </div>
                                    @else
                                        <div class="text-sm font-semibold text-gray-500">Tipe Tidak Diketahui</div>
                                    @endif
                                    <div class="text-sm text-gray-600">{{ $agenda->keterangan }}</div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">Tidak ada data jurnal tersedia.</p>
            @endif
        @endif

        @if ($user->can('jurnal'))
            <!-- JURNAL -->
            @if ($aset->jurnals && $aset->jurnals->count() > 0)
                <div class="mb-6">
                    <h2 class="text-md text-center font-semibold text-primary-600 mb-4 bg-[#d9faff] p-4 rounded-md">
                        JURNAL
                    </h2>
                    <table class="table-auto w-full text-left">
                        <tbody>
                            @foreach ($aset->jurnals as $entry)
                                <div class="p-4 bg-gray-50 rounded-md shadow-md mb-4">
                                    <div class="text-md font-bold text-gray-700">
                                        {{ date('j F Y', (int) $entry->tanggal) }}
                                    </div>
                                    <div class="text-sm text-gray-600">{{ $entry->keterangan }}</div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500">Tidak ada data jurnal tersedia.</p>
            @endif

        @endif

    </div>
</x-body>
