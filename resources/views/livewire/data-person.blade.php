<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900">Penanggung Jawab Aset</h1>
        <div class="flex justify-between items-center gap-4 mb-3">
            <!-- Input Pencarian -->
            <div class="flex-1">
                <input type="text" wire:keyup="updateSearch($event.target.value)" placeholder="Cari Penanggung Jawab..."
                    class="w-full rounded-lg px-4 py-2 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-600" />
            </div>

            <!-- Tombol Tambah Penanggung Jawab -->
            <a href="{{ route('person.create') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                + Tambah Penanggung Jawab
            </a>
        </div>
    </div>

    <table class="w-full border-3 border-separate border-spacing-y-4">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Nama</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Jabatan</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Alamat</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Telepon</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Email</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Keterangan</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Jumlah Aset</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($persons as $person)
                <tr
                    class="bg-gray-300 hover:bg-gray-200 hover:shadow-lg font-semibold transition duration-200 rounded-2xl">
                    <td class="px-6 py-3"></td>
                    <td class="px-6 py-3">{{ $person['nama'] }}</td>
                    <td class="px-6 py-3">{{ $person['jabatan'] }}</td>
                    <td class="px-6 py-3">{{ $person['alamat'] }}</td>
                    <td class="px-6 py-3">{{ $person['telepon'] }}</td>
                    <td class="px-6 py-3">{{ $person['email'] }}</td>
                    <td class="px-6 py-3">{{ $person['keterangan'] }}</td>
                    <td class="text-center px-6 py-3">
                        <!-- Link to aset.index with tooltip -->
                        <a href="{{ route('aset.index', ['penanggung_jawab_id' => $person['id']]) }}"
                            class="text-primary-950 hover:underline"
                            data-tooltip-target="tooltip-jumlah-person-{{ $person['id'] }}">
                            {{ $person['aset_count'] }}
                        </a>
                        <div id="tooltip-jumlah-person-{{ $person['id'] }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Lihat aset untuk "{{ $person['nama'] }}"
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <a href="/person/edit/{{ $person['id'] }}"
                            class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300"
                            data-tooltip-target="tooltip-aset-{{ $person['id'] }}">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <div id="tooltip-aset-{{ $person['id'] }}" role="tooltip"
                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                            Ubah Penanggung Jawab
                            <div class="tooltip-arrow" data-popper-arrow></div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center py-3">Tidak ada data penanggung jawab.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $persons->onEachSide(1)->links() }}
</div>
