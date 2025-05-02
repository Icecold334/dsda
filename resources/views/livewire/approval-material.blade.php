<div>
    <div class="flex w-full justify-evenly border-t-4 py-6">
        <!-- Penulis -->
        <div>
            <div class="block font-semibold text-center mb-2 text-gray-900">Kepala Satuan Pelaksana</div>
            <div class="text-sm border-b-2">
                <div class="flex justify-between px-3">
                    <span class="mr-9 {{ $penulis->id == auth()->id() ? 'font-bold' : '' }}">
                        {{ false ? 'Anda' : $penulis->name }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Iterasi dinamis semua role -->
        @foreach ($roleLists as $roleKey => $users)
        <div>
            <div class="block font-semibold text-center mb-2 text-gray-900">
                @if ($roleKey == 'kepala-subbagian')
                {{ ucwords(str_replace('-', ' ', $roleKey)) }} Tata Usaha
                @elseif ($roleKey == 'kepala-seksi')
                {{ ucwords(str_replace('-', ' ', $roleKey)) }} Pemeliharaan
                @else
                {{ ucwords(str_replace('-', ' ', $roleKey)) }}
                @endif
            </div>
            <table class="w-full mt-3">
                @foreach ($users as $user)
                <tr class="text-sm border-b-2">
                    <td class="flex justify-between px-3">
                        <span class="mr-9 {{ $user->id == auth()->id() ? 'font-bold' : '' }}">
                            {{ $user->name }}
                        </span>
                        @php
                        $status = optional(
                        $user->persetujuan
                        ->where('approvable_id', $permintaan->id ?? 0)
                        ->where('approvable_type', App\Models\DetailPermintaanMaterial::class)
                        ->first()
                        )->is_approved;
                        @endphp

                        <i class="my-1 fa-solid {{ is_null($status) 
                        ? 'fa-circle-question text-secondary-600'
                        : ($status ? 'fa-circle-check text-success-500' : 'fa-circle-xmark text-danger-500') }}">
                        </i>

                    </td>
                </tr>
                @endforeach
            </table>
        </div>
        @endforeach
    </div>
    @if ($showButton)
    <div class="flex">
        <div class="flex space-x-2 justify-center w-full">
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
</div>

@once
@push('scripts')
@if ($currentApprovalIndex + 1 == 3)
<script>
    function confirmApprove() {
        Swal.fire({
            title: 'Input Data Persetujuan',
            html:
        '<input id="nama_driver" class="swal2-input" placeholder="Nama Driver" autocomplete="off">' +
        '<input id="nomor_polisi" class="swal2-input" placeholder="Nomor Polisi" autocomplete="off">' +
        '<input id="nama_security" class="swal2-input" placeholder="Nama Security" autocomplete="off">',
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            preConfirm: () => {
                const driver = document.getElementById('nama_driver').value.trim();
                const security = document.getElementById('nama_security').value.trim();
                const nopol = document.getElementById('nomor_polisi').value.trim();

                if (!driver || !security || !nopol) {
                    Swal.showValidationMessage('Semua kolom harus diisi!');
                    return false;
                }

                return {
                    driver: driver,
                    security: security,
                    nopol: nopol
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('approveConfirmed', 1, null, result.value.driver, result.value.nopol,result.value.security);
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
                    return false;
                }
                return inputValue;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('approveConfirmed', 0, result.value);
            }
        });
    }
</script>
@else
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
                    return false;
                }
                return inputValue;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('approveConfirmed', 0, result.value);
            }
        });
    }
</script>
@endif
@endpush

@push('scripts')
<script>
    function confirmCompletion() {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: "Apakah Anda yakin ingin menyelesaikan RAB ini?",
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
                        @this.call('approveConfirmed', 0, result.value);
                    }
                });
            }
</script>
@endpush
@endonce