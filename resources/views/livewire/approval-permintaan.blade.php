<div>
    <div class="flex w-full justify-evenly border-t-4 py-6">
        <div class="">
            <div class="block font-semibold text-center mb-2 text-gray-900">Penulis</div>

            <div class="text-sm border-b-2 ">
                <div class="flex justify-between px-3">
                    <span class="mr-9 {{ $penulis->id == auth()->id() ? 'font-bold' : '' }}">
                        {{-- {{ $penulis->id == auth()->id() ? 'Anda' : $penulis->name }} --}}
                        {{ false ? 'Anda' : $penulis->name }}
                    </span>
                </div>
            </div>

        </div>
        @if ($permintaan->jenisStok->nama == 'Umum')
            <div class="">
                <div class="block font-semibold text-center mb-2 text-gray-900">
                    Kepala Seksi</div>
                <table class="w-full mt-3">
                    @foreach ($kepalaseksiList as $kepalaseksi)
                        <tr class="text-sm border-b-2 ">
                            <td class="flex justify-between px-3">
                                <span class="mr-9 {{ $kepalaseksi->id == auth()->id() ? 'font-bold' : '' }}">
                                    {{-- {{ $kepalaseksi->id == auth()->id() ? 'Anda' : $kepalaseksi->name }} --}}
                                    {{ false ? 'Anda' : $kepalaseksi->name }}
                                </span>
                                <i
                                    class="my-1 fa-solid {{ is_null(
                                        optional($kepalaseksi->persetujuanPermintaan->where('detail_permintaan_id', $permintaan->id ?? 0)->first())->status,
                                    )
                                        ? 'fa-circle-question text-secondary-600'
                                        : (optional($kepalaseksi->persetujuanPermintaan->where('detail_permintaan_id', $permintaan->id ?? 0)->first())->status
                                            ? 'fa-circle-check text-success-500'
                                            : 'fa-circle-xmark text-danger-500') }}">
                                </i>


                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="">
                <div class="block font-semibold text-center mb-2 text-gray-900">
                    Kepala Sub Bagian</div>
                <table class="w-full mt-3">
                    @foreach ($kasubagList as $kasubag)
                        <tr class="text-sm border-b-2 ">
                            <td class="flex justify-between px-3">
                                <span class="mr-9 {{ $kasubag->id == auth()->id() ? 'font-bold' : '' }}">
                                    {{-- {{ $kasubag->id == auth()->id() ? 'Anda' : $kasubag->name }} --}}
                                    {{ false ? 'Anda' : $kasubag->name }}
                                </span>
                                <i
                                    class="my-1 fa-solid {{ is_null(
                                        optional($kasubag->persetujuanPermintaan->where('detail_permintaan_id', $permintaan->id ?? 0)->first())->status,
                                    )
                                        ? 'fa-circle-question text-secondary-600'
                                        : (optional($kasubag->persetujuanPermintaan->where('detail_permintaan_id', $permintaan->id ?? 0)->first())->status
                                            ? 'fa-circle-check text-success-500'
                                            : 'fa-circle-xmark text-danger-500') }}">
                                </i>


                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @elseif($permintaan->jenisStok->nama == 'Spare Part')
            <div class="">
                <div class="block font-semibold text-center mb-2 text-gray-900">
                    Kepala Sub Bagian Tata Usaha</div>
                <table class="w-full mt-3">
                    @foreach ($tuList as $tu)
                        <tr class="text-sm border-b-2 ">
                            <td class="flex justify-between px-3">
                                <span class="mr-9 {{ $tu->id == auth()->id() ? 'font-bold' : '' }}">
                                    {{ false ? 'Anda' : $tu->name }}
                                    {{-- {{ $tu->id == auth()->id() ? 'Anda' : $tu->name }} --}}
                                </span>
                                <i
                                    class="my-1 fa-solid {{ is_null(
                                        optional($tu->persetujuanPermintaan->where('detail_permintaan_id', $permintaan->id ?? 0)->first())->status,
                                    )
                                        ? 'fa-circle-question text-secondary-600'
                                        : (optional($tu->persetujuanPermintaan->where('detail_permintaan_id', $permintaan->id ?? 0)->first())->status
                                            ? 'fa-circle-check text-success-500'
                                            : 'fa-circle-xmark text-danger-500') }}">
                                </i>


                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="">
                <div class="block font-semibold text-center mb-2 text-gray-900">
                    Kepala Kepala Unit/Suku Dinas</div>
                <table class="w-full mt-3">
                    @foreach (Str::contains($permintaan->unit->nama, 'Suku Dinas') ? $kasudinList : $kaunitList as $kaunit)
                        <tr class="text-sm border-b-2 ">
                            <td class="flex justify-between px-3">
                                <span class="mr-9 {{ $kaunit->id == auth()->id() ? 'font-bold' : '' }}">
                                    {{-- {{ $kaunit->id == auth()->id() ? 'Anda' : $kaunit->name }} --}}
                                    {{ false ? 'Anda' : $kaunit->name }}
                                </span>
                                <i
                                    class="my-1 fa-solid {{ is_null(
                                        optional($kaunit->persetujuanPermintaan->where('detail_permintaan_id', $permintaan->id ?? 0)->first())->status,
                                    )
                                        ? 'fa-circle-question text-secondary-600'
                                        : (optional($kaunit->persetujuanPermintaan->where('detail_permintaan_id', $permintaan->id ?? 0)->first())->status
                                            ? 'fa-circle-check text-success-500'
                                            : 'fa-circle-xmark text-danger-500') }}">
                                </i>


                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @elseif($permintaan->jenisStok->nama == 'Material')
            <div class="">
                <div class="block font-semibold text-center mb-2 text-gray-900">
                    Kepala Unit/Suku Dinas</div>
                <table class="w-full mt-3">
                    @foreach (Str::contains($permintaan->unit->nama, 'Suku Dinas') ? $kasudinList : $kaunitList as $kasudin)
                        <tr class="text-sm border-b-2 ">
                            <td class="flex justify-between px-3">
                                <span class="mr-9 {{ $kasudin->id == auth()->id() ? 'font-bold' : '' }}">
                                    {{-- {{ $kasudin->id == auth()->id() ? 'Anda' : $kasudin->name }} --}}
                                    {{ false ? 'Anda' : $kasudin->name }}
                                </span>
                                <i
                                    class="my-1 fa-solid {{ is_null(
                                        optional($kasudin->persetujuanPermintaan->where('detail_permintaan_id', $permintaan->id ?? 0)->first())->status,
                                    )
                                        ? 'fa-circle-question text-secondary-600'
                                        : (optional($kasudin->persetujuanPermintaan->where('detail_permintaan_id', $permintaan->id ?? 0)->first())->status
                                            ? 'fa-circle-check text-success-500'
                                            : 'fa-circle-xmark text-danger-500') }}">
                                </i>


                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="">
                <div class="block font-semibold text-center mb-2 text-gray-900">
                    Kepala Seksi Pemeliharaan</div>
                <table class="w-full mt-3">
                    @foreach ($pemeliharaanList as $pemeliharaan)
                        <tr class="text-sm border-b-2 ">
                            <td class="flex justify-between px-3">
                                <span class="mr-9 {{ $pemeliharaan->id == auth()->id() ? 'font-bold' : '' }}">
                                    {{-- {{ $pemeliharaan->id == auth()->id() ? 'Anda' : $pemeliharaan->name }} --}}
                                    {{ false ? 'Anda' : $pemeliharaan->name }}
                                </span>
                                <i
                                    class="my-1 fa-solid {{ is_null(
                                        optional($pemeliharaan->persetujuanPermintaan->where('detail_permintaan_id', $permintaan->id ?? 0)->first())->status,
                                    )
                                        ? 'fa-circle-question text-secondary-600'
                                        : (optional($pemeliharaan->persetujuanPermintaan->where('detail_permintaan_id', $permintaan->id ?? 0)->first())->status
                                            ? 'fa-circle-check text-success-500'
                                            : 'fa-circle-xmark text-danger-500') }}">
                                </i>


                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>

        @endif
        <div class="">
            <div class="block font-semibold text-center mb-2 text-gray-900">
                Penjaga Gudang</div>
            <table class="w-full mt-3">
                @foreach ($pjGudangList as $pjGudang)
                    <tr class="text-sm border-b-2 ">
                        <td class="flex justify-between px-3">
                            <span class="mr-9 {{ $pjGudang->id == auth()->id() ? 'font-bold' : '' }}">
                                {{-- {{ $pjGudang->id == auth()->id() ? 'Anda' : $pjGudang->name }} --}}
                                {{ false ? 'Anda' : $pjGudang->name }}
                            </span>
                            <i
                                class="my-1 fa-solid {{ is_null(
                                    optional($pjGudang->persetujuanPermintaan->where('detail_permintaan_id', $permintaan->id ?? 0)->first())->status,
                                )
                                    ? 'fa-circle-question text-secondary-600'
                                    : (optional($pjGudang->persetujuanPermintaan->where('detail_permintaan_id', $permintaan->id ?? 0)->first())->status
                                        ? 'fa-circle-check text-success-500'
                                        : 'fa-circle-xmark text-danger-500') }}">
                            </i>


                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

    </div>
    @if ($showButton)
        <div class="flex">
            <div class="flex space-x-2 justify-center w-full">
                @if ($isLastUser || $lastPj || $lastPpk || $lastPptk || ($lastPjGudang && $permintaan->cancel === 0))
                    {{-- // || $lastKepalaseksi || $lastKasubag --}}
                    <div class="flex flex-col items-center">
                        <input type="file" wire:model="newApprovalFiles" id="approvalFiles" multiple class="hidden">
                        <button type="button" onclick="document.getElementById('approvalFiles').click()"
                            class="text-primary-700 bg-gray-200 border text-center border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition">
                            Unggah File Persetujuan
                        </button>
                        @error('approvalFiles.*')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
                @if ($permintaan->status !== 0 && (!$lastPjGudang || ($lastPjGudang && $permintaan->cancel === 0)))
                    <button type="button"
                        onclick="{{ $isLastUser || $lastPj || $lastPpk || $lastPptk || ($lastPjGudang && $permintaan->cancel === 0) ? 'submitApprovalWithFile()' : 'confirmApprove()' }}"
                        {{-- // || $lastKepalaseksi|| $lastKasubag --}}
                        class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                        {{ !$lastPjGudang ? 'Setuju' : 'Selesai' }}
                    </button>
                    @if (!$lastPjGudang)
                        <button type="button" onclick="confirmReject()"
                            class="text-danger-900 bg-danger-100 hover:bg-danger-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                            Tidak Setuju
                        </button>
                    @endif
                @endif
                {{-- Tombol Selesai atau Batalkan --}}

            </div>
        </div>
    @endif
    @if ($isPenulis && ($lastKasubagDone || $lastkasudinDone || $lastkaunitDone) && is_null($permintaan->cancel))
        <div class="flex justify-center">
            <button type="button" onclick="confirmCompletion()"
                class="text-green-900 bg-green-100 hover:bg-green-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Lanjutkan
            </button>
            <button type="button" onclick="confirmCancellation()"
                class="text-danger-900 bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Batalkan
            </button>
        </div>
    @endif

    @if (count($files))
        <div class="flex justify-center">
            <div class="mt-4 gap-4 w-3/5">
                @if ($files)
                    @foreach ($files as $index => $attachment)
                        <div
                            class="flex items-center justify-between border-b-2 p-3 rounded my-2 shadow-sm bg-white overflow-hidden">
                            <span class="flex items-center space-x-4">
                                @php
                                    $fileType =
                                        $attachment instanceof \Illuminate\Http\UploadedFile
                                            ? $attachment->getClientOriginalExtension()
                                            : pathinfo($attachment, PATHINFO_EXTENSION);
                                @endphp
                                <!-- Icon Based on File Type -->
                                <span class="text-primary-600 text-2xl">
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
                                <span>
                                    <a href="{{ $attachment instanceof \Illuminate\Http\UploadedFile ? $attachment->temporaryUrl() : asset('storage/dokumen-persetujuan-permintaan/' . $attachment) }}"
                                        target="_blank" class="text-gray-800 hover:underline">
                                        {{ $attachment instanceof \Illuminate\Http\UploadedFile ? $attachment->getClientOriginalName() : basename($attachment) }}
                                    </a>
                                </span>
                            </span>

                        </div>
                    @endforeach
                @else
                    <p class="text-gray-500 text-sm">No files uploaded.</p>
                @endif


            </div>
        </div>
    @endif
    @if ($approvalFiles)
        <div class="flex justify-center">
            <div class="gap-4 w-3/5">
                @foreach ($approvalFiles as $index => $attachment)
                    <div class="flex items-center justify-between border-b-2 p-3 rounded my-2 shadow-sm bg-white">
                        <span class="flex items-center space-x-4">
                            @php
                                $fileType =
                                    $attachment instanceof \Illuminate\Http\UploadedFile
                                        ? $attachment->getClientOriginalExtension()
                                        : pathinfo($attachment, PATHINFO_EXTENSION);
                            @endphp
                            <!-- Icon Based on File Type -->
                            <span class="text-primary-600 text-2xl">
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
                            <span>
                                <a href="{{ $attachment instanceof \Illuminate\Http\UploadedFile ? $attachment->temporaryUrl() : asset('storage/uploads/' . $attachment) }}"
                                    target="_blank" class="text-gray-800 hover:underline">
                                    {{ $attachment instanceof \Illuminate\Http\UploadedFile ? $attachment->getClientOriginalName() : basename($attachment) }}
                                </a>
                            </span>
                        </span>

                        <!-- Remove Button -->
                        <button wire:click="removeApprovalFile({{ $index }})"
                            class="text-red-500 hover:text-red-700 text-lg font-bold px-2">
                            &times;
                        </button>
                    </div>
                @endforeach

            </div>
        </div>
    @endif


</div>

@push('scripts')
    <script>
        let fileCount = 0;

        function confirmApprove() {
            Swal.fire({
                title: 'Konfirmasi Persetujuan',
                text: 'Apakah Anda yakin ingin menyetujui kontrak ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Setuju',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('approveConfirmed');
                }
            });
        }

        function submitApprovalWithFile() {
            const fileInput = document.getElementById('approvalFiles');


            if (!fileCount) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Tidak Ditemukan',
                    text: 'Harap unggah file sebelum menyetujui kontrak.',
                });
                return;
            }
            confirmApprove();
            // @this.call('approveWithFile', fileInput.files[0]);
        }

        function confirmReject() {
            Swal.fire({
                title: 'Keterangan',
                input: 'textarea',
                inputPlaceholder: 'Masukkan keterangan',
                inputAttributes: {
                    'aria-label': 'Masukkan alasan Anda'
                },
                showCancelButton: true,
                confirmButtonText: 'Kirim',
                cancelButtonText: 'Batal',
                preConfirm: (inputValue) => {
                    if (!inputValue || inputValue.trim() === '') {
                        Swal.showValidationMessage('Keterangan tidak boleh kosong!');
                        return false; // Prevents submission
                    }
                    return inputValue; // Allows submission
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('rejectConfirmed', result.value);
                }
            });
        }

        document.addEventListener('file_approval', function(event) {
            fileCount = event.detail.count; // Get the count from the event detail
        });
    </script>
@endpush
@push('scripts')
    <script>
        function confirmCompletion() {
            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin ingin menyelesaikan permintaan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Selesaikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.markAsCompleted();
                    Swal.fire(
                        'Berhasil!',
                        'Permintaan telah ditandai sebagai selesai.',
                        'success'
                    );
                }
            });
        }

        function confirmCancellation() {
            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin ingin membatalkan permintaan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Batalkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.cancelRequest();
                    Swal.fire(
                        'Berhasil!',
                        'Permintaan telah dibatalkan.',
                        'success'
                    );
                }
            });
        }
    </script>
@endpush
