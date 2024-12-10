<div>
    <table class="w-full border-0 border-separate border-spacing-y-4">

        @if ($tipe == 'profile')
            <tr>
                <td>

                    <label for="name">Nama</label>
                </td>
                <td>
                    <input type="text" id="name" wire:model.live="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Name" required />
                    @error('name')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
                <td rowspan="2" class="align-top text-right">
                    <label for="ttd" class="block font-semibold mb-2">Tanda Tangan</label>
                    <div class="flex flex-col items-end mt-2">
                        <!-- Foto Preview Container -->
                        <div x-data="{ open: false }" class="relative inline-block w-24 h-24 border rounded-lg">
                            <!-- Trigger untuk membuka modal -->
                            <div class="w-full h-full flex justify-center items-center bg-gray-200 rounded-lg cursor-pointer relative"
                                @click="open = true; document.body.classList.add('overflow-hidden')">
                                @if ($img)
                                    <button
                                        class="absolute -top-2 -right-3 z-30 bg-red-500 hover:bg-red-700 transition duration-200 text-white rounded-full p-1 leading-none w-5 h-5 text-xs flex items-center justify-center shadow"
                                        wire:click="removeImg"
                                        @click.stop="document.body.classList.remove('overflow-hidden'); open = false;">
                                        &times;
                                    </button>
                                @endif
                                <!-- Gambar preview -->
                                <img src="{{ is_string($img) ? asset('storage/usersTTD/' . $img) : ($img ? $img->temporaryUrl() : asset('img/default-pic.png')) }}"
                                    alt="Preview Image" class="w-full h-full object-cover object-center rounded-lg">

                            </div>
                            <!-- Modal untuk Preview Full -->
                            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-300"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="fixed inset-0 bg-black bg-opacity-75 flex flex-col items-center justify-center z-50"
                                @click="open = false; document.body.classList.remove('overflow-hidden')"
                                @keydown.escape.window="open = false; document.body.classList.remove('overflow-hidden')">

                                <!-- Gambar Full Preview -->
                                @if ($img)
                                    <img src="{{ is_string($img) ? asset('storage/usersTTD/' . $img) : ($img ? $img->temporaryUrl() : asset('img/default-pic.png')) }}"
                                        alt="Preview Image" class="max-w-[70rem] max-h-[70rem] mb-4" @click.stop="">

                                    <!-- Tombol Unduh -->
                                    <a href="{{ is_string($img) ? asset('storage/usersTTD/' . $img) : $img->temporaryUrl() }}"
                                        download="{{ is_string($img) ? pathinfo($img, PATHINFO_BASENAME) : $img->getClientOriginalName() }}"
                                        class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200 cursor-pointer">
                                        Unduh
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Tombol Unggah -->
                        <div class="mt-4">
                            <input type="file" wire:model.live="img" accept="image/*" class="hidden" id="imgUpload">
                            <label for="imgUpload"
                                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200 cursor-pointer">
                                + Unggah Foto
                            </label>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>

                    <label for="nip">NIP</label>
                </td>
                <td>
                    <input type="text" id="nip" wire:model.live="nip"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="NIP" required />
                    @error('nip')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>

            <!-- Select for LokasiStok -->
            {{-- <tr>
                <td>
                    <label for="lokasi_stok">Lokasi Stok</label>
                </td>
                <td>
                    @dump($lokasi_stok)
                    @dump($errors->first())
                    <select id="lokasi_stok" wire:model.live="lokasi_stok"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required>
                        <option value="">Pilih Lokasi Stok</option>
                        @foreach ($lokasistoks as $lokasi)
                            <option value="{{ $lokasi->id }}">
                                {{ $lokasi->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('lokasi_stok')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr> --}}

            {{-- <!-- Select for UnitKerja -->
            <tr>
                <td>
                    <label for="unit_kerja">Unit Kerja</label>
                </td>
                <td>
                    @dump($unit_kerja)
                    <select id="unit_kerja" wire:model.live="unit_kerja"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required>
                        <option value="">Pilih Unit Kerja</option>
                        @foreach ($unitkerjas as $unit)
                            @if ($unit->parent_id == null)
                                <!-- Parent Unit -->
                                <option value="{{ $unit->id }}">{{ $unit->nama }}</option>

                                <!-- Child Units -->
                                @foreach ($unitkerjas->where('parent_id', $unit->id) as $childUnit)
                                    <option value="{{ $childUnit->id }}">--- {{ $childUnit->nama }}</option>
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                    @error('unit_kerja')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr> --}}

            <tr>
                <td>

                    <label for="perusahaan">Perusahaan</label>
                </td>
                <td>
                    <input type="text" id="perusahaan" wire:model.live="perusahaan"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Perusahaan" required />
                    @error('perusahaan')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>

                    <label for="alamat">Alamat</label>
                </td>
                <td>
                    <textarea id="alamat" wire:model.live="alamat"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Masukkan Alamat" rows="2"></textarea>
                    @error('alamat')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>

                    <label for="provinsi">Provinsi</label>
                </td>
                <td>
                    <input type="text" id="provinsi" wire:model.live="provinsi"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Provinsi" required />
                    @error('provinsi')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>

                    <label for="kota">Kota</label>
                </td>
                <td>
                    <input type="text" id="kota" wire:model.live="kota"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Kota" required />
                    @error('kota')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
        @endif
        @if ($tipe == 'phone')
            <tr>
                <td>

                    <label for="no_wa">Silahkan Masukan Nomor WhatsApp Anda yang Baru</label>
                </td>
            </tr>
            <tr>
                <td>

                    <label for="no_wa">No. WhatsApp Lama</label>
                </td>
                <td>
                    <span class="text-gray-900 text-sm">{{ $no_wa ?? '----' }}</span>
                </td>
            </tr>
            <tr>
                <td>

                    <label for="new_wa">No. WhatsApp Baru</label>
                </td>
                <td>

                    <input type="text" id="new_wa" wire:model.live="new_wa"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Misal : 08123456789" required />
                    @error('new_wa')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
        @endif
        @if ($tipe == 'email')
            <tr>
                <td>

                    <label for="email">Silahkan Masukan Email Anda yang Baru</label>
                </td>
            </tr>
            <tr>
                <td>

                    <label for="email">Email Lama</label>
                </td>
                <td>
                    <span class="text-gray-900 text-sm">{{ $email }}</span>
                </td>
            </tr>
            <tr>
                <td>

                    <label for="new_email">Email Baru</label>
                </td>
                <td>

                    <input type="text" id="new_email" wire:model.live="new_email"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="" required />
                    @error('new_email')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
        @endif
        @if ($tipe == 'password')
            <tr>
                <td colspan="2">
                    <label for="pass">Silahkan Masukan Password Lama dan Password Baru Anda pada kolom yang
                        tersedia</label>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="pass">Password Lama</label>
                </td>
                <td>

                    <input type="password" id="old_password" wire:model.live="old_password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="" required />
                    @error('old_password')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>

                    <label for="password">Password Baru</label>
                </td>
                <td>

                    <input type="password" id="password" wire:model.live="password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="" required />
                    @error('password')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>

                    <label for="password_confirmation">Ulangi Password Baru</label>
                </td>
                <td>

                    <input type="password" id="password_confirmation " wire:model.live="password_confirmation"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="" required />
                    @error('password_confirmation ')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
        @endif
        @if ($tipe == 'user')
            <tr>
                <td>

                    <label for="name">Nama</label>
                </td>
                <td colspan="2">

                    <input type="text" id="name" wire:model.live="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Name" required />
                    @error('name')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>

            <!-- Select for LokasiStok -->
            <tr>
                <td>
                    <label for="lokasi_stok">Lokasi Stok</label>
                </td>
                <td colspan="2">
                    <select id="lokasi_stok" wire:model.live="lokasi_stok"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required>
                        <option value="">Pilih Lokasi Stok</option>
                        @foreach ($lokasistoks as $lokasi)
                            <option value="{{ $lokasi->id }}">
                                {{ $lokasi->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('lokasi_stok')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>

            <!-- Select for UnitKerja -->
            <tr>
                <td>
                    <label for="unit_kerja">Unit Kerja</label>
                </td>
                <td colspan="2">
                    <select id="unit_kerja" wire:model.live="unit_kerja"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required>
                        <option value="">Pilih Unit Kerja</option>
                        @foreach ($unitkerjas as $unit)
                            @if (Auth::user()->id == 1)
                                @if ($unit->parent_id === null)
                                    <!-- Parent Unit -->
                                    <option value="{{ $unit->id }}">{{ $unit->nama }}</option>

                                    <!-- Child Units -->
                                    @foreach ($unitkerjas->where('parent_id', $unit->id) as $childUnit)
                                        <option value="{{ $childUnit->id }}">--- {{ $childUnit->nama }}</option>
                                    @endforeach
                                @endif
                            @else
                                <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('unit_kerja')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>

            <tr>
                <td>

                    <label for="keterangan">Keterangan</label>
                </td>
                <td colspan="2">
                    <textarea id="keterangan" wire:model.live="keterangan"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Masukkan keterangan" rows="2"></textarea>
                    @error('keterangan')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror

                </td>
            </tr>
            <tr>
                <td>

                    <label for="username">Username</label>
                </td>
                <td colspan="2">
                    <input type="text" id="username" wire:model.live="username"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Username" required />
                    @error('username')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>

                    <label for="email">Email</label>
                </td>
                <td colspan="2">
                    <input type="email" id="email" wire:model.live="email"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Email" required />
                    @error('email')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>

                    <label for="password">Password</label>
                </td>
                <td colspan="2">

                    <input type="password" id="password" wire:model.live="password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Password" required />
                    @error('password')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>

                    <label for="password_confirmation">Ulangi Password</label>
                </td>
                <td colspan="2">

                    <input type="password" id="password_confirmation " wire:model.live="password_confirmation"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Ulangi Password" required />
                    @error('password_confirmation ')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>
                    <label for="roles">Jabatan</label>
                </td>
                <td>
                    <div>
                        @foreach ($roles as $role)
                            <div class="flex items-center mb-2">
                                <input type="checkbox" id="role_{{ $role->id }}" value="{{ $role->name }}"
                                    wire:model.live="selectedRoles"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                <label for="role_{{ $role->id }}"
                                    class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    {{-- {{ ucwords(str_replace('_', ' ', $role->name)) }} --}}
                                    {{ formatRole($role->name) }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('selectedRoles')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
        @endif

    </table>
    <div class="flex justify-end">
        @if ($tipe == 'user')
            @if ($id)
                <button type="button"
                    onclick="confirmRemove('Apakah Anda yakin ingin menghapus user ini?', () => @this.call('removeProfil'))"
                    {{-- wire:click="removeProfil" --}}
                    class="text-danger-900 bg-danger-100 hover:bg-danger-600 px-5 py-2.5 me-2 mb-2 hover:text-white rounded-md border transition duration-200"
                    data-tooltip-target="tooltip-delete-{{ $id }}"><i
                        class="fa-solid fa-trash"></i></button>
                <div id="tooltip-delete-{{ $id }}" role="tooltip"
                    class="absolute z-10 invisible inline-block px-5 py-2.5 me-2 mb-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Hapus Pengguna ini
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            @endif
        @endif
        <button type="button" wire:click="saveProfil"
            class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Simpan</button>

    </div>
</div>
