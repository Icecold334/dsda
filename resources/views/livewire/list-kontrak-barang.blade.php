<table class="w-full border-0 border-separate border-spacing-y-4">
    <thead class="uppercase text-primary-900">
        <th class="w-1/3">Jenis Barang</th>
        <th class="w-1/3">Merk</th>
        <th class="w-1/3">Sisa</th>
    </thead>
    <tbody>
        @if ($vendor_id && $jenis_id)
            @forelse ($merkList as $merk)
                <tr class="border bg-gray-50 hover:bg-gray-200  rounded-lg font-semibold  hover:cursor-pointer  transition duration-200"
                    wire:click="merkClick({{ $merk->id }})">
                    <td class="px-6 py-3">{{ $merk->barangStok->nama }}</td>
                    <td class="px-6 py-3">{{ $merk->nama }}</td>
                    <td class="px-6 py-3">
                        {{ $merk->max_jumlah }}
                        {{ $merk->barangStok->satuanBesar->nama }}
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
