<div>
    <div>
        <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
            <thead>
                <tr class="text-white uppercase">
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">NAMA ASET</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">merk/jenis</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">permintaan</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">disetujui</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">tanggal peminjaman</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">tanggal pengembalian</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">keterangan</th>
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
                </tr>
            </thead>
            <tbody>
                @if ($tanggal_permintaan && $keterangan && $unit_id)
                    @foreach ($list as $index => $item)
                        <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                            <td class="py-3 px-6"></td>
                            <td class="py-3 px-6">{{ $item['aset_name'] }}</td>
                            <td class="py-3 px-6">{{ $item['merk_jenis'] }}</td>
                            <td class="py-3 px-6">{{ $item['permintaan'] }}</td>
                            <td class="py-3 px-6">{{ $item['disetujui'] }}</td>
                            <td class="py-3 px-6">{{ $item['tanggal_peminjaman'] }}</td>
                            <td class="py-3 px-6">{{ $item['tanggal_pengembalian'] }}</td>
                            <td class="py-3 px-6">{{ $item['keterangan'] }}</td>
                            <td class="text-center py-3 px-6">
                                <button wire:click="removeFromList({{ $index }})"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Remove
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                        <td class="py-3 px-6"></td>
                        <td class="py-3 px-6 relative">
                            <input type="text" wire:model.live.debounce.300ms="newAset" {{-- wire:focus="updatedNewAset" --}}
                                wire:blur="blurAsset" placeholder="Cari Aset"
                                class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                            <div
                                class="absolute z-10 w-full bg-white shadow-lg max-h-60 overflow-auto mt-1 border border-gray-200">
                                @foreach ($assetSuggestions as $asset)
                                    <div wire:click="selectAsset({{ $asset->id }})"
                                        class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer">
                                        {{ $asset->nama }}
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td class="py-3 px-6">
                            <input type="text" disabled wire:model.live="newMerkJenis"
                                class="bg-gray-200 cursor-not-allowed border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </td>
                        <td class="py-3 px-6">
                            <input type="number" wire:model.live="newPermintaan" min="1"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Jumlah Permintaan">
                        </td>
                        <td class="py-3 px-6">
                            <input type="number" wire:model.live="newDisetujui" min="0"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Jumlah Disetujui">
                        </td>
                        <td class="py-3 px-6">
                            <input type="date" wire:model.live="newTanggalPeminjaman"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </td>
                        <td class="py-3 px-6">
                            <input type="date" wire:model.live="newTanggalPengembalian"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        </td>
                        <td class="py-3 px-6">
                            <input type="text" wire:model.live="newKeterangan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Keterangan">
                        </td>
                        <td class="text-center py-3 px-6">
                            <button wire:click="addToList"
                                class="text-primary-900 border-primary-600 text-xl border bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                <i class="fa-solid fa-circle-plus"></i>
                            </button>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="9" class="text-center">Lengkapi data diatas terlebih dahulu</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="flex justify-center mt-4">
            @if (count($list) > 0)
                <button wire:click="saveData"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Simpan
                </button>
            @endif
        </div>
    </div>
</div>
