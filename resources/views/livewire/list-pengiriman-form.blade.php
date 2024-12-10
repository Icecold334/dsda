<div>
    <h1 class="text-2xl font-bold text-primary-900 ">{{ $roles }}</h1>
    <table class="w-full border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg">NAMA BARANG</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">LOKASI *</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">BAGIAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">POSISI</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/5" colspan="2">JUMLAH *</th>
                @if ($showDokumen)
                    <th class="py-3 px-6 bg-primary-950 text-center font-semibold">DOKUMEN PENDUKUNG</th>
                    @can('inventaris_upload_foto_bukti')
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold">&nbsp;</th>
                    @endcan
                @endif
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>Diajukan *</th>
                <th>Diterima </th>
                @if ($showDokumen)
                    <th>&nbsp;</th>
                    @can('inventaris_upload_foto_bukti')
                        <th>&nbsp;</th>
                    @endcan
                @endif
                <th></th>
            </tr>
            @foreach ($list as $index => $item)
                <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                    <td class="px-2 py-3 font-semibold">
                        <div>{{ $item['merk']->barangStok->nama }}</div>
                        <div class="font-normal text-sm">
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
                        </div>
                    </td>
                    <td class="px-6 py-3">
                        <select wire:change="updateLokasi({{ $index }}, $event.target.value)"
                            class="
                            {{-- @cannot('inventaris_edit_lokasi_penerimaan')
                                cursor-not-allowed
                            @endcannot --}}
                             bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            @disabled(isset($item['detail'])) {{-- @cannot('inventaris_edit_lokasi_penerimaan')
                                disabled
                            @endcannot --}}>
                            <option value="">Pilih Lokasi</option>
                            @foreach ($lokasis as $lokasi)
                                <option value="{{ $lokasi->id }}" @if ($item['lokasi_id'] == $lokasi->id) selected @endif>
                                    {{ $lokasi->nama }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-6 py-3">
                        <select wire:model="list.{{ $index }}.bagian_id"
                            class="bg-gray-50 border border-gray-300 {{ !$item['editable'] || empty($item['lokasi_id']) || Auth::user()->lokasi_id !== $item['lokasi_id'] ? 'cursor-not-allowed' : '' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            @disabled(!$item['editable'] || empty($item['lokasi_id']) || Auth::user()->lokasi_id !== $item['lokasi_id'])
                            @cannot('inventaris_edit_lokasi_penerimaan')
                                disabled
                            @endcannot>
                            <option value="">Pilih Bagian</option>
                            @foreach ($item['bagians'] as $bagian)
                                <option value="{{ $bagian->id }}" @if ($item['bagian_id'] == $bagian->id) selected @endif>
                                    {{ $bagian->nama }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="px-6 py-3">
                        <select wire:model="list.{{ $index }}.posisi_id"
                            class="bg-gray-50 border border-gray-300 {{ !$item['editable'] || empty($item['bagian_id']) || Auth::user()->lokasi_id !== $item['bagian_id'] ? 'cursor-not-allowed' : '' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            @disabled(!$item['editable'] || empty($item['bagian_id']) || Auth::user()->lokasi_id !== $item['lokasi_id'])>
                            <option value="">Pilih Posisi</option>
                            @foreach ($item['posisis'] as $posisi)
                                <option value="{{ $posisi->id }}" @if ($item['posisi_id'] == $posisi->id) selected @endif>
                                    {{ $posisi->nama }}
                                </option>
                            @endforeach
                        </select>
                    </td>
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
                            &nbsp;

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
                                <input type="number" {{-- wire:model.fill="list.{{ $index }}.jumlah" --}} value="{{ $item['jumlah_diterima'] }}"
                                    wire:model="list.{{ $index }}.jumlah_diterima"
                                    @cannot('inventaris_edit_jumlah_diterima')
                            disabled
                            @endcannot
                                    class="bg-gray-50 border {{ $showDokumen === 1 ? 'cursor-not-allowed' : '' }} border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
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
                                <!-- Check if the photo exists -->
                                @if ($pengiriman)
                                    @if (is_null($pengiriman->status))
                                        <!-- Pengiriman ada dan statusnya null -->
                                        @can('inventaris_unggah_foto_barang_datang')
                                            <!-- Check if the item location matches the user's location -->
                                            <!-- With permission and location matching -->
                                            <div class="relative inline-block">
                                                @if (is_string($item['bukti']))
                                                    <a href="{{ asset('storage/buktiPengiriman/' . $item['bukti']) }}"
                                                        target="_blank">
                                                        <img src="{{ asset('storage/buktiPengiriman/' . $item['bukti']) }}"
                                                            alt="Bukti" class="w-16 h-16 rounded-md">
                                                    </a>
                                                @elseif (is_object($item['bukti']) && method_exists($item['bukti'], 'temporaryUrl'))
                                                    <a href="{{ $item['bukti']->temporaryUrl() }}" target="_blank">
                                                        <img src="{{ $item['bukti']->temporaryUrl() }}" alt="Bukti"
                                                            class="w-16 h-16 rounded-md">
                                                    </a>
                                                @else
                                                    <span class="text-gray-500">Bukti tidak valid</span>
                                                @endif
                                                @if ($item['lokasi_id'] == Auth::user()->lokasi_id)
                                                    <button wire:click="removePhoto({{ $index }})"
                                                        class="absolute -top-2 -right-2 w-4 h-4 bg-red-500 text-white rounded-full text-xs hover:bg-red-700">
                                                        &times;
                                                    </button>
                                                @endif
                                            </div>
                                        @else
                                            <!-- Non-penanggungjawab hanya melihat -->
                                            @if (is_string($item['bukti']))
                                                <a href="{{ asset('storage/buktiPengiriman/' . $item['bukti']) }}"
                                                    target="_blank" class="flex justify-center">
                                                    <img src="{{ asset('storage/buktiPengiriman/' . $item['bukti']) }}"
                                                        alt="Bukti" class="w-16 h-16 rounded-md text-center">
                                                </a>
                                            @elseif (is_object($item['bukti']) && method_exists($item['bukti'], 'temporaryUrl'))
                                                <a href="{{ $item['bukti']->temporaryUrl() }}" target="_blank">
                                                    <img src="{{ $item['bukti']->temporaryUrl() }}" alt="Bukti"
                                                        class="w-16 h-16 rounded-md">
                                                </a>
                                            @else
                                                <span class="text-gray-500">Bukti tidak valid</span>
                                            @endif
                                        @endcan
                                    @else
                                        <!-- Pengiriman ada tapi status bukan null, semua pengguna hanya melihat -->
                                        @if (is_string($item['bukti']))
                                            <img src="{{ asset('storage/buktiPengiriman/' . $item['bukti']) }}"
                                                alt="Bukti" class="w-16 h-16 rounded-md">
                                        @elseif (is_object($item['bukti']) && method_exists($item['bukti'], 'temporaryUrl'))
                                            <a href="{{ $item['bukti']->temporaryUrl() }}" target="_blank">
                                                <img src="{{ $item['bukti']->temporaryUrl() }}" alt="Bukti"
                                                    class="w-16 h-16 rounded-md">
                                            </a>
                                        @else
                                            <span class="text-gray-500">Bukti tidak valid</span>
                                        @endif
                                    @endif
                                @else
                                    <!-- Pengiriman ada tapi status bukan null, semua pengguna hanya melihat -->
                                    @if (is_string($item['bukti']))
                                        <img src="{{ asset('storage/buktiPengiriman/' . $item['bukti']) }}"
                                            alt="Bukti" class="w-16 h-16 rounded-md">
                                    @elseif (is_object($item['bukti']) && method_exists($item['bukti'], 'temporaryUrl'))
                                        <a href="{{ $item['bukti']->temporaryUrl() }}" target="_blank">
                                            <img src="{{ $item['bukti']->temporaryUrl() }}" alt="Bukti"
                                                class="w-16 h-16 rounded-md">
                                        </a>
                                    @else
                                        <span class="text-gray-500">Bukti tidak valid</span>
                                    @endif
                                @endif
                            @else
                                <!-- No photo uploaded, check location and permission -->
                                @can('inventaris_unggah_foto_barang_datang')
                                    @if ($item['lokasi_id'] == Auth::user()->lokasiStok->id)
                                        <!-- With permission and location matching -->
                                        <input type="file" wire:model.live="list.{{ $index }}.bukti"
                                            class="hidden" id="upload-bukti-{{ $index }}">
                                        <button type="button"
                                            onclick="document.getElementById('upload-bukti-{{ $index }}').click()"
                                            class="text-primary-700 bg-gray-200 border border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition">
                                            Unggah Foto
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
                        @if ($item['id'] === null)
                            <button wire:click="removeFromList({{ $index }})"
                                class="text-danger-900 border-danger-600 text-xl border bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                <i class="fa-solid fa-circle-xmark"></i>
                            </button>
                        @endif
                        {{-- @if ($item['id'])
                            <button wire:click="addToList"
                                class="text-primary-900 border-primary-600 text-xl border bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                <i class="fa-solid fa-circle-check"></i>
                            </button>
                        @endif --}}
                        @can('inventaris_unggah_foto_barang_datang')
                            @if (@$showDokumen)
                                <button wire:click="updatePengirimanStok({{ $index }})"
                                    class="text-success-900 border-success-600 text-xl border bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200
                                    ">
                                    <i class="fa-solid fa-circle-check"></i>
                                </button>
                                <!-- Without permission, show "Belum ada unggahan" -->
                                {{-- <span class="text-gray-500">Belum ada unggahan</span> --}}
                            @endif
                        @endcan
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="flex justify-center mt-4">
        @if (!$showDokumen && collect($list)->count() > 0)
            <button wire:click="savePengiriman"
                class="text-primary-900 bg-primary-100 border border-primary-600 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5">
                Simpan
            </button>
        @endif
    </div>
    @push('scripts')
        <script type="module">
            document.addEventListener('error', function(e) {
                let pesan = e.detail.pesan;
                feedback('Gagal!', pesan, 'error')


            });
        </script>
        <script>
            window.addEventListener('showSweetAlert', event => {
                const { message, type } = event.detail;
                Swal.fire({
                    title: type === 'success' ? 'Success' : 'Error',
                    text: message,
                    icon: type,
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endpush
</div>
