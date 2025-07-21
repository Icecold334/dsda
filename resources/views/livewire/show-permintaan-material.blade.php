<div>
    <div class="flex justify-between ">

        <h1 class="text-2xl font-bold text-primary-900 ">DETAIL PERMINTAAN</h1>
        <div>
            <a class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"
                href="/permintaan/material"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
        </div>
    </div>
    <div class="flex py-2 mb-3 justify-end">
        <div>
            @php
            $approvedUsers = $permintaan->persetujuan()
            ->where('is_approved', 1)
            ->get()
            ->pluck('user_id')
            ->unique();

            $usersWithRoles = \App\Models\User::whereIn('id', $approvedUsers)->with('roles')->get();

            $hasApprovedKasubbag = $usersWithRoles->contains(function ($user) {
            return $user->hasRole('Kepala Subbagian');
            });

            $hasApprovedPengurusBarang = $usersWithRoles->contains(function ($user) {
            return $user->hasRole('Pengurus Barang');
            });

            $isKepalaSeksi = $permintaan->user->hasRole('Kepala Seksi');
            $minApprovalCount = $isKepalaSeksi ? 1 : 2;
            @endphp
            {{-- @dump($minApprovalCount) --}}

            {{-- SPB --}}
            @can('upload_spb.read')
            @if ($permintaan->spb_path)
            <a href="{{ asset('storage/spb/' . $permintaan->spb_path) }}" target="_blank"
                class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Unduh SPB
            </a>
            @else
            <a onclick="confirmDownload('spb')"
                class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Unduh SPB
            </a>
            @endcan
            @can('upload_spb.create')
            <a onclick="showUploadModal('spb')"
                class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Unggah SPB
            </a>
            @endcan
            @endif

            {{-- SPPB --}}
            @can('upload_sppb.read')
            @if ($hasApprovedKasubbag)
            @if ($permintaan->sppb_path)
            <a href="{{ asset('storage/sppb/' . $permintaan->sppb_path) }}" target="_blank"
                class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Unduh SPPB
            </a>
            @else
            <a onclick="confirmDownload('sppb')"
                class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Unduh SPPB
            </a>
            @endif
            @endcan
            {{-- @can('upload_sppb.create') --}}
            @if ($hasApprovedKasubbag && !$permintaan->sppb_path)
            <a onclick="showUploadModal('sppb')"
                class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Unggah SPPB
            </a>
            @endif
            {{-- @endcan --}}
            @endif

            {{-- QR-Code --}}
            {{-- @can('qr_print') --}}
            @if ($hasApprovedKasubbag)
            <a wire:click='qrCode'
                class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Unduh QR-Code
            </a>
            @endif
            {{-- @endcan --}}

            {{-- Surat Jalan --}}
            {{-- @can('surat_jalan.read') --}}
            @if ($hasApprovedPengurusBarang)
            @if ($permintaan->suratJalan_path)
            <a href="{{ asset('storage/suratJalan/' . $permintaan->suratJalan_path) }}" target="_blank"
                class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Unduh Surat Jalan
            </a>
            @else
            <a onclick="confirmDownload('suratJalan')"
                class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Unduh Surat Jalan
            </a>
            @endif
            {{-- @endcan --}}
            {{-- @can('surat_jalan.create') --}}
            @if ($hasApprovedPengurusBarang && !$permintaan->suratJalan_path)
            <a onclick="showUploadModal('suratJalan')"
                class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Unggah Surat Jalan
            </a>
            @endif
            {{-- @endcan --}}
            @endif

            {{-- BAST --}}
            @canany(['permintaan.read', 'permintaan.update'])
            @if ($permintaan->status == 3)
            @if ($permintaan->bast_path)
            <a href="{{ asset('storage/bast/' . $permintaan->bast_path) }}" target="_blank"
                class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Unduh BAST
            </a>
            @else
            <a onclick="confirmDownload('bast')"
                class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Unduh BAST
            </a>
            @can('upload_foto.create')
            <a onclick="showUploadModal('bast')"
                class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Unggah BAST
            </a>
            @endcan
            @endif
            @endif
            @endcanany

        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <x-card title="data umum" class="mb-3">
                <table class="w-full">
                    <tr class="font-semibold ">
                        <td>Nomor SPB</td>
                        <td>{{ $permintaan->nodin }}</td>
                    </tr>
                    <tr class="font-semibold ">
                        <td class="w-[40%]">Lokasi Gudang</td>
                        <td>{{ $permintaan->lokasiStok->nama }}</td>
                    </tr>
                    @if ($withRab && !$isSeribu)
                    <!-- Program -->
                    <tr class="font-semibold">
                        <td class="w-1/3">Program</td>
                        <td>{{ $permintaan->rab->program->program }}</td>
                    </tr>

                    <!-- Nama Kegiatan -->
                    <tr class="font-semibold">
                        <td>Nama Kegiatan</td>
                        <td>{{ $permintaan->rab->kegiatan->kegiatan }}</td>
                    </tr>

                    <!-- Sub Kegiatan -->
                    <tr class="font-semibold">
                        <td>Sub Kegiatan</td>
                        <td>{{ $permintaan->rab->subKegiatan->sub_kegiatan }}</td>
                    </tr>

                    <!-- Rincian Sub Kegiatan -->
                    <tr class="font-semibold">
                        <td>Aktivitas Sub Kegiatan</td>
                        <td>{{ $permintaan->rab->aktivitasSubKegiatan->aktivitas }}</td>
                    </tr>

                    <!-- Kode Rekening -->
                    <tr class="font-semibold">
                        <td>Kode Rekening</td>
                        <td>{{ $permintaan->rab->uraianRekening->uraian }}</td>
                    </tr>
                    <!-- Jenis Pekerjaan -->
                    <tr class="font-semibold">
                        <td>Jenis Pekerjaan</td>
                        <td>{{ $permintaan->rab->jenis_pekerjaan }}</td>
                    </tr>
                    <tr class="font-semibold">
                        <td>Lokasi</td>
                        <td>
                            @if ($permintaan->rab->kelurahan)
                            Kelurahan {{ $permintaan->rab->kelurahan->nama }},
                            Kecamatan {{ $permintaan->rab->kelurahan->kecamatan->kecamatan }} –
                            @endif
                            {{ $permintaan->rab->lokasi }}
                        </td>
                    </tr>
                    @endif

                    @if (!$permintaan->rab_id && !$isSeribu)
                    <tr class="font-semibold ">
                        <td>Jenis Pekerjaan</td>
                        <td>{{ $permintaan->nama }}</td>
                    </tr>
                    @endif


                    {{-- <tr class="font-semibold ">
                        <td class="w-[40%]">Kode Permintaan</td>
                        <td>{{ $permintaan->kode_permintaan }}</td>
                    </tr> --}}
                    <tr class="font-semibold">
                        <td>Status</td>
                        <td>
                            <span
                                class="bg-{{ $permintaan->status_warna }}-600 text-{{ $permintaan->status_warna }}-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                                {{ $permintaan->status_teks }}
                            </span>
                        </td>
                    </tr>
                    @if ($permintaan->status === 0)
                    <tr class="font-semibold">
                        <td>Keterangan</td>
                        <td>{{$permintaan->keterangan_ditolak }}</td>
                    </tr>
                    @endif

                    <tr class="font-semibold">
                        <td>Tanggal Pekerjaan</td>
                        <td> {{date('j F Y', $permintaan->tanggal_permintaan) }}</td>
                    </tr>
                    <tr class="font-semibold">
                        <td>Tahun Permintaan</td>
                        <td> {{$permintaan->created_at->format('Y') }}</td>
                    </tr>

                    {{-- @if (!$permintaan->rab_id) --}}
                    <tr class="font-semibold {{ $withRab?'hidden':'' }}">
                        <td>Lokasi Kegiatan</td>
                        <td>
                            @if (!$permintaan->rab_id)
                            @if ($permintaan->kelurahan)
                            Kelurahan {{ $permintaan->kelurahan->nama }},
                            Kecamatan {{ $permintaan->kelurahan->kecamatan->kecamatan }} –
                            @endif
                            {{ $permintaan->lokasi }}
                            @else
                            {{ $permintaan->rab->lokasi }}
                            @endif
                    </tr>
                    @if ($permintaan->rab_id)
                    @if ($permintaan->rab->l&&$permintaan->rab->p&&$permintaan->rab->k)
                    <tr class="font-semibold">
                        <td>Volume Pekerjaan (Panjang, Lebar, Kedalaman)</td>
                        <td class="capitalize">{{ $permintaan->rab->p }}, {{ $permintaan->rab->l }}, {{
                            $permintaan->rab->k }}</td>
                    </tr>
                    @endif
                    @else
                    <tr class="font-semibold">
                        <td>Volume Pekerjaan (Panjang, Lebar, Kedalaman)</td>
                        <td class="capitalize">{{ $permintaan->p }}, {{ $permintaan->l }}, {{
                            $permintaan->k }}</td>
                    </tr>
                    @endif
                    <tr class="font-semibold {{ !$permintaan->rab_id?'':'hidden' }}">
                        <td>Keterangan</td>
                        <td>{{ $permintaan->keterangan ?? '---' }}</td>
                    </tr>
                    {{-- @endif --}}
                </table>
                <div class="font-semibold">Lampiran</div>
                @forelse ($permintaan->lampiranDokumen as $attachment)
                <div class="flex items-center justify-between border-b-4 p-2 rounded my-1">
                    <span class="flex items-center space-x-3">
                        @php
                        $fileType = pathinfo($attachment->path, PATHINFO_EXTENSION);
                        @endphp
                        <span class="text-primary-600">
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

                        <!-- File name with underline on hover and a link to the saved file -->
                        <span>
                            <a href="{{ asset('storage/lampiranRab/' . $attachment->path) }}" target="_blank"
                                class="text-gray-800 hover:underline">
                                {{ basename($attachment->path) }}
                            </a>
                        </span>
                    </span>
                </div>
                @empty
                <div class="flex justify-center text-xl font-semibold">
                    Tidak ada lampiran
                </div>
                @endforelse

            </x-card>
        </div>
        @php
        $approvedUsers = $permintaan->persetujuan()->where('is_approved', 1)->get()->unique('user_id');
        $approvedUserIds = $approvedUsers->pluck('user_id');
        $approvedUsersWithRoles = \App\Models\User::whereIn('id', $approvedUserIds)->with('roles')->get();

        $hasApprovedKasubbag = $approvedUsersWithRoles->contains(fn($user) => $user->hasRole('Kepala Subbagian'));
        $hasApprovedPengurus = $approvedUsersWithRoles->contains(fn($user) => $user->hasRole('Pengurus Barang'));

        $isPengurus = auth()->user()->hasRole('Pengurus Barang');
        $showPengirimanForm = $hasApprovedKasubbag && !$hasApprovedPengurus && $isPengurus;

        $lampiranCount = $permintaan->lampiran->count();
        $canUpload = $isPengurus;
        @endphp
        {{-- @dump($canUpload) --}}
        <div class="grid grid-cols-2 gap-6">
            {{-- Foto Pengiriman --}}
            <x-card title="Foto Pengiriman" class="mb-3">
                @php
                $lampiranCount = $permintaan->lampiran->count();
                $approvedUsers = $permintaan->persetujuan()->where('is_approved', 1)->get()->unique('user_id');
                $approvedUserIds = $approvedUsers->pluck('user_id');
                $approvedUsersWithRoles = \App\Models\User::whereIn('id', $approvedUserIds)->with('roles')->get();

                $hasApprovedKasubbag =
                $approvedUsersWithRoles->contains(fn($user) => $user->hasRole('Kepala Subbagian'));
                $hasApprovedPengurus =
                $approvedUsersWithRoles->contains(fn($user) => $user->hasRole('Pengurus Barang'));

                $isPengurus = auth()->user()->hasRole('Pengurus Barang');
                $canUpload = $hasApprovedKasubbag && !$hasApprovedPengurus && $isPengurus;
                @endphp

                {{-- Tombol unggah & simpan hanya jika boleh upload dan belum ada lampiran --}}
                @if ($canUpload && $lampiranCount < 1) <div wire:loading wire:target="newAttachments">
                    <livewire:loading />
        </div>
        <input type="file" wire:model.live="newAttachments" multiple class="hidden" id="fileUpload">
        <label for="fileUpload"
            class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200 cursor-pointer">
            + Unggah
        </label>
        <a wire:click='saveDoc'
            class="cursor-pointer {{ count($this->attachments) ? '' : 'hidden' }}
                text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
            Simpan
        </a>
        @endif

        {{-- Daftar Lampiran --}}
        <div class="mt-3 max-h-52 overflow-auto">
            @foreach ($permintaan->lampiran as $attachment)
            <div class="flex items-center justify-between border-b-4 p-2 rounded my-1">
                <span class="flex items-center space-x-3">
                    @php $fileType = pathinfo($attachment->path, PATHINFO_EXTENSION); @endphp
                    <span class="text-primary-600">
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
                    <a href="{{ asset('storage/lampiranRab/' . $attachment->path) }}" target="_blank"
                        class="text-gray-800 hover:underline">
                        {{ basename($attachment->path) }}
                    </a>
                </span>
            </div>
            @endforeach

            @foreach ($attachments as $index => $attachment)
            <div class="flex items-center justify-between border-b-4 p-2 rounded my-1">
                <span class="flex items-center space-x-3">
                    @php $fileType = $attachment->getClientOriginalExtension(); @endphp
                    <span class="text-primary-600">
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
                    <a href="{{ $attachment->temporaryUrl() }}" target="_blank" class="text-gray-800 hover:underline">
                        {{ $attachment->getClientOriginalName() }}
                    </a>
                </span>
                @if ($canUpload)
                <button wire:click="removeAttachment({{ $index }})"
                    class="text-red-500 hover:text-red-700">&times;</button>
                @endif
            </div>
            @endforeach

            @if (!$canUpload && $lampiranCount < 1 && count($this->attachments) ) <div
                    class="flex justify-center text-xl font-semibold">Belum ada unggahan
                </div>
                @endif
        </div>
        </x-card>

        {{-- TTD Driver & Keamanan --}}
        <x-card title="Tanda Tangan Driver & Keamanan" class="mb-3">
            <div class="mb-6">
                <h4 class="font-semibold text-sm mb-2">Tanda Tangan Driver</h4>
                @if ($signature)
                <img src="{{ asset('storage/ttdPengiriman/' . $signature) }}" class="border rounded shadow-sm"
                    height="100" alt="TTD Driver">
                @elseif ($canUpload)
                <canvas id="signature-pad-driver" class="border rounded shadow-sm h-25 bg-transparent"
                    height="100"></canvas>
                <button wire:click="resetSignature('driver')" onclick="resetCanvas('driver')"
                    class="bg-danger-600 text-danger-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">Hapus</button>
                <button onclick="saveSignature('driver')"
                    class="bg-success-600 text-success-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">Simpan</button>
                @else
                <div class="text-center text-gray-500 font-medium">Belum ada tanda tangan</div>
                @endif
            </div>

            <div>
                <h4 class="font-semibold text-sm mb-2">Tanda Tangan Keamanan</h4>
                @if ($securitySignature)
                <img src="{{ asset('storage/ttdPengiriman/' . $securitySignature) }}" class="border rounded shadow-sm"
                    height="100" alt="TTD Keamanan">
                @elseif ($canUpload)
                <canvas id="signature-pad-security" class="border rounded shadow-sm h-25 bg-transparent"
                    height="100"></canvas>
                <button onclick="resetCanvas('security')"
                    class="bg-danger-600 text-danger-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">Hapus</button>
                <button onclick="saveSignature('security')"
                    class="bg-success-600 text-success-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">Simpan</button>
                @else
                <div class="text-center text-gray-500 font-medium">Belum ada tanda tangan</div>
                @endif
            </div>
        </x-card>
    </div>

    <div class="col-span-2">
        <x-card title="daftar permintaan">
            <livewire:list-permintaan-material :permintaan='$permintaan'>
                <livewire:approval-material :permintaan='$permintaan'>
        </x-card>
    </div>
</div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
    function confirmDownload(docType) {
        Swal.fire({
            title: 'Gunakan TTD Elektronik (e-TTD)?',
            text: "Apakah Anda ingin menyertakan tanda tangan elektronik pada dokumen?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, gunakan TTD',
            cancelButtonText: 'Tanpa TTD',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('downloadDoc',{ type: docType, withSign: true });
                // Livewire.dispatch('downloadDoc', { type: docType, withSign: true });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                @this.call('downloadDoc',{ type: docType, withSign: false });
            }
        });
    }
</script>
<script>
    let canvasDriver, canvasSecurity, signaturePadDriver, signaturePadSecurity;

    // document.addEventListener("livewire:load", () => {
        initializeSignaturePad();
    // });

    Livewire.on('signatureReset', () => {
        initializeSignaturePad();
    });

    function initializeSignaturePad() {
        canvasDriver = document.getElementById('signature-pad-driver');
        if (canvasDriver) {
            signaturePadDriver = new SignaturePad(canvasDriver);
        }
        canvasSecurity = document.getElementById('signature-pad-security');
        if (canvasSecurity) {
            signaturePadSecurity = new SignaturePad(canvasSecurity);
        }
    }

    function saveSignature(type) {
        // if (signaturePadDriver.isEmpty()||signaturePadSecurity.isEmpty()) {
        //     alert("Tanda tangan belum diisi.");
        //     return;
        // }

        let signatureDataDriver = signaturePadDriver.toDataURL('image/png');
        let signatureDataSecurity = signaturePadSecurity.toDataURL('image/png');

        let signatureData = type == 'driver' ? signatureDataDriver:signatureDataSecurity
        @this.call('signatureSaved', signatureData,type);
    }

    function showUploadModal(type) {
    Swal.fire({
        title: 'Unggah ' + type.toUpperCase(),
        html: '<input type="file" id="uploadFile" multiple accept=".pdf,.jpg,.jpeg,.png" class="swal2-file">',
        confirmButtonText: 'Upload',
        preConfirm: () => {
            const files = document.getElementById('uploadFile').files;
            if (files.length === 0) {
                Swal.showValidationMessage('Minimal 1 file harus dipilih');
            } else if (files.length > 2) {
                Swal.showValidationMessage('Maksimal 2 file diperbolehkan');
            }
            return files;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            @this.call('uploadDokumen', type, Array.from(result.value));
        }
    });
}


    function resetCanvas(type) {
        if (type == 'driver') {
            
            signaturePadDriver.clear();
        } else {
            signaturePadSecurity.clear();
            
        }
    }

    function showUploadModal(type) {
        console.log(type);

        let title = '';

        switch (type) {
            case 'spb':
                title = 'SPB'
                break;
            case 'sppb':
                title = 'SPPB'
                break;
            case 'suratJalan':
                title = 'Surat Jalan'
                break;
            case 'bast':
                title = 'BAST'
                break;
        
            default:
                break;
        }
        
        Swal.fire({
            title: 'Unggah ' + title,
            html: '<input type="file" id="uploadFile" multiple accept=".pdf,.jpg,.jpeg,.png" class="swal2-file">',
            confirmButtonText: 'Upload',
            preConfirm: () => {
                const files = document.getElementById('uploadFile').files;
                if (files.length === 0) {
                    Swal.showValidationMessage('Minimal 1 file harus dipilih');
                } else if (files.length > 2) {
                    Swal.showValidationMessage('Maksimal 2 file diperbolehkan');
                }
                return files;
                }
        }).then((result) => {
            if (result.isConfirmed && result.value) {
                const file = result.value[0];
                
                // Bikin file jadi blob agar bisa dikirim ke Livewire
                const reader = new FileReader();
                reader.onload = () => {
                    @this.call('uploadDokumen', type, reader.result, file.name);
                };
                reader.readAsDataURL(file);
            }
        });
    }
</script>
@endpush