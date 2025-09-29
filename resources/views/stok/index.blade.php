<x-body>
    <div>
        @if (auth()->user()->unitKerja && (auth()->user()->unitKerja->hak ?? 0))
            <livewire:data-stok />
        @else
            <livewire:data-stok-material :all="true" />
        @endif
    </div>
</x-body>