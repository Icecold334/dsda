<div class="p-4">
    <h1 class="text-xl font-bold mb-4">Master Driver</h1>

    @can('driver.create')
    <button wire:click="openModalCreate" class="mb-4 px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">
        + Tambah Driver
    </button>
    @endcan

    <table class="w-full table-auto border">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="p-2">#</th>
                <th class="p-2">Nama</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($drivers as $i => $driver)
            <tr class="border-t">
                <td class="p-2">{{ $i + 1 }}</td>
                <td class="p-2">{{ $driver->nama }}</td>
                <td class="p-2 space-x-2">
                    @can('driver.update')
                    <button wire:click="openModalEdit({{ $driver->id }})"
                        class="text-blue-600 hover:underline">Edit</button>
                    @endcan
                    @can('driver.delete')
                    <button wire:click="delete({{ $driver->id }})" class="text-red-600 hover:underline">Hapus</button>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Modal Form --}}
    @if ($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4">{{ $isEdit ? 'Edit' : 'Tambah' }} Driver</h2>

            <div class="space-y-4">
                <div>
                    <label>Nama</label>
                    <input type="text" wire:model.defer="nama" class="w-full border rounded px-3 py-2">
                    @error('nama') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-2">
                <button wire:click="$set('showModal', false)"
                    class="px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">Batal</button>
                <button wire:click="save"
                    class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">Simpan</button>
            </div>
        </div>
    </div>
    @endif
</div>