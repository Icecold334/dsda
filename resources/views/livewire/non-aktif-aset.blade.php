<div>
    @if ($isEditing)
        <!-- Form Edit Data Non-Aktif -->
        <form wire:submit.prevent="save">
            <x-card title="Edit Data Non-Aktif" class="mb-3">
                {{-- <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Status Aset</label>
                    <select wire:model.live="status"
                        class="w-full border rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-primary-300">
                        <option value="1">Aktif</option>
                        <option value="0">Non-Aktif</option>
                    </select>
                </div> --}}

                @if ($status == 0)
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Tanggal Non-Aktif</label>
                        <input type="date" wire:model.live="tglnonaktif"
                            class="w-full border rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-primary-300">
                        @error('tglnonaktif')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Sebab Non-Aktif</label>
                        <select wire:model.live="alasannonaktif"
                            class="w-full border rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-primary-300">
                            <option value="">Pilih Sebab</option>
                            <option value="Dijual">Dijual</option>
                            <option value="Dibuang">Dibuang</option>
                            <option value="Hibah">Hibah</option>
                        </select>
                        @error('alasannonaktif')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                        <textarea wire:model.live="ketnonaktif" rows="3"
                            class="w-full border rounded-lg px-3 py-2 mt-1 focus:outline-none focus:ring focus:ring-primary-300"></textarea>
                        @error('ketnonaktif')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

                <div class="flex justify-end space-x-3">
                    <button type="button" wire:click="toggleEdit"
                        class="bg-primary-100 text-primary-900 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-primary-600 text-white hover:bg-primary-700 font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                        Simpan
                    </button>
                </div>
            </x-card>
        </form>
    @else
        <!-- Detail Non-Aktif -->
        <x-card title="Status" class="mb-3">
            <div class="flex flex-col space-y-4">
                <div class="flex">
                    <div class="text-sm font-bold min-w-[150px] text-gray-700">Status Aset</div>
                    <div class="text-sm font-bold text-gray-900">{{ $status == 0 ? 'Non-Aktif' : 'Aktif' }}</div>
                </div>
                <div class="flex">
                    <div class="text-sm font-semibold min-w-[150px] text-gray-700">Tanggal Non-Aktif</div>
                    <div class="text-sm text-gray-900">
                        {{ $tglnonaktif }}
                    </div>
                </div>
                <div class="flex">
                    <div class="text-sm font-semibold min-w-[150px] text-gray-700">Sebab Non-Aktif</div>
                    <div class="text-sm text-gray-900">{{ $alasannonaktif ?? '---' }}</div>
                </div>
                <div class="flex">
                    <div class="text-sm font-semibold min-w-[150px] text-gray-700">Keterangan</div>
                    <div class="text-sm text-gray-900">{{ $ketnonaktif ?? '---' }}</div>
                </div>
            </div>

            <div class="flex items-center space-x-2 mt-4">
                @if ($status == 0)
                    <button onclick="confirmActivate()"
                        class="text-primary-900 bg-white border-2 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-3 py-2 transition duration-200">
                        <i class="fa-solid fa-box-open"></i>
                    </button>
                    @if (session('message'))
                        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif

                @endif

                @push('scripts')
                    <script>
                        function confirmActivate() {
                            Swal.fire({
                                title: 'Aktifkan Aset',
                                text: 'Apakah Anda yakin ingin mengaktifkan kembali aset ini?',
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Aktifkan',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Panggil fungsi Livewire untuk update status
                                    @this.call('activateAsset');
                                }
                            });
                        }
                    </script>
                @endpush

                <button wire:click="toggleEdit"
                    class="text-primary-900 bg-white border-2 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-3 py-2 transition duration-200">
                    <i class="fa-solid fa-pen"></i>
                </button>
            </div>
        </x-card>
    @endif
</div>
