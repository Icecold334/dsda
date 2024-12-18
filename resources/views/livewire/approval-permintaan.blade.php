<div>
    <div class="flex w-full justify-evenly border-t-4 py-6">
        <!-- Penulis -->
        <div>
            <div class="block font-semibold text-center mb-2 text-gray-900">Penulis</div>
            <div class="text-sm border-b-2">
                <div class="flex justify-between px-3">
                    <span class="mr-9 {{ $penulis->id == auth()->id() ? 'font-bold' : '' }}">
                        {{ $penulis->id == auth()->id() ? 'Anda' : $penulis->name }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Iterasi dinamis semua role -->
        @foreach ($roleLists as $roleKey => $users)
            <div>
                <div class="block font-semibold text-center mb-2 text-gray-900">
                    {{-- {{ $roleKey }} --}}
                    {{ ucwords(str_replace('-', ' ', $roleKey)) }}
                </div>
                <table class="w-full mt-3">
                    @foreach ($users as $user)
                        <tr class="text-sm border-b-2">
                            <td class="flex justify-between px-3">
                                <span class="mr-9 {{ $user->id == auth()->id() ? 'font-bold' : '' }}">
                                    {{ $user->name }}
                                </span>
                                {{-- @dump($user->persetujuanPermintaan->where('detail_permintaan_id', $permintaan->id ?? 0)) --}}
                                <i
                                    class="my-1 fa-solid {{ is_null(
                                        optional($user->persetujuanPermintaan->where('detail_permintaan_id', $permintaan->id ?? 0)->first())->status,
                                    )
                                        ? 'fa-circle-question text-secondary-600'
                                        : (optional($user->persetujuanPermintaan->where('detail_permintaan_id', $permintaan->id ?? 0)->first())->status
                                            ? 'fa-circle-check text-success-500'
                                            : 'fa-circle-xmark text-danger-500') }}">
                                </i>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endforeach
    </div>

    <!-- Tombol aksi -->
    @if ($showButton)
        <div class="flex">
            <div class="flex space-x-2 justify-center w-full">
                {{-- @if ($isLastUser) --}}
                {{-- <div class="flex flex-col items-center">
                    <input type="file" wire:model="newApprovalFiles" id="approvalFiles" multiple class="hidden">
                    <button type="button" onclick="document.getElementById('approvalFiles').click()"
                        class="text-primary-700 bg-gray-200 border text-center border-primary-500 rounded-lg px-3 py-1.5 hover:bg-primary-600 hover:text-white transition">
                        Unggah File Persetujuan
                    </button>
                    @error('approvalFiles.*')
                        <span class="text-red-500 text-xs">{{ $message }}</span>
                    @enderror
                </div> --}}
                {{-- @endif --}}
                <button type="button" onclick="confirmApprove()"
                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                    Selesai
                </button>
                <button type="button" onclick="confirmReject()"
                    class="text-danger-900 bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                    Tidak Setuju
                </button>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        function confirmApprove() {
            Swal.fire({
                title: 'Konfirmasi Persetujuan',
                text: 'Apakah Anda yakin ingin menyetujui permintaan ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Setuju',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('approveConfirmed', 1);
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
                        return false; // Prevent submission
                    }
                    return inputValue; // Allows submission
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('approveConfirmed', 0, result.value);
                }
            });
        }
    </script>
@endpush
