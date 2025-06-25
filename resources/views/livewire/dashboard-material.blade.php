<div>
    <h1 class="text-2xl font-bold text-primary-900 mb-3">
        @if (auth()->user()->unitKerja)
        <div>{{ auth()->user()->unitKerja->nama }}</div>
        @endif
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-3">
        {{-- Pemasukan --}}
        <x-card :maxH="true" title="Pemasukan Barang Terbaru" class="mb-4">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-800 text-sm uppercase">
                    <tr>
                        <th class="px-2 py-2">Tanggal</th>
                        <th class="px-2 py-2">Gudang</th>
                        <th class="px-2 py-2">Barang</th>
                        <th class="px-2 py-2 text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pemasukanList as $log)
                    <tr class="border-t">
                        <td class="px-2 py-1">{{ $log->tanggal }}</td>
                        <td class="px-2 py-1">{{ $log->nama_gudang }}</td>
                        <td class="px-2 py-1">{{ $log->nama }}</td>
                        <td class="px-2 py-1 text-right">{{ $log->jumlah }} {{ $log->satuan }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-3">Tidak ada pemasukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </x-card>

        {{-- Pengeluaran --}}
        <x-card :maxH="true" title="Pengeluaran Barang Terbaru" class="mb-4">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-800 text-sm uppercase">
                    <tr>
                        <th class="px-2 py-2">Tanggal</th>
                        <th class="px-2 py-2">Gudang</th>
                        <th class="px-2 py-2">Barang</th>
                        <th class="px-2 py-2 text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengeluaranList as $log)
                    <tr class="border-t">
                        <td class="px-2 py-1">{{ $log->tanggal }}</td>
                        <td class="px-2 py-1">{{ $log->nama_gudang }}</td>
                        <td class="px-2 py-1">{{ $log->nama }}</td>
                        <td class="px-2 py-1 text-right">{{ $log->jumlah }} {{ $log->satuan }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-3">Tidak ada pengeluaran</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </x-card>

        {{-- Stok Menipis --}}
        <div class="col-span-1">
            <x-card title="Persediaan Hampir Habis" class="mb-4" :maxH="true">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 text-gray-800 text-sm uppercase">
                        <tr>
                            <th class="px-2 py-2">Gudang</th>
                            <th class="px-2 py-2">Barang</th>
                            <th class="px-2 py-2 text-right">Sisa/Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($stokMenipisList as $item)
                        <tr class="border-t">
                            <td class="px-2 py-1">{{ $item->nama_gudang }}</td>
                            <td class="px-2 py-1">{{ $item->barang }}</td>
                            <td class="px-2 py-1 text-right">{{ max($item->stok, 0) }}</td>
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

    {{-- Permintaan Terbaru --}}
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
                    <tr class="py-3">
                        <td class="text-center font-semibold">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $p->nodin }}</td>
                        <td class="text-center">{{ $p->user->name ?? '-' }}</td>
                        <td class="text-center">
                            {{ $p->created_at->translatedFormat('l, d F Y H:i') }}
                        </td>
                        <td class="text-center">
                            <span
                                class="text-white bg-{{ $p->status_color }}-600 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                                {{ $p->status_label }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ url('/permintaan/permintaan/' . $p->id) }}"
                                class="text-primary-600 px-2 py-1 font-medium hover:bg-primary-500 transition duration-200 hover:text-white rounded-lg">
                                <i class="fa-solid fa-eye"></i>
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