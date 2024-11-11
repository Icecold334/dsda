<div>
    <table class="w-full border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold ">NAMA BARANG</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">LOKASI *</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">BAGIAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">POSISI</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">JUMLAH *</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">BUKTI</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $index => $item)
                <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                    <td class="px-6 py-3 font-semibold"></td>
                    <td class="px-6 py-3 font-semibold">{{ $item['merk'] }}</td>

                    <!-- Lokasi Dropdown -->
                    <td class="px-6 py-3">
                        <select wire:change="updateLokasi({{ $index }}, $event.target.value)"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            @disabled($item['detail'])>
                            <option value="">Pilih Lokasi</option>
                            @foreach ($lokasis as $lokasi)
                                <option value="{{ $lokasi->id }}" @if ($item['lokasi_id'] == $lokasi->id) selected @endif>
                                    {{ $lokasi->nama }}
                                </option>
                            @endforeach
                        </select>
                    </td>

                    <!-- Bagian Dropdown -->
                    <td class="px-6 py-3">
                        <select wire:change="updateBagian({{ $index }}, $event.target.value)"
                            class="bg-gray-50 border border-gray-300 {{ empty($item['lokasi_id']) ? 'cursor-not-allowed' : '' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            @disabled(!$item['editable'] || empty($item['lokasi_id']))>
                            <option value="">Pilih Bagian</option>
                            @forelse ($item['bagians'] as $bagian)
                                <option value="{{ $bagian->id }}" @if ($item['bagian_id'] == $bagian->id) selected @endif>
                                    {{ $bagian->nama }}
                                </option>
                            @empty
                                <option disabled selected>Tidak ada bagian tersedia</option>
                            @endforelse
                        </select>
                    </td>

                    <!-- Posisi Dropdown -->
                    <td class="px-6 py-3">
                        <select wire:change="updatePosisi({{ $index }}, $event.target.value)"
                            class="bg-gray-50 border border-gray-300 {{ empty($item['bagian_id']) ? 'cursor-not-allowed' : '' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            @disabled(!$item['editable'] || empty($item['bagian_id']))>
                            <option value="">Pilih Posisi</option>
                            @forelse ($item['posisis'] as $posisi)
                                <option value="{{ $posisi->id }}" @if ($item['posisi_id'] == $posisi->id) selected @endif>
                                    {{ $posisi->nama }}
                                </option>
                            @empty
                                <option disabled selected>Tidak ada posisi tersedia</option>
                            @endforelse
                        </select>
                    </td>

                    <!-- Jumlah Input -->
                    <td class="px-6 py-3">
                        <div class="flex items-center">
                            <input type="number" wire:model.live="list.{{ $index }}.jumlah"
                                wire:change="updateJumlah({{ $index }}, $event.target.value)"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                min="1" max="{{ $item['max_jumlah'] }}" placeholder="Jumlah"
                                @disabled($item['detail'])>
                            <span
                                class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm">
                                {{ $item['merk_id'] ? optional(App\Models\MerkStok::find($item['merk_id'])->barangStok->satuanBesar)->nama : 'Satuan' }}
                            </span>
                        </div>
                        @if (isset($errorsList[$index]))
                            <p class="text-red-600 text-xs mt-1">{{ $errorsList[$index] }}</p>
                        @else
                            <p class="text-black text-xs mt-1">Jumlah maksimal : {{ $item['max_jumlah'] }}</p>
                        @endif
                    </td>

                    <!-- Bukti Upload -->
                    <td class="px-6 py-3">
                        <input type="file" wire:model.live="list.{{ $index }}.bukti" class="hidden"
                            id="upload-bukti-{{ $index }}">
                        @if (isset($item['bukti']))
                            <div class="relative inline-block">
                                <a href="{{ is_string($item['bukti']) ? asset('storage/buktiPengiriman/' . $item['bukti']) : $item['bukti']->temporaryUrl() }}"
                                    download="{{ is_string($item['bukti']) ? is_string($item['bukti']) : $item['bukti']->getClientOriginalName() }}">
                                    <img src="{{ is_string($item['bukti']) ? asset('storage/buktiPengiriman/' . $item['bukti']) : $item['bukti']->temporaryUrl() }}"
                                        alt="Bukti" class="w-16 h-16 rounded-md">
                                </a>
                                <button wire:click="removePhoto({{ $index }})"
                                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1 text-xs">
                                    &times;
                                </button>
                            </div>
                        @else
                            <button type="button"
                                onclick="document.getElementById('upload-bukti-{{ $index }}').click()"
                                class="text-primary-700 bg-gray-200 border border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition"
                                @disabled(!$item['editable'])>
                                Unggah Foto
                            </button>
                        @endif
                        @error("list.{$index}.bukti")
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </td>

                    <!-- Remove Button -->
                    <td class="text-center py-3">
                        <button wire:click="removeFromList({{ $index }})"
                            class="text-danger-900 border-danger-600 text-xl border bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200"
                            @disabled(!$item['editable'])>
                            <i class="fa-solid fa-circle-xmark"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="flex w-full justify-evenly">
        <!-- Penulis -->
        <div class="flex items-center space-x-2">
            <label for="penulis" class="block text-sm font-medium text-gray-900">Penulis</label>
            <input type="text" id="penulis" wire:model.live="penulis"
                class="border-gray-300 rounded-lg p-2.5 focus:ring-primary-500 focus:border-primary-500 w-full" />
            <button type="button" class="bg-gray-200 rounded-full p-2">
                <i class="fa-solid fa-check text-primary-600"></i>
            </button>
        </div>

        <!-- PJ1 -->
        <div class="flex items-center space-x-2">
            <label for="pj1" class="block text-sm font-medium text-gray-900">Persetujuan 1</label>
            <input type="text" id="pj1" wire:model.live="pj1"
                class="border-gray-300 rounded-lg p-2.5 focus:ring-primary-500 focus:border-primary-500 w-full" />
            <button type="button" class="bg-gray-200 rounded-full p-2">
                <i class="fa-solid fa-check text-primary-600"></i>
            </button>
        </div>

        <!-- PJ2 -->
        <div class="flex items-center space-x-2">
            <label for="pj2" class="block text-sm font-medium text-gray-900">Persetujuan 2</label>
            <input type="text" id="pj2" wire:model.live="pj2"
                class="border-gray-300 rounded-lg p-2.5 focus:ring-primary-500 focus:border-primary-500 w-full" />
            <button type="button" class="bg-gray-200 rounded-full p-2">
                <i class="fa-solid fa-check text-primary-600"></i>
            </button>
        </div>
    </div>


    @if ($vendor_id != null && count($list) > 0)
        <button wire:click='savePengiriman'
            class="text-primary-900 bg-primary-100 border border-primary-600 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
            Simpan
        </button>
    @endif
</div>
