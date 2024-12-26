<div>
    <div class="flex justify-between py-2 mb-3 gap-28">
        <h1 class="text-2xl w-2/3 font-bold text-primary-900 ">Pengaturan Persetujuan
            {{ Str::title($tipe) }} {{ $jenis !== 'kdo' ? Str::title($jenis) : Str::upper($jenis) }}
            @if (auth()->user()->unitKerja)
                {{ auth()->user()->unitKerja->parent ? auth()->user()->unitKerja->parent->nama : auth()->user()->unitKerja->nama }}
            @endif
        </h1>
        <div class="">
            @if (collect($roles)->count() > 1)
                <button type="submit" wire:click="saveApprovalConfiguration"
                    class="text-success-900 bg-success-100 hover:bg-success-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                    Simpan Konfigurasi
                </button>
            @endif

            <a href="/option-approval"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
        </div>
    </div>
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
                                <div class="flex space-x-3 items-center">
                                    @if (collect($roles)->count() > 1)
                                        <button type="button"
                                            class="text-secondary-500 text-sm rotate-90 hover:text-secondary-700">
                                            <i class="fa-solid fa-arrow-right-arrow-left fa-fade"></i>
                                        </button>
                                    @endif
                                    <div class="font-semibold">
                                        {{ $loop->iteration }}.
                                    </div>
                                    <div class="hidden" id="id-role{{ $role['id'] }}">
                                        {{ $role['id'] }}
                                    </div>
                                    <div class="font-semibold">
                                        {{ $role['name'] }}
                                    </div>

                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="flex items-center  space-x-2">
                                        {{-- <label for="rolePeople{{ $index }}" class="text-gray-700 text-sm">Jumlah
                                                Orang</label> --}}
                                        <input type="number" wire:model="roles.{{ $index }}.limit"
                                            data-tooltip-target="rolePeople{{ $index }}"
                                            data-tooltip-placement="top"
                                            wire:change="rolePeople({{ $index }}, $event.target.value)"
                                            class="w-16  px-2 py-1 border rounded-md focus:border-0 focus:ring focus:ring-blue-300"
                                            min="1" placeholder="1">
                                        <div id="rolePeople{{ $index }}" role="tooltip"
                                            class="absolute z-10 normal-case invisible inline-block px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                            Maksimal Orang Yang Dibutuhkan
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                    </div>
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
                    <label for="selectedRole" class="block text-gray-700 font-medium mb-2">Tambah Jabatan</label>
                    <div class="flex">
                        <select wire:model.live="selectedRole" id="selectedRole"
                            class="flex-1 px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-blue-300">
                            <option value="" selected>Pilih Jabatan</option>
                            @foreach ($rolesAvailable as $role)
                                <option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
                            @endforeach

                        </select>
                        @if ($selectedRole)
                            <button type="button" wire:click="addRole"
                                class="ml-2 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                Tambah
                            </button>
                        @endif
                    </div>
                    @error('selectedRole')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Tombol Simpan -->

            </form>
            @push('scripts')
                <script type="module">
                    // document.addEventListener('livewire:load', function() {
                    let sortable = new Sortable(document.getElementById('sortable-roles'), {
                        animation: 150,
                        onEnd: function(evt) {
                            // Ambil data urutan baru berdasarkan elemen
                            const newOrder = Array.from(evt.to.children).map(function(item) {
                                const idElement = item.querySelector(
                                    '[id^="id-role"]'
                                ); // Cari elemen dengan ID yang dimulai dengan "id-role"
                                const limitInput = item.querySelector(
                                    'input[type="number"]'); // Cari input limit

                                return idElement ? {
                                        id: idElement.textContent.trim(), // ID role
                                        limit: limitInput ? parseInt(limitInput.value) || 1 :
                                        1, // Ambil nilai limit atau default 1
                                    } :
                                    null; // Abaikan jika ID tidak ditemukan
                            }).filter(Boolean); // Hapus elemen null dari array

                            // console.log(newOrder); // Debugging untuk melihat data urutan baru

                            // Kirim data ke Livewire untuk memperbarui urutan
                            @this.call('updateRolesOrder', newOrder);
                        },
                    });
                    // });
                </script>
            @endpush

        </x-card>
        <x-card title="Tambahan">
            <div class="flex flex-col gap-6">
                <div class="text-gray-700">
                    <label for="approvalOrder" class="block font-medium mb-2">
                        {{ $approveAfter }}
                    </label>
                    <select wire:model.live="approvalOrder" id="approvalOrder" @disabled(count($roles) < 2)
                        class="w-full px-4 py-2 border rounded-md focus:outline-none  focus:ring focus:ring-blue-300">
                        <option value="" selected>Pilih urutan persetujuan...</option>
                        @foreach (range(1, count($roles)) as $index)
                            <option value="{{ $index }}">Setelah Persetujuan ke-{{ $index }}
                            </option>
                        @endforeach
                    </select>
                    @error('approvalOrder')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                @if ($approvalOrder)
                    <div class="text-gray-700 ">
                        <label for="cancelApprovalOrder" class="block font-medium mb-2">
                            Tentukan setelah persetujuan keberapa penulis dapat membatalkan {{ $tipe }}
                        </label>
                        <select wire:model.live="cancelApprovalOrder" id="cancelApprovalOrder"
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-red-300">
                            <option value="" selected>Pilih urutan persetujuan...</option>
                            @foreach (range($approvalOrder + 1, count($roles)) as $index)
                                <option value="{{ $index }}">Setelah Persetujuan ke-{{ $index }}
                                </option>
                            @endforeach
                        </select>
                        @error('cancelApprovalOrder')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
                {{-- 
                    <div class="text-gray-700">
                        <label for="finalizerRole" class="block font-medium mb-2">
                            Pilih jabatan yang menyelesaikan permintaan
                        </label>
                        <select wire:model.live="finalizerRole" id="finalizerRole" @disabled(count($roles) < 2)
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-green-300">
                            <option value="" selected>Pilih Jabatan</option>
                            @foreach ($rolesAvailable as $role)
                                @if (!collect($roles)->contains('id', $role->id))
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endif
                            @endforeach

                        </select>
                        @error('finalizerRole')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div> --}}
            </div>
        </x-card>


    </div>

    @push('scripts')
        <script type="module">
            document.addEventListener('success', function(e) {
                feedback('Berhasil!', e.detail[0],
                    'success')

            })
        </script>
    @endpush
</div>
