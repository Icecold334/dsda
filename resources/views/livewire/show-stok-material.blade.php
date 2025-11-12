<div class="space-y-6">
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900 uppercase">DATA STOK {{ $lokasi->nama }}</h1>
        <div class="flex items-center gap-2">
            <a href="{{ route('stok.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200 flex items-center h-10">
                Kembali
            </a>

            @if(collect($barangStok)->isNotEmpty())
            <div wire:loading wire:target='downloadExcel' class="flex items-center h-10">
                <livewire:loading />
            </div>
            <button data-tooltip-target="tooltip-excel" wire:click="downloadExcel" wire:loading.attr="disabled"
                wire:target="downloadExcel"
                class="bg-white text-blue-500 border border-blue-500 rounded-lg px-4 py-2.5 flex items-center hover:bg-blue-500 hover:text-white transition-colors disabled:opacity-50 disabled:cursor-not-allowed h-10">
                <span wire:loading.remove wire:target="downloadExcel">
                    <i class="fa-solid fa-file-excel"></i>
                </span>
                <span wire:loading wire:target="downloadExcel" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Export...
                </span>
            </button>
            <div id="tooltip-excel" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Download stok dalam format MS Excel
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            @endif

            @can('penyesuaian.create')
            <button wire:click="$set('showFormPenyesuaian', true)"
                class="text-primary-900 bg-yellow-100 hover:bg-yellow-400 transition duration-200 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 flex items-center h-10">
                Penyesuaian Barang
            </button>
            @endcan

            @can('penyesuaian.create')
            <button wire:click="$set('StokOpnamecreate', true)"
                class="text-primary-900 bg-yellow-100 hover:bg-yellow-400 transition duration-200 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 flex items-center h-10">
                Stok Opname
            </button>
            @endcan
        </div>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <x-card title="Informasi Gudang">
            <table>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $lokasi->nama }}</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{ $lokasi->alamat ?? '-' }}</td>
                </tr>
            </table>
        </x-card>
    </div>

    <div class="flex justify-end mb-4">
        <div class="relative w-full max-w-sm">
            <input type="text" wire:model.live.debounce.500ms="search"
                class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-primary-500 focus:border-primary-500"
                placeholder="Cari kode/nama/spesifikasi...">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M9 3a6 6 0 014.472 10.028l4.25 4.25a1 1 0 01-1.414 1.414l-4.25-4.25A6 6 0 119 3zM5 9a4 4 0 118 0 4 4 0 01-8 0z"
                        clip-rule="evenodd"></path>
            </div>
        </div>
    </div>

    <x-card title="Daftar Barang">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr class="bg-primary-700 text-white ">
                    <th class="px-4 py-2 text-center">Kode Barang</th>
                    <th class="px-4 py-2 text-center">Nama Barang</th>
                    <th class="px-4 py-2 text-center">Kode Merk</th>
                    <th class="px-4 py-2 text-center">Spesifikasi</th>
                    <th class="px-4 py-2 text-center">Jumlah</th>
                    <th class="px-4 py-2 text-center"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($barangStok as $item)
                <tr class="bg-gray-50 hover:bg-gray-200">
                    <td class="px-4 py-2">{{ $item['kode'] }}</td>
                    <td class="px-4 py-2">{{ $item['nama'] }}</td>
                    <td class="px-4 py-2">{{ $item['nama'] }}</td>
                    <td class="px-4 py-2">{{ $item['merk_id'] ?? '-' }}</td>
                    <td class="px-4 py-2">
                        <ul class="list-disc list-inside text-sm">
                            @foreach($item['spesifikasi'] as $spec => $info)
                            <li>{{ $spec }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="px-4 py-2 text-center">
                        <ul class="text-sm">
                            @foreach($item['spesifikasi'] as $spec => $info)
                            <li>{{ $info['jumlah'] }} {{ $item['satuan'] }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="px-4 py-2 text-center">
                        <button class="text-blue-600 hover:text-blue-900"
                            wire:click="showRiwayat({{ $item['id'] }},'{{ $item['nama'] }}')">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-gray-400">Tidak ada data stok</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </x-card>

    @if ($showFormPenyesuaian)
    <div class="fixed -inset-56 bg-black bg-opacity-50 z-[99] flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl p-6 relative">
            <button wire:click="$set('showFormPenyesuaian', false)"
                class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl font-bold">&times;</button>
            <h3 class="text-xl font-semibold mb-4">Penyesuaian Jumlah Barang</h3>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left column: Form -->
                <div class="space-y-4">
                    <div>
                        <label for="merk" class="block font-medium">Pilih Merk</label>
                        <div class="relative w-full" x-data="{ open: false, search: '' }">
                            <div @click.outside="open = false" class="relative">
                                <button type="button"
                                    class="w-full border border-gray-300 rounded-md p-2 flex justify-between items-center bg-white"
                                    @click="open = !open" :class="{ 'bg-gray-100': open }">
                                    <span>
                                        {{ $penyesuaian['merk_id']
                                        ? $merkStokSiapPenyesuaian->firstWhere('id', $penyesuaian['merk_id'])['label']
                                        : 'Pilih Merk' }}
                                    </span>
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div x-show="open" x-transition
                                    class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow">
                                    <input type="text" x-model="search" placeholder="Cari merk..."
                                        class="w-full px-3 py-2 border-b border-gray-200 focus:outline-none text-sm" />

                                    <ul class="max-h-48 overflow-y-auto text-sm">
                                        @foreach ($merkStokSiapPenyesuaian as $merk)
                                        <template
                                            x-if="{{ json_encode(Str::lower($merk['label'])) }}.includes(search.toLowerCase())">
                                            <li class="px-3 py-2 hover:bg-primary-100 cursor-pointer"
                                                @click="$wire.set('penyesuaian.merk_id', {{ $merk['id'] }}); open = false;">
                                                {{ $merk['label'] }}
                                            </li>
                                        </template>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block font-medium">Jenis Penyesuaian</label>
                        <select wire:model="penyesuaian.tipe" class="w-full border rounded p-2">
                            <option value="tambah">Penambahan</option>
                            <option value="kurang">Pengurangan</option>
                        </select>
                    </div>

                    <div class="mt-2">
                        <label class="block font-medium">Jumlah Perubahan</label>
                        <input type="number" wire:model.live.debounce.500ms="penyesuaian.jumlah"
                            class="w-full border rounded p-2" />
                        {{-- @if ($jumlahAkhir !== null)
                        <p class="text-sm text-gray-600">
                            <strong>Stok akhir:</strong> {{ $jumlahAkhir }}
                        </p>
                        @endif --}}
                    </div>

                    <div>
                        <label class="block font-medium">Deskripsi</label>
                        <textareawire:model.live.debounce.500ms="penyesuaian.deskripsi"
                            class="w-full border rounded p-2"></textarea>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button wire:click="simpanPenyesuaian"
                            class="bg-blue-600 hover:bg-blue-800 text-white px-4 py-2 rounded">Simpan</button>
                        <button wire:click="$set('showFormPenyesuaian', false)"
                            class="bg-gray-400 text-white px-4 py-2 rounded">Batal</button>
                    </div>
                </div>

                <!-- Right column: File attachments -->
                <div class="space-y-4">
                    <div>
                        <label class="block font-medium mb-2">Lampiran</label>

                        <!-- Upload button -->
                        <div wire:loading wire:target="newAttachments">
                            <div class="text-sm text-gray-500">Mengupload...</div>
                        </div>

                        <input type="file" wire:model.live.debounce.500ms="newAttachments" multiple class="hidden"
                            id="fileUpload">
                        <label for="fileUpload"
                            class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200 cursor-pointer">
                            + Unggah File
                        </label>

                        @error('newAttachments.*')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                        @error('attachments.*')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- File list -->
                    <div class="max-h-60 overflow-y-auto">
                        @if (!empty($attachments))
                        @foreach ($attachments as $index => $attachment)
                        <div class="flex items-center justify-between border-b-4 p-2 rounded my-1">
                            <span class="flex items-center space-x-3">
                                @php
                                $fileType = $attachment instanceof \Illuminate\Http\UploadedFile
                                ? $attachment->getClientOriginalExtension()
                                : pathinfo($attachment, PATHINFO_EXTENSION);
                                @endphp

                                <!-- Icon Based on File Type -->
                                <span class="text-primary-600 text-xl">
                                    @if (in_array($fileType, ['png', 'jpg', 'jpeg', 'gif']))
                                    <i class="fa-solid fa-image text-green-500"></i>
                                    @elseif($fileType == 'pdf')
                                    <i class="fa-solid fa-file-pdf text-red-500"></i>
                                    @elseif(in_array($fileType, ['doc', 'docx']))
                                    <i class="fa-solid fa-file-word text-blue-500"></i>
                                    @elseif(in_array($fileType, ['xls', 'xlsx']))
                                    <i class="fa-solid fa-file-excel text-green-700"></i>
                                    @elseif(in_array($fileType, ['ppt', 'pptx']))
                                    <i class="fa-solid fa-file-powerpoint text-orange-500"></i>
                                    @elseif(in_array($fileType, ['zip', 'rar']))
                                    <i class="fa-solid fa-file-zipper text-yellow-500"></i>
                                    @else
                                    <i class="fa-solid fa-file text-gray-500"></i>
                                    @endif
                                </span>

                                <!-- File Name with Link -->
                                <span class="text-sm">
                                    @if($attachment instanceof \Illuminate\Http\UploadedFile)
                                    <span class="text-gray-800">{{ $attachment->getClientOriginalName() }}</span>
                                    @else
                                    <a href="{{ asset('storage/lampiran-penyesuaian-stok/' . $attachment) }}"
                                        target="_blank" class="text-gray-800 hover:underline">
                                        {{ basename($attachment) }}
                                    </a>
                                    @endif
                                </span>
                            </span>

                            <!-- Remove Button -->
                            <button wire:click="removeAttachment({{ $index }})"
                                class="text-red-500 hover:text-red-700 text-lg font-bold px-2">
                                &times;
                            </button>
                        </div>
                        @endforeach
                        @else
                        <p class="text-gray-500 text-sm">Belum ada file yang diunggah</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($showModal)
    <div class="fixed -inset-40 bg-black bg-opacity-50 flex items-center justify-center  z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl p-6 relative max-h-96 overflow-y-scroll">
            <button wire:click="closeModal"
                class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl font-bold">&times;</button>
            <h3 class="text-xl font-semibold mb-4">Riwayat Stok: {{ $modalBarangNama }}</h3>

            <table class="w-full border ">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">Kode Transaksi</th>
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2">Tipe</th>
                        <th class="px-4 py-2">Jumlah</th>
                        <th class="px-4 py-2">Merk</th>
                        <th class="px-4 py-2">Tipe</th>
                        <th class="px-4 py-2">Ukuran</th>
                        <th class="px-4 py-2">Bagian</th>
                        <th class="px-4 py-2">Posisi</th>
                        <th class="px-4 py-2">Deskripsi</th>
                        <th class="px-4 py-2">User</th>
                        <th class="px-4 py-2">Lampiran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($modalRiwayat as $item)
                    <tr>
                        <td class="px-4 py-2 text-center">{{ $item['kode'] }} </td>
                        <td class="px-4 py-2 text-center">
                            {{ \Carbon\Carbon::parse($item['tanggal'])->translatedFormat('l, d F Y') }}
                        </td>
                        <td class="px-4 py-2 text-center">
                            @php
                            $bgColor = match ($item['tipe']) {
                            'Pemasukan' => 'bg-blue-600 text-white',
                            'Pengeluaran' => 'bg-gray-600 text-white',
                            'Penyesuaian' => 'bg-yellow-400 text-black',
                            'Pengajuan' => 'bg-red-400 text-white',
                            default => 'bg-slate-300 text-black',
                            };
                            @endphp
                            <span class="{{ $bgColor }} text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                                {{ $item['tipe'] }}
                            </span>
                        </td>
                        @php
                        $jumlah = $item['jumlah'];

                        if ($item['tipe'] === 'Pemasukan') {
                        $jumlah = '+' . $jumlah;
                        } elseif ($item['tipe'] === 'Pengeluaran') {
                        $jumlah = '-' . $jumlah;
                        } elseif ($item['tipe'] === 'Penyesuaian') {
                        $jumlah = $jumlah >= 0 ? '+' . $jumlah : $jumlah;
                        }

                        $textColor = str_starts_with($jumlah, '+') ? 'text-success-700' : 'text-danger-700';
                        @endphp

                        <td class="px-4 py-2 text-center font-semibold {{ $textColor }}">
                            {{ $jumlah }}
                        </td>
                        <td class="px-4 py-2">{{ $item['merk'] }} </td>
                        <td class="px-4 py-2">{{ $item['tipe_merk'] }}</td>
                        <td class="px-4 py-2">{{ $item['ukuran'] }}</td>
                        <td class="px-4 py-2">{{ $item['bagian'] }}</td>
                        <td class="px-4 py-2">{{ $item['posisi'] }}</td>
                        <td class="px-4 py-2">{{ $item['deskripsi'] ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $item['user'] ?? '-' }}</td>
                        <td class="px-4 py-2">
                            @if (!empty($item['attachments']))
                            <div class="space-y-1">
                                @foreach($item['attachments'] as $attachment)
                                <div class="flex items-center space-x-2">
                                    @php
                                    $fileType = pathinfo($attachment['file'], PATHINFO_EXTENSION);
                                    @endphp
                                    <span class="text-xs">
                                        @if (in_array($fileType, ['png', 'jpg', 'jpeg', 'gif']))
                                        <i class="fa-solid fa-image text-green-500"></i>
                                        @elseif($fileType == 'pdf')
                                        <i class="fa-solid fa-file-pdf text-red-500"></i>
                                        @elseif(in_array($fileType, ['doc', 'docx']))
                                        <i class="fa-solid fa-file-word text-blue-500"></i>
                                        @else
                                        <i class="fa-solid fa-file text-gray-500"></i>
                                        @endif
                                    </span>
                                    <a href="{{ asset('storage/' . $attachment['file']) }}" target="_blank"
                                        class="text-xs text-blue-600 hover:underline">
                                        {{ $attachment['original_name'] }}
                                    </a>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="12" class="text-center py-4 text-gray-400">Tidak ada riwayat transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif


    <!-- StokOpname Modal -->
    @if ($StokOpnamecreate)
    <div class="fixed -inset-56 bg-black bg-opacity-50 z-[99] flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl p-6 relative">
            <button wire:click="$set('StokOpnamecreate', false)"
                class="absolute top-2 right-2 text-gray-500 hover:text-red-600 text-xl font-bold">&times;
            </button>

            <h3 class="text-xl font-semibold mb-4 text-primary-900">Stok Opname {{ $lokasi->nama }}</h3>

            <div class="flex flex-col sm:flex-row justify-between items-center gap-3 mb-4">
                {{-- Tombol Download Template --}}
                <a href="{{ route('stok.template-opname') }}"
                    class="bg-blue-600 text-white px-5 py-2.5 rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                    <i class="fa-solid fa-file-arrow-down"></i>
                    <span>Download Template</span>
                </a>

                {{-- Upload file --}}
                <div class="flex items-center gap-3">
                    <label
                        class="bg-yellow-500 text-white px-5 py-2.5 rounded-lg hover:bg-yellow-600 transition cursor-pointer flex items-center gap-2">
                        <i class="fa-solid fa-upload"></i> Upload File SO
                        <input type="file" wire:model="soFile" accept=".csv,.xlsx" hidden>
                    </label>

                    @if ($uploadedFileName)
                    <div class="flex items-center gap-2 text-sm bg-gray-100 border border-gray-300 px-3 py-2 rounded">
                        <i class="fa-solid fa-file-excel text-green-600"></i>
                        <span class="text-gray-800">{{ $uploadedFileName }}</span>
                        <button wire:click="removeSoFile" class="text-red-500 hover:text-red-700 ml-2">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    @endif
                </div>

                {{-- Jalankan Penyesuaian --}}
                <button wire:click="processSO" wire:loading.attr="disabled"
                    class="bg-green-600 text-white px-5 py-2.5 rounded-lg hover:bg-green-700 transition disabled:opacity-50 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i>
                    <span wire:loading.remove wire:target="processSO">Jalankan Penyesuaian</span>
                    <span wire:loading wire:target="processSO" class="flex items-center text-sm">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </div>

            <div class="mt-4 border-t pt-4 text-sm text-gray-600">
                <p><strong>Petunjuk:</strong></p>
                <ul class="list-disc ml-5 mt-1 space-y-1">
                    <li>Klik <b>Download Template</b> untuk mendapatkan format Excel stok opname.</li>
                    <li>Isi stok aktual setiap barang di kolom <b>stok</b>.</li>
                    <li>Upload kembali file tersebut melalui tombol <b>Upload SO</b>.</li>
                    <li>Setelah itu klik <b>Jalankan Penyesuaian</b> untuk mencatat hasil opname ke sistem.</li>
                </ul>
            </div>
        </div>
    </div>
    @endif


    <script>
        document.addEventListener('toast', (c) => {
            var { type, message } = c.detail[0]

            // Livewire.on('toast', ({ title, message, type }) => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: type || 'success',
                title: message,
                // text: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            // });
        });
    </script>
</div>