<div>
    <x-card title="Tambah Jabatan Baru">
        <!-- Success Message -->
        @if (session()->has('success'))
            <div class="p-3 mb-4 text-sm text-green-800 bg-green-100 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="save" class="space-y-4">
            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Jabatan</label>
                <input type="text" id="name" wire:model.live="name"
                    class="block w-full mt-1 p-2 border rounded-lg focus:ring-primary-500 focus:border-primary-500 text-sm"
                    placeholder="Masukkan nama jabatan">
                @error('name')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <!-- Guard Name Field -->
            {{-- <div>
                <label for="guard_name" class="block text-sm font-medium text-gray-700">Guard Name</label>
                <input type="text" id="guard_name" wire:model="guard_name"
                    class="block w-full mt-1 p-2 border rounded-lg focus:ring-primary-500 focus:border-primary-500 text-sm"
                    placeholder="Masukkan guard name">
                @error('guard_name')
                    <span class="text-sm text-red-500">{{ $message }}</span>
                @enderror
            </div> --}}

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" 
                    class="px-4 py-2 text-white bg-primary-600 hover:bg-primary-700 font-medium rounded-lg text-sm transition">
                    Simpan
                </button>
            </div>
        </form>
    </x-card>
</div>
