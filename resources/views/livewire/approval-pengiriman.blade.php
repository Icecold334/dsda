<div>
    {{-- {{ ($lastPptk) }}
    {{ $checkPreviousApproval }} --}}
    <div class="flex w-full justify-evenly border-t-4 py-6">
        <div class="">
            <div class="block font-semibold text-center mb-2 text-gray-900">Penulis </div>

            <div class="text-sm border-b-2 ">
                <div class="flex justify-between px-3">
                    <span class="mr-9 {{ $penulis->id == auth()->id() ? 'font-bold' : '' }}">
                        {{ $penulis->id == auth()->id() ? 'Anda' : $penulis->name }}
                    </span>
                </div>
            </div>

        </div>

        {{-- @role('penanggungjawab') --}}

        {{-- <div class="">
            <div class="block font-semibold text-center mb-2 text-gray-900">Penanggung Jawab</div>
            <table class="w-full mt-3">
                @foreach ($pjList as $pj)
                    <tr class="text-sm border-b-2 ">
                        <td class="flex justify-between px-3">
                            <span class="mr-9 {{ $pj->id == auth()->id() ? 'font-bold' : '' }}">
                                {{ $pj->id == auth()->id() ? 'Anda' : $pj->name }}
                            </span>
                            <i
                                class="my-1 fa-solid {{ is_null(
                                    optional($pj->persetujuanPengiriman->where('detail_pengiriman_id', $pengiriman->id ?? 0)->first())->status,
                                )
                                    ? 'fa-circle-question text-secondary-600'
                                    : (optional($pj->persetujuanPengiriman->where('detail_pengiriman_id', $pengiriman->id ?? 0)->first())->status
                                        ? 'fa-circle-check text-success-500'
                                        : 'fa-circle-xmark text-danger-500') }}">
                            </i>


                        </td>
                    </tr>
                @endforeach
            </table>
        </div> --}}
        <div class="">
            <div class="block font-semibold text-center mb-2 text-gray-900">
                Penerima Barang</div>
            <table class="w-full mt-3">
                @foreach ($penerimaList as $penerima)
                    <tr class="text-sm border-b-2 ">
                        <td class="flex justify-between px-3">
                            <span class="mr-9 {{ $penerima->id == auth()->id() ? 'font-bold' : '' }}">
                                {{ $penerima->id == auth()->id() ? 'Anda' : $penerima->name }}
                            </span>
                            <i
                                class="my-1 fa-solid {{ ($indikatorPenerima == 0) 
                                    ?'fa-circle-question text-secondary-600' : 'fa-circle-check text-success-500'}}" id="penerimaApp">
                            </i>
                            {{-- <i
                                class="my-1 fa-solid {{ is_null(
                                    optional($penerima->persetujuanPengiriman->where('detail_pengiriman_id', $pengiriman->id ?? 0)->first())->status,
                                )
                                    ? 'fa-circle-question text-secondary-600'
                                    : (optional($penerima->persetujuanPengiriman->where('detail_pengiriman_id', $pengiriman->id ?? 0)->first())->status
                                        ? 'fa-circle-check text-success-500'
                                        : 'fa-circle-xmark text-danger-500') }}">
                            </i> --}}


                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="">
            <div class="block font-semibold text-center mb-2 text-gray-900">
                Pemeriksa Barang</div>
            <table class="w-full mt-3">
                @foreach ($pemeriksaList as $pemeriksa)
                    <tr class="text-sm border-b-2 ">
                        <td class="flex justify-between px-3">
                            <span class="mr-9 {{ $pemeriksa->id == auth()->id() ? 'font-bold' : '' }}">
                                {{ $pemeriksa->id == auth()->id() ? 'Anda' : $pemeriksa->name }}
                            </span>
                            <i
                                class="my-1 fa-solid {{ is_null(
                                    optional($pemeriksa->persetujuanPengiriman->where('detail_pengiriman_id', $pengiriman->id ?? 0)->first())->status,
                                )
                                    ? 'fa-circle-question text-secondary-600'
                                    : (optional($pemeriksa->persetujuanPengiriman->where('detail_pengiriman_id', $pengiriman->id ?? 0)->first())->status
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
                Pejabat Pelaksana Teknis Kegiatanss</div>
            <table class="w-full mt-3">
                @foreach ($pptkList as $pptk)
                    <tr class="text-sm border-b-2 ">
                        <td class="flex justify-between px-3">
                            <span class="mr-9 {{ $pptk->id == auth()->id() ? 'font-bold' : '' }}">
                                {{ $pptk->id == auth()->id() ? 'Anda' : $pptk->name }}
                            </span>
                            <i
                                class="my-1 fa-solid {{ is_null(
                                    optional($pptk->persetujuanPengiriman->where('detail_pengiriman_id', $pengiriman->id ?? 0)->first())->status,
                                )
                                    ? 'fa-circle-question text-secondary-600'
                                    : (optional($pptk->persetujuanPengiriman->where('detail_pengiriman_id', $pengiriman->id ?? 0)->first())->status
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
                Pejabat Pembuat Komitmen</div>
            <table class="w-full mt-3">
                @foreach ($ppkList as $ppk)
                    <tr class="text-sm border-b-2 ">
                        <td class="flex justify-between px-3">
                            <span class="mr-9 {{ $ppk->id == auth()->id() ? 'font-bold' : '' }}">
                                {{ $ppk->id == auth()->id() ? 'Anda' : $ppk->name }}
                            </span>
                            <i
                                class="my-1 fa-solid {{ is_null(
                                    optional($ppk->persetujuanPengiriman->where('detail_pengiriman_id', $pengiriman->id ?? 0)->first())->status,
                                )
                                    ? 'fa-circle-question text-secondary-600'
                                    : (optional($ppk->persetujuanPengiriman->where('detail_pengiriman_id', $pengiriman->id ?? 0)->first())->status
                                        ? 'fa-circle-check text-success-500'
                                        : 'fa-circle-xmark text-danger-500') }}">
                            </i>


                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

        {{-- @endrole --}}
    </div>
    {{-- @hasanyrole($roles) --}}
    @if ($showButton && Auth::user()->hasRole('Pemeriksa Barang'))
        <div class="flex {{ $indikatorPenerima == 0 ? 'hidden' : '' }}">
            <div class="flex space-x-2 justify-center w-full">
                @if ($isLastUser || $lastPj || $lastPpk || $lastPptk || $lastPenerima || $lastPemeriksa)
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
                    <div class="flex flex-col items-center">
                        <input type="file" wire:model="newbapfiles" id="bapfiles" multiple class="hidden">
                        <button type="button" onclick="document.getElementById('bapfiles').click()"
                            class="text-primary-700 bg-gray-200 border text-center border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition">
                            Unggah Berita Acara
                        </button>
                        @error('bapfiles.*')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- <input type="file" wire:model="approvalFiles" id="approvalFiles" multiple class="hidden">
                        <button type="button" onclick="document.getElementById('approvalFiles').click()"
                            class="text-primary-700 bg-gray-200 border text-center border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition">
                            Unggah File Persetujuan
                        </button>
                        @error('approvalFiles.*')
                            <span class="text-red-500 text-xs">{{ $message }}</span>
                        @enderror --}}
                @endif
                <button type="button"
                    onclick="{{ $isLastUser || $lastPj || $lastPpk || $lastPptk || $lastPenerima || $lastPemeriksa ? 'submitApprovalWithFile()' : 'confirmApprove()' }}"
                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                    Setuju
                </button>
                <button type="button" onclick="confirmReject()"
                    class="text-danger-900 bg-danger-100 hover:bg-danger-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                    Tidak Setuju
                </button>
            </div>

        </div>
    @endif

    @hasanyrole('Pejabat Pelaksana Teknis Kegiatan|Pejabat Pembuat Komitmen')
    <div class="flex">
        <div class="flex space-x-2 justify-center w-full">
            <button type="button"
                onclick="confirmApprove()"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200
                {{ count($checkPreviousApproval) > 0 ? '' : 'hidden' }}">
                Setuju
            </button>
        </div>
    </div>
    @endhasanyrole
    {{-- @if ()
        
    @endif --}}
    

    <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-4xl">
        <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-2 lg:gap-y-16">
            {{-- @endhasanyrole --}}



            <div class="relative pl-16">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-primary-900 dark:text-white">File Persetujuan
                </h5>

                <div class="mt-4 gap-4 w-3/5">
                    @if (count($files) > 0)
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
                                            <a href="{{ $attachment instanceof \Illuminate\Http\UploadedFile ? $attachment->temporaryUrl() : asset('storage/dokumen-persetujuan-pengiriman/' . $attachment) }}"
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

                    @endif
                </div>
            </div>
            <div class="relative pl-16">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-primary-900 dark:text-white">File BAP</h5>
                <div class="mt-4 gap-4 w-3/5">
                    @if ($arrayfiles)
                        @foreach ($arrayfiles as $index => $attachment)
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
                                        <a href="{{ $attachment instanceof \Illuminate\Http\UploadedFile ? $attachment->temporaryUrl() : asset('storage/dokumen-persetujuan-pengiriman/bap/' . $attachment) }}"
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
        </dl>
    </div>


    {{-- <div class="flex justify-center">

        </div> --}}
    <div class="mx-auto max-w-2xl lg:max-w-4xl">
        <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-10 lg:max-w-none lg:grid-cols-2 lg:gap-y-16">
            <div class="relative pl-16">
                @if ($approvalFiles)
                    {{-- <h5 class="mb-2 text-2xl font-bold tracking-tight text-primary-900 dark:text-white">File Persetujuan
                    </h5> --}}
                    <div class="gap-4 w-3/5">
                        @foreach ($approvalFiles as $index => $attachment)
                            <div
                                class="flex items-center justify-between border-b-2 p-3 rounded my-2 shadow-sm bg-white">
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
                @endif
            </div>
            <div class="relative pl-16">
                @if ($bapfiles)
                    {{-- <h5 class="mb-2 text-2xl font-bold tracking-tight text-primary-900 dark:text-white">File BAP</h5> --}}
                    <div class="gap-4 w-3/5">
                        @foreach ($bapfiles as $index => $attachment)
                            <div
                                class="flex items-center justify-between border-b-2 p-3 rounded my-2 shadow-sm bg-white">
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
                                <button wire:click="removeBapFile({{ $index }})"
                                    class="text-red-500 hover:text-red-700 text-lg font-bold px-2">
                                    &times;
                                </button>
                            </div>
                        @endforeach

                    </div>

                @endif
            </div>
        </dl>
    </div>



</div>
@push('scripts')

    <script>
        document.addEventListener('statusAppPenerima', function(event) {
            console.log(event.detail.data); // Get the count from the event detail
        });

        let fileCount = 0,
            BapCount = 0;

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
                    if ('{{ $roles }}' == 'Pejabat Pelaksana Teknis Kegiatan'){
                        @this.call('ApprovePPTK');
                    } else if ('{{ $roles }}' == 'Pejabat Pembuat Komitmen'){
                        @this.call('ApprovePPKandFinish');
                    } else {
                        @this.call('approveConfirmed');
                    }
                }
            });
        }


        function submitApprovalWithFile() {
            const fileInput = document.getElementById('approvalFiles');



            if (!fileCount) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Tidak Ditemukan',
                    text: 'Harap unggah file Persetujuan sebelum menyetujui kontrak.',
                });
                return;
            }

            if (!BapCount) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Tidak Ditemukan',
                    text: 'Harap Unggah File Bap sebelum menyetujui kontrak.',
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

        document.addEventListener('bap_file', function(event) {
            BapCount = event.detail.count; // Get the count from the event detail
        });
    </script>
@endpush


{{-- @push('scripts')
    <script>
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
    </script>
@endpush --}}
