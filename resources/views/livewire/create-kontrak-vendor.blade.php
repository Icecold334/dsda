<div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <x-card title="Pilih Vendor">
            <div>
                <label for="vendor_id" class="block mb-1 text-sm font-medium">Pilih Vendor</label>
                <div class="relative w-full" x-data="{ open: false, search: '' }">
                    <div @click.outside="open = false" class="relative">
                        <button type="button"
                            class="w-full border border-gray-300 rounded-md p-2 flex justify-between items-center bg-white"
                            @click="open = !open" :class="{ 'bg-gray-100': open }">
                            <span>
                                {{ $vendor_id
                                ? \App\Models\Toko::find($vendor_id)?->nama ?? '-' : 'Pilih Vendor' }}
                            </span>
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-transition
                            class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-md shadow">
                            <input @readonly($isAdendum) type="text" x-model="search" placeholder="Cari vendor..."
                                class="w-full px-3 py-2 border-b border-gray-200 focus:outline-none text-sm" />

                            <ul class="max-h-48 overflow-y-auto text-sm">
                                @foreach ($vendors as $index => $vendor)
                                <template wire:key="vendor-{{ $index }}"
                                    x-if="'{{ Str::lower($vendor->nama) }}'.includes(search.toLowerCase())">
                                    <li class="px-3 py-2 hover:bg-primary-100 cursor-pointer"
                                        @click="$wire.set('vendor_id', {{ $vendor->id }}); open = false;">
                                        {{ $vendor->nama }}
                                    </li>
                                </template>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            @if ($showAddVendorForm)
            <div class="mt-4 space-y-3">
                <input @readonly($isAdendum) type="text" wire:model.live="nama"
                    class="bg-gray-50 border border-gray-300  text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Nama Vendor">
                <input @readonly($isAdendum) type="text" wire:model.live="alamat"
                    class="bg-gray-50 border border-gray-300  text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Alamat">
                <input @readonly($isAdendum) type="text" wire:model.live="kontak"
                    class="bg-gray-50 border border-gray-300  text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                    placeholder="Kontak">
                <div class="flex justify-between">
                    <button wire:click="addNewVendor"
                        class="bg-primary-600 text-white px-3 py-1 rounded">Simpan</button>
                    <button wire:click="toggleAddVendorForm" class="bg-gray-300 px-3 py-1 rounded">Batal</button>
                </div>
            </div>
            @else
            <button wire:click="toggleAddVendorForm" class="mt-2 text-sm text-primary-700">
                <i class="fa fa-plus"></i> Tambah Vendor Baru
            </button>
            @endif
        </x-card>
        <x-card title="DOKUMEN kontrak">
            <livewire:upload-surat-kontrak />
        </x-card>
        {{-- <x-card title="Dokumen Kontrak">
            <input @readonly($isAdendum) type="file" wire:model="dokumen" class="w-full">
            @error('dokumen') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
        </x-card> --}}
    </div>

    <x-card title="Detail Kontrak" class="mt-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-end gap-2">
                <div class="flex-1">
                    @if ($isAdendum)
                    <label class="block text-sm">Nomor Kontrak Lama</label>
                    <input type="text" value="{{ $nomor_kontrak }}" readonly
                        class="bg-gray-100 border border-gray-300 text-sm rounded-lg block w-full p-2.5">

                    <label class="block text-sm mt-2">Nomor Kontrak Baru</label>
                    <input type="text" wire:model.live="nomor_kontrak_baru"
                        class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-2.5"
                        placeholder="Isi nomor kontrak baru">
                    @else
                    <label class="block text-sm">Nomor Kontrak</label>
                    <input type="text" wire:model.live="nomor_kontrak"
                        class="bg-white border border-gray-300 text-sm rounded-lg block w-full p-2.5">
                    @endif
                </div>
                @if ($isAdendum)
                <div class="mb-2">
                    <button wire:click="resetAdendum" class="text-xs text-red-600 hover:underline focus:outline-none">
                        <i class="fa fa-times mr-1"></i> Batal
                    </button>
                </div>
                @endif
            </div>
            <div>
                <label class="block text-sm">Tanggal Kontrak</label>
                <input @readonly($isAdendum) type="date" wire:model.live="tanggal_kontrak"
                    class="bg-gray-50 border border-gray-300  text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
            </div>
            <div>
                <label class="block text-sm">Metode Pengadaan</label>
                <select wire:model.live="metode_id"
                    class="bg-gray-50 border border-gray-300  text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    <option value="">Pilih Metode</option>
                    @foreach ($metodes as $metode)
                    <option value="{{ $metode->id }}">{{ $metode->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm">Jenis Barang</label>
                <select wire:model.live="jenis_id"
                    class="bg-gray-50 border border-gray-300  text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                    <option value="1">Barang Umum</option>
                    <option value="2">Material</option>
                    <option value="3">Jasa</option>
                </select>
            </div>
        </div>
    </x-card>

    <x-card title="Daftar Barang" class="mt-6">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-4">
            {{-- Barang --}}
            <div class="col-span-2">
                <label class="block text-sm font-medium">Pilih Barang</label>
                <div class="relative w-full" x-data="{ open: false, search: '' }">
                    <button type="button"
                        class="w-full border border-gray-300 rounded-md p-2 flex justify-between items-center bg-white"
                        @click="open = !open">
                        <span>
                            {{ $barang_id ? \App\Models\BarangStok::find($barang_id)?->nama ?? '-' : 'Cari Barang' }}
                        </span>
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" x-transition
                        class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-md shadow">
                        <input @readonly($isAdendum) type="text" x-model="search" placeholder="Cari barang..."
                            class="w-full px-3 py-2 border-b border-gray-200 focus:outline-none text-sm" />

                        <ul class="max-h-48 overflow-y-auto text-sm">
                            @foreach ($barangs as $index => $barang)
                            <li wire:key="barang-{{ $index }}"
                                x-show="{{ json_encode(Str::lower($barang->nama)) }}.includes(search.toLowerCase())"
                                @click="$wire.set('barang_id', {{ $barang->id }}); open = false;"
                                class="px-3 py-2 hover:bg-primary-100 cursor-pointer"
                                x-text="{{ json_encode($barang->nama) }}">
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Spesifikasi --}}
            <div class="col-span-4">
                <label class="block text-sm font-medium mb-1">Spesifikasi</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                    @foreach (['nama', 'tipe', 'ukuran'] as $field)
                    <div x-data="{ open: false, search: @entangle('specifications.' . $field).live }" class="relative">
                        <input type="text" x-model="search" @focus="open = true" @click.outside="open = false"
                            class="w-full border border-gray-300 rounded-md p-2 text-sm"
                            placeholder="Ketik atau pilih {{ ucfirst($field) }}..." />

                        <ul x-show="open" x-transition
                            class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-md shadow max-h-48 overflow-y-auto text-sm">
                            @foreach ($specOptions[$field] as $value)
                            <li wire:key="{{ $field.now() }}-{{ $index }}"
                                class="px-3 py-2 hover:bg-primary-100 cursor-pointer"
                                @click="search = '{{ $value }}'; open = false;">
                                {{ $value }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Jumlah --}}
            <div class="col-span-2">
                <label class="block text-sm font-medium">Jumlah</label>
                <input type="number" wire:model.live="jumlah" min="1"
                    class="w-full p-2 border border-gray-300 rounded-md text-sm" placeholder="Jumlah">
            </div>

            <div x-data="{
                            formatted: '',
                            get raw() {
                                return @entangle('newHarga').live
                            },
                            set raw(value) {
    const strValue = String(value ?? '');
    const number = strValue.replace(/[^0-9]/g, '');
    this.formatted = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(number);
    $wire.set('newHarga', number);
},
                            init() {
                                this.raw = this.raw;
                            }
                        }">
                <label class="block text-sm font-medium">Harga Satuan</label>
                <input type="text" x-model="formatted" @input="raw = $event.target.value"
                    class="w-full p-2 border border-gray-300 rounded-md text-sm" placeholder="Rp 12.000">
            </div>

            {{-- PPN --}}
            <div class="col-span-2">
                <label class="block text-sm font-medium">PPN</label>
                <select wire:model.live="newPpn" class="w-full p-2 border border-gray-300 rounded-md text-sm">
                    <option value="0">Termasuk PPN</option>
                    <option value="11">PPN 11%</option>
                    <option value="12">PPN 12%</option>
                </select>
            </div>

            {{-- Tombol Tambah --}}
            <div class="col-span-6 flex justify-end">
                @if ($barang_id && $jumlah && $newHarga)
                <button wire:click="addToList"
                    class="mt-2 bg-primary-600 text-white px-4 py-2 rounded hover:bg-primary-700 transition">
                    <i class="fa fa-plus mr-1"></i> Tambah ke Daftar Barang
                </button>
                @else
                <span class="text-sm text-gray-500 mt-2">Lengkapi semua data sebelum menambahkan</span>
                @endif
            </div>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto mt-6">
            <table class="min-w-full text-sm border border-gray-300">
                <thead class="bg-primary-700 text-white">
                    <tr>
                        <th class="p-2 text-left">Barang</th>
                        <th class="p-2 text-left">Spesifikasi</th>
                        <th class="p-2 text-center">Jumlah</th>
                        @if ($isAdendum)
                        <th class="p-2 text-center">Jumlah Terkirim</th>
                        @endif
                        <th class="p-2 text-right">Harga</th>
                        <th class="p-2 text-center">PPN</th>
                        <th class="p-2 text-right">Total</th>
                        <th class="p-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if (count($list) > 0)
                    @foreach ($list as $index => $item)
                    @php
                    $harga = (int) str_replace('.', '', $item['harga']);
                    $subtotal = $harga * $item['jumlah'];
                    $ppn = $item['ppn'] ? ($subtotal * ((int) $item['ppn'] / 100)) : 0;
                    $total = $subtotal + $ppn;
                    @endphp
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="p-2">{{ $item['barang'] }}</td>
                        <td class="p-2">{{ collect($item['specifications'])->filter()->implode(', ') }}</td>
                        <td class="p-2 text-center">{{ $item['jumlah'] }} {{ $item['satuan'] }}</td>
                        @if ($isAdendum)
                        <td class="p-2 text-center">{{ $item['jumlah_terkirim'] }} {{ $item['satuan'] }}</td>
                        @endif
                        <td class="p-2 text-right">Rp {{ number_format($harga, 0, '', '.') }}</td>
                        <td class="p-2 text-center">{{ $item['ppn'] == 0 ? 'Termasuk PPN' : $item['ppn'].'%' }}</td>
                        <td class="p-2 text-right font-semibold">Rp {{ number_format($total, 0, '', '.') }}</td>
                        <td class="p-2 text-center">
                            @if (!empty($item['can_delete']) && $item['can_delete'])
                            <button wire:click="removeFromList({{ $index }})" class="text-red-600 hover:text-red-800">
                                <i class="fa fa-trash"></i>
                            </button>
                            @elseif (!empty($item['readonly']))
                            <span class="text-xs text-gray-800 font-semibold italic">Barang sudah selesai dikirim</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="{{ $isAdendum ? 8 : 7 }}" class="text-center p-4 text-gray-500">
                            Belum ada barang ditambahkan
                        </td>
                    </tr>
                    @endif
                    @if (count($list) > 0)
                    <tr class="bg-gray-100 font-bold">
                        <td colspan="5" class="p-2 text-right">TOTAL</td>
                        <td class="p-2 text-right">Rp {{ $nominal_kontrak }}</td>
                        <td></td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </x-card>

    <div class="flex justify-center mt-6">
        <button wire:click="saveKontrak"
            class="text-primary-900 bg-primary-100 border border-primary-600 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
            <i class="fa fa-save mr-2"></i> Simpan Kontrak
        </button>
    </div>

    <script>
        window.addEventListener('konfirmasi-adendum', event => {
        Swal.fire({
        title: 'Nomor Kontrak Sudah Ada',
        text: `Nomor kontrak ${event.detail.nomor} sudah ada. Apakah ingin membuat adendum?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Adendum',
        cancelButtonText: 'Batal'
        }).then((result) => {
        if (result.isConfirmed) {
            @this.call('prosesAdendum', event.detail.id)
            } else {
            @this.set('nomor_kontrak', '');
            }
        });
        });
    </script>
</div>