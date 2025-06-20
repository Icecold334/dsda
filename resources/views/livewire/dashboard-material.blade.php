<div>
    <h1 class="text-2xl font-bold text-primary-900 mb-3">
        @if (auth()->user()->unitKerja)
        <div>{{ auth()->user()->unitKerja->nama }}</div>
        @endif
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-3">
        <div class="col-span-2 ">
            <x-card :maxH="true" title="Daftar Keluar Masuk Barang" class="mb-4">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-gray-800 text-sm uppercase">
                        <tr>
                            <th class="px-2 py-2">Tanggal</th>
                            <th class="px-2 py-2">Tipe</th>
                            <th class="px-2 py-2">Nama Barang</th>
                            <th class="px-2 py-2 text-right">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($keluarMasukList as $log)
                        <tr class="border-t">
                            <td class="px-2 py-1">{{ \Carbon\Carbon::parse($log->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-2 py-1 text-center">
                                <span class="bg-{{ $log->tipe == 'Pemasukan' ? 'primary' : 'secondary' }}-600
                                                                       text-{{ $log->tipe == 'Pemasukan' ? 'primary' : 'secondary' }}-100
                                                                       text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    {{ $log->tipe == 'Pemasukan' ? 'Pemasukan' : 'Pengeluaran' }}
                                </span>
                            </td>
                            <td class="px-2 py-1">{{ $log->merkStok->barangStok->nama ?? '-' }}</td>
                            <td class="px-2 py-1 text-right">{{ $log->jumlah }} {{
                                $log->merkStok->barangStok->satuanBesar->nama }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-3">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </x-card>
        </div>

        <div class="col-span-1">
            <x-card title="Barang Dengan Persediaan Rendah" class="mb-4">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-gray-800 text-sm uppercase">
                        <tr>
                            <th class="px-2 py-2">Nama Barang</th>
                            <th class="px-2 py-2 text-right">Stok</th>
                            {{-- <th class="px-2 py-2 text-right">Minimal</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stokMenipisList as $item)
                        <tr class="border-t">
                            <td class="px-2 py-1">{{ $item->nama }}</td>
                            <td class="px-2 py-1 text-right">{{ max($item->stok,0) }}</td>
                            {{-- <td class="px-2 py-1 text-right">{{ $item->minimal }}</td> --}}
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-3">Semua stok aman</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </x-card>
        </div>
    </div>

    <div class="col-span-3">
        <x-card title="Permintaan Terbaru" class="mb-4">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No. SPB</th>
                        <th>Pemohon</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($permintaanTerbaru as $index => $p)
                    <tr>
                        <td class="text-center font-semibold">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $p->nodin }}</td>
                        <td class="text-center">{{ $p->user->name ?? '-' }}</td>
                        <td class="text-center">{{ $p->created_at->translatedformat('d F Y H:i:s') }}</td>
                        <td class="text-center">
                            <span
                                class="inline-block px-2 py-1 text-xs font-semibold text-white bg-{{ $p->status_color }}-600 rounded-full">
                                {{ $p->status_label }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ url('/permintaan/permintaan/' . $p->id) }}"
                                class="text-blue-600 hover:underline font-medium">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-3">Tidak ada permintaan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </x-card>
    </div>
</div>