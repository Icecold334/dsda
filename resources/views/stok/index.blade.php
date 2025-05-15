<x-body>
    <div>
        @if (auth()->user()->unitKerja->hak)
        <livewire:data-stok />
        @else
        <livewire:data-stok-material />
        @endif
    </div>
</x-body>