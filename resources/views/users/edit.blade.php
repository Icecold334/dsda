<x-body>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-primary-900">Edit User</h1>
        <nav class="text-sm text-gray-600">
            <a href="{{ route('users.index') }}" class="hover:text-primary-600">Master Data User</a>
            <span class="mx-2">></span>
            <span>Edit User: {{ $user->name }}</span>
        </nav>
    </div>

    <div class="bg-white shadow-md rounded-lg">
        <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Edit Informasi User</h2>
            </div>

            <div class="px-6 py-4 space-y-6">
                <!-- Current Files Preview -->
                @if($user->foto || $user->ttd)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($user->foto)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Saat Ini</label>
                                <img src="{{ Storage::url($user->foto) }}" alt="Foto {{ $user->name }}"
                                    class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                            </div>
                        @endif
                        @if($user->ttd)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanda Tangan Saat Ini</label>
                                <img src="{{ Storage::url($user->ttd) }}" alt="TTD {{ $user->name }}"
                                    class="w-32 h-16 object-cover rounded-lg border border-gray-300">
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-500 @enderror"
                            required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            Username
                        </label>
                        <input type="text" id="username" name="username" value="{{ old('username', $user->username) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('username') border-red-500 @enderror">
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('email') border-red-500 @enderror"
                            required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nip" class="block text-sm font-medium text-gray-700 mb-2">
                            NIP
                        </label>
                        <input type="number" id="nip" name="nip" value="{{ old('nip', $user->nip) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('nip') border-red-500 @enderror">
                        @error('nip')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password Baru
                        </label>
                        <input type="password" id="password" name="password"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('password') border-red-500 @enderror">
                        <p class="mt-1 text-sm text-gray-500">Kosongkan jika tidak ingin mengubah password</p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password Baru
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    </div>
                </div>

                <!-- Email Verification Status -->
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <span class="text-sm font-medium text-gray-700 mr-2">Status Email:</span>
                        @if($user->email_verified_at)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Verified pada {{ $user->email_verified_at->format('d/m/Y H:i') }}
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Belum Verified
                            </span>
                        @endif
                    </div>
                    <a href="{{ route('users.toggle-email-verification', $user) }}"
                        class="text-sm text-primary-600 hover:text-primary-700 underline">
                        {{ $user->email_verified_at ? 'Batalkan Verifikasi' : 'Verifikasi Email' }}
                    </a>
                </div>

                <!-- Organization Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="unit_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Unit Kerja
                        </label>
                        <select id="unit_id" name="unit_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('unit_id') border-red-500 @enderror">
                            <option value="">-- Pilih Unit Kerja --</option>
                            @foreach($unitKerjas as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id', $user->unit_id) == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="lokasi_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Lokasi Stok
                        </label>
                        <select id="lokasi_id" name="lokasi_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('lokasi_id') border-red-500 @enderror">
                            <option value="">-- Pilih Lokasi Stok --</option>
                            @foreach($lokasiStoks as $lokasi)
                                <option value="{{ $lokasi->id }}" {{ old('lokasi_id', $user->lokasi_id) == $lokasi->id ? 'selected' : '' }}>
                                    {{ $lokasi->nama }} - {{ $lokasi->unitKerja->nama ?? 'No Unit' }}
                                </option>
                            @endforeach
                        </select>
                        @error('lokasi_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kecamatan_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Kecamatan
                        </label>
                        <select id="kecamatan_id" name="kecamatan_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('kecamatan_id') border-red-500 @enderror">
                            <option value="">-- Pilih Kecamatan --</option>
                            @foreach($kecamatans as $kecamatan)
                                <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id', $user->kecamatan_id) == $kecamatan->id ? 'selected' : '' }}>
                                    {{ $kecamatan->kecamatan }}
                                </option>
                            @endforeach
                        </select>
                        @error('kecamatan_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="perusahaan" class="block text-sm font-medium text-gray-700 mb-2">
                            Perusahaan
                        </label>
                        <input type="text" id="perusahaan" name="perusahaan"
                            value="{{ old('perusahaan', $user->perusahaan) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('perusahaan') border-red-500 @enderror">
                        @error('perusahaan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="no_wa" class="block text-sm font-medium text-gray-700 mb-2">
                            No. WhatsApp
                        </label>
                        <input type="text" id="no_wa" name="no_wa" value="{{ old('no_wa', $user->no_wa) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('no_wa') border-red-500 @enderror">
                        @error('no_wa')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat
                        </label>
                        <textarea id="alamat" name="alamat" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('alamat') border-red-500 @enderror">{{ old('alamat', $user->alamat) }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Roles -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Role
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($roles as $role)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                    class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                    {{ in_array($role->id, old('roles', $userRoles)) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700">{{ $role->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('roles')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Uploads -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="foto" class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Profil Baru
                        </label>
                        <input type="file" id="foto" name="foto" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('foto') border-red-500 @enderror">
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, GIF. Max: 2MB. Kosongkan jika tidak
                            ingin mengubah</p>
                        @error('foto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="ttd" class="block text-sm font-medium text-gray-700 mb-2">
                            Tanda Tangan Digital Baru
                        </label>
                        <input type="file" id="ttd" name="ttd" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('ttd') border-red-500 @enderror">
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, GIF. Max: 2MB. Kosongkan jika tidak
                            ingin mengubah</p>
                        @error('ttd')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Additional Information -->
                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan
                    </label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('keterangan') border-red-500 @enderror">{{ old('keterangan', $user->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('users.index') }}"
                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Batal
                </a>
                <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm bg-primary-600 text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Update User
                </button>
            </div>
        </form>
    </div>
</x-body>