<x-body>
    <div class="flex justify-between py-2 mb-3">
        <!-- Judul Dinamis -->
        <h1 class="text-2xl font-bold text-primary-900">
            {{ $tipe === 'edit' ? 'Edit Penanggung Jawab' : 'Tambah Penanggung Jawab' }}
        </h1>
        <div>
            <a href="{{ route('person.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2">
        <div class="border p-4 rounded-lg shadow-md">
            @dump($tipe)
            <livewire:add-person :tipe="$tipe" :id="$person" />
        </div>
    </div>
</x-body>
