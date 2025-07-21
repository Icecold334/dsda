<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900 ">
            {{ $tipe === null
            ? 'Pelayanan Umum'
            : ($tipe == 'spare-part'
            ? 'Permintaan Spare Part'
            : 'Permintaan Material') }}
            @if (auth()->user()->unitKerja)
            {{-- {{ auth()->user()->unitKerja->nama }} --}}
            @endif
        </h1>
        <div>
            <div class="flex gap-4">
                <input type="date" wire:model.live="tanggal" class="border rounded-lg px-4 py-2 w-40" />
                <input type="text" wire:model.live="search" class="border rounded-lg px-4 py-2 w-40"
                    placeholder="Cari Nomor SPB" />

                <select wire:model.live="status" class="border rounded-lg px-4 py-2 w-40">
                    <option value="">Semua Status</option>
                    <option value="diproses">diproses</option>
                    <option value="ditolak">ditolak</option>
                    <option value="disetujui">disetujui</option>
                    <option value="sedang dikirim">sedang dikirim</option>
                    <option value="selesai">selesai</option>
                </select>

                <div wire:loading wire:target='downloadExcel'>
                    <livewire:loading>
                </div>

                @can('pelayanan_xls')
                @if ($permintaans->count())
                <button data-tooltip-target="tooltip-excel" wire:click="downloadExcel"
                    class="bg-white text-blue-500 h-10 border border-blue-500 rounded-lg px-4 py-2 flex items-center hover:bg-blue-500 hover:text-white transition-colors">
                    <i class="fa-solid fa-file-excel"></i>
                </button>
                <div id="tooltip-excel" role="tooltip"
                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Unduh dalam format excel
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
                @endif
                @endcan

                @can('permintaan.create')
                <div wire:click='tambahPermintaan'
                    class="text-primary-900 cursor-pointer bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 transition duration-200">
                    + Tambah Permintaan
                </div>
                @endcan
            </div>
        </div>
    </div>

    <table class="w-full border-3 border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">NOMOR SPB</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold {{ $isSeribu ?'hidden':'' }}">JENIS
                    PEKERJAAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold {{ $isSeribu ?'hidden':'' }}">LOKASI</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">TANGGAL PEKERJAAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">TAHUN PERMINTAAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">STATUS</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($permintaans as $permintaan)
            <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                <td class="px-6 py-3"></td>
                <td class="px-6 py-3 font-semibold">
                    <div>{{ Str::ucfirst($permintaan['kode']) }}</div>
                    <div class="text-gray-500 text-sm {{ !$isSeribu ?'hidden':'' }}">
                        {{ $permintaan['nomor_rab'] }}
                    </div>
                </td>
                <td class="px-6 py-3 font-semibold {{ $isSeribu ?'hidden':'' }}">
                    <div>{{ Str::ucfirst($permintaan['jenis_pekerjaan']) }}</div>
                    <div class="text-gray-500 text-sm">{{ $permintaan['nomor_rab'] }}</div>
                </td>
                <td class="px-6 py-3 font-semibold text-center {{ $isSeribu ?'hidden':'' }}">{{ $permintaan['lokasi'] }}
                </td>
                <td class="px-6 py-3 font-semibold text-center">{{ date('j F Y', $permintaan['tanggal']) }}</td>
                <td class="px-6 py-3 font-semibold text-center">{{$permintaan['created_at'] }}</td>
                <td class="py-3 px-6 font-semibold {{ $tipe == 'material' ? 'hidden' : '' }}">
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



    @push('scripts')
    <script type="module">
        document.addEventListener('gagal', function ({detail}) {
                feedback('Akses Ditolak!', detail.pesan, 'error');
                });
    </script>
    @endpush
    {{-- @php
    $roleLabel = [
    'kepala-seksi' => 'Kepala Seksi Pemeliharaan',
    'kepala-subbagian' => 'Kepala Subbagian Tata Usaha',
    'pengurus-barang' => 'Pengurus Barang',
    ];
    @endphp --}}

    @if ($showTimelineModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl py-4 px-2">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 ms-6">Riwayat Permintaan</h2>

            <div class="overflow-y-auto max-h-[65vh] pe-2">
                <ol class="relative border-s border-gray-500 dark:border-gray-700 m-6 ps-4">
                    @php
                    $roleLabel = [
                    'kepala-seksi' => 'Kepala Seksi Pemeliharaan',
                    'kepala-subbagian' => 'Kepala Subbagian Tata Usaha',
                    'pengurus-barang' => 'Pengurus Barang',
                    ];
                    @endphp
                    @php
                    $flowStopped = false; // indikator aliran berhenti setelah "Ditolak"
                    @endphp

                    @foreach ($roleList as $slug => $users)
                    @php
                    $label = $roleLabel[$slug] ?? ucfirst(str_replace('-', ' ', $slug));

                    $approvedUser = !$flowStopped
                    ? collect($approvalTimeline)->first(fn($item) =>
                    \Illuminate\Support\Str::contains(strtolower($item['role']),
                    strtolower($label)))
                    : null;

                    if ($approvedUser) {
                    $status = $approvedUser['status'];
                    $user = $approvedUser['user'];
                    $desc = $approvedUser['desc'] ?? null;
                    $tanggal = $approvedUser['tanggal'];
                    $img = $approvedUser['img'] ?? null;

                    if ($status === 'Ditolak') {
                    $flowStopped = true; // stop aliran approval setelah ini
                    }
                    } else {
                    // $status = $flowStopped ? 'Belum Diperiksa' : 'Diproses';
                    $status = $flowStopped ? 'Tidak Dilanjutkan' : 'Diproses';
                    // $user = $flowStopped ? '-' : ($users[0]['name'] ?? '-');
                    $user = $users[0]['name'] ?? '-';
                    $desc = $flowStopped ? null : null;
                    $tanggal = $flowStopped ? '-' : null;
                    $img = $flowStopped ? null : ($users[0]['foto'] ?? null);
                    }

                    // Warna status
                    $badgeColor = match($status) {
                    'Disetujui' => 'bg-green-100 text-green-800',
                    'Ditolak' => 'bg-red-100 text-red-800',
                    'Diproses' => 'bg-yellow-100 text-yellow-800',
                    'Tidak Dilanjutkan' => 'bg-gray-200 text-gray-600',
                    default => 'bg-gray-100 text-gray-500',
                    };

                    $color = match($status) {
                    'Disetujui' => 'text-green-600',
                    'Ditolak' => 'text-red-600',
                    'Diproses' => 'text-yellow-600',
                    'Tidak Dilanjutkan' => 'text-gray-600',
                    default => 'text-gray-500',
                    };

                    $initial = strtoupper(substr($user, 0, 1));
                    $imgPath = $img ? storage_path('app/public/' . $img) : null;
                    $imgExists = $imgPath && file_exists($imgPath);
                    @endphp

                    <li class="mb-2 ms-6">
                        <span
                            class="absolute flex items-center justify-center w-9 h-9 rounded-full -start-4 ring-8 ring-white {{ $badgeColor }}">
                            @if ($imgExists)
                            <img src="{{ asset('storage/' . $img) }}" class="rounded-full w-6 h-6 object-cover" />
                            @else
                            <span class="text-xs font-bold text-gray-700">{{ $initial }}</span>
                            @endif
                        </span>

                        <h3 class=" text-md font-semibold text-gray-900 dark:text-white">{{ $label }}</h3>
                        <time class="block  text-xs font-normal leading-none text-gray-400 dark:text-gray-500">{{
                            $tanggal ?? '-'
                            }}</time>
                        <p class="text-sm  font-semibold text-gray-600">{{ $user ?? '-' }}</p>

                        @if (!empty($desc))
                        <p class="text-sm text-gray-500  italic">{!! $desc !!}

                        </p>
                        @endif

                        <span class="inline-block  px-2 py-0.5 text-xs rounded-full {{ $badgeColor }}">
                            {{ $status }}
                        </span>
                    </li>
                    @endforeach
                </ol>
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