<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">PERBARUI PROFILE PENGGUNA</h1>
        <div>
            <a href="{{ route('profil.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-3">
        <div>
            <!-- Form Update Profil -->
            <form action="{{ route('profil.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <x-card title="Edit Profil" class="mb-3">
                    <div class="space-y-4">
                        <!-- Nama -->
                        <div class="flex items-center space-x-4">
                            <label for="name" class="w-1/4 text-sm font-medium text-gray-700">Nama</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                class="block w-full border border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>

                        <!-- Perusahaan / Organisasi -->
                        <div class="flex items-center space-x-4">
                            <label for="company"
                                class="w-1/4 text-sm font-medium text-gray-700">Perusahaan/Organisasi</label>
                            <input type="text" id="company" name="company"
                                value="{{ old('company', $user->company) }}"
                                class="block w-full border border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>

                        <!-- Alamat -->
                        <div class="flex items-center space-x-4">
                            <label for="address" class="w-1/4 text-sm font-medium text-gray-700">Alamat</label>
                            <input type="text" id="address" name="address"
                                value="{{ old('address', $user->address) }}"
                                class="block w-full border border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>

                        <!-- Provinsi -->
                        <div class="flex items-center space-x-4">
                            <label for="province" class="w-1/4 text-sm font-medium text-gray-700">Provinsi</label>
                            <select id="province" name="province"
                                class="block w-full border border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="JAWA TENGAH"
                                    {{ old('province', $user->province) == 'JAWA TENGAH' ? 'selected' : '' }}>JAWA
                                    TENGAH</option>
                                <option value="DKI JAKARTA"
                                    {{ old('province', $user->province) == 'DKI JAKARTA' ? 'selected' : '' }}>DKI
                                    JAKARTA</option>
                                <!-- Tambahkan opsi lainnya -->
                            </select>
                        </div>

                        <!-- Kota / Kabupaten -->
                        <div class="flex items-center space-x-4">
                            <label for="city" class="w-1/4 text-sm font-medium text-gray-700">Kota/Kabupaten</label>
                            <select id="city" name="city"
                                class="block w-full border border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="SUKOHARJO"
                                    {{ old('city', $user->city) == 'SUKOHARJO' ? 'selected' : '' }}>SUKOHARJO</option>
                                <option value="SOLO" {{ old('city', $user->city) == 'SOLO' ? 'selected' : '' }}>SOLO
                                </option>
                                <!-- Tambahkan opsi lainnya -->
                            </select>
                        </div>

                        <!-- Tombol Simpan -->
                        <div class="flex justify-end mt-4">
                            <button type="button" wire:click="#"
                                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                                Simpan
                            </button>
                        </div>
                    </div>
                </x-card>
            </form>
        </div>
</x-body>
