<div>
    <div class="flex w-full justify-evenly border-t-4 py-6">
        <!-- Penulis -->
        <div>
            <div class="block font-semibold text-center mb-2 text-gray-900">Pemohon</div>
            <div class="text-sm border-b-2">
                <div class="flex justify-between px-3">
                    <span class="mr-9 {{ $penulis->id == auth()->id() ? 'font-bold' : '' }}">
                        {{ false ? 'Anda' : $penulis->name }}
                    </span>
                </div>
            </div>
        </div>
        <div>
            <div class="block font-semibold text-center mb-2 text-gray-900">Kepala Pemohon</div>
            <div class="text-sm border-b-2">
                <div class="flex justify-between items-center px-3">
                    <span class="mr-2 {{ $kepalaPemohon && $kepalaPemohon->id == auth()->id() ? 'font-bold' : '' }}">
                        {{ $kepalaPemohon ? $kepalaPemohon->name : 'Tidak Ada Kepala' }}
                    </span>
                    @if ($kepalaPemohon)
                        <i class="fa-solid fa-circle-check text-success-500"></i>
                    @endif
                </div>

            </div>
        </div> <!-- Iterasi dinamis semua role -->
        @foreach ($roleLists as $roleKey => $users)
            <div>
                <div class="block font-semibold text-center mb-2 text-gray-900">
                    {{-- {{ $roleKey }} --}}
                    {{ ucwords(str_replace('-', ' ', $roleKey)) }}
                </div>
                <table class="w-full">
                    @foreach ($users as $user)
                        <tr class="text-sm border-b-2">
                            <td class="flex justify-between px-3">
                                <span class="mr-9 {{ $user->id == auth()->id() ? 'font-bold' : '' }}">
                                    {{ $user->name }}
                                </span>
                                {{-- @dump($user->persetujuanPermintaan->where('detail_permintaan_id', $permintaan->id ?? 0)) --}}
                                <i
                                    class=" fa-solid {{ is_null(
                                        optional($user->{"persetujuan{$tipe}"}->where('detail_' . Str::lower($tipe) . '_id', $permintaan->id ?? 0)->first())->status,
                                    )
                                        ? 'fa-circle-question text-secondary-600'
                                        : (optional(
                                            $user->{"persetujuan{$tipe}"}->where('detail_' . Str::lower($tipe) . '_id', $permintaan->id ?? 0)->first(),
                                        )->status
                                            ? 'fa-circle-check text-success-500'
                                            : 'fa-circle-xmark text-danger-500') }}">
                                </i>

                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endforeach

        <div>
            <div class="block font-semibold text-center mb-2 text-gray-900">Kepala Subbagian</div>
            <div class="text-sm border-b-2">
                <div class="flex justify-between items-center px-3">
                    <span
                        class="mr-2 {{ $kepalaSubbagian && $kepalaSubbagian->id == auth()->id() ? 'font-bold' : '' }}">
                        {{ $kepalaSubbagian->name ?? 'Tidak Ada Kepala' }}
                    </span>
                    @if ($kepalaSubbagian)
                        @if ($tipe == 'permintaan' && $permintaan->proses)
                            <i class="fa-solid fa-circle-check text-success-500"></i>
                        @elseif ($tipe == 'peminjaman' && $permintaan->status == true && $permintaan->cancel === 0)
                            <i class="fa-solid fa-circle-check text-success-500"></i>
                        @else
                            <i class="fa-solid fa-circle-question text-secondary-600"></i>
                        @endif
                    @endif
                </div>

            </div>
        </div>

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
                    Setuju
                </button>
                {{-- @if ($showButtonApproval) --}}
                <button type="button" onclick="confirmReject()"
                    class="text-danger-900 bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                    Tidak Setuju
                </button>
                {{-- @endif --}}
            </div>
        </div>
    @endif
    @if ($isPenulis && $showCancelOption && is_null($permintaan->cancel))
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

@push('scripts')
    <script>
        function confirmCompletion() {
            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin dengan permintaan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Saya yakin!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.markAsCompleted();
                    Swal.fire(
                        'Berhasil!',
                        'Permintaan telah ditandai siap digunakan.',
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
                    @this.call('approveConfirmed', 0, result.value);
                }
            });
        }
    </script>
@endpush
