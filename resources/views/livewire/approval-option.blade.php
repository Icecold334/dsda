<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900 ">Pengaturan Persetujuan
            {{ Str::title($tipe) }} {{ $jenis !== 'kdo' ? Str::title($jenis) : Str::upper($jenis) }}
            @if (auth()->user()->unitKerja)
                {{ auth()->user()->unitKerja->parent ? auth()->user()->unitKerja->parent->nama : auth()->user()->unitKerja->nama }}
            @endif
        </h1>
        <div>
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
        @if ($tipe != 'pengiriman')
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
                                    <div class="flex items-center space-x-4">
                                        <!-- Radio Button untuk Menyetujui -->
                                        <label class="flex items-center space-x-1">
                                            <input type="radio" wire:model.live="rolesApproval.{{ $role['id'] }}"
                                                value="1">
                                            <span>Perlu Persetujuan</span>
                                        </label>

                                        <!-- Radio Button untuk Mengetahui -->
                                        <label class="flex items-center space-x-1">
                                            <input type="radio" wire:model.live="rolesApproval.{{ $role['id'] }}"
                                                value="0">
                                            <span>Mengetahui</span>
                                        </label>

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
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
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
                                // Ambil urutan baru berdasarkan elemen ID
                                const newOrder = Array.from(evt.to.children).map(function(item) {
                                    const idElement = item.querySelector(
                                        '[id^="id-role"]'); // Cari elemen dengan ID dimulai dengan "id-role"
                                    return idElement ? idElement.textContent.trim() :
                                        null; // Ambil nilai ID jika elemen ditemukan
                                }).filter(Boolean); // Hapus null dari array

                                // console.log(newOrder); // Debugging untuk melihat urutan baru
                                @this.call('updateRolesOrder', newOrder); // Panggil Livewire untuk memperbarui urutan
                            },
                        });
                        // });
                    </script>
                @endpush
            </x-card>
            <x-card title="Tambahan">
                {{-- @if ($tipe == 'permintaan') --}}
                @if (true)
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
                                    Tentukan setelah persetujuan keberapa user dapat membatalkan
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
                @else
                @endif
            </x-card>
            @if ($tipe == 'permintaan' && $jenis == 'umum')
                <x-card title="Pengaturan Penanggung Jawab">
                    {{-- @if (true) --}}
                    <div
                        class="w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 rounded-t-lg bg-primary-100 dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800"
                            id="defaultTab" data-tabs-toggle="#defaultTabContent" role="tablist">
                            <li class="me-2">
                                <button id="konsumsi-tab" data-tabs-target="#konsumsi" type="button" role="tab"
                                    aria-controls="konsumsi" aria-selected="true"
                                    class="inline-block p-4 hover:text-white hover:bg-primary-300 transition duration-200 dark:hover:bg-gray-700 dark:hover:text-gray-300">Konsumsi</button>
                            </li>
                            <li class="me-2">
                                <button id="kdo-tab" data-tabs-target="#kdo" type="button" role="tab"
                                    aria-controls="kdo" aria-selected="false"
                                    class="inline-block p-4 hover:text-white hover:bg-primary-300 transition duration-200 dark:hover:bg-gray-700 dark:hover:text-gray-300">KDO</button>
                            </li>
                            <li class="me-2">
                                <button id="carwash-tab" data-tabs-target="#carwash" type="button" role="tab"
                                    aria-controls="carwash" aria-selected="false"
                                    class="inline-block p-4 hover:text-white hover:bg-primary-300 transition duration-200 dark:hover:bg-gray-700 dark:hover:text-gray-300">Voucher
                                    Carwash</button>
                            </li>
                            <li class="me-2">
                                <button id="atk-tab" data-tabs-target="#atk" type="button" role="tab"
                                    aria-controls="atk" aria-selected="false"
                                    class="inline-block p-4 hover:text-white hover:bg-primary-300 transition duration-200 dark:hover:bg-gray-700 dark:hover:text-gray-300">Alat
                                    Tulis Kantor (ATK) Dll</button>
                            </li>
                        </ul>
                        <div id="defaultTabContent">
                            <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="konsumsi"
                                role="tabpanel">
                                <div class="flex flex-col gap-6">
                                    <div class="text-gray-700">
                                        <label for="approvalOrder" class="block font-medium mb-2">
                                            Pilih User untuk Penanggung Jawab Konsumsi.
                                        </label>
                                        {{-- @dd($roles) --}}
                                        <select wire:model.live="finalizerRole" id="finalizerRole"
                                            {{-- @disabled(count($user) < 2) --}}
                                            class="w-full px-4 py-2 border rounded-md focus:outline-none  focus:ring focus:ring-blue-300">
                                            <option value="" selected>Pilih Penanggung Jawab Konsumsi</option>
                                            @foreach ($user as $item)
                                                <option value="{{ $item['id'] }}">{{ $item['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('finalizerRole')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="kdo"
                                role="tabpanel">
                                <div class="flex flex-col gap-6">
                                    <div class="text-gray-700">
                                        <label for="approvalOrder" class="block font-medium mb-2">
                                            Pilih User untuk Penanggung Jawab KDO.
                                        </label>
                                        {{-- @dd($roles) --}}
                                        <select wire:model.live="finalizerRole" id="finalizerRole"
                                            {{-- @disabled(count($user) < 2) --}}
                                            class="w-full px-4 py-2 border rounded-md focus:outline-none  focus:ring focus:ring-blue-300">
                                            <option value="" selected>Pilih Penanggung Jawab KDO</option>
                                            @foreach ($user as $item)
                                                <option value="{{ $item['id'] }}">{{ $item['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('finalizerRole')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="carwash"
                                role="tabpanel">
                                <div class="flex flex-col gap-6">
                                    <div class="text-gray-700">
                                        <label for="approvalOrder" class="block font-medium mb-2">
                                            Pilih User untuk Penanggung Jawab Voucher Carwash.
                                        </label>
                                        {{-- @dd($roles) --}}
                                        <select wire:model.live="finalizerRole" id="finalizerRole"
                                            {{-- @disabled(count($user) < 2) --}}
                                            class="w-full px-4 py-2 border rounded-md focus:outline-none  focus:ring focus:ring-blue-300">
                                            <option value="" selected>Pilih Penanggung Jawab Voucher Carwash
                                            </option>
                                            @foreach ($user as $item)
                                                <option value="{{ $item['id'] }}">{{ $item['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('finalizerRole')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="atk"
                                role="tabpanel">
                                <div class="flex flex-col gap-6">
                                    <div class="text-gray-700">
                                        <label for="approvalOrder" class="block font-medium mb-2">
                                            Pilih User untuk Penanggung Jawab ATK dan Lain lain.
                                        </label>
                                        {{-- @dd($roles) --}}
                                        <select wire:model.live="finalizerRole" id="finalizerRole"
                                            {{-- @disabled(count($user) < 2) --}}
                                            class="w-full px-4 py-2 border rounded-md focus:outline-none  focus:ring focus:ring-blue-300">
                                            <option value="" selected>Pilih Penanggung Jawab ATK dan Lain lain
                                            </option>
                                            @foreach ($user as $item)
                                                <option value="{{ $item['id'] }}">{{ $item['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('finalizerRole')
                                            <span class="text-red-500 text-sm">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </x-card>
            @endif
        @endif
        @if ($tipe !== 'permintaan')

            <x-card title="Pengaturan Pemeriksa Barang">
                {{-- @if (true) --}}
                @if ($tipe !== 'pengiriman')
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
                                    Tentukan setelah persetujuan keberapa user dapat membatalkan
                                </label>
                                <select wire:model.live="cancelApprovalOrder" id="cancelApprovalOrder"
                                    class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-red-300">
                                    <option value="" selected>Pilih urutan persetujuan...</option>
                                    @foreach (range($approvalOrder + 1, count($roles)) as $index)
                                        <option value="{{ $index }}">Setelah Persetujuan
                                            ke-{{ $index }}
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
                @else
                    <div class="flex flex-col gap-6">
                        <div class="text-gray-700">
                            <label for="approvalOrder" class="block font-medium mb-2">
                                Pilih pemeriksa barang penentu jumlah barang yang layak masuk stok.
                            </label>
                            {{-- @dd($roles) --}}
                            <select wire:model.live="finalizerRole" id="finalizerRole" @disabled(count($roles) < 2)
                                class="w-full px-4 py-2 border rounded-md focus:outline-none  focus:ring focus:ring-blue-300">
                                <option value="" selected>Pilih Pemeriksa barang</option>
                                @foreach ($roles as $item)
                                    <option value="{{ $item['id'] }}">{{ $item['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('finalizerRole')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endif
            </x-card>
        @endif


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
