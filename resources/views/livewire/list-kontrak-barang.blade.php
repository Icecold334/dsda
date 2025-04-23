<div>

    <table class="w-full border-0 border-separate border-spacing-y-4">
        <thead class="uppercase text-primary-900">
            <th class="w-1/2">Barang</th>
            <th class="w-1/4">Sisa Pengiriman</th>
            <th class="w-1/12"></th>
        </thead>
        <tbody>
            @if ($vendor_id && $jenis_id)
            @forelse ($merkList as $merk)
            <tr wire:key="{{ $merk['id'] }}" class=" border bg-gray-50 rounded-lg font-semibold">
                <td class="px-6 py-3">
                    <div>{{ $merk['merk']->barangStok->nama }}</div>
                    <div class="font-normal text-sm">
                        <table class="w-full">
                            <tr class="">
                                <td class=" w-1/3 text-center">{{ $merk['merk']->nama ?? '-' }}</td>
                                <td class="border-x-2 border-primary-600 w-1/3  text-center">
                                    {{ $merk['merk']->tipe ?? '-' }}</td>
                                <td class=" w-1/3  text-center">
                                    {{ $merk['merk']->ukuran ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td class="px-6 py-3">
                    {{ max($merk['max_jumlah'], 0) }}
                    {{ $merk['satuan'] }}
                </td>
                <td class="px-6 py-3">
                    <div class="{{ $merk['max_jumlah'] > 0 ? 'cursor-pointer' : 'hidden' }} text-primary-600 border-2 text-center py-1 w-8  rounded-md hover:text-white hover:bg-primary-600 transition duration-200"
                        @if ($merk['max_jumlah']> 0) wire:click="merkClick({{ $merk['id'] }})" @endif>
                        <i class="fa-solid fa-plus "></i>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center font-semibold">
                    {{ $vendor_id ? 'Data Kosong' : 'Pilih Vendor' }}
                </td>
            </tr>
            @endforelse
            @else
            <tr>
                <td colspan="3" class="text-center font-semibold">
                    {{ $vendor_id ? 'Data Kosong' : 'Pilih Vendor' }}
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>