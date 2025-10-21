<div>
    <div wire:loading wire:target='downloadExcel'>
        <livewire:loading>
    </div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900 ">Daftar {{ $RKB }}
            @if (auth()->user()->unitKerja)
                {{ $sudin }}
            @endif
        </h1>

        <div class="flex items-center gap-4">
            <div class="flex gap-4">
                <input type="text" wire:model.live="search" class="border rounded-lg px-4 py-2 w-40"
                    placeholder="Cari Jenis Pekerjaan" />
                <select wire:model.live="status" class="border rounded-lg px-4 py-2 w-40">
                    <option value="">Semua Status</option>
                    <option value="diproses">diproses</option>
                    <option value="ditolak">ditolak</option>
                    <option value="disetujui">disetujui</option>
                </select>
                <select wire:model.live="tahun" class="border rounded-lg px-3 py-2 w-32">
                    <option value="">Semua Tahun</option>
                    @foreach($daftarTahun as $th)
                        <option value="{{ $th }}">{{ $th }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                @can('rab.create')
                    <a href="{{ route('rab.create') }}"
                        class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200 whitespace-nowrap flex-shrink-0">
                        + Tambah {{ $Rkb }}
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <table class="w-full border-3 border-separate border-spacing-y-4 ">
        <thead>
            <tr class="text-white uppercase">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Jenis Pekerjaan</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">tahun Anggaran </th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">lokasi</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">tanggal pelaksanaan</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">status</th>
                <th class="py-3 px-6 bg-primary-950 text-center w-[8%] font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rabs as $rab)
                <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                    <td class="py-3 px-6"></td>
                    <td class="py-3 px-6 font-semibold">
                        <div>{{ $rab->jenis_pekerjaan }}</div>
                    </td>
                    <td class="py-3 px-6 font-semibold text-center">
                        <div>{{ $rab->created_at->format('Y') }}</div>
                    </td>
                    <td class="py-3 px-6 font-semibold">
                        <div>{{ $rab->lokasi }}</div>
                    </td>
                    <td class="py-3 px-6 font-semibold text-center">
                        <div>{{ $rab->selesai->format('d F Y') }} - {{ $rab->mulai->format('d F Y') }}</div>
                    </td>
                    <td class="py-3 px-6 font-semibold text-center">
                        <span
                            class="bg-{{ $rab->status_warna }}-600 text-{{ $rab->status_warna }}-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                            {{ $rab->status_teks }}
                        </span>
                    </td>
                    <td class="py-3 px-6">
                        <div class="flex gap-2">
                            <!-- Tombol Lihat RAB -->
                            <a href="{{ route('rab.show', ['rab' => $rab->id]) }}"
                                class="text-primary-950 hover:text-white hover:bg-primary-600 px-3 py-2 rounded border border-primary-600 transition duration-200"
                                data-tooltip-target="tooltip-view-{{ $rab->id }}">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <div id="tooltip-view-{{ $rab->id }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Lihat RAB
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>

                            <!-- Tombol Edit RAB -->
                            @php
                                $user = auth()->user();
                                $canEditRab = $user->hasRole('superadmin') || $user->unit_id === null ||
                                    (($rab->user->unit_id === $user->unit_id ||
                                        $rab->user->unitKerja->parent_id === $user->unit_id) &&
                                        is_null($rab->status));
                            @endphp

                            @if($canEditRab)
                                <a href="{{ route('rab.edit', ['rab' => $rab->id]) }}"
                                    class="text-yellow-600 hover:text-white hover:bg-yellow-600 px-3 py-2 rounded border border-yellow-600 transition duration-200"
                                    data-tooltip-target="tooltip-edit-{{ $rab->id }}">
                                    <i class="fa-solid fa-edit"></i>
                                </a>
                                <div id="tooltip-edit-{{ $rab->id }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                    Edit RAB
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            @endif

                            <!-- Tombol History -->
                            <button wire:click="showHistory({{ $rab->id }})"
                                class="text-green-600 hover:text-white hover:bg-green-600 px-3 py-2 rounded border border-green-600 transition duration-200"
                                data-tooltip-target="tooltip-history-{{ $rab->id }}">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                            </button>
                            <div id="tooltip-history-{{ $rab->id }}" role="tooltip"
                                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                Lihat Riwayat Permintaan
                                <div class="tooltip-arrow" data-popper-arrow></div>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data {{ Str::lower($RKB) }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $rabs->onEachSide(1)->links() }}

    {{-- Modal untuk menampilkan riwayat permintaan material --}}
    @if($showHistoryModal)
        {{-- Overlay background modal --}}
        <div class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4"
            wire:click="closeHistoryModal">

            {{-- Container utama modal --}}
            <div class="relative w-full max-w-6xl bg-white rounded-xl shadow-2xl max-h-[90vh] flex flex-col"
                wire:click.stop>

                {{-- Header Modal --}}
                <div class="flex justify-between items-center p-6 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">
                            Riwayat Permintaan Material
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Daftar semua permintaan material untuk RAB ini</p>
                    </div>
                    <button wire:click="closeHistoryModal"
                        class="text-gray-400 hover:text-gray-600 hover:bg-gray-200 p-2 rounded-lg transition duration-200">
                        <i class="fa-solid fa-times text-xl"></i>
                    </button>
                </div>

                {{-- Content Area --}}
                <div class="flex-1 overflow-hidden flex flex-col">
                    {{-- Search Section --}}
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex justify-between items-center">
                            <div class="relative max-w-md">
                                <input type="text" wire:model.live="searchSpb" placeholder="Cari berdasarkan nomor SPB..."
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition duration-200">

                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                                    <i class="fa-solid fa-search text-gray-400"></i>
                                </div>
                            </div>
                            <div class="text-sm text-gray-600">
                                <i class="fa-solid fa-info-circle mr-1"></i>
                                Menampilkan permintaan dengan status: Dikirim & Selesai
                            </div>
                        </div>
                    </div>

                    {{-- Table Section --}}
                    <div class="flex-1 overflow-auto p-6">
                        @if(count($historyData) > 0)
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="w-full">
                                        <thead>
                                            <tr class="bg-primary-50 border-b border-primary-100">
                                                <th class="text-left p-4 font-semibold text-primary-900">No. Nota Dinas</th>
                                                <th class="text-left p-4 font-semibold text-primary-900">Tanggal</th>
                                                <th class="text-left p-4 font-semibold text-primary-900">Pemohon</th>
                                                <th class="text-left p-4 font-semibold text-primary-900">Kecamatan</th>
                                                <th class="text-center p-4 font-semibold text-primary-900">Total Item</th>
                                                <th class="text-center p-4 font-semibold text-primary-900">Status</th>
                                                <th class="text-center p-4 font-semibold text-primary-900">Aksi</th>
                                            </tr>
                                        </thead>

                                        <tbody class="divide-y divide-gray-100">
                                            @foreach($historyData as $item)
                                                <tr class="hover:bg-gray-50 transition duration-200">
                                                    <td class="p-4">
                                                        <div class="font-semibold text-primary-900">
                                                            {{ $item['nodin'] }}
                                                        </div>
                                                    </td>

                                                    <td class="p-4">
                                                        <div class="text-sm text-gray-700">
                                                            {{ $item['tanggal'] }}
                                                        </div>
                                                    </td>

                                                    <td class="p-4">
                                                        <div class="font-medium text-gray-900">
                                                            {{ $item['pemohon'] }}
                                                        </div>
                                                    </td>

                                                    <td class="p-4">
                                                        <div class="text-sm text-gray-700">
                                                            {{ $item['kecamatan'] }}
                                                        </div>
                                                    </td>

                                                    <td class="p-4 text-center">
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            {{ $item['total_items'] }} item
                                                        </span>
                                                    </td>

                                                    <td class="p-4 text-center">
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-{{ $item['status_color'] }}-800 text-white">
                                                            {{ $item['status'] }}
                                                        </span>
                                                    </td>

                                                    <td class="p-4 text-center">
                                                        <a href="{{ route('showPermintaan', ['tipe' => 'material', 'id' => $item['id']]) }}"
                                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 hover:text-primary-700 transition duration-200">
                                                            <i class="fa-solid fa-eye mr-2"></i>
                                                            Detail
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        @else
                            <div class="text-center py-16">
                                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                                    <i class="fa-solid fa-inbox text-3xl text-gray-400"></i>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 mb-2">
                                    @if($searchSpb)
                                        Tidak ada data ditemukan
                                    @else
                                        Belum ada permintaan material
                                    @endif
                                </h4>
                                <p class="text-gray-500 max-w-sm mx-auto">
                                    @if($searchSpb)
                                        Tidak ada permintaan yang ditemukan dengan kata kunci "{{ $searchSpb }}"
                                    @else
                                        Belum ada permintaan material dengan status dikirim atau selesai untuk RAB ini.
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Footer Modal --}}
                <div class="flex justify-end items-center p-6 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                    <button wire:click="closeHistoryModal"
                        class="px-6 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 font-medium">
                        <i class="fa-solid fa-times mr-2"></i>
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>