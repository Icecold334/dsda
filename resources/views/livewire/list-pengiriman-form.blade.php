<div>
    <h1 class="text-2xl font-bold text-primary-900 ">{{ $roles }}</h1>
    <table class="w-full border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg">NAMA BARANG</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold ">SPESIFIKASI</th>
                <th class="py-3 px-6 bg-primary-950 text-center w-1/6  font-semibold">LOKASI *</th>
                @if ($showDokumen)
                    <th class="py-3 px-6 bg-primary-950 text-center w-1/6 font-semibold">BAGIAN</th>
                    <th class="py-3 px-6 bg-primary-950 text-center w-1/6 font-semibold">POSISI</th>
                @endif
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/5" colspan="2">JUMLAH *</th>
                @if ($showDokumen)
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">DOKUMEN PENDUKUNG</th>
                    <th
                        class="py-3 px-6 bg-primary-950 text-center {{ $showDokumen ? 'rounded-r-lg' : '' }} font-semibold">
                    </th>
                @else
                    <th class="py-3 px-6 bg-primary-950 text-center rounded-r-lg font-semibold"></th>
                @endif
            </tr>
        </thead>

        <tbody>
            @if ($showDokumen)
                <tr>
                    <th colspan="5"></th>
                    <th>Diajukan *</th>
                    <th>Diterima </th>
                </tr>
            @endif
            @foreach ($list as $index => $item)
                <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                    <td class="px-4 py-3 font-semibold">
                        {{-- editable {{ $item['editable'] }} --}}
                        <div>{{ $item['merk']->barangStok->nama }}</div>

                    </td>
                    <td class="px-3 py-3">
                        <table class="w-full">
                            <tr>
                                <td class=" w-1/3 ">{{ $item['merk']->nama ?? '-' }}</td>
                                <td
                                    class="border-x-2 border-primary-600 w-1/3  {{ $item['merk']->tipe ? '' : 'text-center' }}">
                                    {{ $item['merk']->tipe ?? '-' }}</td>
                                <td class=" w-1/3  {{ $item['merk']->ukuran ? '' : 'text-center' }}">
                                    {{ $item['merk']->ukuran ?? '-' }}</td>
                            </tr>
                        </table>
                    </td>
                    <td class="px-6 py-3">
                        <select wire:change="updateLokasi({{ $index }}, $event.target.value)"
                            class="
                            {{-- @cannot('inventaris_edit_lokasi_penerimaan')
                                cursor-not-allowed
                            @endcannot --}}
                            bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            @disabled(isset($item['detail']))>
                            <option value="">Pilih Lokasi</option>
                            @foreach ($lokasis as $lokasi)
                                <option value="{{ $lokasi->id }}" @if ($item['lokasi_id'] == $lokasi->id) selected @endif>
                                    {{ $lokasi->nama }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    @if ($showDokumen)
                        <td class="px-6 py-3">
                            <select wire:model="list.{{ $index }}.bagian_id"
                                wire:change="updateBagianId({{ $index }}, $event.target.value)"
                                wire:model.live="list.{{ $index }}.bagian_id"
                                class="bg-gray-50 border border-gray-300 {{ !$item['editable'] || empty($item['lokasi_id']) || $authLokasi !== $item['lokasi_id'] ? 'cursor-not-allowed' : '' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                @disabled(!$item['editable'] || empty($item['lokasi_id']) || $authLokasi !== $item['lokasi_id'])
                                @cannot('inventaris_edit_lokasi_penerimaan')
                                disabled
                            @endcannot>
                                <option value="">Pilih Bagian</option>
                                @foreach ($item['bagians'] as $bagian)
                                    <option value="{{ $bagian->id }}"
                                        @if ($item['bagian_id'] == $bagian->id) selected @endif>
                                        {{ $bagian->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-6 py-3">
                            <select wire:model="list.{{ $index }}.posisi_id"
                                wire:model.live="list.{{ $index }}.posisi_id"
                                class="bg-gray-50 border border-gray-300 {{ !$item['editable'] || empty($item['bagian_id']) || $authLokasi !== $item['bagian_id'] ? 'cursor-not-allowed' : '' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                @disabled(!$item['editable'] || empty($item['bagian_id']) || $authLokasi !== $item['lokasi_id'])>
                                <option value="">Pilih Posisi</option>
                                @foreach ($item['posisis'] as $posisi)
                                    <option value="{{ $posisi->id }}"
                                        @if ($item['posisi_id'] == $posisi->id) selected @endif>
                                        {{ $posisi->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    @endif
                    <td class="px-2 py-3">
                        <div class="flex items-center">
                            <input type="number" {{-- wire:model.fill="list.{{ $index }}.jumlah" --}} value="{{ $item['jumlah'] }}"
                                wire:input="updateJumlah({{ $index }}, $event.target.value)"
                                @disabled($showDokumen)
                                class="bg-gray-50 border {{ $showDokumen ? 'cursor-not-allowed' : '' }} border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
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
                        @if (!$showDokumen)
                            <p class="text-black text-xs mt-1">Jumlah akumulasi maksimal : {{ $item['max_jumlah'] }}
                            </p>
                        @endif
                        {{-- @endif --}}
                        {{-- @endif --}}
                    </td>
                    <td class="px-2 py-3">
                        <div class="flex items-center">
                            @if ($showDokumen)
                                <input type="number" {{-- wire:model.fill="list.{{ $index }}.jumlah" --}} value=""
                                    wire:model="list.{{ $index }}.jumlah_diterima"
                                    wire:model.live="list.{{ $index }}.jumlah_diterima" {{-- @cannot('inventaris_edit_jumlah_diterima') disabled @endcannot --}}
                                    disabled
                                    class="bg-gray-50 border {{ 1 ? 'cursor-not-allowed' : '' }} border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    min="1" {{-- max="{{ $item['max_jumlah'] }}" --}} placeholder="Jumlah">
                                <span
                                    class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm">
                                    {{ $item['merk_id'] ? optional(App\Models\MerkStok::find($item['merk_id'])->barangStok->satuanBesar)->nama : 'Satuan' }}
                                </span>
                            @endif
                        </div>
                    </td>
                    @if ($showDokumen)
                        <td class="px-6 py-3 text-center">
                            @if (isset($item['bukti']))
                                <div class="relative inline-block">
                                    <a href="{{ is_string($item['bukti'])
                                        ? asset('storage/buktiPengiriman/' . $item['bukti'])
                                        : (is_object($item['bukti']) && method_exists($item['bukti'], 'temporaryUrl')
                                            ? $item['bukti']->temporaryUrl()
                                            : null) }}"
                                        target="_blank">
                                        <img src="{{ is_string($item['bukti'])
                                            ? asset('storage/buktiPengiriman/' . $item['bukti'])
                                            : (is_object($item['bukti']) && method_exists($item['bukti'], 'temporaryUrl')
                                                ? $item['bukti']->temporaryUrl()
                                                : null) }}"
                                            alt="Bukti" class="w-16 h-16 rounded-md">
                                    </a>
                                    @if (
                                        $item['editable'] &&
                                            $item['lokasi_id'] == $authLokasi &&
                                            auth()->user()->can('inventaris_unggah_foto_barang_datang'))
                                        <button wire:click="removePhoto({{ $index }})"
                                            class="absolute -top-2 -right-2 w-4 h-4 bg-red-500 text-white rounded-full text-xs hover:bg-red-700">&times;</button>
                                    @endif
                                </div>
                            @else
                                <!-- No photo uploaded, check location and permission -->
                                @can('inventaris_unggah_foto_barang_datang')
                                    @if ($item['lokasi_id'] == $authLokasi)
                                        <!-- With permission and location matching -->
                                        <input type="file" wire:model="list.{{ $index }}.bukti"
                                            wire:model.live="list.{{ $index }}.bukti" class="hidden"
                                            id="upload-bukti-{{ $index }}">
                                        <button type="button"
                                            onclick="document.getElementById('upload-bukti-{{ $index }}').click()"
                                            class="text-primary-700 bg-gray-200 border border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition">
                                            Unggah
                                        </button>
                                    @else
                                        <span class="text-gray-500">Belum ada unggahan</span>
                                    @endif
                                @else
                                    <!-- Without permission, show "Belum ada unggahan" -->
                                    <span class="text-gray-500">Belum ada unggahan</span>
                                @endcan
                            @endif
                        </td>
                    @endif



                    <td class="text-center">

                        {{-- {{ $item['bagian_id'] && $item['posisi_id'] && $item['bukti'] ? '' : 'hidden' }} --}}
                        @if ($item['id'] === null)
                            <button wire:click="removeFromList({{ $index }})"
                                class="text-danger-900 border-danger-600 text-xl border bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </button>
                        @endif

                        @if ($showDokumen)
                            <button wire:click="updatePengirimanStok({{ $index }})"
                                class="text-success-900 border-success-600 text-xl border bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200
                                    {{ $item['bukti'] && $item['editable'] && $item['lokasi_id'] == $authLokasi ? '' : 'hidden' }}
                                     ">
                                <i class="fa-solid fa-circle-check"></i>
                            </button>
                            <!-- Without permission, show "Belum ada unggahan" -->
                            {{-- <span class="text-gray-500">Belum ada unggahan</span> --}}
                            <!-- Your content or logic here -->
                            {{-- @can('inventaris_edit_jumlah_diterima') --}}
                            <button wire:click="openApprovalModal({{ $item['id'] }})"
                                class="text-primary-900 border-primary-600 text-xl border bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200
                            {{-- {{ $showButtonPemeriksa && !$item['editable'] ? '' : 'hidden' }}"> --}}
                            {{ !$item['editable'] ? '' : 'hidden' }}">
                                <i class="fa-solid fa-info"></i>
                            </button>
                            {{-- @endcan --}}
                        @endif

                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="flex justify-center mt-4">
        {{-- @dump($showApprovalModal, $approvalData, $selectedItemId) --}}

        @if (!$showDokumen && collect($list)->count() > 0)
            <button wire:click="savePengiriman"
                class="text-primary-900 bg-primary-100 border border-primary-600 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5">
                Simpan
            </button>
        @endif
    </div>

    @if ($showApprovalModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg w-2/3">
                <!-- Modal Header -->
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-xl font-semibold">Setujui Pengiriman Barang</h3>
                    <button wire:click="openApprovalModal({{ $selectedItemId }})"
                        class="text-gray-500 hover:text-gray-800">
                        &times;
                    </button>
                </div>
                {{-- @dd($approvalData) --}}
                <!-- Modal Body -->
                <div class="p-4 space-y-4">

                    <!-- Daftar Lokasi, Jumlah, dan Catatan -->
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200 text-left">
                                <th class="border px-4 py-2">Nama</th>
                                <th class="border px-4 py-2">Jumlah Disetujui</th>
                                <th class="border px-4 py-2">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($approvalData as $index => $pengiriman)
                                <tr>

                                    <td
                                        class="border px-4 py-2 {{ auth()->id() == $pengiriman['id'] ? 'font-semibold' : '' }}">
                                        {{ $pengiriman['nama'] }}</td>

                                    <td class="border px-4 py-2">
                                        <input type="number" wire:model="approvalData.{{ $index }}.jumlah"
                                            @disabled($pengiriman['jumlah'] || !$showButtonPemeriksa || auth()->id() != $pengiriman['id'])
                                            class="w-full {{ $pengiriman['jumlah'] || !$showButtonPemeriksa || auth()->id() != $pengiriman['id'] ? 'cursor-not-allowed bg-gray-200' : '' }} border rounded px-2 py-1"
                                            min="0">
                                    </td>
                                    <td class="border px-4 py-2">
                                        <textarea wire:model="approvalData.{{ $index }}.catatan" @disabled($pengiriman['jumlah'] || !$showButtonPemeriksa || auth()->id() != $pengiriman['id'])
                                            class="w-full {{ $pengiriman['jumlah'] || !$showButtonPemeriksa || auth()->id() != $pengiriman['id'] ? 'cursor-not-allowed bg-gray-200' : '' }} border rounded px-2 py-1"
                                            rows="1" placeholder="Belum ada catatan"></textarea>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="font-semibold py-4 text-center" colspan="7">Barang Tidak Tersedia
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end p-4 border-t">
                    <button wire:click="approveItem({{ $selectedItemId }})" {{-- optional(collect($approvalData)->where('id', auth()->id())->first())['jumlah'] --}}
                        class="bg-primary-500 {{ !optional(
                            collect($approvalData)->where('id', auth()->id())->first(),
                        )['jumlah'] &&
                        $showButtonPemeriksa &&
                        auth()->id() ==
                            optional(
                                collect($approvalData)->where('id', auth()->id())->first(),
                            )['id']
                            ? ''
                            : 'hidden' }} hover:bg-primary-700 text-white font-bold py-2 px-4 rounded">
                        Setujui
                    </button>
                    <button wire:click="openApprovalModal({{ $selectedItemId }})"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    @endif


    {{-- @if ($noteModalVisible)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg w-1/2">
                <!-- Modal Header -->
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-xl font-semibold">Catatan Persetujuan</h3>
                    <button wire:click="$set('noteModalVisible', false)" class="text-gray-500 hover:text-gray-800">
                        &times;
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-4 space-y-4">
                    <table class="w-full border-collapse border border-gray-300">
                        <thead>
                            <tr class="bg-gray-200 text-left">
                                <th class="border px-4 py-2">Spesifikasi</th>

                                <th class="border px-4 py-2">Lokasi</th>
                                <th class="border px-4 py-2">Bagian</th>
                                <th class="border px-4 py-2">Posisi</th>
                                <th class="border px-4 py-2">Jumlah Disetujui</th>
                                <th class="border px-4 py-2">Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($selectedItemNotes as $note)
                                <tr>
                                    <td class="border px-4 py-2">
                                        <div>Merk : {{ $note['merk']->nama ?? '-' }}</div>
                                        <div>Tipe : {{ $note['merk']->ukuran ?? '-' }}</div>
                                        <div>Ukuran : {{ $note['merk']->tipe ?? '-' }}</div>
                                    </td>
                                    <td class="border px-4 py-2">{{ $note['lokasi'] }}</td>
                                    <td class="border px-4 py-2">{{ $note['bagian'] }}</td>
                                    <td class="border px-4 py-2">{{ $note['posisi'] }}</td>
                                    <td class="border px-4 py-2">{{ $note['jumlah_disetujui'] }}</td>
                                    <td class="border px-4 py-2">{{ $note['catatan'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Modal Footer -->
                <div class="flex justify-end p-4 border-t">
                    <button wire:click="$set('noteModalVisible', false)"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif --}}

</div>
