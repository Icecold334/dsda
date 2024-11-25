<div>

    <table class="w-full border-0 border-separate border-spacing-y-4">
        <thead class="uppercase text-primary-900">
            <th class="w-1/3">Jenis Barang</th>
            <th class="w-1/3">Spesifikasi</th>
            <th class="w-1/3">Sisa Pengiriman</th>
        </thead>
        <tbody>
            @if ($vendor_id && $jenis_id)
                @forelse ($merkList as $merk)
                    <tr wire:key='{{ $merk['id'] }}'
                        class="{{ $merk['max_jumlah'] > 0 ? 'cursor-pointer' : 'cursor-not-allowed' }} border bg-gray-50 hover:bg-gray-200  rounded-lg font-semibold    transition duration-200"
                        @if ($merk['max_jumlah'] > 0) wire:click="merkClick({{ $merk['id'] }})" @endif>
                        <td class="px-6 py-3">{{ $merk['barang_stok']->nama }}</td>
                        <td class="px-6 py-3">{{ $merk['merk']->nama }}</td>
                        <td class="px-6 py-3">
                            {{ max($merk['max_jumlah'], 0) }}
                            {{ $merk['satuan'] }}
                        </td>
                        {{-- <td>
                    {{ $merk->transaksiStok()->where('vendor_id', 4)->whereHas('kontrakStok', function ($query) {
                            $query->where('type', true);
                        })->sum('jumlah') }}
                </td> --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center font-semibold">
                            {{ $vendor_id ? 'Data Kosong' : 'Pilih Vendor' }}
                        </td>
                    </tr>
                @endforelse
            @endif
        </tbody>
    </table>
</div>
