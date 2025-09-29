<div>
    <div class="flex w-full justify-evenly border-t-4 py-6">
        <!-- Penulis -->
        <div>
            <div class="block font-semibold text-center mb-2 text-gray-900">
                @php
                    // BYPASS: Untuk periode 12-19 Agustus 2025, jika permintaan dibuat oleh Citrin (245),
                    // tampilkan sebagai dibuat oleh Yusuf (252) dengan role Kepala Seksi Pemeliharaan
                    $isTransferPeriod = $permintaan->created_at->between('2025-08-12 00:00:00', '2025-08-19 23:59:59');
                    $isCitrinRequest = $penulis->id == 245;

                    if ($isTransferPeriod && $isCitrinRequest) {
                        $displayRole = 'Kepala Seksi Pemeliharaan';
                    } elseif ($penulis->hasRole('Kepala Seksi') && $penulis->unitKerja) {
                        $displayRole = 'Kepala ' . $penulis->unitKerja->nama;
                    } else {
                        $displayRole = implode(', ', $penulis->roles->pluck('name')->toArray());
                    }
                @endphp
                {{ $displayRole }}
            </div>
            <div class="text-sm border-b-2">
                <div class="flex justify-between px-3">
                    <span class="mr-9 {{ $penulis->id == auth()->id() ? 'font-bold' : '' }}">
                        @php
                            // BYPASS: Tampilkan nama Yusuf jika permintaan periode transfer oleh Citrin
                            if ($isTransferPeriod && $isCitrinRequest) {
                                $displayName = 'Yusuf Saut Pangibulan, ST, MPSDA';
                            } else {
                                $displayName = $penulis->id === auth()->id() && 0 ? 'Anda' : $penulis->name;
                            }
                        @endphp
                        {{ $displayName }}
                    </span>
                    {{-- <i class="my-1 fa-solid fa-circle-check text-success-500"></i> --}}
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
                                    @php
                                        // BYPASS: Untuk periode 12-19 Agustus 2025, jika ada approval dari Yusuf (252) 
                                        // untuk role Kepala Seksi, tampilkan nama Yusuf
                                        $displayName = $user->name;
                                        $isKepalaSeksi = $roleKey == 'kepala-seksi';

                                        // Cek apakah ada approval dari Yusuf untuk permintaan ini dalam periode transfer
                                        $transferApproval = null;
                                        if ($isKepalaSeksi) {
                                            $transferApproval = \App\Models\Persetujuan::where('approvable_id', $permintaan->id ?? 0)
                                                ->where('approvable_type', App\Models\DetailPermintaanMaterial::class)
                                                ->where('user_id', 252)
                                                ->whereBetween('created_at', ['2025-08-12 00:00:00', '2025-08-19 23:59:59'])
                                                ->first();
                                        }

                                        // Jika ada transfer approval, override nama dan status
                                        if ($transferApproval) {
                                            $displayName = 'Yusuf Saut Pangibulan, ST, MPSDA';
                                            $status = $transferApproval->is_approved;
                                        } else {
                                            // Logic normal
                                            $status = optional(
                                                $user->persetujuan
                                                    ->where('approvable_id', $permintaan->id ?? 0)
                                                    ->where('approvable_type', App\Models\DetailPermintaanMaterial::class)
                                                    ->first()
                                            )->is_approved;
                                        }
                                    @endphp

                                    <span class="mr-9 {{ $user->id == auth()->id() ? 'font-bold' : '' }}">
                                        {{ $displayName }}
                                    </span>

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
                    class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                    Setuju
                </button>

                <button type="button" onclick="confirmReject()"
                    class="text-danger-900 bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                    Tidak Setuju
                </button>
            </div>
        </div>
    @endif
    <!-- Modal Pengurus Barang: Konfirmasi Persetujuan (sudah tidak digunakan - logic dipindah ke JavaScript) -->
</div>

@once
    @push('scripts')
        <script>
            function confirmApprove() {
                const currentRole = @json(auth()->user()->roles->pluck('name')->toArray());
                const listDrivers = @json($listDrivers);
                const listSecurities = @json($listSecurities);

                if (currentRole.includes('Kepala Subbagian Tata Usaha')) {
                    Swal.fire({
                        title: 'Input Nomor SPPB',
                        input: 'text',
                        inputPlaceholder: 'Nomor SPPB',
                        showCancelButton: true,
                        confirmButtonText: 'Simpan',
                        cancelButtonText: 'Batal',
                        preConfirm: (value) => {
                            if (!value || value.trim() === '') {
                                Swal.showValidationMessage('Nomor SPPB tidak boleh kosong!');
                                return false;
                            }
                            return value;
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.call('approveConfirmed', 1, null, null, null, null, result.value);
                        }
                    });

                } else if (currentRole.includes('Pengurus Barang')) {
                    // Ambil data terbaru dari server untuk memastikan data driver/security/nopol up-to-date
                    @this.call('checkDriverData').then((data) => {
                        if (!data.driver || !data.security || !data.nopol) {
                            Swal.fire({
                                title: 'Data Pengiriman Belum Lengkap',
                                text: 'Silakan lengkapi data driver, security, dan nomor polisi terlebih dahulu di bagian "Tanda Tangan Driver & Keamanan".',
                                icon: 'warning',
                                confirmButtonText: 'OK'
                            });
                            return;
                        }

                        // Jika data lengkap, tampilkan modal input nomor surat jalan
                        Swal.fire({
                            title: 'Input Nomor Surat Jalan',
                            input: 'text',
                            inputPlaceholder: 'Nomor Surat Jalan',
                            showCancelButton: true,
                            confirmButtonText: 'Simpan',
                            cancelButtonText: 'Batal',
                            preConfirm: (value) => {
                                if (!value || value.trim() === '') {
                                    Swal.showValidationMessage('Nomor Surat Jalan tidak boleh kosong!');
                                    return false;
                                }
                                return value;
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                @this.call('approveConfirmed', 1, null, null, null, null, null, result.value);
                            }
                        });
                    });
                } else {
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