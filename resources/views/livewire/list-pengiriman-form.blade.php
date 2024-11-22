<div>
    <table class="w-full border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">NAMA BARANG</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">LOKASI *</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">BAGIAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">POSISI</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">JUMLAH *</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">DOKUMEN PENDUKUNG</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $index => $item)
                <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                    <td class="px-6 py-3 font-semibold"></td>
                    <td class="px-6 py-3 font-semibold">{{ $item['merk'] }}</td>
                    <td class="px-6 py-3">
                        <select wire:change="updateLokasi({{ $index }}, $event.target.value)"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            @disabled(isset($item['detail']))>
                            <option value="">Pilih Lokasi</option>
                            @foreach ($lokasis as $lokasi)
                                <option value="{{ $lokasi->id }}" @if ($item['lokasi_id'] == $lokasi->id) selected @endif>
                                    {{ $lokasi->nama }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-6 py-3">
                        <select wire:change="updateBagian({{ $index }}, $event.target.value)"
                            class="bg-gray-50 border border-gray-300 {{ empty($item['lokasi_id']) ? 'cursor-not-allowed' : '' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            @disabled(!$item['editable'] || empty($item['lokasi_id']))>
                            <option value="">Pilih Bagian</option>
                            @foreach ($item['bagians'] as $bagian)
                                <option value="{{ $bagian->id }}" @if ($item['bagian_id'] == $bagian->id) selected @endif>
                                    {{ $bagian->nama }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-6 py-3">
                        <select wire:change="updatePosisi({{ $index }}, $event.target.value)"
                            class="bg-gray-50 border border-gray-300 {{ empty($item['bagian_id']) ? 'cursor-not-allowed' : '' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            @disabled(!$item['editable'] || empty($item['bagian_id']))>
                            <option value="">Pilih Posisi</option>
                            @foreach ($item['posisis'] as $posisi)
                                <option value="{{ $posisi->id }}" @if ($item['posisi_id'] == $posisi->id) selected @endif>
                                    {{ $posisi->nama }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-6 py-3">
                        <input type="number" wire:model.live="list.{{ $index }}.jumlah"
                            wire:change="updateJumlah({{ $index }}, $event.target.value)"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5"
                            min="1" max="{{ $item['max_jumlah'] }}" placeholder="Jumlah">
                    </td>
                    <td class="px-6 py-3">
                        <input type="file" wire:model.live="list.{{ $index }}.bukti" class="hidden"
                            id="upload-bukti-{{ $index }}">
                        @if (isset($item['bukti']))
                            <div class="relative inline-block">
                                <a href="{{ is_string($item['bukti']) ? asset('storage/buktiPengiriman/' . $item['bukti']) : $item['bukti']->temporaryUrl() }}"
                                    download="{{ is_string($item['bukti']) ? $item['bukti'] : $item['bukti']->getClientOriginalName() }}">
                                    <img src="{{ is_string($item['bukti']) ? asset('storage/buktiPengiriman/' . $item['bukti']) : $item['bukti']->temporaryUrl() }}"
                                        alt="Bukti" class="w-16 h-16 rounded-md">
                                </a>
                            </div>
                        @else
                            <button type="button"
                                onclick="document.getElementById('upload-bukti-{{ $index }}').click()"
                                class="text-primary-700 bg-gray-200 border text-center border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition">
                                Unggah Foto
                            </button>
                        @endif
                    </td>
                    <td class="text-center">
                        <button wire:click="removeFromList({{ $index }})"
                            class="text-danger-900 border-danger-600 text-xl border bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="flex justify-center mt-4">
        <button wire:click="savePengiriman"
            class="text-primary-900 bg-primary-100 border border-primary-600 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5">
            Simpan
        </button>
    </div>
</div>
