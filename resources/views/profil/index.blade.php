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
                            <span class="text-sm font-medium text-gray-700 w-1/3">Nama</span>
                            <span class="text-sm text-gray-500 w-2/3">{{ $user->name }}</span>
                        </div>
                        <div class="flex justify-between border-b py-2">
                            <span class="text-sm font-medium text-gray-700 w-1/3">Perusahaan/Organisasi</span>
                            <span class="text-sm text-gray-500 w-2/3">[Nama Perusahaan]</span>
                        </div>
                        <div class="flex justify-between border-b py-2">
                            <span class="text-sm font-medium text-gray-700 w-1/3">Alamat</span>
                            <span class="text-sm text-gray-500 w-2/3">[Alamat Lengkap]</span>
                        </div>
                        <div class="flex justify-between border-b py-2">
                            <span class="text-sm font-medium text-gray-700 w-1/3">Provinsi</span>
                            <span class="text-sm text-gray-500 w-2/3">[Nama Provinsi]</span>
                        </div>
                        <div class="flex justify-between border-b py-2">
                            <span class="text-sm font-medium text-gray-700 w-1/3">Kabupaten/Kota</span>
                            <span class="text-sm text-gray-500 w-2/3">[Nama Kabupaten/Kota]</span>
                        </div>
                        <div class="flex mt-4">
                            <a href="{{ route('profil.edit', ['profil' => $user->id]) }}"
                                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                                Edit Profil
                            </a>
                        </div>
                    </div>
                </x-card>
            </div>
            <!-- Login dan Keamanan -->
            <div>
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
                                <span class="text-sm text-gray-500 w-3/3">{{ $user->no_wa ?? 'Tidak tersedia'}}</span>
                                <a href="{{ route('person.index', ['user' => $user->id]) }}"
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
                                <a href="{{ route('person.index', ['user' => $user->id]) }}"
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
                                <a href="{{ route('person.index', ['user' => $user->id]) }}"
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
            </div>
        </div>
        <div>
            <!-- Pengguna Tambahan -->
            <div>
                <x-card title="Pengguna Tambahan" class="mb-3">
                    <div class="flex mt-4 mb-6">
                        <button type="button" wire:click="#"
                            class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200">
                            + Buat Pengguna Tambahan
                        </button>
                    </div>
                    @forelse ($Users as $user)
                        <div class="border rounded-lg shadow-md p-4 mb-4 bg-white">
                            <!-- Header Section -->
                            <div class="flex justify-between items-center mb-4">
                                <p class="text-base font-medium text-gray-700">
                                    <strong>Nama:</strong> <span>{{ $user->name }}</span>
                                </p>
                                <div class="flex space-x-2">
                                    <a href="{{ route('person.index', ['person' => $user->id]) }}"
                                        class=" text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300 "
                                        data-tooltip-target="tooltip-delete-{{ $user->id }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                    <div id="tooltip-delete-{{ $user->id }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        Hapus Pengguna ini
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                    <a href="{{ route('person.index', ['person' => $user->id]) }}"
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
                                <p><strong>Email:</strong> {{ $user->email }}</p>
                                <p><strong>Jabatan:</strong> {{ $user->formatted_role ?? 'Tidak tersedia' }}</p>
                                <p><strong>Username:</strong> {{ $user->username ?? 'Tidak tersedia' }}</p>
                                <p><strong>Password:</strong> '*************'</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-gray-500 text-sm">
                            <p>Belum ada pengguna tambahan yang terdaftar.</p>
                        </div>
                    @endforelse
                </x-card>
            </div>
        </div>
    </div>
</x-body>