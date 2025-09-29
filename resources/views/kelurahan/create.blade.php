<x-body>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900">Tambah Kelurahan</h1>
        <div>
            <a href="{{ route('kelurahan.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2">
        <div class="border p-6 rounded-lg shadow-md bg-white">
            <form action="{{ route('kelurahan.store') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <x-input-label for="nama" value="Nama Kelurahan" />
                    <x-text-input id="nama" name="nama" type="text" class="mt-2 block w-full"
                        value="{{ old('nama') }}" required autofocus />
                    <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                </div>

                <div class="mb-6">
                    <x-input-label for="kecamatan_id" value="Kecamatan" />
                    <select id="kecamatan_id" name="kecamatan_id" class="mt-2 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm @error('kecamatan_id') border-red-500 @enderror" required>
                        <option value="">Pilih Kecamatan</option>
                        @foreach ($kecamatans as $kecamatan)
                            <option value="{{ $kecamatan->id }}" {{ old('kecamatan_id') == $kecamatan->id ? 'selected' : '' }}>
                                {{ $kecamatan->kecamatan }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('kecamatan_id')" class="mt-2" />
                </div>

                <div class="flex items-center gap-4">
                    <x-primary-button>
                        {{ __('Simpan') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-body>