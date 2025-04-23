<div>
    @if (!$uploaded)
    <div class="font-medium mb-3">Unggah foto Surat Jalan (1 file) dan Foto Barang (bisa banyak) di sini.</div>
    @endif
    {{-- Loading Indicator --}}
    <div wire:loading wire:target="surat_jalan, foto_barang, newFotoBarang">
        <livewire:loading />
    </div>

    {{-- Tombol Upload Hanya Muncul Sebelum Submit --}}
    @if (!$uploaded)
    {{-- Surat Jalan --}}
    <input type="file" wire:model.live="surat_jalan" accept="image/*" class="hidden" id="uploadSuratJalan">
    <label for="uploadSuratJalan"
        class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200 cursor-pointer">
        + Unggah Surat Jalan
    </label>

    @if ($surat_jalan)
    <div class="mt-2 flex items-center space-x-3 border p-2 rounded">
        <i class="fa-solid fa-file-image text-green-500"></i>
        <a href="{{ $surat_jalan->temporaryUrl() }}" target="_blank" class="text-gray-800 hover:underline">
            {{ $surat_jalan->getClientOriginalName() }}
        </a>
    </div>
    @endif

    {{-- Foto Barang --}}
    <input type="file" wire:model.live="newFotoBarang" multiple accept="image/*" class="hidden" id="uploadFotoBarang">
    <label for="uploadFotoBarang"
        class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200 cursor-pointer">
        + Unggah Foto Barang
    </label>
    @endif

    {{-- Hasil Upload (setelah simpan) --}}
    @if ($uploaded)
    <div class="mt-5 mb-2 font-semibold text-primary-800">📄 Surat Jalan</div>
    @if ($storedSuratJalan)
    <div class="flex items-center space-x-3 border p-2 rounded">
        <i class="fa-solid fa-file-image text-green-500"></i>
        <a href="{{ Storage::url($storedSuratJalan) }}" target="_blank" class="text-gray-800 hover:underline">
            {{ basename($storedSuratJalan) }}
        </a>
    </div>
    @endif

    <div class="mt-5 mb-2 font-semibold text-primary-800">📷 Foto Barang</div>
    @endif

    {{-- Daftar Foto Barang --}}
    <div class="mt-3 max-h-40 overflow-y-auto pr-1 space-y-1">
        @foreach ($foto_barang as $index => $file)
        <div class="flex items-center justify-between border-b-4 p-2 rounded my-1">
            <span class="flex items-center space-x-3">
                <i class="fa-solid fa-image text-green-500"></i>
                @if ($uploaded)
                <a href="{{ Storage::url($file) }}" target="_blank" class="text-gray-800 hover:underline">
                    {{ basename($file) }}
                </a>
                @else
                <a href="{{ $file->temporaryUrl() }}" target="_blank" class="text-gray-800 hover:underline">
                    {{ $file->getClientOriginalName() }}
                </a>
                @endif
            </span>

            @if (!$uploaded)
            <button wire:click="removeFotoBarang({{ $index }})" class="text-red-500 hover:text-red-700">&times;</button>
            @endif
        </div>
        @endforeach
    </div>

    {{-- Tombol Simpan --}}
    @if (!$uploaded)
    <div class="mt-4">
        <button wire:click="save" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Simpan
        </button>
    </div>
    @endif
</div>