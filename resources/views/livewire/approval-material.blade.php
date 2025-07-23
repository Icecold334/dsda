<div>
    <div class="flex w-full justify-evenly border-t-4 py-6">
        <!-- Penulis -->
        <div>
            <div class="block font-semibold text-center mb-2 text-gray-900">
                @if ($penulis->hasRole('Kepala Seksi') && $penulis->unitKerja)
                Kepala {{ $penulis->unitKerja->nama }}
                @else
                {{ implode(', ', $penulis->roles->pluck('name')->toArray()) }}
                @endif
            </div>
            <div class="text-sm border-b-2">
                <div class="flex justify-between px-3">
                    <span class="mr-9 {{ $penulis->id == auth()->id() ? 'font-bold' : '' }}">
                        {{ $penulis->id === auth()->id() && 0 ? 'Anda' : $penulis->name }}
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
            @if (auth()->user()->hasRole('Pengurus Barang'))
            <button type="button" wire:click="openModal"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Setuju
            </button>
            @else
            <button type="button" onclick="confirmApprove()"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Setuju
            </button>
            @endif

            <button type="button" onclick="confirmReject()"
                class="text-danger-900 bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Tidak Setuju
            </button>
        </div>
    </div>
    @endif
    <!-- Modal Pengurus Barang: Konfirmasi Persetujuan -->
    <div
        class="@if (!$showModal) hidden @endif fixed top-0 left-0 z-50 w-full h-full overflow-y-auto bg-black bg-opacity-40 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
            <div class="flex justify-between items-center border-b pb-3 mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Persetujuan Pengiriman</h3>
                <button wire:click="$set('showModal', false)" class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="space-y-4">
                {{-- Input Nomor Surat Jalan --}}
                <div>
                    <label for="noSuratJalan" class="block text-sm font-medium text-gray-700">Nomor Surat Jalan</label>
                    <input type="text" wire:model="noSuratJalan" id="noSuratJalan"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    @error('noSuratJalan') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                {{-- Dropdown Driver --}}
                <div>
                    <label for="selectedDriverId" class="block text-sm font-medium text-gray-700">Pilih Driver</label>
                    <select wire:model="selectedDriverId" id="selectedDriverId"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                        <option value="">-- Pilih Driver --</option>
                        @foreach ($listDrivers as $driver)
                        <option value="{{ $driver->id }}">{{ $driver->nama }}</option>
                        @endforeach
                    </select>
                    @error('selectedDriverId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Input Nopol --}}
                <div>
                    <label for="nopol" class="block text-sm font-medium text-gray-700">Nomor Polisi</label>
                    <input type="text" wire:model="nopol" id="nopol"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                    @error('nopol') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Dropdown Security --}}
                <div>
                    <label for="selectedSecurityId" class="block text-sm font-medium text-gray-700">Pilih
                        Security</label>
                    <select wire:model="selectedSecurityId" id="selectedSecurityId"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary-500 focus:border-primary-500">
                        <option value="">-- Pilih Security --</option>
                        @foreach ($listSecurities as $security)
                        <option value="{{ $security->id }}">{{ $security->nama }}</option>
                        @endforeach
                    </select>
                    @error('selectedSecurityId') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button wire:click="$set('showModal', false)"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">Batal</button>
                <button wire:click="submitPengirimanApproval"
                    class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700">Kirim</button>
            </div>
        </div>
    </div>
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
            let driverOptions = '<option value=\"\">Pilih Driver</option>';
            let securityOptions = '<option value=\"\">Pilih Security</option>';

            listDrivers.forEach(driver => {
                driverOptions += `<option value=\"${driver.id}\">${driver.nama}</option>`;
            });

            listSecurities.forEach(security => {
                securityOptions += `<option value=\"${security.id}\">${security.nama}</option>`;
            });

            Swal.fire({
                title: 'Input Data Pengiriman',
                html: `
                    <select id="driver_id" class="swal2-select" style="width:100%;padding:6px;margin-bottom:10px">
                        ${driverOptions}
                    </select>
                    <input id="nopol" class="swal2-input" placeholder="Nomor Polisi">
                    <select id="security_id" class="swal2-select" style="width:100%;padding:6px;margin-top:10px">
                        ${securityOptions}
                    </select>
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    const driverId = document.getElementById('driver_id').value;
                    const nopol = document.getElementById('nopol').value.trim();
                    const securityId = document.getElementById('security_id').value;

                    if (!driverId || !nopol || !securityId) {
                        Swal.showValidationMessage('Semua kolom harus diisi!');
                        return false;
                    }

                    return {
                        driver_id: driverId,
                        nopol: nopol,
                        security_id: securityId
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('approveConfirmed', 1, null, result.value.driver_id, result.value.nopol, result.value.security_id);
                }
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