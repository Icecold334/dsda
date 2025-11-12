@use('Illuminate\Support\Facades\Storage')

<div>
    <!-- Search and Filter Section -->
    <div class="bg-white shadow-md rounded-lg p-4 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Pencarian</label>
                <input type="text" wire:model.live="search" id="search" placeholder="Nama, email, username, atau NIP..."
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select wire:model.live="role" id="role"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">Semua Role</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Unit Kerja</label>
                <select wire:model.live="unit" id="unit"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">Semua Unit</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="verified" class="block text-sm font-medium text-gray-700 mb-1">Status Verifikasi</label>
                <select wire:model.live="verified" id="verified"
                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">Semua Status</option>
                    <option value="1">Terverifikasi</option>
                    <option value="0">Belum Verifikasi</option>
                </select>
            </div>

            <div class="md:col-span-4 flex space-x-2">
                <button type="button" wire:click="resetFilters"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                    <i class="fas fa-times mr-1"></i> Reset
                </button>
            </div>
        </div>
    </div>

    <!-- Loading indicator -->
    <div wire:loading class="text-center py-4">
        <div
            class="inline-flex items-center px-4 py-2 font-semibold leading-6 text-sm shadow rounded-md text-white bg-primary-500 transition ease-in-out duration-150">
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            Loading...
        </div>
    </div>

    <!-- Bulk Actions Form -->
    <form id="bulkActionForm" method="POST" action="{{ route('users.bulk-action') }}">
        @csrf
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <!-- Bulk Action Bar -->
            <div id="bulkActionBar" class="hidden bg-blue-50 border-b border-blue-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <span id="selectedCount" class="text-blue-700 font-medium">0 user dipilih</span>
                    </div>
                    <div class="flex space-x-2">
                        <select name="action" id="bulkAction" class="rounded-md border-gray-300 text-sm" required>
                            <option value="">Pilih Aksi</option>
                            <option value="verify">Verifikasi Email</option>
                            <option value="unverify">Batalkan Verifikasi</option>
                            <option value="assign_role">Assign Role</option>
                            <option value="delete">Hapus</option>
                        </select>

                        <select name="role_id" id="roleSelect" class="hidden rounded-md border-gray-300 text-sm">
                            <option value="">Pilih Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>

                        <button type="button" onclick="executeBulkAction()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm transition duration-200">
                            Jalankan
                        </button>
                        <button type="button" onclick="clearSelection()"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm transition duration-200">
                            Batal
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto" wire:loading.remove>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left">
                                <input type="checkbox" id="selectAll" onchange="toggleAllCheckboxes()"
                                    class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                NIP
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Unit Kerja
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr>
                                <td class="px-6 py-4">
                                    <input type="checkbox" name="users[]" value="{{ $user->id }}"
                                        onchange="updateBulkActionBar()"
                                        class="user-checkbox rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($user->foto)
                                            <img class="h-10 w-10 rounded-full mr-3" src="{{ Storage::url($user->foto) }}"
                                                alt="{{ $user->name }}">
                                        @else
                                            <div
                                                class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                                <span
                                                    class="text-gray-600 text-sm font-medium">{{ substr($user->name, 0, 2) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            @if($user->username)
                                                <div class="text-sm text-gray-500">{{ $user->username }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $user->nip ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $user->unitKerja->nama ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->roles->count() > 0)
                                        @foreach($user->roles as $role)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1 mb-1">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-gray-500">No Role</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->email_verified_at)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('users.show', $user) }}"
                                            class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" onclick="confirmDelete('{{ route('users.destroy', $user) }}')"
                                            class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    @if($search || $role || $unit || $verified !== '')
                                        Tidak ada data user yang sesuai dengan filter
                                    @else
                                        Tidak ada data user
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
                <div class="px-6 py-3 bg-gray-50">
                    {{ $users->links() }}
                </div>
            @endif
        </div>
    </form>
</div>

<script>
    function toggleAllCheckboxes() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.user-checkbox');

        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });

        updateBulkActionBar();
    }

    function updateBulkActionBar() {
        const checkboxes = document.querySelectorAll('.user-checkbox:checked');
        const bulkActionBar = document.getElementById('bulkActionBar');
        const selectedCount = document.getElementById('selectedCount');

        if (checkboxes.length > 0) {
            bulkActionBar.classList.remove('hidden');
            selectedCount.textContent = `${checkboxes.length} user dipilih`;
        } else {
            bulkActionBar.classList.add('hidden');
        }
    }

    function clearSelection() {
        const checkboxes = document.querySelectorAll('.user-checkbox, #selectAll');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });
        updateBulkActionBar();
    }

    // Show/hide role select based on action
    document.addEventListener('DOMContentLoaded', function () {
        const bulkActionSelect = document.getElementById('bulkAction');
        if (bulkActionSelect) {
            bulkActionSelect.addEventListener('change', function () {
                const roleSelect = document.getElementById('roleSelect');
                if (this.value === 'assign_role') {
                    roleSelect.classList.remove('hidden');
                    roleSelect.required = true;
                } else {
                    roleSelect.classList.add('hidden');
                    roleSelect.required = false;
                }
            });
        }
    });

    function executeBulkAction() {
        const action = document.getElementById('bulkAction').value;
        const checkboxes = document.querySelectorAll('.user-checkbox:checked');

        if (!action) {
            Swal.fire('Error', 'Silakan pilih aksi yang akan dilakukan', 'error');
            return;
        }

        if (checkboxes.length === 0) {
            Swal.fire('Error', 'Silakan pilih minimal satu user', 'error');
            return;
        }

        if (action === 'assign_role') {
            const roleSelect = document.getElementById('roleSelect');
            if (!roleSelect.value) {
                Swal.fire('Error', 'Silakan pilih role yang akan diberikan', 'error');
                return;
            }
        }

        let confirmText = '';
        let confirmButtonText = '';

        switch (action) {
            case 'verify':
                confirmText = `Verifikasi email untuk ${checkboxes.length} user?`;
                confirmButtonText = 'Ya, Verifikasi';
                break;
            case 'unverify':
                confirmText = `Batalkan verifikasi email untuk ${checkboxes.length} user?`;
                confirmButtonText = 'Ya, Batalkan';
                break;
            case 'assign_role':
                const roleName = document.getElementById('roleSelect').selectedOptions[0].text;
                confirmText = `Berikan role "${roleName}" kepada ${checkboxes.length} user?`;
                confirmButtonText = 'Ya, Berikan Role';
                break;
            case 'delete':
                confirmText = `Hapus ${checkboxes.length} user? Aksi ini tidak dapat dibatalkan!`;
                confirmButtonText = 'Ya, Hapus';
                break;
        }

        Swal.fire({
            title: 'Konfirmasi',
            text: confirmText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('bulkActionForm').submit();
            }
        });
    }

    function confirmDelete(deleteUrl) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data user akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create a form dynamically and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;

                // Add CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Add method spoofing for DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);

                // Add to body and submit
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>