<div x-data="{ open: false }" class="relative w-full">
    
    @if($disabled)
        <input 
            type="text"
            class="bg-gray-100 border border-gray-300 text-gray-700 text-sm rounded-lg w-full p-2.5 cursor-not-allowed"
            value="{{ $search }}"
            readonly
            disabled
            style="background-color: #f3f4f6 !important; cursor: not-allowed;"
        />
    @else
        {{-- Mode NORMAL: Searchable dropdown --}}
        <input 
            type="text"
            class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
            placeholder="Cari..." 
            wire:model.live="search" 
            @focus="open = true" 
            @click.away="open = false" 
        />

    <ul x-show="open"
        class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-lg max-h-60 overflow-y-auto shadow-lg" style="display: none;">
        @forelse($filteredOptions as $item)
        @php
            $view = is_array($item) ? ($item[$label] ?? '') : ($item->{$label} ?? '');
            $itemId = is_array($item) ? ($item['id'] ?? null) : ($item->id ?? null);
        @endphp
        <li class="px-4 py-2 cursor-pointer hover:bg-blue-100"
            wire:click="select('{{ $itemId }}')">
            {{ $view }}
        </li>
        @empty
        <li class="px-4 py-2 text-gray-500">Tidak ditemukan</li>
        @endforelse
    </ul>

    {{-- @if($search)
    <div class="mt-2 text-sm text-gray-600">
        Dipilih: {{ $search }}
    </div>
    @endif --}}
</div>