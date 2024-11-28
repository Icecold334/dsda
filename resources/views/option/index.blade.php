<x-body>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900 ">Pengaturan</h1>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-3">
        <div>
            <livewire:setting-options />
        </div>
        <div>
            <livewire:scan-qr-code-permissions />
        </div>
    </div>
</x-body>
