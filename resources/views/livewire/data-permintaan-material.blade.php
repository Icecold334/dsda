<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900 ">
            {{ $tipe === null
            ? 'Pelayanan Umum'
            : ($tipe == 'spare-part'
            ? 'Permintaan Spare Part'
            : 'Permintaan
            Material') }}
            @if (auth()->user()->unitKerja)
            {{-- {{ auth()->user()->unitKerja->parent ? auth()->user()->unitKerja->parent->nama :
            auth()->user()->unitKerja->nama }} --}}
            {{-- {{ auth()->user()->unitKerja->nama }} --}}
            @endif
        </h1>
        <div>
            <div class="flex gap-4">
                <!-- Search Input -->
                <input type="date" wire:model.live="tanggal" class="border rounded-lg px-4 py-2 w-40" />

                <input type="text" wire:model.live="search" class="border rounded-lg px-4 py-2 w-40"
                    placeholder="Cari Nomor SPB" />

                <select wire:model.live="status" class="border rounded-lg px-4 py-2 w-40">
                    <option value="">Semua Status</option>
                    <option value="diproses">diproses</option>
                    <option value="ditolak">ditolak</option>
                    {{-- <option value="dibatalkan">dibatalkan</option> --}}
                    <option value="disetujui">disetujui</option>
                    {{-- <option value="siap diambil">siap diambil</option> --}}
                    <option value="sedang dikirim">sedang dikirim</option>
                    <option value="selesai">selesai</option>
                </select>
                <div wire:loading wire:target='downloadExcel'>
                    <livewire:loading>
                </div>
                @if ($permintaans->count() && 0)
                @can('pelayanan_xls')
                <button data-tooltip-target="tooltip-excel" wire:click="downloadExcel"
                    class="bg-white text-blue-500 h-10 border border-blue-500 rounded-lg px-4 py-2 flex items-center hover:bg-blue-500 hover:text-white transition-colors"><i
                        class="fa-solid fa-file-excel"></i></button>
                <div id="tooltip-excel" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Unduh dalam format excel
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                @endcan
                @endif
                <div wire:click='tambahPermintaan'
                    class="text-primary-900 cursor-pointer bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 transition duration-200">
                    + Tambah Permintaan
                </div>
            </div>

        </div>

    </div>
    <table class="w-full border-3 border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">NOMOR SPB</span>
                </th>
                <th class=" py-3 px-6 bg-primary-950 text-center font-semibold {{ $isSeribu ?'hidden':'' }}">JENIS
                    PEKERJAAN
                </th>
                {{-- <th class="py-3 px-6 bg-primary-950 text-center font-semibold">NOMOR RAB</th> --}}
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold {{ $isSeribu ?'hidden':'' }}">LOKASI</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold ">
                    TANGGAL PEKERJAAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold ">
                    TAHUN PERMINTAAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">STATUS</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($permintaans as $permintaan)
            <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                <td class="px-6 py-3"></td>
                <td class="px-6 py-3 font-semibold">
                    <div>
                        {{ Str::ucfirst($permintaan['kode']) }}
                    </div>
                    <div class="text-gray-500 text-sm {{ !$isSeribu ?'hidden':'' }}">
                        {{-- {{ $permintaan['kategori']?->nama }} --}}
                        {{ $permintaan['nomor_rab'] }}
                    </div>
                </td>
                <td class="px-6 py-3 font-semibold {{ $isSeribu ?'hidden':'' }} ">
                    <div>
                        {{ Str::ucfirst($permintaan['jenis_pekerjaan']) }}
                    </div>
                    <div class="text-gray-500 text-sm">
                        {{-- {{ $permintaan['kategori']?->nama }} --}}
                        {{ $permintaan['nomor_rab'] }}
                    </div>
                </td>
                <td class="px-6 py-3 font-semibold text-center {{ $isSeribu ?'hidden':'' }}">{{ $permintaan['lokasi'] }}
                </td>
                <td class="px-6 py-3 font-semibold text-center">{{ date('j F Y', $permintaan['tanggal']) }}</td>
                <td class="px-6 py-3 font-semibold text-center">{{$permintaan['created_at'] }}</td>
                <td class="px-6 py-3 font-semibold {{ $tipe == 'material' ? 'hidden' : '' }}">
                    <div class="text-gray-600 text-sm">
                        {{ $permintaan['sub_unit']?->nama ?? $permintaan['unit']?->nama }}
                    </div>
                </td>

                <td class="py-3 px-6">
                    <p class="font-semibold text-gray-800 text-center">

                        <span
                            class="bg-{{ $permintaan['status_warna'] }}-600 text-{{ $permintaan['status_warna'] }}-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                            {{ $permintaan['status_teks'] }}
                        </span>

                    </p>
                </td>
                <td class="py-3 px-6 text-center">
                    <a href="/permintaan/{{ $permintaan['tipe'] === 'peminjaman' ? 'peminjaman' : 'permintaan' }}/{{ $permintaan['id'] }}"
                        class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300"
                        data-tooltip-target="tooltip-permintaan-{{ $permintaan['id'] }}">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                    <div id="tooltip-permintaan-{{ $permintaan['id'] }}" role="tooltip"
                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Lihat Detail Permintaan
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <button wire:click="openApprovalTimeline({{ $permintaan['id'] }}, '{{ $permintaan['tipe'] }}')"
                        class="ml-2 text-green-600 hover:text-white hover:bg-green-600 px-3 py-2 rounded border border-green-600"
                        data-tooltip-target="tooltip-timeline-{{ $permintaan['id'] }}">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </button>
                    <div id="tooltip-timeline-{{ $permintaan['id'] }}" role="tooltip"
                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                        Lihat Riwayat Approval
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="text-center text-gray-600 text-lg">Tidak ada data yang ditemukan.</div>
                </td>
            </tr>
            @endforelse
        </tbody>

    </table>


    {{-- {{ $permintaans->onEachSide(1)->links() }} --}}
    @push('scripts')
    <script type="module">
        document.addEventListener('gagal', function ({detail}) {
            feedback('Akses Ditolak!', detail.pesan, 'error');
            });
    </script>
    @endpush

    @if ($showTimelineModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Timeline Persetujuan</h2>

            <div class="space-y-4 max-h-[60vh] overflow-y-auto pr-2">
                @forelse ($approvalTimeline as $item)
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-gray-200 overflow-hidden">
                        @if ($item['img'])
                        <img src="{{ asset('storage/' . $item['img']) }}" class="w-full h-full object-cover">
                        @else
                        <div
                            class="w-full h-full flex items-center justify-center bg-gray-400 text-white text-sm font-bold">
                            {{ strtoupper(substr($item['user'], 0, 1)) }}
                        </div>
                        @endif
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold">{{ $item['user'] }} ({{ $item['role'] }})</p>
                        <p class="text-xs text-gray-500">{{ $item['tanggal'] }}</p>
                        <span
                            class="inline-block mt-1 px-2 py-0.5 text-xs rounded-full bg-{{ $item['status'] === 'Disetujui' ? 'green' : 'yellow' }}-100 text-{{ $item['status'] === 'Disetujui' ? 'green' : 'yellow' }}-800">
                            {{ $item['status'] }}
                        </span>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-sm">Belum ada riwayat persetujuan.</p>
                @endforelse
            </div>

            <div class="flex justify-end mt-6">
                <button wire:click="$set('showTimelineModal', false)"
                    class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-500">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @endif
</div>