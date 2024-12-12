@php
    $typeNames = [
        'history' => 'Riwayat',
        'agenda' => 'Agenda',
        'keuangan' => 'Keuangan',
        'jurnal' => 'Jurnal',
    ];

    $dayMap = [
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
        7 => 'Minggu',
    ];
@endphp

<div>
    @if ($type === 'history')
        <div class="flex justify-end items-center bg-primary-200 rounded-lg mb-3">
            <a href="javascript:void(0)" wire:click="openModal()"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                + Tambah {{ $typeNames[$type] ?? ucfirst($type) }}
            </a>
        </div>
        <div class="grid grid-cols-1 gap-4">
            @forelse ($items as $item)
                <div wire:key="{{ $item->id }}"
                    class="w-full p-4 bg-white border hover:bg-gray-100 transition duration-200 border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <!-- Left Column: Table -->
                        <div class="w-full">
                            <table class="w-full text-sm border-collapse border-spacing-2">
                                <tbody>
                                    <tr>
                                        <td class="font-semibold w-40">Sejak Tanggal</td>
                                        <td>{{ date('j F Y', $item->tanggal) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold w-40">Penanggung Jawab</td>
                                        <td>{{ $item->person->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold w-40">Lokasi</td>
                                        <td>{{ $item->lokasi->nama ?? '---' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold w-40">Jumlah</td>
                                        <td>{{ $item->jumlah ?? '---' }} Unit</td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold w-40">Kondisi</td>
                                        <td>{{ $item->kondisi ?? '---' }}%</td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold w-40">Kelengkapan</td>
                                        <td>{{ $item->kelengkapan ?? '---' }}%</td>
                                    </tr>
                                    <tr>
                                        <td class="font-semibold w-40">Keterangan</td>
                                        <td>{{ $item->keterangan ?? '-' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Right Column: Buttons -->
                        <div>
                            <div class="flex">
                                <button
                                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"
                                    onclick="confirmRemove('Apakah Anda yakin ingin menghapus Riwayat ini?', () => @this.call('delete', {{ $item->id }}))">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <button wire:click="openModal({{ $item->id }})"
                                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="grid grid-cols-1 gap-3">
                    <div>Fitur ini berfungsi untuk mencatat perjalanan riwayat (history) saat memiliki aset. Misalnya
                        perubahan
                        lokasi, penanggung jawab / pemegang, hingga kondisi dan kelengkapannya.</div>

                    <div class="italic">
                        <span class="font-bold">GOAL:</span> Jika terjadi masalah, Anda bisa melacak keberadaan dan
                        siapa
                        yang
                        bertanggung jawab terhadap aset ini.
                    </div>
                </div>
            @endforelse
        </div>
    @elseif ($type === 'agenda')
        <div class="flex justify-end items-center bg-primary-200 rounded-lg mb-3">
            <a href="javascript:void(0)" wire:click="openModal()"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                + Tambah {{ $typeNames[$type] ?? ucfirst($type) }}
            </a>
        </div>
        <div class="grid grid-cols-1 gap-4">
            @forelse ($items as $item)
                <div wire:key="{{ $item->id }}"
                    class="w-full p-4 bg-white border hover:bg-gray-100 transition duration-200 border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <!-- Left Column: Table -->
                        <div>
                            @if ($item->tipe === 'mingguan')
                                <div class="text-sm font-semibold text-gray-500">Mingguan</div>
                                <div class="text-lg font-bold text-primary-700">
                                    Setiap Hari {{ $dayMap[$item->hari] ?? 'Tidak Diketahui' }}
                                </div>
                            @elseif ($item->tipe === 'bulanan')
                                <div class="text-sm font-semibold text-gray-500">Bulanan</div>
                                <div class="text-lg font-bold text-primary-700">
                                    Setiap Tanggal {{ $item->hari }}
                                </div>
                            @elseif ($item->tipe === 'tahunan')
                                <div class="text-sm font-semibold text-gray-500">Tahunan</div>
                                <div class="text-lg font-bold text-primary-700">
                                    Setiap {{ date('j F', $item->tanggal) }}
                                </div>
                            @elseif ($item->tipe === 'tanggal_tertentu')
                                <div class="text-sm font-semibold text-gray-500">Tanggal</div>
                                <div class="text-lg font-bold text-primary-700">
                                    {{ date('j F Y', $item->tanggal) }}
                                </div>
                            @else
                                <div class="text-sm font-semibold text-gray-500">Tipe Tidak Diketahui</div>
                            @endif
                            <div class="text-sm text-gray-600">{{ $item->keterangan }}</div>
                        </div>

                        <!-- Right Column: Buttons -->
                        <div>
                            <div class="flex">
                                <button
                                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"
                                    onclick="confirmRemove('Apakah Anda yakin ingin menghapus Agenda ini?', () => @this.call('delete', {{ $item->id }}))">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <button wire:click="openModal({{ $item->id }})"
                                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="grid grid-cols-1 gap-3">
                    <div>Fitur ini berfungsi untuk mencatat agenda atau kalender aset, misalnya jadwal servis atau
                        perawatan, tanggal saat sparepart harus diganti, jadwal pajak, dan sebagainya. Anda bisa melihat
                        jadwal aset untuk seminggu ke depan pada Home.
                    </div>

                    <div class="italic">
                        <span class="font-bold">GOAL:</span> Sebagai pengingat kapan aset harus dirawat dan harus
                        diambil
                        tindakan.
                    </div>
                </div>
            @endforelse
        </div>
    @elseif ($type === 'keuangan')
        <div class="flex justify-end items-center bg-primary-200 rounded-lg mb-3">
            <a href="javascript:void(0)" wire:click="openModal()"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                + Tambah {{ $typeNames[$type] ?? ucfirst($type) }}</a>
        </div>
        <div class="grid grid-cols-1 gap-4">
            @forelse ($items as $item)
                <div wire:key="{{ $item->id }}"
                    class="w-full p-4 bg-white border hover:bg-gray-100 transition duration-200 border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-3">
                            <!-- Icon -->
                            <div>
                                <div
                                    class="flex items-center justify-center w-8 h-8 rounded-full 
                            {{ $item->tipe === 'out' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                    {!! $item->tipe === 'out'
                                        ? '<i class="fa-solid fa-arrow-right-from-bracket"></i>'
                                        : '<i class="fa-solid fa-arrow-right-to-bracket"></i>' !!}
                                </div>
                            </div>
                            <!-- Transaction Details -->
                            <div>
                                <div class="text-sm font-semibold text-gray-800">
                                    {{ date('d M Y', $item->tanggal) }}
                                </div>
                                <div class="text-sm text-gray-600">{{ $item->keterangan }}</div>
                            </div>
                        </div>

                        <!-- Right Column: Buttons -->
                        <div>
                            <div class="flex">
                                <button
                                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"
                                    onclick="confirmRemove('Apakah Anda yakin ingin menghapus Keuangan ini?', () => @this.call('delete', {{ $item->id }}))">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <button wire:click="openModal({{ $item->id }})"
                                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="grid grid-cols-1 gap-3">
                    <div>Fitur ini berfungsi untuk mencatat transaksi pengeluaran dan pemasukan yang berhubungan dengan
                        aset
                        ini, misalnya biaya servis, pajak, penggantian spare-part, biaya perawatan, dan sebagainya.
                    </div>

                    <div class="italic">
                        <span class="font-bold">GOAL:</span> Anda jadi tahu berapa total biaya yang sudah Anda keluarkan
                        atau dapatkan sebagai dampak kepemilikan aset ini.
                    </div>
                </div>
            @endforelse
            @if ($items->isNotEmpty())
                <!-- Summary Section -->
                <div
                    class="mt-6 p-4 bg-white border border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
                    <table class="w-full text-sm">
                        <tbody>
                            <tr class="border-b">
                                <td class="font-semibold text-gray-800">Total Pengeluaran</td>
                                <td class="text-right text-gray-900">Rp
                                    {{ number_format($totalPengeluaran, 2, ',', '.') }}
                                </td>
                            </tr>
                            <tr class="border-b">
                                <td class="font-semibold text-gray-800">Total Pemasukan</td>
                                <td class="text-right text-gray-900">Rp
                                    {{ number_format($totalPemasukan, 2, ',', '.') }}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold text-gray-800">Selisih</td>
                                <td class="text-right font-bold text-gray-900">Rp
                                    {{ number_format($totalPemasukan - $totalPengeluaran, 2, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    @elseif ($type === 'jurnal')
        <div class="flex justify-end items-center bg-primary-200 rounded-lg mb-3">
            <a href="javascript:void(0)" wire:click="openModal()"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                + Tambah {{ $typeNames[$type] ?? ucfirst($type) }}</a>
        </div>
        <div class="grid grid-cols-1 gap-4">
            @forelse ($items as $item)
                <div wire:key="{{ $item->id }}"
                    class="w-full p-4 bg-white border hover:bg-gray-100 transition duration-200 border-gray-200 rounded-lg shadow-lg dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex justify-between items-center">
                        <!-- Left Column: Table -->
                        <div>
                            <div class="text-lg font-bold text-primary-700">{{ date('j F Y', (int) $item->tanggal) }}
                            </div>
                            <div class="text-sm text-gray-600">{{ $item->keterangan }}</div>
                        </div>

                        <!-- Right Column: Buttons -->
                        <div>
                            <div class="flex">
                                <button
                                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"
                                    onclick="confirmRemove('Apakah Anda yakin ingin menghapus Jurnal ini?', () => @this.call('delete', {{ $item->id }}))">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                                <button wire:click="openModal({{ $item->id }})"
                                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="grid grid-cols-1 gap-3">
                    <div>Fitur ini berfungsi sebagai semacam buku harian untuk mencatat berbagai kejadian yang menyertai
                        aset, misalnya saat ada perbaikan, kecelakaan, atau hal-hal lain yang sekiranya perlu direkam.
                    </div>

                    <div class="italic">
                        <span class="font-bold">GOAL:</span> Anda memiliki catatan kapan dan apa saja kejadian yang
                        sudah dialami aset ini.
                    </div>
                </div>
            @endforelse
        </div>
    @endif
    <div>
        <!-- Modal -->
        @if ($isModalOpen)
            <div id="modal" class="fixed inset-0 z-50 overflow-y-auto bg-gray-800 bg-opacity-50">
                <div class="flex items-center justify-center min-h-screen">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
                        <h2 class="text-lg font-semibold mb-4">{{ $modalId ? 'Edit' : 'Tambah' }}
                            {{ $typeNames[$type] ?? ucfirst($type) }}
                        </h2>

                        <form wire:submit.prevent="save">
                            @if ($type === 'history')
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1">Sejak Tanggal *</label>
                                    <input type="date" wire:model.live="modalData.tanggal"
                                        class="w-full border rounded-lg px-3 py-2">
                                    @error('modalData.tanggal')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1">Penanggung Jawab *</label>
                                    {{-- <input type="text" id="person" wire:model.live="person"
                                        wire:focus="focusPerson"
                                        class="w-full border rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                        placeholder="Masukkan Penanggung Jawab" wire:blur="hideSuggestionsPerson"
                                        required>
                                    @if ($showSuggestionsPerson)
                                        <ul
                                            class="absolute z-20 w-96 bg-white border border-gray-300 rounded mt-1 max-h-60 overflow-auto">
                                            @foreach ($suggestionsPerson as $suggestionPerson)
                                                <li wire:click="selectSuggestionPerson({{ $suggestionPerson['id'] }}, '{{ $suggestionPerson['nama'] }}')"
                                                    class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer transition duration-200">
                                                    {{ $suggestionPerson['nama'] }}
                                                </li>
                                            @endforeach

                                        </ul>
                                    @endif --}}
                                    <input type="text" wire:model.live="person"
                                        wire:input="fetchSuggestions('person', $event.target.value)"
                                        class="w-full border rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                        placeholder="Masukkan Penanggung Jawab" wire:blur="hideSuggestions('person')"
                                        required>
                                    @if ($suggestions['person'])
                                        <ul
                                            class="absolute z-20 w-96 bg-white border border-gray-300 rounded mt-1 max-h-60 overflow-auto">
                                            @foreach ($suggestions['person'] as $suggestion)
                                                <li wire:click="selectSuggestion('person', '{{ $suggestion }}')"
                                                    class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer transition duration-200">
                                                    {{ $suggestion }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    @error('person')
                                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1">Lokasi *</label>
                                    {{-- <input type="text" id="lokasi" wire:model.live="lokasi"
                                        wire:focus="focusLokasi"
                                        class="w-full border rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                        placeholder="Masukkan Lokasi" wire:blur="hideSuggestionsLokasi" required>
                                    @if ($showSuggestionsLokasi)
                                        <ul
                                            class="absolute z-20 w-96 bg-white border border-gray-300 rounded mt-1 max-h-60 overflow-auto">
                                            @foreach ($suggestionsLokasi as $suggestionLokasi)
                                                <li wire:click="selectSuggestionLokasi({{ $suggestionLokasi['id'] }}, '{{ $suggestionLokasi['nama'] }}')"
                                                    class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer transition duration-200">
                                                    {{ $suggestionLokasi['nama'] }}
                                                </li>
                                            @endforeach

                                        </ul>
                                    @endif --}}
                                    <input type="text" wire:model.live="lokasi"
                                        wire:input="fetchSuggestions('lokasi', $event.target.value)"
                                        class="w-full border rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500 block p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                        placeholder="Masukkan Lokasi" wire:blur="hideSuggestions('lokasi')" required>
                                    @if ($suggestions['lokasi'])
                                        <ul
                                            class="absolute z-20 w-96 bg-white border border-gray-300 rounded mt-1 max-h-60 overflow-auto">
                                            @foreach ($suggestions['lokasi'] as $suggestion)
                                                <li wire:click="selectSuggestion('lokasi', '{{ $suggestion }}')"
                                                    class="px-4 py-2 hover:bg-blue-500 hover:text-white cursor-pointer transition duration-200">
                                                    {{ $suggestion }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    @error('lokasi')
                                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4 grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Jumlah *</label>
                                        <div class="flex items-center">
                                            <input type="number" wire:model.live="modalData.jumlah"
                                                class="border rounded-lg px-3 py-2 w-full">
                                            <span class="ml-2">Unit</span>
                                        </div>
                                        @error('modalData.jumlah')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Kondisi (%) *</label>
                                        <input type="number" wire:model.live="modalData.kondisi"
                                            class="border rounded-lg px-3 py-2 w-full">
                                        @error('modalData.kondisi')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium mb-1">Kelengkapan (%) *</label>
                                        <input type="number" wire:model.live="modalData.kelengkapan"
                                            class="border rounded-lg px-3 py-2 w-full">
                                        @error('modalData.kelengkapan')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium mb-1">Keterangan *</label>
                                    <input type="text" wire:model.live="modalData.keterangan"
                                        class="w-full border rounded-lg px-3 py-2">
                                    @error('modalData.keterangan')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            @elseif ($type === 'agenda')
                                <div class="mb-4">
                                    <label class="block text-sm font-medium">Tipe *</label>
                                    <select wire:model.live="modalData.tipe"
                                        class="w-full border rounded-lg px-3 py-2">
                                        <option value="">Pilih Tipe</option>
                                        <option value="mingguan">Berkala: Mingguan</option>
                                        <option value="bulanan">Berkala: Bulanan</option>
                                        <option value="tahunan">Berkala: Tahunan</option>
                                        <option value="tanggal_tertentu">Tanggal Tertentu</option>
                                    </select>
                                    @error('modalData.tipe')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Input Dinamis Berdasarkan Tipe -->
                                @if ($isMingguan)
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium">Setiap Hari *</label>
                                        <select wire:model.live="modalData.hari"
                                            class="w-full border rounded-lg px-3 py-2">
                                            <option value="">Pilih Hari</option>
                                            <option value="1">Senin</option>
                                            <option value="2">Selasa</option>
                                            <option value="3">Rabu</option>
                                            <option value="4">Kamis</option>
                                            <option value="5">Jumat</option>
                                            <option value="6">Sabtu</option>
                                            <option value="7">Minggu</option>
                                        </select>
                                        @error('modalData.hari')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif
                                @if ($isBulanan)
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium">Setiap Tanggal *</label>
                                        <select wire:model.live="modalData.hari"
                                            class="w-full border rounded-lg px-3 py-2">
                                            <option value="">Pilih Tanggal</option>
                                            @for ($i = 1; $i <= $maxDays; $i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                        @error('modalData.hari')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif

                                @if ($isTahunan)
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium">Setiap Tanggal *</label>
                                        <div class="flex space-x-2">
                                            <select wire:model.live="modalData.bulan"
                                                class="w-full border rounded-lg px-3 py-2">
                                                <option value="">Pilih Bulan</option>
                                                @for ($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}">
                                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                                    </option>
                                                @endfor
                                            </select>
                                            @error('modalData.bulan')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                            <select wire:model.live="modalData.hari"
                                                class="w-full border rounded-lg px-3 py-2">
                                                <option value="">Pilih Tanggal</option>
                                                @for ($i = 1; $i <= $maxDays; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                            @error('modalData.hari')
                                                <span class="text-red-500 text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @endif

                                @if ($isTanggalTertentu)
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium">Pada Tanggal *</label>
                                        <input type="date" wire:model.live="modalData.tanggal"
                                            class="w-full border rounded-lg px-3 py-2">
                                        @error('modalData.tanggal')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif

                                <!-- Agenda / Aktivitas -->
                                <div class="mb-4">
                                    <label class="block text-sm font-medium">Agenda / Aktivitas *</label>
                                    <textarea wire:model.live="modalData.keterangan" rows="3" class="w-full border rounded-lg px-3 py-2"
                                        placeholder="Deskripsi atau keterangan agenda ini"></textarea>
                                    @error('modalData.keterangan')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            @elseif ($type == 'keuangan')
                                <div class="mb-4">
                                    <label class="block text-sm font-medium">Tipe *</label>
                                    <select wire:model="modalData.tipe" class="w-full border rounded-lg px-3 py-2">
                                        <option value="">Pilih Tipe</option>
                                        <option value="in">Pemasukan</option>
                                        <option value="out">Pengeluaran</option>
                                    </select>
                                    @error('modalData.tipe')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium">Tanggal *</label>
                                    <input type="date" wire:model.live="modalData.tanggal"
                                        class="w-full border rounded-lg px-3 py-2">
                                    @error('modalData.tanggal')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium">Nominal (Rp) *</label>
                                    <input type="text" wire:model.live="modalData.nominal"
                                        class="w-full border rounded-lg px-3 py-2 text-right" placeholder="0,00">
                                    @error('modalData.nominal')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium">Keterangan</label>
                                    <textarea wire:model.live="modalData.keterangan" rows="3" class="w-full border rounded-lg px-3 py-2"
                                        placeholder="Opsional, masukkan keterangan transaksi"></textarea>
                                    @error('modalData.keterangan')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            @elseif ($type == 'jurnal')
                                <div class="mb-4">
                                    <label class="block text-sm font-medium">Tanggal *</label>
                                    <input type="date" wire:model.live="modalData.tanggal"
                                        class="w-full border rounded-lg px-3 py-2">
                                    @error('modalData.tanggal')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium">Kejadian *</label>
                                    <textarea wire:model.live="modalData.keterangan" rows="3" class="w-full border rounded-lg px-3 py-2"
                                        placeholder="Catat kejadian penting yang terjadi..."></textarea>
                                    @error('modalData.keterangan')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                            <div class="flex justify-end">
                                <button type="button" wire:click="closeModal"
                                    class="bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Batal</button>
                                <button type="submit"
                                    class="bg-primary-600 text-white px-4 py-2 rounded-lg">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>


</div>
