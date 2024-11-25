<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">Permintaan Umum</h1>
        <div>
            <a href="/permintaan-stok"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                Kembali</a>
            {{-- <a href="#"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                Tambah Peminjaman</a> --}}
        </div>
    </div>

    <livewire:form-permintaan :permintaan="$permintaan" :tanggal_permintaan="Carbon\Carbon::now()->format('Y-m-d')" :keterangan="$permintaan->keterangan" :unit_id="$permintaan->unit_id" :sub_unit_id="$permintaan->sub_unit_id">
        <livewire:approval-permintaan :permintaan="$permintaan">

</x-body>
