<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <x-card title="Pengaturan Urutan Persetujuan">
        <form wire:submit.prevent="saveApprovalConfiguration">
            <!-- Daftar Peran -->
            <div class="mb-6">
                <label for="roles" class="block text-gray-700 font-medium mb-2">Urutan Jabatan</label>
                <ul id="sortable-roles" class="bg-gray-100 rounded-md p-4">
                    @forelse ($roles as $index => $role)
                        <li
                            class="flex items-center justify-between bg-white border rounded-md p-3 mb-2 {{ collect($roles)->count() > 1 ? 'cursor-move' : '' }}">
                            <div class="flex space-x-3">
                                @if (collect($roles)->count() > 1)
                                    <button type="button"
                                        class="text-secondary-500 rotate-90 hover:text-secondary-700">
                                        <i class="fa-solid fa-arrow-right-arrow-left fa-fade"></i>
                                    </button>
                                @endif
                                <div class="font-semibold">
                                    {{ $role }}
                                </div>
                            </div>
                            <div>

                                <button type="button" wire:click="removeRole({{ $index }})"
                                    class="text-red-500 hover:text-red-700">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </li>
                    @empty
                        <li class="flex items-center justify-center bg-white border rounded-md p-3 mb-2">
                            <div class="font-semibold">
                                Tambahkan Jabatan
                            </div>
                        </li>
                    @endforelse
                </ul>
                <div class="text-gray-500 text-sm font-semibold">{{ $pesan }}</div>
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
            @if (collect($roles)->count() > 1)
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                        Simpan Konfigurasi
                    </button>
                </div>
            @endif
        </form>
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
    <x-card title="tambahan">
        <livewire:searchable-dropdown />
    </x-card>
</div>
