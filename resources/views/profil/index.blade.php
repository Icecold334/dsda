<x-body>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900">Data Pengguna</h1>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-3">
        <div>
            <!-- Profil Saya -->
            <div>
                <x-card title="Profil Saya" class="mb-3">
                    <div class="space-y-2">
                        <div class="flex justify-between border-b py-2">
                            <span class="text-sm font-medium text-gray-700 w-1/3">User ID</span>
                            <span class="text-sm text-gray-500 w-2/3">{{ $user->id }}</span>
                        </div>
                        <div class="flex justify-between border-b py-2">
                            <span class="text-sm font-medium text-gray-700 w-1/3">Nama</span>
                            <span class="text-sm text-gray-500 w-2/3">{{ $user->name }}</span>
                        </div>
                        <div class="flex justify-between border-b py-2">
                            <span class="text-sm font-medium text-gray-700 w-1/3">NIP</span>
                            <span class="text-sm text-gray-500 w-2/3">{{ $user->nip ?? '--  ' }}</span>
                        </div>
                        <div class="flex justify-between border-b py-2">
                            <span class="text-sm font-medium text-gray-700 w-1/3">Lokasi Gudang</span>
                            <span
                                class="text-sm text-gray-500 w-2/3">{{ $user->lokasiStok->nama ?? 'Tidak Ditemukan' }}</span>
                        </div>
                        <div class="flex justify-between border-b py-2">
                            <span class="text-sm font-medium text-gray-700 w-1/3">Unit Kerja</span>
                            <span
                                class="text-sm text-gray-500 w-2/3">{{ $user->unitKerja->nama ?? 'Tidak Ditemukan' }}</span>
                        </div>
                        <div class="flex justify-between border-b py-2">
                            <span class="text-sm font-medium text-gray-700 w-1/3">Jabatan</span>
                            <span
                                class="text-sm text-gray-500 w-2/3">{{ $user->formatted_roles ?? 'Tidak Ditemukan' }}</span>
                        </div>
                        {{-- <div class="flex justify-between border-b py-2">
                            <span class="text-sm font-medium text-gray-700 w-1/3">Perusahaan/Organisasi</span>
                            <span
                                class="text-sm text-gray-500 w-2/3">{{ $user->perusahaan ?? 'Tidak Ditemukan' }}</span>
                        </div>
                        <div class="flex justify-between border-b py-2">
                            <span class="text-sm font-medium text-gray-700 w-1/3">Alamat</span>
                            <span class="text-sm text-gray-500 w-2/3">{{ $user->alamat ?? 'Tidak Ditemukan' }}</span>
                        </div>
                        <div class="flex justify-between border-b py-2">
                            <span class="text-sm font-medium text-gray-700 w-1/3">Provinsi</span>
                            <span class="text-sm text-gray-500 w-2/3">{{ $user->provinsi ?? 'Tidak Ditemukan' }}</span>
                        </div>
                        <div class="flex justify-between border-b py-2">
                            <span class="text-sm font-medium text-gray-700 w-1/3">Kabupaten/Kota</span>
                            <span class="text-sm text-gray-500 w-2/3">{{ $user->kota ?? 'Tidak Ditemukan' }}</span>
                        </div> --}}
                        <!-- No. WhatsApp -->
                        <div class="flex justify-between items-center border-b pb-2">
                            <span class="text-sm font-medium text-gray-700 w-1/3">No WhatsApp</span>
                            <div class="flex items-center w-2/3">
                                <span class="text-sm text-gray-500 w-3/3">{{ $user->no_wa ?? 'Tidak tersedia' }}</span>
                                <a href="profil/phone/{{ $user->id }}"
                                    class="text-primary-950 px-3 py-2 rounded-md border hover:bg-slate-300 transition duration-200 ml-2"
                                    data-tooltip-target="tooltip-user-{{ $user->id }}">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>
                                <div id="tooltip-user-{{ $user->id }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                    Ganti Email
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex justify-between items-center border-b pb-2">
                            <span class="text-sm font-medium text-gray-700 w-1/3">Email</span>
                            <div class="flex items-center w-2/3">
                                <span class="text-sm text-gray-500 w-3/3">{{ $user->email }}</span>
                                <a href="/profil/email/{{ $user->id }}"
                                    class="text-primary-950 px-3 py-2 rounded-md border hover:bg-slate-300 transition duration-200 ml-2"
                                    data-tooltip-target="tooltip-user-{{ $user->id }}">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>
                                <div id="tooltip-user-{{ $user->id }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                    Ganti Email
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="flex justify-between items-center border-b pb-2">
                            <span class="text-sm font-medium text-gray-700 w-1/3">Password</span>
                            <div class="flex items-center w-2/3">
                                <span class="text-sm text-gray-500 w-3/3">********</span>
                                <a href="profil/password/{{ $user->id }}"
                                    class="text-primary-950 px-3 py-2 rounded-md border hover:bg-slate-300 transition duration-200 ml-2"
                                    data-tooltip-target="tooltip-user-{{ $user->id }}">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>
                                <div id="tooltip-user-{{ $user->id }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                    Ganti Password
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex mt-4">
                            <a href="/profil/profile"
                                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                                Edit Profil
                            </a>
                        </div>
                    </div>
                </x-card>
            </div>
            <!-- Login dan Keamanan -->
            {{-- <div>
                <x-card title="Login dan Keamanan" class="mb-3">
                    <div class="space-y-4">
                        <!-- User ID -->
                        <div class="flex justify-between items-center border-b pb-2">
                            <span class="text-sm font-medium text-gray-700 w-1/3">User ID</span>
                            <span class="text-sm text-gray-500 w-2/3">{{ $user->id }}</span>
                        </div>

                        <!-- No. WhatsApp -->
                        <div class="flex justify-between items-center border-b pb-2">
                            <span class="text-sm font-medium text-gray-700 w-1/3">No WhatsApp</span>
                            <div class="flex items-center w-2/3">
                                <span class="text-sm text-gray-500 w-3/3">{{ $user->no_wa ?? 'Tidak tersedia' }}</span>
                                <a href="profil/phone/{{ $user->id }}"
                                    class="text-primary-950 px-3 py-2 rounded-md border hover:bg-slate-300 transition duration-200 ml-2"
                                    data-tooltip-target="tooltip-user-{{ $user->id }}">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>
                                <div id="tooltip-user-{{ $user->id }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                    Ganti Email
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex justify-between items-center border-b pb-2">
                            <span class="text-sm font-medium text-gray-700 w-1/3">Email</span>
                            <div class="flex items-center w-2/3">
                                <span class="text-sm text-gray-500 w-3/3">{{ $user->email }}</span>
                                <a href="/profil/email/{{ $user->id }}"
                                    class="text-primary-950 px-3 py-2 rounded-md border hover:bg-slate-300 transition duration-200 ml-2"
                                    data-tooltip-target="tooltip-user-{{ $user->id }}">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>
                                <div id="tooltip-user-{{ $user->id }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                    Ganti Email
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="flex justify-between items-center border-b pb-2">
                            <span class="text-sm font-medium text-gray-700 w-1/3">Password</span>
                            <div class="flex items-center w-2/3">
                                <span class="text-sm text-gray-500 w-3/3">********</span>
                                <a href="profil/password/{{ $user->id }}"
                                    class="text-primary-950 px-3 py-2 rounded-md border hover:bg-slate-300 transition duration-200 ml-2"
                                    data-tooltip-target="tooltip-user-{{ $user->id }}">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>
                                <div id="tooltip-user-{{ $user->id }}" role="tooltip"
                                    class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                    Ganti Password
                                    <div class="tooltip-arrow" data-popper-arrow></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-card>
            </div> --}}
        </div>

        @if (canViewAdditionalUsers($user))
            <div>
                <!-- Pengguna Tambahan -->
                {{-- <x-card title="Pengguna Tambahan" class="mb-3">
                    <div class="overflow-y-auto max-h-[45.5rem]">
                        <div class="flex justify-between mt-4 mb-6">
                            <a href="/profil/user"
                                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                                Buat Pengguna Tambahan</a>

                            <form action="{{ route('profil.index') }}" method="GET">
                                <div class="flex space-x-2 items-center">
                                    <!-- Input pencarian -->
                                    <input type="text" name="search" placeholder="Cari pengguna..."
                                        class="px-4 py-2 border rounded-lg w-full" value="{{ request('search') }}">

                                    <!-- Tombol Cari -->
                                    <button type="submit"
                                        class="bg-white text-blue-500 h-10 border border-blue-500 rounded-lg px-4 py-2 flex items-center hover:bg-blue-500 hover:text-white transition-colors">
                                        Cari
                                    </button>

                                    <!-- Tombol Reset -->
                                    @if (request()->has('search') && request('search') != '')
                                        <a href="{{ route('profil.index') }}"
                                            class="bg-white text-blue-500 h-10 border border-blue-500 rounded-lg px-4 py-2 flex items-center hover:bg-blue-500 hover:text-white transition-colors">
                                            <i class="fa-solid fa-rotate-right"></i>
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                        @forelse ($Users as $user)
                            <div class="border rounded-lg shadow-md p-4 mb-4 bg-white">
                                <!-- Header Section -->
                                <div class="flex justify-between items-center mb-4">
                                    <p class="text-base font-medium text-gray-700">
                                        <strong>Nama:</strong> <span>{{ $user->name }}</span>
                                    </p>
                                    <div class="flex space-x-2">
                                        <a href="profil/user/{{ $user->id }}""
                                            class=" text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300 "
                                            data-tooltip-target="tooltip-edit-{{ $user->id }}">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <div id="tooltip-edit-{{ $user->id }}" role="tooltip"
                                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                            Edit Pengguna ini
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Details Section -->
                                <div class="text-sm text-gray-500 space-y-2">
                                    <p><strong>NIP:</strong> {{ $user->nip ?? '-' }}</p>
                                    <p><strong>Email:</strong> {{ $user->email ?? '-' }}</p>
                                    <p><strong>Jabatan:</strong> {{ $user->formatted_roles ?? '-' }}</p>
                                    <p><strong>Username:</strong> {{ $user->username ?? '-' }}</p>
                                    <p><strong>Unit Kerja:</strong> {{ $user->unitKerja->nama ?? '-' }} </p>
                                    <p><strong>Lokasi Gudang:</strong> {{ $user->lokasiStok->nama ?? '-' }} </p> --}}
                {{-- <p><strong>Keterangan:</strong> {{ $user->keterangan ?? '-' }}</p> --}}
                {{-- </div>
                            </div>
                        @empty
                            <div class="text-gray-500 text-sm">
                                <p>Belum ada pengguna tambahan yang terdaftar.</p>
                            </div>
                        @endforelse
                    </div>
                </x-card>
            </div> --}}
                <livewire:additional-user />
        @endif
    </div>
</x-body>
