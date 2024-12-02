<div>
    <table class="w-full border-3 border-separate border-spacing-y-4">
        {{ $cekApproval }}

        <br>

        {{ $pj_isapprove }}
        <thead>
            <tr class="text-white bg-primary-950 uppercase">
                <th class="py-3 px-6 text-center font-semibold rounded-l-lg w-[10%]">Barang</th>
                <th class="py-3 px-6 text-center font-semibold w-[20%]">Spesifikasi</th>
                <th class="py-3 px-6 text-center font-semibold w-[13%]">Jumlah</th>
                <th class="py-3 px-6 text-center font-semibold">Lokasi Penerimaan</th>
                <th class="py-3 px-6 text-center font-semibold">Keterangan</th>
                <th class="py-3 px-6 text-center font-semibold w-[15%]">dokumen pendukung</th>
                <th class="py-3 px-6 text-center font-semibold w-[10%]">status</th>
                <th class="py-3 px-6 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @if ($vendor_id && $jenis_id)
                @foreach ($list as $index => $item)
                    <tr class="bg-gray-50 hover:bg-gray-200">
                        <td class="px-2 py-3">
                            <input
                                class="bg-gray-50 border cursor-not-allowed border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                type="text" value="{{ $item['barang'] }}" placeholder="Barang" disabled>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex space-x-2">
                                @foreach ($item['specifications'] as $key => $value)
                                    <input
                                        class="bg-gray-50 cursor-not-allowed border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                        type="text" value="{{ $value }}" placeholder="{{ ucfirst($key) }}"
                                        disabled>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            <div class="flex">
                                <input
                                    class="bg-gray-50 border cursor-not-allowed border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                    type="number" value="{{ $item['jumlah'] }}" placeholder="Jumlah" disabled>
                                <div
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-r-lg focus:ring-blue-500 focus:border-blue-500 block max-w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                    {{ $item['satuan'] }}
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-6">
                            <textarea class="bg-gray-50 border cursor-not-allowed border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full"
                                placeholder="Lokasi Penerimaan" disabled>{{ $item['lokasi_penerimaan'] }}</textarea>
                        </td>
                        <td class="py-3 px-6">
                            <textarea class="bg-gray-50 border cursor-not-allowed border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full"
                                placeholder="Keterangan" disabled>{{ $item['keterangan'] }}</textarea>
                        </td>
                        <td class="text-center py-3 px-6">
                            <input type="file" wire:model.live="list.{{ $index }}.bukti" class="hidden"
                                id="upload-bukti-{{ $index }}">

                            @if (isset($item['bukti']))
                                <!-- Display uploaded proof preview with remove icon -->
                                <div class="relative inline-block">
                                    <a href="{{ is_string($item['bukti']) ? asset('storage/buktiTransaksi/' . $item['bukti']) : $item['bukti']->temporaryUrl() }}"
                                        download="{{ is_string($item['bukti']) ? pathinfo($item['bukti'], PATHINFO_BASENAME) : $item['bukti']->getClientOriginalName() }}">
                                        <img src="{{ is_string($item['bukti']) ? asset('storage/buktiTransaksi/' . $item['bukti']) : $item['bukti']->temporaryUrl() }}"
                                            alt="Bukti" class="w-16 h-16 rounded-md">
                                    </a>
                                    <button wire:click="removePhoto({{ $index }})"
                                        class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600  text-white rounded-full w-5 h-5 text-xs">
                                        &times;
                                    </button>
                                </div>
                            @else
                                <!-- Show upload button if no file is selected -->
                                <button type="button"
                                    onclick="document.getElementById('upload-bukti-{{ $index }}').click()"
                                    class="text-primary-700 bg-gray-200 border border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition">
                                    <i class="fa-solid fa-file-arrow-up"></i> Upload
                                </button>
                            @endif

                            @error("list.{$index}.bukti")
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </td>
                        <td class="text-center py-3 px-6">
                            @can('persetujuan')
                                {{-- Jika pengguna adalah PPK --}}
                                @if ($item['bukti'] && auth()->user()->hasRole('ppk') && $ppk_isapprove)
                                    <button onclick="confirmApproval({{ $index }}, 'ppk')"
                                        class="text-green-700 bg-green-100 border border-green-600 rounded-lg px-3 py-1.5 hover:bg-green-600 hover:text-white transition">
                                        Approve PPK
                                    </button>
                                @endif

                                {{-- Jika pengguna adalah PPTK dan sudah ada approval dari PPK --}}
                                @if ($item['bukti'] && auth()->user()->hasRole('pptk') && $pptk_isapprove && !($ppk_isapprove))
                                    <button onclick="confirmApproval({{ $index }}, 'pptk')"
                                        class="text-green-700 bg-green-100 border border-green-600 rounded-lg px-3 py-1.5 hover:bg-green-600 hover:text-white transition">
                                        Approve PPTK
                                    </button>
                                @endif

                                {{-- Jika pengguna adalah PJ dan sudah ada approval dari PPTK --}}
                                @if ($item['bukti'] && auth()->user()->hasRole('penanggungjawab') && $pj_isapprove && !$pptk_isapprove)
                                    <button onclick="confirmApproval({{ $index }}, 'pj')"
                                        class="text-green-700 bg-green-100 border border-green-600 rounded-lg px-3 py-1.5 hover:bg-green-600 hover:text-white transition">
                                        Approve PJ
                                    </button>
                                @endif

                                {{-- Status persetujuan hanya ditampilkan jika bukti belum diisi --}}
                                @if (empty($item['bukti']))
                                    <span
                                        class="{{ $item['status'] ? 'text-green-600' : ($item['status'] === 0 ? 'text-red-600' : 'text-gray-600') }}">
                                        {{ is_null($item['status']) ? 'Menunggu' : ($item['status'] ? 'Disetujui' : 'Ditolak') }}
                                    </span>
                                @endif
                            @else
                                {{-- Untuk pengguna yang tidak memiliki hak persetujuan --}}
                                <span
                                    class="{{ $item['status'] ? 'text-green-600' : ($item['status'] === 0 ? 'text-red-600' : 'text-gray-600') }}">
                                    {{ is_null($item['status']) ? (empty($item['bukti']) ? 'Menunggu' : '') : ($item['status'] ? 'Disetujui' : 'Ditolak') }}
                                </span>
                            @endcan
                        </td>

                        @push('scripts')
                            <script>
                                function confirmApproval(index) {
                                    Swal.fire({
                                        title: 'Apakah anda yakin?',
                                        text: "Anda akan melakukan tindakan pada transaksi ini.",
                                        icon: 'question',
                                        showCancelButton: true,
                                        confirmButtonColor: '#d33',
                                        cancelButtonColor: '#3085d6',
                                        showConfirmButton: false,
                                        confirmButtonText: 'Tolak',
                                        cancelButtonText: 'Setujui'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            // Jika transaksi ditolak, minta alasan
                                            Swal.fire({
                                                title: 'Alasan Penolakan',
                                                input: 'textarea',
                                                inputPlaceholder: 'Masukkan alasan penolakan...',
                                                inputAttributes: {
                                                    'aria-label': 'Masukkan alasan penolakan'
                                                },
                                                showCancelButton: true,
                                                confirmButtonText: 'Kirim Alasan',
                                                cancelButtonText: 'Batal',
                                                inputValidator: (value) => {
                                                    if (!value) {
                                                        return 'Anda harus memasukkan alasan!'
                                                    }
                                                }
                                            }).then((reasonResult) => {
                                                if (reasonResult.isConfirmed) {
                                                    // Kirim alasan penolakan ke server menggunakan @this
                                                    @this.call('disapproveTransaction', index, reasonResult.value);
                                                    Swal.fire(
                                                        'Ditolak!',
                                                        'Alasan Anda telah dicatat.',
                                                        'success'
                                                    );
                                                }
                                            });
                                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                                            // Jika transaksi disetujui
                                            @this.call('approveTransaction', index);
                                            Swal.fire(
                                                'Disetujui!',
                                                'Transaksi telah disetujui.',
                                                'success'
                                            );
                                        }
                                    });
                                }
                            </script>
                        @endpush

                        <td class="text-center py-3">
                            @if (!$item['id'])
                                <button wire:click="removeFromList({{ $index }})"
                                    class="text-danger-900 border-danger-600 text-xl border bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                                    <i class="fa-solid fa-circle-xmark"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td class="px-2 py-3">
                        <div class="flex space-x-2">
                            <input
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                type="text" wire:model.live="newBarang" wire:blur="blurBarang"
                                placeholder="Cari Barang">
                            @if (!$newBarangId)
                                <button wire:click="openBarangModal"
                                    class="px-3 py-1 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"><i
                                        class="fa-solid fa-circle-plus"></i></button>
                            @endif
                        </div>
                        @if ($barangSuggestions)
                            <ul
                                class="absolute z-10 w-96 bg-white border border-gray-300 rounded-lg mt-2 max-h-60 overflow-auto shadow-lg">
                                @foreach ($barangSuggestions as $suggestion)
                                    <li wire:click="selectBarang('{{ $suggestion['id'] }}', '{{ $suggestion['nama'] }}')"
                                        class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer">
                                        {{ $suggestion['nama'] }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex space-x-2 ">
                            @foreach (['merek' => 'Merek', 'tipe' => 'Tipe', 'ukuran' => 'Ukuran'] as $key => $label)
                                <input
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                    type="text" wire:model.live="specifications.{{ $key }}"
                                    wire:input="updateSpecification('{{ $key }}', $event.target.value)"
                                    wire:blur="blurSpecification('{{ $key }}')"
                                    placeholder="{{ $label }}">
                                @if (count($suggestions[$key]) > 0)
                                    <ul
                                        class="absolute z-10 w-96 bg-white border border-gray-300 rounded-lg mt-12 max-h-60 overflow-auto shadow-lg">
                                        @foreach ($suggestions[$key] as $suggestion)
                                            <li class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer"
                                                wire:click="selectSpecification('{{ $key }}', '{{ $suggestion }}')">
                                                {{ $suggestion }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            @endforeach
                        </div>
                    </td>
                    <td class="px-6 py-3">
                        <div class="flex">
                            <input
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600"
                                type="number" wire:model.live="newJumlah" placeholder="Jumlah">
                            <div
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-r-lg focus:ring-blue-500 focus:border-blue-500 block max-w-full p-2.5 dark:bg-gray-700 dark:border-gray-600">
                                {{ !$newBarangId ? 'Satuan' : App\Models\BarangStok::find($newBarangId)->satuanBesar->nama }}
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-6">
                        <textarea wire:model.live="newLokasiPenerimaan"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full"
                            placeholder="Lokasi Penerimaan"></textarea>
                    </td>
                    <td class="py-3 px-6">
                        <textarea wire:model.live="newKeterangan"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg p-2.5 w-full" placeholder="Keterangan"></textarea>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <input type="file" wire:model="newBukti" class="hidden" id="upload-new-bukti">
                        @if ($newBukti)
                            <!-- Display uploaded proof preview with remove icon -->
                            <div class="relative inline-block">
                                <a href="{{ $newBukti->temporaryUrl() }}"
                                    download="{{ $newBukti->getClientOriginalName() }}">
                                    <img src="{{ $newBukti->temporaryUrl() }}" alt="Bukti"
                                        class="w-16 h-16 rounded-md">
                                </a>
                                <button wire:click="removeNewPhoto"
                                    class="absolute -top-2 -right-2 bg-red-500 hover:bg-red-600  text-white rounded-full w-5 h-5 text-xs">
                                    &times;
                                </button>
                            </div>
                        @else
                            <!-- Show upload button if no file is selected -->
                            <button type="button" onclick="document.getElementById('upload-new-bukti').click()"
                                class="text-primary-700 bg-gray-200 border border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition">
                                <i class="fa-solid fa-file-arrow-up"></i> Upload
                            </button>
                        @endif
                        @error('newBukti')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </td>
                    <td class="text-center py-3">
                        <button wire:click="addToList"
                            class="text-primary-900 border-primary-600 text-xl border  {{ ($specifications['merek'] || $specifications['tipe'] || $specifications['ukuran']) && $newBarangId && $newKeterangan && $newLokasiPenerimaan ? '' : 'hidden' }} bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                            <i class="fa-solid fa-circle-check"></i>
                        </button>
                    </td>
                </tr>
            @else
                <tr class="bg-gray-50 hover:bg-gray-20">
                    <td colspan="8" class="text-center font-semibold">Lengkapi data diatas</td>
                </tr>
            @endif
        </tbody>
    </table>

    @if (true)
        @if ($vendor_id && count($list) > 0)
            {{-- @if (true) --}}
            <div class="flex justify-center"><button wire:click='saveKontrak'
                    class="text-primary-900 bg-primary-100 border border-primary-600 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Simpan</button>

                @if (
                    $dokumenCount &&
                        $nomor_kontrak &&
                        $metode_id &&
                        !collect($list)->filter(function ($item) {
                                return $item['status'] == null;
                            })->count())
                    <button wire:click='finishKontrak'
                        class="text-primary-900 bg-primary-100 border border-primary-600 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Selesaikan
                        Kontrak</button>
                @endif
            </div>
        @endif
        @if ($showBarangModal)
            {{-- @if (true) --}}
            <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-1/2 dark:bg-gray-800">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tambah Barang Baru</h2>

                    <!-- Nama Barang -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-300">Nama Barang</label>
                        <input type="text" wire:model.live="newBarangName"
                            class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Nama Barang">
                        @error('newBarangName')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Satuan Besar -->
                    <div class="mb-4 relative">
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-300">Satuan Besar</label>
                        <input type="text" wire:model.live="newBarangSatuanBesar"
                            wire:input="fetchSuggestions('satuanBesar', $event.target.value)"
                            class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Masukkan Satuan Besar">
                        @if ($suggestions['satuanBesar'])
                            <ul
                                class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg mt-1 max-h-60 overflow-auto shadow-lg">
                                @foreach ($suggestions['satuanBesar'] as $suggestion)
                                    <li wire:click="selectSuggestion('satuanBesar', '{{ $suggestion }}')"
                                        class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer">
                                        {{ $suggestion }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        @error('newBarangSatuanBesar')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Satuan Kecil -->
                    <div class="mb-4 relative">
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-300">Satuan Kecil
                            (Opsional)</label>
                        <input type="text" wire:model.live="newBarangSatuanKecil"
                            wire:input="fetchSuggestions('satuanKecil', $event.target.value)"
                            class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Masukkan Satuan Kecil">
                        @if ($suggestions['satuanKecil'])
                            <ul
                                class="absolute z-10 w-full bg-white border border-gray-300 rounded-lg mt-1 max-h-60 overflow-auto shadow-lg">
                                @foreach ($suggestions['satuanKecil'] as $suggestion)
                                    <li wire:click="selectSuggestion('satuanKecil', '{{ $suggestion }}')"
                                        class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer">
                                        {{ $suggestion }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        @error('newBarangSatuanKecil')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Jumlah Satuan Kecil dalam Satuan Besar -->
                    @if ($newBarangSatuanKecil)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-900 dark:text-gray-300">Jumlah Satuan
                                Kecil dalam
                                Satuan Besar</label>
                            <input type="number" wire:model.live="jumlahKecilDalamBesar"
                                class="block w-full px-4 py-2 text-gray-900 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Contoh: 12">
                            @error('jumlahKecilDalamBesar')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4">
                        <button wire:click="closeBarangModal"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">Batal</button>
                        <button wire:click="saveNewBarang"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Simpan</button>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>
