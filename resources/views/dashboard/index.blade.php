<x-body>
    @if (auth()->user()->unitKerja?->hak == 1)
    <div>
        <livewire:dashboard-display-umum />
    </div>
    @else
    <livewire:dashboard-material />

    @endif

</x-body>