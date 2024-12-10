<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">Form
            {{ request()->is('permintaan/add/permintaan') ? 'Pelayanan Umum' : (request()->is('permintaan/add/spare-part') ? 'Permintaan Spare Part' : 'Permintaan Material') }}
        </h1>
        <div>
            <a href="/permintaan/{{ request()->is('permintaan/add/permintaan') ? 'umum' : (request()->is('permintaan/add/spare-part') ? 'spare-part' : 'material') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
        </div>
    </div>
    <livewire:form-permintaan :tipe="$tipe">
</x-body>
