<x-body>
    <div>
        @if (auth()->user()->unitKerja->hak || 1)
        <livewire:data-stok />
        @else
        <livewire:data-stok-material />
        @endif
    </div>
</x-body>