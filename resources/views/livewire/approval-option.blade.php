<x-card title="Pengaturan Urutan Persetujuan">
    <div class="p-6 bg-white shadow rounded-lg">
        <form wire:submit.prevent="saveApprovalConfiguration">
            <!-- Daftar Peran -->
            <div class="mb-6">
                <label for="roles" class="block text-gray-700 font-medium mb-2">Urutan Peran</label>
                <ul id="sortable-roles" class="bg-gray-100 rounded-md p-4">
                    @foreach ($roles as $index => $role)
                        <li class="flex items-center justify-between bg-white border rounded-md p-3 mb-2 cursor-move">
                            <span>{{ $role }}</span>
                            <button type="button" wire:click="removeRole({{ $index }})"
                                class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </li>
                    @endforeach
                </ul>
                <small class="text-gray-500">Urutkan peran sesuai alur persetujuan.</small>
            </div>

            <div class="mb-6">
                <label for="selectedRole" class="block text-gray-700 font-medium mb-2">Tambah Peran</label>
                <div class="flex">
                    <select wire:model="selectedRole" id="selectedRole"
                        class="flex-1 px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300">
                        <option value="" selected>Pilih Jabatan</option>
                        @foreach ($rolesAvailable as $role)
                            <option value="{{ $role }}">{{ $role }}</option>
                        @endforeach
                    </select>
                    <button type="button" wire:click="addRole"
                        class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Tambah
                    </button>
                </div>
                @error('selectedRole')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Tombol Simpan -->
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                    Simpan Konfigurasi
                </button>
            </div>
        </form>
    </div>
    @push('scripts')
        <script type="module">
            // document.addEventListener('livewire:load', function() {
            let sortable = new Sortable(document.getElementById('sortable-roles'), {
                animation: 150,
                onEnd: function(evt) {
                    // Kirim urutan baru ke Livewire
                    const newOrder = Array.from(evt.to.children).map((item) => item.textContent.trim());
                    @this.call('updateRolesOrder', newOrder);
                },
            });
            // });
        </script>
    @endpush
</x-card>
