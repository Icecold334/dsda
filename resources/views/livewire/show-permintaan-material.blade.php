<div>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">DETAIL PERMINTAAN</h1>
        <div>

            <a wire:click='spb'
                class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Unduh
                SPB</a>
            @if ($permintaan->persetujuan()->where('is_approved',1)->get()->unique('user_id')->count() >= 2)
            <a wire:click='sppb'
                class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Unduh
                SPPB</a>

            <a wire:click='qrCode'
                class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Unduh
                QR-Code</a>
            @endif
            @if ($permintaan->persetujuan()->where('is_approved',1)->get()->unique('user_id')->count() >= 3)
            <a wire:click='suratJalan'
                class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Unduh
                Surat Jalan</a>
            @endif
            <a href="/permintaan/material"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>

        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <x-card title="data umum" class="mb-3">
                <table class="w-full">
                    @if ($permintaan->rab_id)
                    <tr class="font-semibold ">
                        <td>RAB</td>
                        <td>{{ $permintaan->rab->nama }}</td>
                    </tr>
                    @endif
                    <tr class="font-semibold ">
                        <td class="w-[40%]">Lokasi Gudang</td>
                        <td>{{ $permintaan->lokasiStok->nama }}</td>
                    </tr>
                    <tr class="font-semibold ">
                        <td class="w-[40%]">Kode Permintaan</td>
                        <td>{{ $permintaan->kode_permintaan }}</td>
                    </tr>
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
                        <td>Tanggal Permintaan</td>
                        <td> {{date('j F Y', $permintaan->tanggal_permintaan) }}</td>
                    </tr>
                    {{-- <tr class="font-semibold">
                        <td>Unit Kerja</td>
                        <td>{{ $permintaan->unit->nama }}</td>
                    </tr>
                    <tr class="font-semibold">
                        <td>Sub-Unit</td>
                        <td>{{ $permintaan->subUnit->nama ?? '---' }}</td>
                    </tr> --}}
                    <tr class="font-semibold {{ !$permintaan->rab_id?'':'hidden' }}">
                        <td>Keterangan</td>
                        <td>{{ $permintaan->keterangan ?? '---' }}</td>
                    </tr>
                </table>
            </x-card>
        </div>
        <div
            class="grid grid-cols-2 gap-6 {{ $permintaan->persetujuan()->where('is_approved',1)->get()->unique('user_id')->count() >= 3 ?'':'hidden' }}">
            <x-card title="Foto Barang" class="mb-3  ">
                <div wire:loading wire:target="newAttachments">
                    <livewire:loading>
                </div>
                <input type="file" wire:model.live="newAttachments" multiple class="hidden" id="fileUpload">
                <label for="fileUpload"
                    class="{{ $permintaan->persetujuan()->where('is_approved',1)->count() <= 4 && !$isOut ?'':'hidden' }}  text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200 cursor-pointer">
                    + Unggah
                </label>
                <a wire:click='saveDoc'
                    class="cursor-pointer {{ count($this->attachments) ?'':'hidden' }} text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Simpan</a>
                <div class="mt-3 max-h-24 overflow-auto">
                    @if ($isOut)
                    @forelse ($permintaan->lampiran as $attachment)
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
                    @else

                    @foreach ($attachments as $index => $attachment)
                    <div class="flex items-center justify-between border-b-4 p-2 rounded my-1">
                        <span class="flex items-center space-x-3">
                            @php
                            $fileType = $attachment->getClientOriginalExtension();
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

                            <!-- File name with underline on hover and a temporary URL -->
                            <span>
                                <a href="{{ $attachment->temporaryUrl() }}" target="_blank"
                                    class="text-gray-800 hover:underline">
                                    {{ $attachment->getClientOriginalName() }}
                                </a>
                            </span>
                        </span>

                        <!-- Remove Button -->
                        <button wire:click="removeAttachment({{ $index }})"
                            class="text-red-500 hover:text-red-700">&times;
                        </button>
                    </div>
                    @endforeach
                    @endif

                </div>
            </x-card>
            <x-card title="Tanda Tangan Driver" class="mb-3">
                <div>
                    @if (!$signature)
                    <canvas id="signature-pad" class="border rounded shadow-sm h-25 bg-transparent"
                        height="100"></canvas>
                    <button wire:click="resetSignature" class="btn btn-sm btn-warning mt-2"
                        onclick="resetCanvas()">Hapus</button>
                    <button class="btn btn-sm btn-primary mt-2" onclick="saveSignature()">Simpan</button>
                    @else
                    <img src="{{ asset('storage/ttdPengiriman/'.$signature) }}" class="border rounded shadow-sm"
                        height="100" alt="Signature Preview">
                    <br>
                    {{-- <button wire:click="resetSignature" class="btn btn-sm btn-danger mt-2">Reset </button> --}}
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
    let canvas, signaturePad;

    // document.addEventListener("livewire:load", () => {
        initializeSignaturePad();
    // });

    Livewire.on('signatureReset', () => {
        initializeSignaturePad();
    });

    function initializeSignaturePad() {
        canvas = document.getElementById('signature-pad');
        if (canvas) {
            signaturePad = new SignaturePad(canvas);
        }
    }

    function saveSignature() {
        if (signaturePad.isEmpty()) {
            alert("Tanda tangan belum diisi.");
            return;
        }

        let signatureData = signaturePad.toDataURL('image/png');
        @this.call('signatureSaved', signatureData);
    }

    function resetCanvas() {
        signaturePad.clear();
    }
</script>
@endpush