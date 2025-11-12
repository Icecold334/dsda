<div>

    <div class="mb-4">
        <label class="block mb-1 font-medium">Pilih Barang</label>
        <selectwire:model.live.debounce.500ms="newBarangId"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            <option value="">-- Pilih Barang --</option>
            @foreach ($barangs as $barang)
            <option value="{{ $barang->id }}">{{ $barang->nama }}</option>
            @endforeach
            </select>
    </div>

    <div class="mb-4">
        <label class="block mb-1 font-medium">Pilih Merk</label>
        <selectwire:model.live.debounce.500ms="newMerkId"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            <option value="">-- Pilih Merk --</option>
            @foreach ($merks as $merk)
            <option value="{{ $merk->id }}">{{ $merk->nama }} {{ $merk->tipe }} {{ $merk->ukuran }}</option>
            @endforeach
            </select>
    </div>

    <div class="mb-4">
        <label class="block mb-1 font-medium">Jumlah (maks: {{ $maxJumlah }})</label>
        <input type="number" wire:model.live.debounce.500ms="newJumlah" min="1" max="{{ $maxJumlah }}"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
    </div>

    <div class="mb-4">
        <label class="block mb-1 font-medium">Pilih Bagian (Opsional)</label>
        <selectwire:model.live.debounce.500ms="newBagianId"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
            <option value="">-- Pilih Bagian --</option>
            @foreach ($bagians as $bagian)
            <option value="{{ $bagian->id }}">{{ $bagian->nama }}</option>
            @endforeach
            </select>
    </div>

    <div class="mb-4">
        <label class="block mb-1 font-medium">Pilih Posisi (Opsional)</label>
        <selectwire:model.live.debounce.500ms="newPosisiId"
            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200"
            @disabled(!$newBagianId)>
            <option value="">-- Pilih Posisi --</option>
            @foreach ($posisis as $posisi)
            <option value="{{ $posisi->id }}">{{ $posisi->nama }}</option>
            @endforeach
            </select>
    </div>

    <button wire:click="addToList"
        class="bg-primary-600 hover:bg-primary-700 text-white font-semibold px-4 py-2 rounded">Tambah Ke Daftar</button>

    @if (count($list) > 0)
    <table class="mt-6 w-full border border-gray-200 rounded-lg">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="px-4 py-2">Barang</th>
                <th class="px-4 py-2">Merk</th>
                <th class="px-4 py-2">Jumlah</th>
                <th class="px-4 py-2">Bagian</th>
                <th class="px-4 py-2">Posisi</th>
                <th class="px-4 py-2"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $item)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $item['merk']->barangStok->nama }}</td>
                <td class="px-4 py-2">{{ $item['merk']->nama }} {{ $item['merk']->tipe }} {{ $item['merk']->ukuran }}
                </td>
                <td class="px-4 py-2">{{ $item['jumlah'] }}</td>
                <td class="px-4 py-2">{{ $item['bagian_id'] ? \App\Models\BagianStok::find($item['bagian_id'])->nama :
                    '-' }}</td>
                <td class="px-4 py-2">{{ $item['posisi_id'] ? \App\Models\PosisiStok::find($item['posisi_id'])->nama :
                    '-' }}</td>
                <td class="px-4 py-2 text-center">
                    <button wire:click="removeFromList({{ $loop->index }})"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold px-3 py-1 rounded text-sm">
                        <i class="fa fa-times-circle mr-1"></i> Batal
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <button wire:click="save"
        class="mt-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded">Simpan Pengiriman</button>
    @endif
</div>