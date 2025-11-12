<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900">Riwayat Keluar Masuk Barang</h1>
        <div class="flex flex-wrap gap-4 mb-4 items-end">
            <div class="w-32">
                <selectwire:model.live.debounce.500ms="filterJenis" class="border rounded-lg px-4 py-2 w-full">
                    <option value="">-- Semua Jenis --</option>
                    <option value="0">Keluar</option>
                    <option value="1">Masuk</option>
                    <option value="2">Penyesuaian</option>
                    </select>
            </div>
            <div class="w-32">
                <input type="date" wire:model.live.debounce.500ms="filterFromDate"
                    class="border rounded-lg px-4 py-2 w-full" />
            </div>
            <div class="w-32">
                <input type="date" wire:model.live.debounce.500ms="filterToDate"
                    class="border rounded-lg px-4 py-2 w-full" />
            </div>
            <div class="w-32">
                <selectwire:model.live.debounce.500ms="filterMonth" class="border rounded-lg px-4 py-2 w-full">
                    <option value="">-- Semua Bulan --</option>
                    @foreach(range(1, 12) as $m)
                    <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                    @endforeach
                    </select>
            </div>
            <div class="w-32">
                <selectwire:model.live.debounce.500ms="filterYear" class="border rounded-lg px-4 py-2 w-full">
                    <option value="">-- Semua Tahun --</option>
                    @foreach(range(now()->year, now()->year - 5) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                    </select>
            </div>
            <div>
                <button type="button" wire:click="resetFilters"
                    class="px-4 py-2 bg-blue-700 text-white rounded hover:bg-blue-400 transition">
                    <i class="fa fa-sync-alt"></i>
                </button>
            </div>
        </div>
    </div>

    <table class="w-full border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white uppercase">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Tanggal</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Gudang</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Jenis</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Volume</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/12"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($list as $index => $row)
            <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                <td class="py-3 px-6 text-center font-semibold">{{ $row['tanggal'] }}</td>
                <td class="py-3 px-6 text-center">{{ $row['gudang_nama'] }}</td>
                <td class="py-3 px-6 text-center">
                    <span
                        class="bg-{{ $row['jenis'] === 1 ? 'primary' : ($row['jenis'] === 0 ? 'secondary' : 'warning') }}-600 text-white text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ $row['jenis'] === 1 ? 'Masuk' : ($row['jenis'] === 0 ? 'Keluar' : 'Penyesuaian') }}
                    </span>
                </td>
                <td class="py-3 px-6 text-center font-semibold">{{ abs($row['volume']) }}</td>
                <td class="py-3 px-6 text-center">
                    <button class="text-primary-950 px-3 py-2 rounded-md border hover:bg-slate-300"
                        wire:click="selectedTanggal('{{ $row['unique_key'] ?? $row['datetime'] . '|' . $row['gudang_id'] . '|' . $row['jenis'] }}')">
                        <i class="fa-solid fa-eye"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-gray-500 py-4">Tidak ada data ditemukan</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4 flex justify-center gap-2">
        <button wire:click="$set('page', {{ max(1, $page - 1) }})" @disabled($page===1)
            class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
            &laquo; Prev
        </button>

        <span class="px-4 py-2 text-gray-700 font-medium">Halaman {{ $page }}</span>

        <button wire:click="$set('page', {{ $page + 1 }})"
            class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">
            Next &raquo;
        </button>
    </div>

    @if($modalVisible)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white w-full max-w-4xl rounded-lg shadow-lg overflow-hidden">
            <div class="flex justify-between items-center px-6 py-4 border-b">
                <h2 class="text-lg font-semibold">
                    Riwayat Barang {{ $jenisDipilih === 1 ? 'Masuk' : ($jenisDipilih === 0 ? 'Keluar' : 'Penyesuaian')
                    }} - {{ $tanggalDipilih }}
                </h2>
                <button wire:click="$set('modalVisible', false)" class="text-gray-500 hover:text-gray-800">
                    ✕
                </button>
            </div>

            <div class="p-6 overflow-y-auto max-h-[70vh]">
                <table class="w-full table-auto text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 text-left">Barang</th>
                            <th class="px-3 py-2 text-left">Merk</th>
                            <th class="px-3 py-2 text-left">Tipe</th>
                            <th class="px-3 py-2 text-left">Ukuran</th>
                            <th class="px-3 py-2 text-center">Jumlah</th>
                            <th class="px-3 py-2 text-center">User</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($detailList as $item)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-3 py-2">{{ $item->merkStok->barangStok->nama ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $item->merkStok->nama ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $item->merkStok->tipe ?? '-' }}</td>
                            <td class="px-3 py-2">{{ $item->merkStok->ukuran ?? '-' }}</td>
                            @php
                            $jumlah = $item->jumlah;

                            if ($jenisDipilih === 1) {
                            $jumlah = '+' . $jumlah;
                            } elseif ($jenisDipilih === 0) {
                            $jumlah = '-' . $jumlah;
                            } else {
                            $jumlah = $jumlah >= 0 ? '+' . $jumlah : $jumlah;
                            }
                            $textColor = str_starts_with($jumlah, '+') ? 'text-success-700' : 'text-danger-700';
                            // }

                            $textColor = str_starts_with($jumlah, '+') ? 'text-success-700' : 'text-danger-700';
                            @endphp
                            <td class="px-3 py-2 text-center font-semibold {{ $textColor }}">
                                {{ $jumlah }} {{ $item->merkStok->barangStok->satuanBesar->nama ?? '' }}
                            </td>
                            <td class="px-3 py-2">{{ $item->user->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 py-4">Tidak ada data ditemukan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

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