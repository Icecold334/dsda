<div>
    <!-- Filter dan Pencarian -->
    <div class="flex items-center space-x-3 mb-4">
        <select wire:model.live="selectedCategory"
            class="w-1/2 p-2 border rounded-lg focus:ring-primary-500 focus:border-primary-500 text-sm">
            <option value="">Semua Kategori</option>
            <option value="0">Tidak Berkategori</option>
            @foreach ($kategoris as $kategori)
                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                @if ($kategori->children->isNotEmpty())
                    @foreach ($kategori->children as $child)
                        <option value="{{ $child->id }}">--- {{ $child->nama }}</option>
                    @endforeach
                @endif
            @endforeach
        </select>
        <input type="text" wire:model.live="search" placeholder="Cari Nama Aset"
            class="w-full p-2 border rounded-lg focus:ring-primary-500 focus:border-primary-500 text-sm">
    </div>

    <!-- Daftar Aset -->
    <div class="overflow-y-auto max-h-96">
        @foreach ($assets as $asset)
            <div class="flex items-center justify-between border-b py-2">
                <div class="flex items-center space-x-3">
                    <img src="{{ $asset->qrCode }}" alt="QR Code" class="w-12 h-12">
                    <div>
                        <div class="text-sm font-medium text-gray-700">{{ $asset->nama }}</div>
                        <div class="text-xs text-gray-500">{{ $asset->kategori->nama }}</div>
                    </div>
                </div>
                <button wire:click="addToSelected({{ $asset->id }})"
                    class="px-2 py-1 text-primary-900 bg-gray-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm transition">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
        @endforeach
    </div>
</div>
    