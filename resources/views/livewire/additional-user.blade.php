<div>
    <x-card title="Pengguna Tambahan" class="mb-3">
        <div class="overflow-y-auto max-h-[45.5rem]">
            <!-- Form Pencarian -->
            <div class="flex justify-between mt-4 mb-6">
                {{-- <a href="/profil/user"
                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                    + Buat Pengguna Tambahan
                </a> --}}
                <div class="flex space-x-2 items-center">
                    <!-- Input Pencarian -->
                    <input type="text" wire:model.live.debounce.500ms="search" placeholder="Cari pengguna..."
                        class="px-4 py-2 border rounded-lg w-[25rem]">

                    {{-- @if ($search) --}}
                    <!-- Tombol Reset -->
                    <a href="/profil/user"
                        class="bg-white text-blue-500 w-10 h-10 border border-blue-500 rounded-lg  flex items-center justify-center hover:bg-blue-500 hover:text-white transition-colors">
                        <i class="fa-solid fa-user-plus"></i>
                    </a>
                    {{-- @endif --}}
                </div>
            </div>

            <!-- Daftar Pengguna -->
            @forelse ($users->sortBy('email_verified_at') as $user)
            <div class="border rounded-lg shadow-md p-4 mb-4 bg-white">
                <div class="flex justify-between items-center mb-4">
                    <p class="text-base font-medium text-gray-700">
                        <strong>Nama:</strong> <span>{{ $user->name }}</span>
                        @if (!$user->email_verified_at)
                        <span
                            class="bg-warning-500 text-black text-xs font-medium mx-2 px-2.5 py-0.5 rounded-full ">Belum
                            Terverifikasi</span>
                        @endif
                    </p>
                    <div class="flex space-x-2">
                        <a href="profil/user/{{ $user->id }}"
                            class="text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300">
                            <i class="fa-solid fa-pencil"></i>
                        </a>
                    </div>
                </div>
                <div class="text-sm text-gray-500 space-y-2">
                    <p><strong>NIP:</strong> {{ $user->nip ?? '-' }}</p>
                    <p><strong>Email:</strong> {{ $user->email ?? '-' }}</p>
                    <p><strong>Jabatan:</strong> {{ $user->formatted_roles ?? '-' }}</p>
                    <p><strong>Username:</strong> {{ $user->username ?? '-' }}</p>
                    <p><strong>Unit Kerja:</strong> {{ $user->unitKerja->nama ?? '-' }}</p>
                    <p><strong>Lokasi Gudang:</strong> {{ $user->lokasiStok->nama ?? '-' }}</p>
                </div>
            </div>
            @empty
            <div class="text-gray-500 text-sm">
                <p>Belum ada pengguna tambahan yang terdaftar.</p>
            </div>
            @endforelse
        </div>
    </x-card>
</div>