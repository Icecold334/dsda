<div class="">

    <div class="">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Kontrak</label>
            <input type="text" wire:model.live.debounce.500ms="nomor_kontrak"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200" />
            @error('nomor_kontrak')
            <span class="text-sm text-red-500">{{ $message }}</span>
            @enderror
        </div>
        @if ($kontrak)
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Vendor</label>
            <input type="text" readonly value="{{ $kontrak->vendorStok->nama }}"
                class="w-full bg-gray-100 border-gray-300 rounded-lg shadow-sm" />
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Metode Pengadaan</label>
            <input type="text" readonly value="{{ $kontrak->metodePengadaan->nama }}"
                class="w-full bg-gray-100 border-gray-300 rounded-lg shadow-sm" />
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kontrak</label>
            <input type="text" readonly value="{{ Carbon\Carbon::parse($kontrak->tanggal_kontrak)->format('d M Y') }}"
                class="w-full bg-gray-100 border-gray-300 rounded-lg shadow-sm" />
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Pengiriman (Gudang)</label>
            <selectwire:model.live.debounce.500ms="gudang_id"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200">
                <option value="">Pilih Gudang</option>
                @foreach ($listGudang as $gudang)
                <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                @endforeach
                </select>
                @error('gudang_id')
                <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
        </div>
        @endif

    </div>
</div>