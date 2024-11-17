<div>
    <div class="flex w-full justify-evenly border-t-4 py-6">
        <div class="">
            <div class="block font-semibold text-center mb-2 text-gray-900">Penulis</div>
            @if ($kontrak)
                @if ($kontrak->type)
                    <div class="text-sm border-b-2 ">
                        <div class="flex justify-between px-3">
                            <span class="mr-9 {{ $kontrak->user->id == auth()->id() ? 'font-bold' : '' }}">
                                {{ $kontrak->user->id == auth()->id() ? 'Anda' : $kontrak->user->name }}
                            </span>
                        </div>
                    </div>
                @else
                    @foreach ($penulis as $item)
                        <div class="text-sm border-b-2 ">
                            <div class="flex justify-between px-3">
                                <span class="mr-9 {{ $item->user->id == auth()->id() ? 'font-bold' : '' }}">
                                    {{ $item->user->id == auth()->id() ? 'Anda' : $item->user->name }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                @endif
            @else
                @foreach ($penulis as $item)
                    <div class="text-sm border-b-2 ">
                        <div class="flex justify-between px-3">
                            <span class="mr-9 {{ $item->user->id == auth()->id() ? 'font-bold' : '' }}">
                                {{ $item->user->id == auth()->id() ? 'Anda' : $item->user->name }}
                            </span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        @if ($kontrak)
            {{-- @role('penanggungjawab') --}}

            <div class="">
                <div class="block font-semibold text-center mb-2 text-gray-900">Penanggung Jawab</div>
                <table class="w-full mt-3">
                    @foreach ($pjList as $pj)
                        <tr class="text-sm border-b-2 ">
                            <td class="flex justify-between px-3">
                                <span class="mr-9 {{ $pj->id == auth()->id() ? 'font-bold' : '' }}">
                                    {{ $pj->id == auth()->id() ? 'Anda' : $pj->name }}
                                </span>
                                <i
                                    class="my-1 fa-solid {{ is_null(optional($pj->persetujuanKontrak->where('kontrak_id', $kontrak->id ?? 0)->first())->status)
                                        ? 'fa-circle-question text-secondary-600'
                                        : (optional($pj->persetujuanKontrak->where('kontrak_id', $kontrak->id ?? 0)->first())->status
                                            ? 'fa-circle-check text-success-500'
                                            : 'fa-circle-xmark text-danger-500') }}">
                                </i>


                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
            @if (!$kontrak->type)
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
                                        class="my-1 fa-solid {{ is_null(optional($ppk->persetujuanKontrak->where('kontrak_id', $kontrak->id ?? 0)->first())->status)
                                            ? 'fa-circle-question text-secondary-600'
                                            : (optional($ppk->persetujuanKontrak->where('kontrak_id', $kontrak->id ?? 0)->first())->status
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
                        Pejabat Pelaksana Teknis Kegiatan</div>
                    <table class="w-full mt-3">
                        @foreach ($pptkList as $pptk)
                            <tr class="text-sm border-b-2 ">
                                <td class="flex justify-between px-3">
                                    <span class="mr-9 {{ $pptk->id == auth()->id() ? 'font-bold' : '' }}">
                                        {{ $pptk->id == auth()->id() ? 'Anda' : $pptk->name }}
                                    </span>
                                    <i
                                        class="my-1 fa-solid {{ is_null(optional($pptk->persetujuanKontrak->where('kontrak_id', $kontrak->id ?? 0)->first())->status)
                                            ? 'fa-circle-question text-secondary-600'
                                            : (optional($pptk->persetujuanKontrak->where('kontrak_id', $kontrak->id ?? 0)->first())->status
                                                ? 'fa-circle-check text-success-500'
                                                : 'fa-circle-xmark text-danger-500') }}">
                                    </i>


                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif
            {{-- @endrole --}}
        @endif
    </div>
    @hasanyrole($roles)
        @if (
            $kontrak &&
                !$user->persetujuanKontrak()->where('kontrak_id', $kontrak->id ?? 0)->exists())
            <div class="flex">
                <div class="flex space-x-2 justify-center w-full">
                    <button type="button" onclick="confirmApprove()"
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
    @else
    @endhasanyrole

</div>

@push('scripts')
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
@endpush
