<div>
    {{-- Flash Messages --}}
    @if(session('message'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('message') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

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
                    return $user->hasRole('Kepala Subbagian Tata Usaha');
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
            {{--
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
            --}}

        </div>
    </div>

    <!-- Data Umum dan Foto Pengiriman - Grid 2 kolom sejajar -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Data Umum -->
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
                    <tr class="font-semibold {{ $withRab ? 'hidden' : '' }}">
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
                        @if ($permintaan->rab->l && $permintaan->rab->p && $permintaan->rab->k)
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
                    <tr class="font-semibold {{ !$permintaan->rab_id ? '' : 'hidden' }}">
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

        <!-- Foto Pengiriman -->
        @php
            $approvedUsers = $permintaan->persetujuan()->where('is_approved', 1)->get()->unique('user_id');
            $approvedUserIds = $approvedUsers->pluck('user_id');
            $approvedUsersWithRoles = \App\Models\User::whereIn('id', $approvedUserIds)->with('roles')->get();

            $hasApprovedKasubbag = $approvedUsersWithRoles->contains(fn($user) => $user->hasRole('Kepala Subbagian Tata Usaha'));
            $hasApprovedPengurus = $approvedUsersWithRoles->contains(fn($user) => $user->hasRole('Pengurus Barang'));

            $isPengurus = auth()->user()->hasRole('Pengurus Barang');
            $canUpload = $hasApprovedKasubbag && !$hasApprovedPengurus && $isPengurus;
            $lampiranCount = $permintaan->lampiran->count();
        @endphp

        <div>
            <x-card title="Foto Pengiriman" class="mb-3">


                {{-- Tombol unggah & simpan hanya jika boleh upload dan belum ada lampiran --}}
                @if ($canUpload && $lampiranCount < 1)
                    <div wire:loading wire:target="newAttachments">
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
                                <a href="{{ asset('storage/dokumenKontrak/' . $attachment->path) }}" target="_blank"
                                    class="text-gray-800 hover:underline">
                                    {{ basename($attachment->path) }}
                                </a>
                            </span>

                            {{-- Tombol hapus foto untuk edit mode --}}
                            @if(auth()->user()->hasRole('Pengurus Barang') && $permintaan->status == 2 && isset($showEditFoto) && $showEditFoto)
                                <button onclick="confirmDeleteFoto({{ $attachment->id }})"
                                    class="text-red-500 hover:text-red-700 text-sm px-2 py-1 rounded">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endif
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
                                <a href="{{ $attachment->temporaryUrl() }}" target="_blank"
                                    class="text-gray-800 hover:underline">
                                    {{ $attachment->getClientOriginalName() }}
                                </a>
                            </span>
                            @if ($canUpload)
                                <button wire:click="removeAttachment({{ $index }})"
                                    class="text-red-500 hover:text-red-700">&times;</button>
                            @endif
                        </div>
                    @endforeach

                    @if (!$canUpload && $lampiranCount < 1 && count($this->attachments) < 1)
                        <div class="flex justify-center text-xl font-semibold">Belum ada unggahan</div>
                    @endif
                </div>
            </x-card>
        </div>
    </div>

    <!-- Tanda Tangan Driver & Keamanan - Full Width -->
    <div class="mb-6">
        @php
            $canInputDriverInfo = $hasApprovedKasubbag && !$hasApprovedPengurus && $isPengurus;
        @endphp

        <x-card title="Tanda Tangan Driver & Keamanan">
            {{-- Tombol Edit untuk data pengiriman --}}
            @if(auth()->user()->hasRole('Pengurus Barang') && $permintaan->status == 2 && $permintaan->driver && $permintaan->security && $permintaan->nopol)
                <div class="flex justify-end mb-3">
                    @if(!$isEditMode)
                        <button wire:click="enableEditMode"
                            class="text-sm px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                            <i class="fas fa-edit mr-1"></i> Edit Data Pengiriman
                        </button>
                    @else
                        <div class="flex space-x-2">
                            <button wire:click="updateDriverInfo"
                                class="text-sm px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition">
                                <i class="fas fa-save mr-1"></i> Simpan
                            </button>
                            <button wire:click="cancelEdit"
                                class="text-sm px-3 py-1 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                                <i class="fas fa-times mr-1"></i> Batal
                            </button>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Form Input Driver, Security, dan Nopol --}}
            @if ($canInputDriverInfo && (!$permintaan->driver || !$permintaan->security || !$permintaan->nopol))
                <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <h5 class="font-semibold text-blue-800 mb-3">Input Data Pengiriman</h5>

                    @if (session()->has('message'))
                        <div class="mb-3 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="mb-3 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Driver</label>
                            <select wire:model="selectedDriverId"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Pilih Driver</option>
                                @foreach(\App\Models\Driver::where('unit_id', auth()->user()->unit_id)->get() as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->nama }}</option>
                                @endforeach
                            </select>
                            @error('selectedDriverId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Security</label>
                            <select wire:model="selectedSecurityId"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Pilih Security</option>
                                @foreach(\App\Models\Security::where('unit_id', auth()->user()->unit_id)->get() as $security)
                                    <option value="{{ $security->id }}">{{ $security->nama }}</option>
                                @endforeach
                            </select>
                            @error('selectedSecurityId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Polisi</label>
                            <input type="text" wire:model="inputNopol"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"
                                placeholder="Masukkan nomor polisi">
                            @error('inputNopol') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mt-3">
                        <button wire:click="saveDriverInfo"
                            class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                            Simpan Data Pengiriman
                        </button>
                    </div>
                </div>
            @endif

            {{-- Informasi Driver --}}
            <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                @if($isEditMode)
                    {{-- Edit Mode --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Driver</label>
                            <select wire:model="editDriverId"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Pilih Driver</option>
                                @foreach(\App\Models\Driver::where('unit_id', auth()->user()->unit_id)->get() as $driver)
                                    <option value="{{ $driver->id }}">{{ $driver->nama }}</option>
                                @endforeach
                            </select>
                            @error('editDriverId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Security</label>
                            <select wire:model="editSecurityId"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                                <option value="">Pilih Security</option>
                                @foreach(\App\Models\Security::where('unit_id', auth()->user()->unit_id)->get() as $security)
                                    <option value="{{ $security->id }}">{{ $security->nama }}</option>
                                @endforeach
                            </select>
                            @error('editSecurityId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Polisi</label>
                            <input type="text" wire:model="editNopol"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500"
                                placeholder="Masukkan nomor polisi">
                            @error('editNopol') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>
                @else
                    {{-- View Mode --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <span class="font-semibold text-gray-600">Driver:</span>
                            <span class="ml-2">{{ $permintaan->driver ?? 'Belum ditentukan' }}</span>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-600">Security:</span>
                            <span class="ml-2">{{ $permintaan->security ?? 'Belum ditentukan' }}</span>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-600">No. Polisi:</span>
                            <span class="ml-2">{{ $permintaan->nopol ?? 'Belum ditentukan' }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="font-semibold text-sm">Tanda Tangan Driver</h4>
                        {{-- Tombol ulangi TTD untuk Pengurus Barang saat status sedang dikirim --}}
                        @if(auth()->user()->hasRole('Pengurus Barang') && $permintaan->status == 2 && $signature)
                            <button onclick="confirmRetrySignature('driver')"
                                class="text-xs px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                <i class="fas fa-redo mr-1"></i> Ulangi TTD
                            </button>
                        @endif
                    </div>
                    @if ($signature)
                        <img src="{{ asset('storage/ttdPengiriman/' . $signature) }}" class="border rounded shadow-sm"
                            height="100" alt="TTD Driver">
                    @elseif ($canUpload || (auth()->user()->hasRole('Pengurus Barang') && $permintaan->status == 2))
                        <canvas id="signature-pad-driver" class="border rounded shadow-sm h-25 bg-transparent"
                            height="100"></canvas>
                        <div class="mt-2">
                            <button onclick="resetCanvas('driver')"
                                class="bg-danger-600 text-danger-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">Hapus</button>
                            <button onclick="saveSignature('driver')"
                                class="bg-success-600 text-success-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">Simpan</button>
                        </div>
                    @else
                        <div class="text-center text-gray-500 font-medium border rounded p-4">Belum ada tanda tangan</div>
                    @endif
                </div>

                <div>
                    <div class="flex justify-between items-center mb-2">
                        <h4 class="font-semibold text-sm">Tanda Tangan Keamanan</h4>
                        {{-- Tombol ulangi TTD untuk Pengurus Barang saat status sedang dikirim --}}
                        @if(auth()->user()->hasRole('Pengurus Barang') && $permintaan->status == 2 && $securitySignature)
                            <button onclick="confirmRetrySignature('security')"
                                class="text-xs px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                <i class="fas fa-redo mr-1"></i> Ulangi TTD
                            </button>
                        @endif
                    </div>
                    @if ($securitySignature)
                        <img src="{{ asset('storage/ttdPengiriman/' . $securitySignature) }}"
                            class="border rounded shadow-sm" height="100" alt="TTD Keamanan">
                    @elseif ($canUpload || (auth()->user()->hasRole('Pengurus Barang') && $permintaan->status == 2))
                        <canvas id="signature-pad-security" class="border rounded shadow-sm h-25 bg-transparent"
                            height="100"></canvas>
                        <div class="mt-2">
                            <button onclick="resetCanvas('security')"
                                class="bg-danger-600 text-danger-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">Hapus</button>
                            <button onclick="saveSignature('security')"
                                class="bg-success-600 text-success-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">Simpan</button>
                        </div>
                    @else
                        <div class="text-center text-gray-500 font-medium border rounded p-4">Belum ada tanda tangan</div>
                    @endif
                </div>
            </div>
        </x-card>
    </div>

    <!-- Daftar Permintaan dan Approval - Full Width -->
    <div>
            <x-card title="daftar permintaan">
                <div class="mb-6">
                    <livewire:list-permintaan-material :permintaan="$permintaan" />
                </div>
                <livewire:approval-material :permintaan="$permintaan" />
            </x-card>
    </div>


</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script>
        // Fungsi mandiri untuk SweetAlert QR Code
        function showQrAlert(type, message) {
            // Karena QR hanya keluar saat status disetujui,
            // handle hanya untuk status dikirim/selesai dengan icon X
            Swal.fire({
                icon: 'error',  // Selalu gunakan tanda silang
                title: 'Gagal Scan QR',  // Title tetap
                text: message,  // Text dinamis: "permintaan sedang dikirim" atau "telah selesai"
                showConfirmButton: true,
                confirmButtonText: 'OK',
                confirmButtonColor: '#EF4444', // red
                allowOutsideClick: false,
                allowEscapeKey: false,
                customClass: {
                    popup: 'qr-alert-popup',
                    title: 'qr-alert-title',
                    content: 'qr-alert-content'
                }
            });
        }

        // Event listener untuk QR Code alerts dari Livewire
        Livewire.on('showAlert', (params) => {
            const { type, message } = params;
            showQrAlert(type, message);
        });

        // Event listener untuk SweetAlert success
        Livewire.on('swal:success', (params) => {
            const { title, text } = params;
            Swal.fire({
                icon: 'success',
                title: title,
                text: text,
                showConfirmButton: true,
                confirmButtonText: 'OK',
                confirmButtonColor: '#10B981'
            });
        });
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
                    // FIX: Pastikan parameter dikirim dengan benar
                    @this.call('downloadDoc', { type: docType, withSign: true });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    @this.call('downloadDoc', { type: docType, withSign: false });
                }
            });
        }
    </script>
    <script>
        // Function untuk konfirmasi ulangi tanda tangan
        function confirmRetrySignature(type) {
            // Langsung reset tanpa konfirmasi SweetAlert
            if (type === 'driver') {
                @this.call('resetSignatureDriver');
            } else {
                @this.call('resetSignatureSecurity');
            }
            // Inisialisasi ulang signature pad setelah reset
            setTimeout(() => {
                initializeSignaturePad();
            }, 100);
        }

        let canvasDriver, canvasSecurity, signaturePadDriver, signaturePadSecurity;

        // Inisialisasi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function () {
            initializeSignaturePad();
        });

        // Event listener untuk reset signature
        Livewire.on('signatureReset', () => {
            setTimeout(() => {
                initializeSignaturePad();
            }, 200);
        });

        // Event listener untuk refresh component
        document.addEventListener('livewire:init', () => {
            initializeSignaturePad();
        });

        // Event listener untuk Livewire component updates
        document.addEventListener('livewire:updated', () => {
            setTimeout(() => {
                initializeSignaturePad();
            }, 100);
        });

        function initializeSignaturePad() {
            canvasDriver = document.getElementById('signature-pad-driver');
            if (canvasDriver && (!signaturePadDriver || signaturePadDriver.canvas !== canvasDriver)) {
                signaturePadDriver = new SignaturePad(canvasDriver);
                console.log('Signature pad driver initialized');
            }
            canvasSecurity = document.getElementById('signature-pad-security');
            if (canvasSecurity && (!signaturePadSecurity || signaturePadSecurity.canvas !== canvasSecurity)) {
                signaturePadSecurity = new SignaturePad(canvasSecurity);
                console.log('Signature pad security initialized');
            }
        }

        function saveSignature(type) {
            let signatureData;
            let isEmpty = false;

            if (type == 'driver') {
                // Pastikan signature pad driver sudah diinisialisasi
                if (!signaturePadDriver) {
                    console.log("Canvas tanda tangan driver belum tersedia, mencoba inisialisasi ulang...");
                    initializeSignaturePad();
                    // Tunggu sebentar agar signature pad terinisialisasi
                    setTimeout(() => {
                        saveSignature(type);
                    }, 100);
                    return;
                }
                isEmpty = signaturePadDriver.isEmpty();
                signatureData = signaturePadDriver.toDataURL('image/png');
            } else {
                // Pastikan signature pad security sudah diinisialisasi
                if (!signaturePadSecurity) {
                    console.log("Canvas tanda tangan security belum tersedia, mencoba inisialisasi ulang...");
                    initializeSignaturePad();
                    // Tunggu sebentar agar signature pad terinisialisasi
                    setTimeout(() => {
                        saveSignature(type);
                    }, 100);
                    return;
                }
                isEmpty = signaturePadSecurity.isEmpty();
                signatureData = signaturePadSecurity.toDataURL('image/png');
            }

            if (isEmpty) {
                console.log("Tanda tangan belum diisi. Silakan buat tanda tangan terlebih dahulu.");
                return;
            }

            @this.call('signatureSaved', signatureData, type);
        }

        function resetCanvas(type) {
            if (type == 'driver') {
                // Pastikan signature pad driver sudah diinisialisasi
                if (!signaturePadDriver) {
                    initializeSignaturePad();
                }
                if (signaturePadDriver) {
                    signaturePadDriver.clear();
                }
            } else {
                // Pastikan signature pad security sudah diinisialisasi
                if (!signaturePadSecurity) {
                    initializeSignaturePad();
                }
                if (signaturePadSecurity) {
                    signaturePadSecurity.clear();
                }
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