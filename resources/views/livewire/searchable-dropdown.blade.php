<div class="relative" x-data="{ open: false }">
    <input type="text" placeholder="Cari..." wire:model.live="query" @focus="open = true" @click.away="open = false"
        @keydown.escape="open = false" class="w-full border rounded p-2" wire:focus="showSuggestion"
        wire:input="showSuggestion" wire:blur="hideSuggestion" />

    @if ($show)
        <ul class="absolute z-50 bg-white border rounded overflow-y-scroll max-h-40 w-full mt-1"
            @click.outside="open = false">
            @forelse($options as $option)
                <li class="p-2 hover:bg-gray-200 cursor-pointer" wire:click="selectOption({{ $option['id'] }})"
                    @click="open = false">
                    {{ $option['nama'] }}
                </li>
            @empty
                <li class="p-2 text-gray-500">Data tidak ditemukan.</li>
            @endforelse
        </ul>

    @endif
    @if ($selectedOption)
        <div class="mt-2 text-sm text-green-600">
            Pilihan: {{ $query }}
        </div>
    @endif
</div>
