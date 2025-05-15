<x-body>
    @if (auth()->user()->unitKerja->hak)
    <livewire:show-stok :barang="$barang" :stok="$stok" />
    @else
    <livewire:show-stok-material :lokasi_id="$id" />
    @endif
</x-body>