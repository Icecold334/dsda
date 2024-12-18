<x-body>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900 ">Pengaturan Persetujuan
            {{ Str::title($tipe . ' ' . $jenis) }}
            @if (auth()->user()->unitKerja)
                {{ auth()->user()->unitKerja->parent ? auth()->user()->unitKerja->parent->nama : auth()->user()->unitKerja->nama }}
            @endif
        </h1>
        <div>
            <a href="/option-approval"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
        </div>
    </div>

    <livewire:approval-option :tipe="$tipe" :jenis="$jenis">

</x-body>
