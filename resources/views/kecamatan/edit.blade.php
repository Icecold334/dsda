<x-body>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900">Edit Kecamatan</h1>
        <div>
            <a href="{{ route('kecamatan.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2">
        <div class="border p-6 rounded-lg shadow-md bg-white">
            <form action="{{ route('kecamatan.update', $kecamatan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <x-input-label for="kecamatan" value="Nama Kecamatan" />
                    <x-text-input id="kecamatan" name="kecamatan" type="text" class="mt-2 block w-full"
                        value="{{ old('kecamatan', $kecamatan->kecamatan) }}" required autofocus />
                    <x-input-error :messages="$errors->get('kecamatan')" class="mt-2" />
                </div>

                <div class="mb-6">
                    <x-input-label for="unit_id" value="Unit Kerja" />
                    <select id="unit_id" name="unit_id"
                        class="mt-2 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Pilih Unit Kerja</option>
                        @foreach ($unitKerjas as $unitKerja)
                            <option value="{{ $unitKerja->id }}" {{ old('unit_id', $kecamatan->unit_id) == $unitKerja->id ? 'selected' : '' }}>
                                {{ $unitKerja->nama }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('unit_id')" class="mt-2" />
                </div>

                <div class="flex items-center gap-4">
                    <x-primary-button>
                        {{ __('Perbarui') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-body>