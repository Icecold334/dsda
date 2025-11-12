<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900 ">
            {{ $tipe === null
            ? 'Pelayanan Umum'
            : ($tipe == 'spare-part'
            ? 'Permintaan Spare Part'
            : 'Permintaan Material') }}
            @if (auth()->user()->unitKerja)
            {{-- {{ auth()->user()->unitKerja->nama }} --}}
            @endif
        </h1>
        <div>
            <div class="flex gap-4">
                <input type="date" wire:model.live.debounce.500ms="tanggal" class="border rounded-lg px-4 py-2 w-40" />
                <input type="text" wire:model.live.debounce.500ms="search" class="border rounded-lg px-4 py-2 w-40"
                    placeholder="Cari Nomor SPB" />

                <selectwire:model.live.debounce.500ms="status" class="border rounded-lg px-4 py-2 w-40">
                    <option value="">Semua Status</option>
                    <option value="draft">Draft</option>
                    <option value="diproses">diproses</option>
                    <option value="ditolak">ditolak</option>
                    <option value="disetujui">disetujui</option>
                    <option value="sedang dikirim">sedang dikirim</option>
                    <option value="selesai">selesai</option>
                    </select>

                    <selectwire:model.live.debounce.500ms="sortBy" class="border rounded-lg px-4 py-2 w-40">
                        <option value="terbaru">Terbaru</option>
                        <option value="terlama">Terlama</option>
                        </select>

                        <div wire:loading wire:target='downloadExcel'>
                            <livewire:loading>
                        </div>

                        @can('pelayanan_xls')
                        @if ($total > 0)
                        <button data-tooltip-target="tooltip-excel" wire:click="downloadExcel"
                            class="bg-white text-blue-500 h-10 border border-blue-500 rounded-lg px-4 py-2 flex items-center hover:bg-blue-500 hover:text-white transition-colors">
                            <i class="fa-solid fa-file-excel"></i>
                        </button>
                        <div id="tooltip-excel" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Unduh dalam format excel
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                        @endif
                        @endcan

                        @can('permintaan.create')
                        <div wire:click='tambahPermintaan'
                            class="text-primary-900 cursor-pointer bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 transition duration-200">
                            + Tambah Permintaan
                        </div>
                        @endcan
            </div>
        </div>
    </div>

    <table class="w-full border-3 border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">NOMOR SPB</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold {{ $isSeribu ? 'hidden' : '' }}">JENIS
                    PEKERJAAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold {{ $isSeribu ? 'hidden' : '' }}">LOKASI
                </th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">TANGGAL PEKERJAAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">TANGGAL PERMINTAAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">STATUS</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($permintaans as $permintaan)
            <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                <td class="px-6 py-3"></td>
                <td class="px-6 py-3 font-semibold">
                    <div>{{ Str::ucfirst($permintaan['kode']) }}</div>
                    <div class="text-gray-500 text-sm {{ !$isSeribu ? 'hidden' : '' }}">
                        {{ $permintaan['nomor_rab'] }}
                    </div>
                </td>
                <td class="px-6 py-3 font-semibold {{ $isSeribu ? 'hidden' : '' }}">
                    <div>{{ Str::ucfirst($permintaan['jenis_pekerjaan']) }}</div>
                    <div class="text-gray-500 text-sm">{{ $permintaan['nomor_rab'] }}</div>
                </td>
                <td class="px-6 py-3 font-semibold text-center {{ $isSeribu ? 'hidden' : '' }}">
                    {{ $permintaan['lokasi'] }}
                </td>
                <td class="px-6 py-3 font-semibold text-center">{{ date('j F Y', $permintaan['tanggal']) }}</td>
                <td class="px-6 py-3 font-semibold text-center">{{ $permintaan['created_at']->format('d F Y') }}</td>
                <td class="py-3 px-6 font-semibold {{ $tipe == 'material' ? 'hidden' : '' }}">
                    <div class="text-gray-600 text-sm">
                        {{ $permintaan['sub_unit']?->nama ?? $permintaan['unit']?->nama }}
                    </div>
                </td>
                <td class="py-3 px-6">
                    <p class="font-semibold text-gray-800 text-center">
                        <span
                            class="bg-{{ $permintaan['status_warna'] }}-600 text-{{ $permintaan['status_warna'] }}-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                            {{ $permintaan['status_teks'] }}
                        </span>
                    </p>
                </td>
                <td class="py-3 px-6 text-center">
                    <div class="flex justify-center items-center gap-2">
                        <a href="/permintaan/{{ $permintaan['tipe'] === 'peminjaman' ? 'peminjaman' : 'permintaan' }}/{{ $permintaan['id'] }}"
                            class="text-blue-600 hover:text-white hover:bg-blue-600 px-3 py-2 rounded border border-blue-600 transition-colors duration-200"
                            data-tooltip-target="tooltip-permintaan-{{ $permintaan['id'] }}">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                        <div id="tooltip-permintaan-{{ $permintaan['id'] }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Lihat Detail Permintaan
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>

                        {{-- Edit Draft Button - Only for draft status --}}
                        @if ($permintaan['status'] === 4 && $permintaan['can_edit'])
                        <a href="/permintaan/material/edit/{{ $permintaan['id'] }}"
                            class="text-yellow-600 hover:text-white hover:bg-yellow-600 px-3 py-2 rounded border border-yellow-600 transition-colors duration-200"
                            data-tooltip-target="tooltip-edit-draft-{{ $permintaan['id'] }}">
                            <i class="fa-solid fa-edit"></i>
                        </a>
                        <div id="tooltip-edit-draft-{{ $permintaan['id'] }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Edit Draft
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                        @endif

                        <button wire:click="openApprovalTimeline({{ $permintaan['id'] }}, '{{ $permintaan['tipe'] }}')"
                            class="text-green-600 hover:text-white hover:bg-green-600 px-3 py-2 rounded border border-green-600 transition-colors duration-200"
                            data-tooltip-target="tooltip-timeline-{{ $permintaan['id'] }}">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </button>
                        <div id="tooltip-timeline-{{ $permintaan['id'] }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                            Lihat Riwayat Approval
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>

                        @if ($permintaan['can_delete'])
                        <button onclick="confirmDeletePermintaan({{ $permintaan['id'] }})"
                            class="text-red-600 hover:text-white hover:bg-red-600 px-3 py-2 rounded border border-red-600 transition-colors duration-200"
                            data-tooltip-target="tooltip-delete-{{ $permintaan['id'] }}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                        <div id="tooltip-delete-{{ $permintaan['id'] }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                            Hapus Permintaan
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                        @endif

                        @if ($permintaan['can_admin_edit'])
                        <button onclick="confirmAdminEditStatus({{ $permintaan['id'] }})"
                            class="text-orange-600 hover:text-white hover:bg-orange-600 px-3 py-2 rounded border border-orange-600 transition-colors duration-200"
                            data-tooltip-target="tooltip-admin-status-{{ $permintaan['id'] }}">
                            <i class="fa-solid fa-edit"></i>
                        </button>
                        <div id="tooltip-admin-status-{{ $permintaan['id'] }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                            Edit Status Sebagai Admin
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                        @endif

                        @if ($permintaan['can_admin_delete'])
                        <button onclick="confirmAdminDeletePermintaan({{ $permintaan['id'] }})" class="text-purple-600 hover:text-white hover:bg-purple-600 px-3 py-2 rounded border
                                                                border-purple-600 transition-colors duration-200"
                            data-tooltip-target="tooltip-admin-delete-{{ $permintaan['id'] }}">
                            <i class="fa-solid fa-user-slash"></i>
                        </button>
                        <div id="tooltip-admin-delete-{{ $permintaan['id'] }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip">
                            Hapus Sebagai Admin
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="text-center text-gray-600 text-lg py-8">Tidak ada data yang ditemukan.</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($total > $perPage)
    <div class="flex justify-center mt-6">
        <nav class="inline-flex items-center -space-x-px">
            {{-- Previous Button --}}
            @if($currentPage > 1)
            <button wire:click="previousPage"
                class="px-3 py-2 ml-0 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700">
                <span class="sr-only">Previous</span>
                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
            @else
            <span
                class="px-3 py-2 ml-0 leading-tight text-gray-300 bg-white border border-gray-300 rounded-l-lg cursor-not-allowed">
                <span class="sr-only">Previous</span>
                <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                        clip-rule="evenodd"></path>
                </svg>
            </span>
            @endif

            {{-- Page Numbers --}}
            @php
            $start = max(1, $currentPage - 2);
            $end = min($totalPages, $currentPage + 2);
            @endphp

            @for($i = $start; $i <= $end; $i++) @if($i==$currentPage) <span
                class="px-3 py-2 leading-tight text-blue-600 bg-blue-50 border border-gray-300 hover:bg-blue-100 hover:text-blue-700">
                {{ $i }}
                </span>
                @else
                <button wire:click="gotoPage({{ $i }})"
                    class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700">
                    {{ $i }}
                </button>
                @endif
                @endfor

                {{-- Next Button --}}
                @if($currentPage < $totalPages) <button wire:click="nextPage"
                    class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700">
                    <span class="sr-only">Next</span>
                    <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    </button>
                    @else
                    <span
                        class="px-3 py-2 leading-tight text-gray-300 bg-white border border-gray-300 rounded-r-lg cursor-not-allowed">
                        <span class="sr-only">Next</span>
                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    @endif
        </nav>
    </div>
    @endif

    @push('scripts')
    <script>
        document.addEventListener('gagal', function ({ detail }) {
                feedback('Akses Ditolak!', detail.pesan, 'error');
            });

            // Handle flash messages dengan auto close loading
            @if (session('success'))
                if (typeof Swal !== 'undefined') {
                    Swal.close(); // Close any loading modal
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            @endif

            @if (session('error'))
                if (typeof Swal !== 'undefined') {
                    Swal.close(); // Close any loading modal
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: '{{ session('error') }}',
                        confirmButtonText: 'OK'
                    });
                }
            @endif

            // Listen for Livewire events to close loading modals
            document.addEventListener('livewire:initialized', () => {
                // Listen untuk event permintaan-deleted
                Livewire.on('permintaan-deleted', () => {
                    // Close any loading modal and reload
                    if (typeof Swal !== 'undefined') {
                        Swal.close();
                    }
                    setTimeout(() => {
                        window.location.reload();
                    }, 100);
                });

                // Listen untuk event admin-delete-completed
                Livewire.on('admin-delete-completed', () => {
                    // Close any loading modal and reload
                    if (typeof Swal !== 'undefined') {
                        Swal.close();
                    }
                    setTimeout(() => {
                        window.location.reload();
                    }, 100);
                });

                // Listen untuk event admin-status-changed
                Livewire.on('admin-status-changed', () => {
                    // Close any loading modal and reload
                    if (typeof Swal !== 'undefined') {
                        Swal.close();
                    }
                    setTimeout(() => {
                        window.location.reload();
                    }, 100);
                });

                // Listen untuk event admin-status-change-completed
                Livewire.on('admin-status-change-completed', () => {
                    // Close any loading modal even on error
                    if (typeof Swal !== 'undefined') {
                        Swal.close();
                    }
                    setTimeout(() => {
                        window.location.reload();
                    }, 100);
                });

                // Close loading when any Livewire action completes
                Livewire.hook('morph.updated', () => {
                    if (typeof Swal !== 'undefined' && Swal.isVisible()) {
                        // Check if it's a loading modal and close it
                        const currentModal = Swal.getPopup();
                        if (currentModal && currentModal.querySelector('.swal2-loader')) {
                            Swal.close();
                        }
                    }
                });
            });

            // Function untuk konfirmasi hapus permintaan
            function confirmDeletePermintaan(permintaanId) {
                if (typeof Swal === 'undefined') {
                    const reason = prompt('Alasan menghapus permintaan (opsional):');
                    if (confirm('Apakah Anda yakin ingin menghapus permintaan ini? Tindakan ini tidak dapat dibatalkan.')) {
                        @this.call('deletePermintaan', permintaanId, reason);
                    }
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi Hapus Permintaan',
                    html: `
                                                            <p>Apakah Anda yakin ingin menghapus permintaan ini?</p>
                                                            <p style="color: #dc2626; font-weight: 600; margin-top: 8px;">Tindakan ini tidak dapat dibatalkan.</p>
                                                            <p style="color: #6b7280; font-size: 0.875rem; margin-top: 8px;">Semua data terkait termasuk lampiran dan detail permintaan akan ikut terhapus.</p>
                                                        `,
                    icon: 'warning',
                    input: 'textarea',
                    inputLabel: 'Alasan menghapus (opsional)',
                    inputPlaceholder: 'Jelaskan alasan menghapus permintaan...',
                    inputAttributes: {
                        'aria-label': 'Alasan menghapus permintaan',
                        'style': 'min-height: 80px;'
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="fa-solid fa-trash"></i> Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    focusCancel: true,
                    preConfirm: (reason) => {
                        return reason;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Menghapus...',
                            text: 'Sedang menghapus permintaan',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Call Livewire method with reason
                        @this.call('deletePermintaan', permintaanId, result.value);
                    }
                });
            }

            // Function untuk konfirmasi hapus permintaan sebagai admin
            function confirmAdminDeletePermintaan(permintaanId) {
                if (typeof Swal === 'undefined') {
                    const reason = prompt('Alasan menghapus permintaan sebagai admin (wajib):');
                    if (reason && reason.trim() !== '') {
                        if (confirm('Apakah Anda yakin ingin menghapus permintaan ini sebagai ADMIN? Tindakan ini tidak dapat dibatalkan.')) {
                            @this.call('adminDeletePermintaan', permintaanId, reason);
                        }
                    } else {
                        alert('Alasan hapus wajib diisi untuk admin.');
                    }
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi Hapus Permanen',
                    icon: 'warning',
                    input: 'textarea',
                    inputLabel: 'Alasan hapus sebagai admin (wajib) *',
                    inputPlaceholder: 'Jelaskan alasan menghapus permintaan sebagai admin...',
                    inputAttributes: {
                        'aria-label': 'Alasan hapus sebagai admin',
                        'style': 'min-height: 100px;',
                        'required': true
                    },
                    inputValidator: (value) => {
                        if (!value || value.trim() === '') {
                            return 'Alasan hapus wajib diisi untuk admin!';
                        }
                        if (value.length > 500) {
                            return 'Alasan hapus maksimal 500 karakter!';
                        }
                    },
                    showCancelButton: true,
                    confirmButtonColor: '#7c3aed',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="fa-solid fa-user-slash"></i> Hapus Sebagai Admin',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    focusCancel: true
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        // Show loading
                        Swal.fire({
                            title: 'Menghapus sebagai Admin...',
                            text: 'Sedang menghapus permintaan',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Call Livewire method with reason
                        @this.call('adminDeletePermintaan', permintaanId, result.value);
                    }
                });
            }

            // Function untuk konfirmasi edit status sebagai admin
            function confirmAdminEditStatus(permintaanId) {
                if (typeof Swal === 'undefined') {
                    const newStatus = prompt('Status baru (0=Ditolak, 1=Disetujui, 2=Sedang Dikirim, 3=Selesai, null=Diproses):');
                    const reason = prompt('Alasan mengubah status (opsional):');
                    if (newStatus !== null) {
                        if (confirm('Apakah Anda yakin ingin mengubah status permintaan ini sebagai ADMIN?')) {
                            @this.call('adminEditStatus', permintaanId, newStatus === 'null' ? null : parseInt(newStatus), reason);
                        }
                    }
                    return;
                }

                Swal.fire({
                    title: 'Edit Status Permintaan',
                    html: `
                                            <div class="text-left">
                                                <label class="block text-sm font-medium text-gray-700 mb-2">Status Baru</label>
                                                <select id="statusSelect" class="w-full p-2 border border-gray-300 rounded-md mb-4">
                                                    <option value="">Diproses</option>
                                                    <option value="0">Ditolak</option>
                                                    <option value="1">Disetujui</option>
                                                    <option value="2">Sedang Dikirim</option>
                                                    <option value="3">Selesai</option>
                                                </select>

                                                <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Perubahan (opsional)</label>
                                                <textarea id="reasonTextarea" class="w-full p-2 border border-gray-300 rounded-md" rows="3" placeholder="Jelaskan alasan perubahan status..."></textarea>
                                            </div>
                                        `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#f97316',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: '<i class="fa-solid fa-edit"></i> Ubah Status',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    focusCancel: true,
                    preConfirm: () => {
                        const status = document.getElementById('statusSelect').value;
                        const reason = document.getElementById('reasonTextarea').value;

                        if (reason.length > 500) {
                            Swal.showValidationMessage('Alasan maksimal 500 karakter!');
                            return false;
                        }

                        return {
                            status: status === '' ? null : parseInt(status),
                            reason: reason.trim()
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Mengubah Status...',
                            text: 'Sedang mengubah status permintaan',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Call Livewire method
                        @this.call('adminEditStatus', permintaanId, result.value.status, result.value.reason);
                    }
                });
            }
    </script>
    @endpush
    {{-- @php
    $roleLabel = [
    'kepala-seksi' => 'Kepala Seksi Pemeliharaan',
    'kepala-subbagian' => 'Kepala Subbagian Tata Usaha',
    'pengurus-barang' => 'Pengurus Barang',
    ];
    @endphp --}}

    @if ($showTimelineModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl py-4 px-2">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 ms-6">Riwayat Permintaan</h2>

            <div class="overflow-y-auto max-h-[65vh] pe-2">
                <ol class="relative border-s border-gray-500 dark:border-gray-700 m-6 ps-4">
                    @php
                    $roleLabel = [
                    'kepala-seksi' => 'Kepala Seksi Pemeliharaan',
                    'kepala-subbagian' => 'Kepala Subbagian Tata Usaha',
                    'pengurus-barang' => 'Pengurus Barang',
                    ];
                    @endphp
                    @php
                    $flowStopped = false; // indikator aliran berhenti setelah "Ditolak"
                    @endphp

                    @foreach ($roleList as $slug => $users)
                    @php
                    $label = $roleLabel[$slug] ?? ucfirst(str_replace('-', ' ', $slug));

                    $approvedUser = !$flowStopped
                    ? collect($approvalTimeline)->first(fn($item) =>
                    \Illuminate\Support\Str::contains(
                    strtolower($item['role']),
                    strtolower($label)
                    ))
                    : null;

                    if ($approvedUser) {
                    $status = $approvedUser['status'];
                    $user = $approvedUser['user'];
                    $desc = $approvedUser['desc'] ?? null;
                    $tanggal = $approvedUser['tanggal'];
                    $img = $approvedUser['img'] ?? null;

                    // BYPASS: Untuk periode 12-19 Agustus 2025, jika role adalah Kepala Seksi Pemeliharaan
                    // dan ada approval dari Yusuf (252), tampilkan Yusuf sebagai yang approve
                    if ($slug === 'kepala-seksi' && $selectedId) {
                    $transferApproval = \App\Models\Persetujuan::where('approvable_id', $selectedId)
                    ->where('approvable_type', 'App\Models\DetailPermintaanMaterial')
                    ->where('user_id', 252)
                    ->where('is_approved', 1)
                    ->whereBetween('created_at', ['2025-08-12 00:00:00', '2025-08-19 23:59:59'])
                    ->first();

                    if ($transferApproval) {
                    $user = 'Yusuf Saut Pangibulan, ST, MPSDA';
                    $tanggal = $transferApproval->created_at->format('d M Y H:i');
                    }
                    }

                    if ($status === 'Ditolak') {
                    $flowStopped = true; // stop aliran approval setelah ini
                    }
                    } else {
                    // $status = $flowStopped ? 'Belum Diperiksa' : 'Diproses';
                    $status = $flowStopped ? 'Tidak Dilanjutkan' : 'Diproses';
                    // $user = $flowStopped ? '-' : ($users[0]['name'] ?? '-');
                    $user = $users[0]['name'] ?? '-';
                    $desc = $flowStopped ? null : null;
                    $tanggal = $flowStopped ? '-' : null;
                    $img = $flowStopped ? null : ($users[0]['foto'] ?? null);
                    }

                    // Warna status
                    $badgeColor = match ($status) {
                    'Disetujui' => 'bg-green-100 text-green-800',
                    'Ditolak' => 'bg-red-100 text-red-800',
                    'Diproses' => 'bg-yellow-100 text-yellow-800',
                    'Tidak Dilanjutkan' => 'bg-gray-200 text-gray-600',
                    default => 'bg-gray-100 text-gray-500',
                    };

                    $color = match ($status) {
                    'Disetujui' => 'text-green-600',
                    'Ditolak' => 'text-red-600',
                    'Diproses' => 'text-yellow-600',
                    'Tidak Dilanjutkan' => 'text-gray-600',
                    default => 'text-gray-500',
                    };

                    $initial = strtoupper(substr($user, 0, 1));
                    $imgPath = $img ? storage_path('app/public/' . $img) : null;
                    $imgExists = $imgPath && file_exists($imgPath);
                    @endphp

                    <li class="mb-2 ms-6">
                        <span
                            class="absolute flex items-center justify-center w-9 h-9 rounded-full -start-4 ring-8 ring-white {{ $badgeColor }}">
                            @if ($imgExists)
                            <img src="{{ asset('storage/' . $img) }}" class="rounded-full w-6 h-6 object-cover" />
                            @else
                            <span class="text-xs font-bold text-gray-700">{{ $initial }}</span>
                            @endif
                        </span>

                        <h3 class=" text-md font-semibold text-gray-900 dark:text-white">{{ $label }}</h3>
                        <time class="block  text-xs font-normal leading-none text-gray-400 dark:text-gray-500">{{
                            $tanggal ?? '-'
                            }}</time>
                        <p class="text-sm  font-semibold text-gray-600">{{ $user ?? '-' }}</p>

                        @if (!empty($desc))
                        <p class="text-sm text-gray-500  italic">{!! $desc !!}

                        </p>
                        @endif

                        <span class="inline-block  px-2 py-0.5 text-xs rounded-full {{ $badgeColor }}">
                            {{ $status }}
                        </span>
                    </li>
                    @endforeach
                </ol>
            </div>

            <div class="flex justify-end mt-6">
                <button wire:click="$set('showTimelineModal', false)"
                    class="px-4 py-2 bg-gray-700 text-white rounded hover:bg-gray-500">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @endif
</div>