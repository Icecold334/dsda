<x-body>
    <div>
        @if (auth()->user()->unitKerja->hak)
        <livewire:data-stok />
        @else
        <livewire:data-stok-material :all="$sudin" />
        @endif
    </div>
</x-body>