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
                        <div class="flex items-center">
                            <input type="number" {{-- wire:model.fill="list.{{ $index }}.jumlah" --}} value="{{ $item['jumlah'] }}"
                                wire:input="updateJumlah({{ $index }}, $event.target.value)"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                min="1" {{-- max="{{ $item['max_jumlah'] }}" --}} placeholder="Jumlah">
                            <span
                                class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm">
                                {{ $item['merk_id'] ? optional(App\Models\MerkStok::find($item['merk_id'])->barangStok->satuanBesar)->nama : 'Satuan' }}
                            </span>
                        </div>
                        {{-- @if (!$item['merk_id']) --}}
                        {{-- @if (isset($errorsList[$index])) --}}
                        {{-- <p class="text-red-600 text-xs mt-1">{{ $errorsList[$index] }}</p> --}}
                        {{-- @else --}}
                        <p class="text-black text-xs mt-1">Jumlah akumulasi maksimal : {{ $item['max_jumlah'] }}</p>
                        {{-- @endif --}}
                        {{-- @endif --}}
                    </td>
                    <td class="px-6 py-3">
                        @if ($pengiriman)
                            @if (is_null($pengiriman->status))
                                <!-- Pengiriman ada dan statusnya null -->
                                @role('penanggungjawab')
                                    <!-- Input dan tombol untuk upload hanya untuk penanggungjawab -->
                                    <input type="file" wire:model.live="list.{{ $index }}.bukti" class="hidden"
                                        id="upload-bukti-{{ $index }}">
                                    @if (isset($item['bukti']))
                                        <!-- Tampilkan dan berikan opsi untuk menghapus jika sudah ada gambar -->
                                        <div class="relative inline-block">
                                            <a href="{{ asset('storage/buktiPengiriman/' . $item['bukti']) }}"
                                                target="_blank">
                                                <img src="{{ asset('storage/buktiPengiriman/' . $item['bukti']) }}"
                                                    alt="Bukti" class="w-16 h-16 rounded-md">
                                            </a>
                                            <button wire:click="removeDocument({{ $index }})"
                                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full text-xs p-1 hover:bg-red-700">&times;</button>
                                        </div>
                                    @else
                                        <button type="button"
                                            onclick="document.getElementById('upload-bukti-{{ $index }}').click()"
                                            class="text-primary-700 bg-gray-200 border border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition">Unggah
                                            Foto</button>
                                    @endif
                                @else
                                    <!-- Non-penanggungjawab hanya melihat -->
                                    @if (isset($item['bukti']))
                                        <img src="{{ asset('storage/buktiPengiriman/' . $item['bukti']) }}" alt="Bukti"
                                            class="w-16 h-16 rounded-md">
                                    @else
                                        <span class="text-gray-500">Belum ada unggahan</span>
                                    @endif
                                @endrole
                            @else
                                <!-- Pengiriman ada tapi status bukan null, semua pengguna hanya melihat -->
                                @if (isset($item['bukti']))
                                    <img src="{{ asset('storage/buktiPengiriman/' . $item['bukti']) }}" alt="Bukti"
                                        class="w-16 h-16 rounded-md">
                                @else
                                    <span class="text-gray-500">Belum ada unggahan</span>
                                @endif
                            @endif
                        @else
                            <!-- Tidak ada pengiriman, hanya penanggungjawab yang bisa upload/edit -->
                            @role('penanggungjawab')
                                <input type="file" wire:model.live="list.{{ $index }}.bukti" class="hidden"
                                    id="upload-bukti-{{ $index }}">
                                <button type="button"
                                    onclick="document.getElementById('upload-bukti-{{ $index }}').click()"
                                    class="text-primary-700 bg-gray-200 border border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition">Unggah
                                    Foto</button>
                            @else
                                <span class="text-gray-500">Belum ada unggahan</span>
                            @endrole
                        @endif


                    </td>
                    <td class="text-center">
                        @if ($item['id'] === null)
                            <button wire:click="removeFromList({{ $index }})"
                                class="text-danger-900 border-danger-600 text-xl border bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="flex justify-center mt-4">
        @if (!$pengiriman && !optional($pengiriman)->status)
            <button wire:click="savePengiriman"
                class="text-primary-900 bg-primary-100 border border-primary-600 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5">
                Simpan
            </button>
        @endif
    </div>
</div>
