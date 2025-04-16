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
                    <tr class="font-semibold">
                        <td>Kode {{ Str::ucfirst($tipe) }}</td>
                        <td>{{ $permintaan->kode_permintaan ?? $permintaan->kode_peminjaman }}</td>
                    </tr>
                    <tr class="font-semibold">
                        <td>Status</td>
                        <td> <span
                                class="
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
                                            : ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status === null
                                                ? 'diproses'
                                                : ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status === 1
                                                    ? 'disetujui'
                                                    : 'ditolak')))) }}
                            </span>

                        </td>
                    </tr>
                    @if ($permintaan->status === 0)
                        <tr class="font-semibold">
                            <td>Note Tidak Disetujui</td>
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
                        <td>{{ $permintaan->unit->nama ?? '---' }}</td>
                    </tr>
                    <tr class="font-semibold">
                        <td>Sub-Unit</td>
                        <td>{{ $permintaan->subUnit->nama ?? '---' }}</td>
                    </tr>
                    <tr class="font-semibold">
                        <td>Keterangan</td>
                        <td>{{ $permintaan->keterangan ?? '---' }}</td>
                    </tr>
                    @if ($permintaan->kategori_id == 1 && $tipe == 'peminjaman')
                        <tr class="font-semibold">
                            <td colspan="2" class="py-2">Syarat dan Ketentuan KDO</td>
                        </tr>
                        <tr>
                            <td class="pl-4 align-top text-right">1.</td>
                            <td>Harap menjaga kebersihan KDO dan memastikan tidak ada sampah yang tertinggal di dalam
                                mobil.</td>
                        </tr>
                        <tr>
                            <td class="pl-4 align-top text-right">2.</td>
                            <td>Mohon informasikan ke kontak CS apabila penggunaan KDO sudah selesai.</td>
                        </tr>
                    @elseif($tipe == 'peminjaman' && $permintaan->kategori_id == 2)
                        <tr class="font-semibold">
                            <td colspan="2" class="py-2">Syarat dan Ketentuan Peminjaman Ruang Rapat</td>
                        </tr>
                        <tr>
                            <td class="pl-4 align-top text-right">1.</td>
                            <td>Peminjaman ruang rapat bidang via form, hanya untuk peminjaman lintas bidang/unit.</td>
                        </tr>
                        <tr>
                            <td class="pl-4 align-top text-right">2.</td>
                            <td>Jika bidangnya sendiri yang pakai ruang rapat, tidak perlu isi form.</td>
                        </tr>
                        <tr>
                            <td class="pl-4 align-top text-right">3.</td>
                            <td>Harap memastikan terjaganya kebersihan ruang rapat (tidak ada sampah, kursi dan meja
                                dirapihkan ke posisi semula, papan tulis harus dibersihkan).</td>
                        </tr>
                        <tr>
                            <td class="pl-4 align-top text-right">4.</td>
                            <td>Harap infokan ke kontak CS apabila penggunaan ruang rapat sudah selesai.</td>
                        </tr>
                    @elseif($tipe == 'permintaan' && $permintaan->kategori_id == 4)
                        <tr class="font-semibold">
                            <td colspan="2" class="py-2">Syarat dan Ketentuan Permintaan Konsumsi Rapat</td>
                        </tr>
                        <tr>
                            <td class="pl-4 align-top text-right">1.</td>
                            <td>Pemesan wajib menyiapkan administrasi kelengkapan SPJ (absensi asli, notulen asli dan
                                foto kegiatan)
                                dan harus diserahkan ke Subbag Umum Maksimal 2 (dua) hari setelah kegiatan selesai
                                dilaksanakan.</td>
                        </tr>
                        <tr>
                            <td class="pl-4 align-top text-right">2.</td>
                            <td>Apabila administrasi SPJ lewat dari 2 (dua) hari, maka permintaan selanjutnya tidak bisa
                                diproses
                                sampai administrasi SPJ diserahkan ke Subbag Umum.</td>
                        </tr>
                    @elseif($tipe == 'permintaan' && $permintaan->kategori_id == 6)
                        <tr class="font-semibold">
                            <td colspan="2" class="py-2">Syarat dan Ketentuan Permintaan Vocher Carwash</td>
                        </tr>
                        <tr>
                            <td class="pl-4 align-top text-right">1.</td>
                            <td>Berlaku untuk kendaraan roda 4 berplat merah di lingkungan Dinas Sumber Daya Air
                                Provinsi DKI Jakarta.</td>
                        </tr>
                        <tr>
                            <td class="pl-4 align-top text-right">2.</td>
                            <td>Vocher bisa diambil ke CS Umum atau Insan (081386995922).</td>
                        </tr>
                        <tr>
                            <td class="pl-4 align-top text-right">3.</td>
                            <td>Pengambilan vocher maksimal 2 hari setalah input di form, lewat dari 2 hari maka
                                dianggap batal dan harus mengajukan ulang.</td>
                        </tr>
                        <tr>
                            <td class="pl-4 align-top text-right">4.</td>
                            <td>Harap foto kendaraan ketika sedang di cuci.</td>
                        </tr>
                        <tr>
                            <td class="pl-4 align-top text-right">5.</td>
                            <td>Setelah selesai cuci mobil harap serahkan Bon dari Carwash ke CS Umum atau Insan.</td>
                        </tr>
                    @endif
                    @if (
                        $tipe == 'peminjaman' &&
                            $permintaan->status === 1 &&
                            $permintaan->cancel === 0 &&
                            empty($permintaan->img_pengembalian) &&
                            auth()->id() == $permintaan->user_id &&
                            Str::lower($permintaan->kategori->nama) !== 'peralatan kantor')
                        <tr>
                            <livewire:pengembalian-button :permintaan="$permintaan">
                        </tr>
                    @endif
                    @if ($tipe == 'peminjaman' && $permintaan->status == 1 && $permintaan->img_pengembalian)
                        <tr>
                            <td>
                                <span class="text-green-600 font-semibold">Sudah dikembalikan</span><br>
                                <a href="{{ asset('storage/pengembalianUmum/' . $permintaan->img_pengembalian) }}"
                                    target="_blank">
                                    <img src="{{ asset('storage/pengembalianUmum/' . $permintaan->img_pengembalian) }}"
                                        alt="Foto Pengembalian" class="mt-2 rounded shadow"
                                        style="max-width: 8rem; max-height: 8rem; width: auto; height: auto;">
                                </a>
                            </td>
                            <td>
                                <span class="text-red-600 font-semibold italic">Note</span><br>
                                {{ $permintaan->keterangan_pengembalian }}
                            </td>
                        </tr>
                    @endif
                </table>
            </x-card>
        </div>
        <div class="grid gap-6">
            @if ($tipe == 'permintaan' && $permintaan->status === 1)
                <x-card title="QR Code" class="mb-3">
                    <div class="flex justify-around">
                        <div
                            class="w-80 h-80 overflow-hidden relative flex justify-center  p-4 hover:shadow-lg transition duration-200  border-2 rounded-lg bg-white">
                            @if ($tipe == 'peminjaman')
                                <a href="{{ route('permintaan.downloadQrImage', ['tipe' => 'peminjaman', 'kode' => $permintaan->id]) }}"
                                    class="w-full h-full">
                                    <img src="{{ asset($permintaan->kode_peminjaman ? 'storage/qr_peminjaman/' . $permintaan->kode_peminjaman . '.png' : 'img/default-pic.png') }}"
                                        data-tooltip-target="tooltip-QR" alt="QR Code"
                                        class="w-full h-full object-cover object-center rounded-sm">
                                </a>
                            @else
                                <a href="{{ route('permintaan.downloadQrImage', ['tipe' => 'permintaan', 'kode' => $permintaan->id]) }}"
                                    class="w-full h-full">
                                    <img src="{{ asset($permintaan->kode_permintaan ? 'storage/qr_permintaan/' . $permintaan->kode_permintaan . '.png' : 'img/default-pic.png') }}"
                                        data-tooltip-target="tooltip-QR" alt="QR Code"
                                        class="w-full h-full object-cover object-center rounded-sm">
                                </a>
                            @endif
                        </div>
                        <div id="tooltip-QR" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Klik Untuk Mengunduh QR-Code Ini
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </div>
                </x-card>
            @elseif ($permintaan->cancel === 0)
                <x-card title="QR Code" class="mb-3">
                    <div class="flex justify-around">
                        <div
                            class="w-80 h-80 overflow-hidden relative flex justify-center  p-4 hover:shadow-lg transition duration-200  border-2 rounded-lg bg-white">
                            @if ($tipe == 'peminjaman')
                                <a href="{{ route('permintaan.downloadQrImage', ['tipe' => 'peminjaman', 'kode' => $permintaan->id]) }}"
                                    class="w-full h-full">
                                    <img src="{{ asset($permintaan->kode_peminjaman ? 'storage/qr_peminjaman/' . $permintaan->kode_peminjaman . '.png' : 'img/default-pic.png') }}"
                                        data-tooltip-target="tooltip-QR" alt="QR Code"
                                        class="w-full h-full object-cover object-center rounded-sm">
                                </a>
                            @else
                                <a href="{{ route('permintaan.downloadQrImage', ['tipe' => 'permintaan', 'kode' => $permintaan->id]) }}"
                                    class="w-full h-full">
                                    <img src="{{ asset($permintaan->kode_permintaan ? 'storage/qr_permintaan/' . $permintaan->kode_permintaan . '.png' : 'img/default-pic.png') }}"
                                        data-tooltip-target="tooltip-QR" alt="QR Code"
                                        class="w-full h-full object-cover object-center rounded-sm">
                                </a>
                            @endif
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
                    @if ($permintaan->kategori_id === 6)
                        <livewire:approval-permintaan-voucher :permintaan="$permintaan">
                        @elseif($permintaan->kategori_id === 5)
                            <livewire:approval-permintaan-perbaikan-kdo :permintaan="$permintaan">
                            @elseif($permintaan->kategori_id === 4)
                                <livewire:approval-permintaan-konsumsi :permintaan="$permintaan">
                                @elseif(in_array($permintaan->kategori_id, [1, 2, 3]))
                                    <livewire:approval-permintaan-a-t-k :permintaan="$permintaan">
                                    @else
                                        <livewire:approval-permintaan :permintaan="$permintaan">
                    @endif
                @else
                    <livewire:approval-permintaan :permintaan="$permintaan">
                @endif
            </x-card>
        </div>
    </div>
</x-body>
