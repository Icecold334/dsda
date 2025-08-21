<x-body>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-primary-900">Detail User</h1>
        <nav class="text-sm text-gray-600">
            <a href="{{ route('users.index') }}" class="hover:text-primary-600">Master Data User</a>
            <span class="mx-2">></span>
            <span>{{ $user->name }}</span>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="text-center">
                    @if($user->foto)
                        <img class="w-32 h-32 rounded-full mx-auto mb-4 object-cover" src="{{ Storage::url($user->foto) }}"
                            alt="{{ $user->name }}">
                    @else
                        <div class="w-32 h-32 rounded-full bg-gray-300 flex items-center justify-center mx-auto mb-4">
                            <span class="text-gray-600 text-2xl font-medium">{{ substr($user->name, 0, 2) }}</span>
                        </div>
                    @endif

                    <h2 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h2>
                    @if($user->username)
                        <p class="text-gray-600">{{ $user->username }}</p>
                    @endif

                    <div class="mt-4 space-y-2">
                        @if($user->email_verified_at)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Email Verified
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Email Not Verified
                            </span>
                        @endif
                    </div>

                    <!-- Roles -->
                    @if($user->roles->count() > 0)
                        <div class="mt-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Roles</h3>
                            <div class="flex flex-wrap gap-2 justify-center">
                                @foreach($user->roles as $role)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $role->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Signature -->
                    @if($user->ttd)
                        <div class="mt-4">
                            <h3 class="text-sm font-medium text-gray-700 mb-2">Tanda Tangan Digital</h3>
                            <img class="w-24 h-12 mx-auto object-cover border border-gray-300 rounded"
                                src="{{ Storage::url($user->ttd) }}" alt="TTD {{ $user->name }}">
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="mt-6 flex space-x-3 justify-center">
                        <a href="{{ route('users.edit', $user) }}"
                            class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition-colors">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDelete(this)"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                                <i class="fas fa-trash mr-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details Card -->
        <div class="lg:col-span-2">
            <div class="bg-white shadow-md rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Detail</h3>
                </div>

                <div class="px-6 py-4 space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h4 class="text-md font-medium text-gray-900 mb-3">Informasi Dasar</h4>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-900">{{ $user->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">NIP</dt>
                                <dd class="text-sm text-gray-900">{{ $user->nip ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">No. WhatsApp</dt>
                                <dd class="text-sm text-gray-900">{{ $user->no_wa ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Perusahaan</dt>
                                <dd class="text-sm text-gray-900">{{ $user->perusahaan ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Organization Information -->
                    <div>
                        <h4 class="text-md font-medium text-gray-900 mb-3">Informasi Organisasi</h4>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Unit Kerja</dt>
                                <dd class="text-sm text-gray-900">{{ $user->unitKerja->nama ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Lokasi Stok</dt>
                                <dd class="text-sm text-gray-900">
                                    @if($user->lokasiStok)
                                        {{ $user->lokasiStok->nama }}
                                        @if($user->lokasiStok->unitKerja)
                                            <span class="text-gray-500">({{ $user->lokasiStok->unitKerja->nama }})</span>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Kecamatan</dt>
                                <dd class="text-sm text-gray-900">{{ $user->kecamatan->kecamatan ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Address Information -->
                    @if($user->alamat)
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-3">Alamat</h4>
                            <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $user->alamat }}</p>
                        </div>
                    @endif

                    <!-- Additional Information -->
                    @if($user->keterangan)
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-3">Keterangan</h4>
                            <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $user->keterangan }}</p>
                        </div>
                    @endif

                    <!-- System Information -->
                    <div>
                        <h4 class="text-md font-medium text-gray-900 mb-3">Informasi Sistem</h4>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tanggal Dibuat</dt>
                                <dd class="text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Terakhir Diupdate</dt>
                                <dd class="text-sm text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                            @if($user->email_verified_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email Diverifikasi</dt>
                                    <dd class="text-sm text-gray-900">{{ $user->email_verified_at->format('d/m/Y H:i') }}
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <!-- Permissions Preview -->
                    @if($user->roles->count() > 0)
                        <div>
                            <h4 class="text-md font-medium text-gray-900 mb-3">Preview Permissions</h4>
                            <div class="bg-gray-50 rounded-lg p-4">
                                @php
                                    $allPermissions = $user->getAllPermissions();
                                @endphp
                                @if($allPermissions->count() > 0)
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                        @foreach($allPermissions->take(12) as $permission)
                                            <span class="text-xs px-2 py-1 bg-gray-200 text-gray-700 rounded">
                                                {{ $permission->name }}
                                            </span>
                                        @endforeach
                                        @if($allPermissions->count() > 12)
                                            <span class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded">
                                                +{{ $allPermissions->count() - 12 }} more
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-sm text-gray-500">Tidak ada permissions</p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'bg-primary-600 hover:bg-primary-700 text-white font-bold py-2 px-4 rounded'
                    }
                });
            });
        </script>
    @endif

    <script>
        function confirmDelete(button) {
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
                    button.closest('form').submit();
                }
            });
        }
    </script>
</x-body>