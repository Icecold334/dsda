<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>INVENTA Manajemen Aset</title>
    <script type="importmap">
    {
        "imports": {
            "https://esm.sh/v135/prosemirror-model@1.22.3/es2022/prosemirror-model.mjs": "https://esm.sh/v135/prosemirror-model@1.19.3/es2022/prosemirror-model.mjs", 
            "https://esm.sh/v135/prosemirror-model@1.22.1/es2022/prosemirror-model.mjs": "https://esm.sh/v135/prosemirror-model@1.19.3/es2022/prosemirror-model.mjs"
        }
    }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://kit.fontawesome.com/5fd2369345.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/2.0.6/css/dataTables.bootstrap5.css"> --}}
    <link rel="stylesheet"
        href="https://gistcdn.githack.com/mfd/09b70eb47474836f25a21660282ce0fd/raw/e06a670afcb2b861ed2ac4a1ef752d062ef6b46b/Gilroy.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-md shadow-md">
        <!-- Title Section using Flexbox -->
        <p class="text-sm font-semibold text-gray-500">Dinas Sumber Daya Air (DSDA)</p>
        <h2 class="text-lg font-semibold text-gray-500">{{ $aset->nama }}</h2>


        <!-- ASET Section with Flexbox layout -->
        {{-- @if ($aset->systemcode)
            <img src="{{ asset('storage/qr/' . $aset->systemcode . '.png') }}" alt="QR Code"
                class="w-20 h-20 rounded-md shadow-lg">
        @else
            <p class="text-gray-500">QR tidak tersedia</p>
        @endif --}}
        <table class="w-full text-sm text-gray-600">
            <tbody>
                <tr>
                    <td class="py-2 font-bold">Kode Aset</td>
                    <td class="py-2" colspan="3">{{ $aset->kode }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-bold">Kode Sistem</td>
                    <td class="py-2" colspan="3">{{ $aset->systemcode }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-bold">Kategori</td>
                    <td class="py-2" colspan="3">{{ $aset->kategori->nama }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-bold">Status</td>
                    <td class="py-2" colspan="3">{{ $aset->status == 1 ? 'Aktif' : 'Non Aktif' }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-bold">Keterangan</td>
                </tr>
                <tr>
                    <td class="py-2">{{ $aset->keterangan ?? '---' }}</td>
                </tr>
            </tbody>
        </table>
        <!-- DETAIL ASET Section -->
        <h2 class="text-lg font-semibold text-blue-600 bg-blue-100 p-2 rounded-md mb-4">DETAIL ASET</h2>
        <table class="w-full text-sm text-gray-600">
            <tbody>
                <tr>
                    <td class="py-2 font-bold">Merk</td>
                    <td class="py-2" colspan="3">{{ $aset->merk->nama }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-bold">Tipe</td>
                    <td class="py-2" colspan="3">{{ $aset->tipe ?? '---' }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-bold">Produsen</td>
                    <td class="py-2" colspan="3">{{ $aset->produsen ?? '---' }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-bold">Kode Produsen</td>
                    <td class="py-2" colspan="3">{{ $aset->noseri ?? '---' }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-bold">Tahun Produksi</td>
                    <td class="py-2" colspan="3">{{ $aset->thnproduksi ?? '---' }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-bold">Deskripsi</td>
                </tr>
                <tr>
                    <td class="py-2">{{ $aset->deskripsi ?? '---' }}</td>
                </tr>
            </tbody>
        </table>

        <!-- PEMBELIAN Section -->
        <h2 class="text-lg font-semibold text-blue-600 bg-blue-100 p-2 rounded-md mb-4">PEMBELIAN</h2>
        <table class="w-full text-sm text-gray-600">
            <tbody>
                <tr>
                    <td class="py-2 font-bold">Tanggal</td>
                    <td class="py-2" colspan="3">{{ date('d M Y', $aset->tanggalbeli) }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-bold">Distributor</td>
                    <td class="py-2" colspan="3">{{ $aset->toko->nama }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-bold">No. Invoice</td>
                    <td class="py-2" colspan="3">{{ $aset->invoice ?? '---' }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-bold">Jumlah</td>
                    <td class="py-2" colspan="3">{{ $aset->jumlah }} Unit</td>
                </tr>
                <tr>
                    <td class="py-2 font-bold">Harga Satuan</td>
                    <td class="py-2" colspan="3">{{ rupiah($aset->hargasatuan) }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-bold">Harga Total</td>
                    <td class="py-2" colspan="3">{{ rupiah($aset->hargatotal) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- LAMPIRAN -->
        <h2 class="text-lg font-semibold text-blue-600 bg-blue-100 rounded-md">LAMPIRAN</h2>
        @if ($aset->lampirans->isNotEmpty())
            @foreach ($aset->lampirans as $attachment)
                @php
                    $fileType = pathinfo($attachment->file, PATHINFO_EXTENSION);
                @endphp
                <div class="flex items-left">
                    <a href="{{ asset('storage/LampiranAset/' . $attachment->file) }}" target="_blank"
                        class="text-blue-600 hover:underline">
                        {{ basename($attachment->file) }}
                    </a>
                </div>
            @endforeach
        @else
            <p class="text-gray-500">Tidak ada lampiran tersedia.</p>
        @endif


        <!-- UMUR & PENYUSUTAN -->
        <h2 class="text-lg font-semibold text-blue-600 bg-blue-100 p-2 rounded-md mb-4">UMUR & PENYUSUTAN</h2>
        <table class="w-full text-sm text-gray-600">
            <tbody>
                <tr>
                    <td class="py-2 font-bold">Umur Ekonomi</td>
                    <td class="py-2" colspan="3">{{ $aset->umur }} Tahun</td>
                </tr>
                <tr>
                    <td class="py-2 font-bold">Penyusutan</td>
                    <td class="py-2" colspan="3">{{ rupiah($aset->penyusutan) }} / bulan</td>
                </tr>
                <tr>
                    <td class="py-2 font-bold">Usia Aset</td>
                    <td class="py-2" colspan="3">{{ usia_aset($aset->tanggalbeli) }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-bold">Nilai Sekarang</td>
                    <td class="py-2">
                        {{ rupiah(nilaisekarang($aset->hargatotal, $aset->tanggalbeli, $aset->umur)) }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- RIWAYAT -->
        <h2 class="text-lg font-semibold text-blue-600 bg-blue-100 p-2 rounded-md mb-4">RIWAYAT</h2>
        @if ($aset->histories && $aset->histories->count() > 0)
            <table class="w-full text-sm border-collapse border-spacing-2">
                <tbody>
                    @foreach ($aset->histories as $history)
                        <tr>
                            <td class="font-semibold w-40">Sejak Tanggal</td>
                            <td colspan="3">{{ date('d M Y', $history->tanggal) }}</td>
                        </tr>
                        <tr>
                            <td class="font-semibold w-40">Penanggung Jawab</td>
                            <td>{{ $history->person->nama }}</td>
                        </tr>
                        <tr>
                            <td class="font-semibold w-40">Lokasi</td>
                            <td>{{ $history->lokasi->nama }}</td>
                        </tr>
                        <tr>
                            <td class="font-semibold w-40">Jumlah</td>
                            <td>{{ $history->jumlah * 1 }} Unit</td>
                        </tr>
                        <tr>
                            <td class="font-semibold w-40">Kondisi</td>
                            <td>{{ $history->kondisi }}%</td>
                        </tr>
                        <tr>
                            <td class="font-semibold w-40">Kelengkapan</td>
                            <td>{{ $history->kelengkapan }}%</td>
                        </tr>
                        <tr>
                            <td class="font-semibold w-40">Keterangan</td>
                            <td>{{ $history->keterangan }}</td>
                        </tr>
                        <!-- Garis pemisah setelah satu riwayat -->
                        <tr>
                            <td colspan="4">
                                <hr class="border-t border-gray-300 my-2">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500">Tidak ada riwayat tersedia.</p>
        @endif


        <!-- KEUANGAN -->
        <h2 class="text-lg font-semibold text-blue-600 bg-blue-100 p-2 rounded-md mb-4">KEUANGAN</h2>
        @if ($aset->keuangans && $aset->keuangans->count() > 0)
            <table class="w-full text-sm text-gray-600">
                <tbody>
                    @foreach ($aset->keuangans as $finance)
                        <tr class="border-b">
                            <td class="py-2 px-4">{{ date('d M Y', $finance->tanggal) }}</td>
                            <td class="py-2 px-4">
                                {{ $finance->tipe === 'in' ? 'Pemasukan' : ($finance->tipe === 'out' ? 'Pengeluaran' : 'Tidak Diketahui') }}
                            </td>
                            <td class="py-2 px-4">{{ rupiah($finance->nominal) }}</td>
                            <td class="py-2 px-4">{{ $finance->keterangan }}</td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <hr class="border-t border-gray-300">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500">Tidak ada data keuangan tersedia.</p>
        @endif

        <!-- AGENDA -->
        <h2 class="text-lg font-semibold text-black mb-4">AGENDA</h2>

        @if ($aset->agendas && $aset->agendas->count() > 0)
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

            <table class="w-full text-sm text-gray-600">
                <tbody>
                    @foreach ($aset->agendas as $agenda)
                        <tr>
                            <td class="py-2 font-bold text-black ">
                                @if ($agenda->tipe === 'mingguan')
                                    Mingguan: Setiap Hari {{ $dayMap[$agenda->hari] ?? 'Tidak Diketahui' }}
                                @elseif ($agenda->tipe === 'bulanan')
                                    Bulanan: Setiap Tanggal {{ $agenda->hari }}
                                @elseif ($agenda->tipe === 'tahunan')
                                    Tahunan: Setiap {{ date('j F', strtotime($agenda->tanggal)) }}
                                @elseif ($agenda->tipe === 'tanggal_tertentu')
                                    Tanggal: {{ date('j F Y', strtotime($agenda->tanggal)) }}
                                @else
                                    Tipe Tidak Diketahui
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="py-2 text-black font-semibold">
                                {{ $agenda->keterangan }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <hr class="border-t border-gray-300 my-2">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500">Tidak ada data agenda tersedia.</p>
        @endif


        <!-- JURNAL -->
        <h2 class="text-lg font-semibold text-black mb-2">JURNAL</h2>
        @if ($aset->jurnals && $aset->jurnals->count() > 0)
            <div class="border border-blue-400 rounded-md p-4">
                <table class="w-full text-sm text-gray-600">
                    <tbody>
                        @foreach ($aset->jurnals as $entry)
                            <tr>
                                <td class="py-2">{{ date('j F Y', (int) $entry->tanggal) }}</td>
                            </tr>
                            <tr>
                                <td class="py-2">{{ $entry->keterangan }}</td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <hr class="border-t border-gray-300 my-2">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">Tidak ada data jurnal tersedia.</p>
        @endif


    </div>
</body>


</html>
