<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">DETAIL {{ Str::upper($tipe) }}</h1>
        <div>
            @if ($tipe == 'peminjaman')
            <a href="/permintaan-stok"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
            @else
            <a href="/permintaan{{ $permintaan->jenis_id == 3 ? '-stok' : ($permintaan->jenis_id == 2 ? '/spare-part' : '/material') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
            @endif
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <x-card title="data umum" class="mb-3">
                <table class="w-full">
                    @if ($permintaan->rab_id)
                    <tr class="font-semibold">
                        <td>RAB</td>
                        <td>{{ $permintaan->rab->nama }}</td>
                    </tr>
                    @endif
                    <tr class="font-semibold">
                        <td>Kode {{ Str::ucfirst($tipe) }}</td>
                        <td>{{ $permintaan->kode_permintaan ?? $permintaan->kode_peminjaman }}</td>
                    </tr>
                    <tr class="font-semibold">
                        <td>Status</td>
                        <td> <span class="
        bg-{{ $permintaan->cancel === 1
            ? 'secondary'
            : ($permintaan->cancel === 0 && $permintaan->proses === 1
                ? 'primary'
                : ($permintaan->cancel === 0 && $permintaan->proses === null
                    ? 'info'
                    : ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status === null
                        ? 'warning'
                        : ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status === 1
                            ? 'success'
                            : 'danger')))) }}-600
        text-{{ $permintaan->cancel === 1
            ? 'secondary'
            : ($permintaan->cancel === 0 && $permintaan->proses === 1
                ? 'primary'
                : ($permintaan->cancel === 0 && $permintaan->proses === null
                    ? 'info'
                    : ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status === null
                        ? 'warning'
                        : ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status === 1
                            ? 'success'
                            : 'danger')))) }}-100
        text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                                {{ $permintaan->cancel === 1
                                ? 'dibatalkan'
                                : ($permintaan->cancel === 0 && $permintaan->proses === 1
                                ? 'selesai'
                                : ($permintaan->cancel === 0 && $permintaan->proses === null
                                ? 'siap digunakan'
                                : ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status
                                === null
                                ? 'diproses'
                                : ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status
                                === 1
                                ? 'disetujui'
                                : 'ditolak')))) }}
                            </span>

                        </td>
                    </tr>
                    @if ($permintaan->status === 0)
                    <tr class="font-semibold">
                        <td>Keterangan</td>
                        <td>{{ $permintaan->persetujuan->where('status', 0)->last()->keterangan }}</td>
                    </tr>
                    @endif
                    <tr class="font-semibold">
                        <td>Tanggal {{ Str::ucfirst($tipe) }}</td>
                        <td> {{ $permintaan->kategori_id == 4
                            ? date('j F Y - H:i', $permintaan->tanggal_permintaan)
                            : date('j F Y', $permintaan->tanggal_permintaan) }}
                        </td>
                    </tr>
                    @if ($permintaan->kategori_id == 4)
                    <tr class="font-semibold">
                        <td>Lokasi/Ruang </td>
                        <td>
                            @if (!is_null($permintaan->lokasi_id))
                            {{ optional($permintaan->ruang)->nama ?? '-' }}
                            @else
                            {{ $permintaan->lokasi_lain ?? '-' }}<br>
                            {{ $permintaan->alamat_lokasi ?? '-' }}<br>
                            {{ $permintaan->kontak_person ?? '-' }}
                            @endif
                        </td>
                    </tr>
                    <tr class="font-semibold">
                        <td>Jumlah Peserta</td>
                        <td>{{ $permintaan->jumlah_peserta }}</td>
                    </tr>
                    @endif
                    @if ($permintaan->kategori_id == 5)
                    <tr class="font-semibold">
                        <td>KDO</td>
                        <td>
                            {{ optional($permintaan->aset)->nama ?? '-' }}
                        </td>
                    </tr>
                    <tr class="font-semibold">
                        <td>Tanggal Masuk</td>
                        <td>{{ \Carbon\Carbon::parse($permintaan->tanggal_masuk)->translatedFormat('j F Y') }}</td>
                    </tr>
                    <tr class="font-semibold">
                        <td>Tanggal Keluar</td>
                        <td>{{ \Carbon\Carbon::parse($permintaan->tanggal_keluar)->translatedFormat('j F Y') }}
                        </td>
                    </tr>
                    @endif
                    <tr class="font-semibold">
                        <td>Unit Kerja</td>
                        <td>{{ $permintaan->unit->nama }}</td>
                    </tr>
                    <tr class="font-semibold">
                        <td>Sub-Unit</td>
                        <td>{{ $permintaan->subUnit->nama ?? '---' }}</td>
                    </tr>
                    <tr class="font-semibold {{ !$permintaan->rab_id?'':'hidden' }}">
                        <td>Keterangan</td>
                        <td>{{ $permintaan->keterangan ?? '---' }}</td>
                    </tr>
                </table>
            </x-card>
        </div>
        <div class="grid gap-6">
            @if ($permintaan->cancel === 0)
            <x-card title="QR Code" class="mb-3">
                <div class="flex justify-around">
                    <div
                        class="w-80 h-80 overflow-hidden relative flex justify-center  p-4 hover:shadow-lg transition duration-200  border-2 rounded-lg bg-white">
                        <a href="{{ route('permintaan.downloadQrImage', $permintaan->id) }}" class="w-full h-full">
                            <img src="{{ asset($permintaan->kode_permintaan ? 'storage/qr_permintaan/' . $permintaan->kode_permintaan . '.png' : 'img/default-pic.png') }}"
                                data-tooltip-target="tooltip-QR" alt="QR Code"
                                class="w-full h-full object-cover object-center rounded-sm">
                        </a>
                    </div>
                    <div id="tooltip-QR" role="tooltip"
                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Klik Untuk Mengunduh QR-Code Ini
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
            </x-card>
            @endif
        </div>
        <div class="col-span-2">
            <x-card title="daftar permintaan">
                @if ($tipe == 'permintaan')
                <livewire:list-permintaan-form :permintaan="$permintaan">
                    @else
                    <livewire:list-peminjaman-form :peminjaman="$permintaan">
                        @endif
                        @if ($tipe == 'permintaan')
                        <livewire:approval-permintaan :permintaan="$permintaan">
                            @else
                            <livewire:approval-permintaan :permintaan="$permintaan">
                                @endif
            </x-card>
        </div>
    </div>
</x-body>