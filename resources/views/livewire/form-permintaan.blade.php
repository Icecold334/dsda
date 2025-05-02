<div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <x-card title="data umum">
                <table class="w-full border-separate border-spacing-y-4">
                    @if ($kategori == 'material')
                    <tr>
                        <td class="w-1/3">
                            <label for="rab_id" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Jenis Permintaan *</label>
                        </td>
                        <td>
                            <select wire:model.live="withRab" @disabled($listCount> 0)
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                focus:ring-primary-500
                                focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600
                                dark:text-white
                                dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="0">Tanpa RAB</option>
                                <option value="1">Menggunakan RAB</option>

                            </select>
                            @error('withRab')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>
                    <tr class="{{ $withRab?'':'hidden' }}">
                        <td class="w-1/3">
                            <label for="rab_id" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Pilih RAB *</label>
                        </td>
                        <td>
                            <select wire:model.live="rab_id" @disabled($listCount> 0)
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                focus:ring-primary-500
                                focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600
                                dark:text-white
                                dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="">Pilih RAB</option>
                                @foreach ($rabs as $rab)
                                <option value="{{ $rab->id }}">{{ $rab->kegiatan->kegiatan }}</option>
                                @endforeach
                            </select>
                            @error('rab_id')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>
                    <tr class="{{ !$withRab?'':'hidden' }}">
                        <td class="w-1/3">
                            <label for="namaKegiatan" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Nama Kegiatan *</label>
                        </td>
                        <td>
                            <input type="text" wire:model.live="namaKegiatan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Nama Kegiatan">
                        </td>
                    </tr>
                    <tr class="{{ !$withRab?'':'hidden' }}">
                        <td class="w-1/3">
                            <label for="nodin" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Nomor Nota Dinas *</label>
                        </td>
                        <td>
                            <input type="text" wire:model.live="nodin"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Nomor Nota Dinas">
                        </td>
                    </tr>
                    <tr>
                        <td class="w-1/3">
                            <label for="gudang_id" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Pilih Lokasi Gudang *</label>
                        </td>
                        <td>
                            <select wire:model.live="gudang_id" @disabled($listCount> 0)
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                focus:ring-primary-500
                                focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600
                                dark:text-white
                                dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="">Pilih Gudang</option>
                                @foreach ($gudangs as $gudang)
                                <option value="{{ $gudang->id }}">{{ $gudang->nama }}</option>
                                @endforeach
                            </select>
                            @error('gudang_id')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td class="font-semibold">
                            @if ($tipe == 'permintaan' && $kategori_id == 4)
                            <label for="tanggal_permintaan" class="block mb-2 ">Tanggal dan Waktu Penyediaan
                                *</label>
                            @else
                            <label for="tanggal_permintaan" class="block mb-2 ">Tanggal
                                Permintaan
                                *</label>
                            @endif
                        </td>
                        <td>
                            @if ($tipe == 'permintaan' && $kategori_id == 4)
                            <input type="datetime-local" id="tanggal_permintaan" wire:model.live="tanggal_permintaan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            @error('tanggal_permintaan')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                            @else
                            <input type="date" id="tanggal_permintaan" wire:model.live="tanggal_permintaan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            @error('tanggal_permintaan')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                            @endif
                        </td>
                    </tr>
                    @if ($tipe == 'peminjaman')
                    <tr>
                        <td class="w-1/3">
                            <label for="tipePeminjaman" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Pilih Layanan *</label>
                        </td>
                        <td>
                            <select wire:model.live="tipePeminjaman" @disabled($listCount> 0 || true)
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700
                                dark:border-gray-600 dark:text-white dark:focus:ring-primary-500
                                dark:focus:border-primary-500">
                                <option value="">Pilih Layanan</option>
                                <option value="Ruangan">Peminjaman Ruangan</option>
                                <option value="KDO">Peminjaman KDO</option>
                                <option value="Peralatan Kantor">Peminjaman Peralatan Kantor</option>
                            </select>
                            @error('tipePeminjaman')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>
                    @endif
                    @if ($showKategori)
                    <tr>
                        <td class="w-1/3">
                            <label for="kategori_id" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Kategori *</label>
                        </td>
                        <td>
                            <select wire:model.live="kategori_id" @disabled($listCount> 0 || true)
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg
                                focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700
                                dark:border-gray-600 dark:text-white dark:focus:ring-primary-500
                                dark:focus:border-primary-500">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama }}
                                </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>
                    @endif
                    @if ($tipe == 'permintaan' && $kategori_id == 4)

                    <tr>
                        <td class="w-1/3">
                            <label for="ruang" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Lokasi/Ruang *</label>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <select wire:model.live="RuangId"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">

                                    <option value="">Pilih Lokasi/Ruang</option>

                                    @foreach ($ruangs as $ruang)
                                    <option value="{{ $ruang->id }}">{{ $ruang->nama }}</option>
                                    @endforeach

                                    <option value="0">Lokasi/Ruang Lain</option> <!-- Opsi Tambahan -->
                                </select>

                                @error('RuangId')
                                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </div>
                            <!-- Jika "Lokasi/Ruang Lain" dipilih, tampilkan input tambahan -->
                            @if ($RuangId === '0')
                            <div class="mt-2">
                                <input type="text" wire:model.live="LokasiLain"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                    placeholder="Nama Lokasi">

                                <input type="text" wire:model.live="AlamatLokasi"
                                    class="mt-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                    placeholder="Alamat Lokasi">

                                <input type="text" wire:model.live="KontakPerson"
                                    class="mt-2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                                    placeholder="Kontak Person">
                            </div>
                            @endif

                        </td>
                    </tr>
                    <tr>
                        <td class="w-1/3">
                            <label for="jumlah_peserta" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Jumlah Peserta *</label>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <input type="number" wire:model.live="peserta" min="1"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                    placeholder="Jumlah">
                                <span
                                    class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm">
                                    Peserta
                                </span>
                            </div>
                        </td>
                    </tr>
                    @endif
                    @if ($tipe == 'permintaan' && $kategori_id == 5)
                    <tr>
                        <td class="w-1/3">
                            <label for="ruang" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                KDO *</label>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <select wire:model.live="KDOId"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                    <option value="">Pilih KDO</option>
                                    @foreach ($kdos as $kdo)
                                    <option value="{{ $kdo->id }}">
                                        {{ $kdo->merk->nama . ' ' . $kdo->nama . ' - ' . $kdo->noseri }}
                                    </option>
                                    @endforeach
                                </select>

                                @error('KDOId')
                                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-semibold">
                            <label for="tanggal_masuk" class="block mb-2 ">Tanggal
                                Masuk
                                *</label>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <input type="date" id="tanggal_masuk" wire:model.live="tanggal_masuk"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                @error('tanggal_masuk')
                                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="font-semibold">
                            <label for="tanggal_keluar" class="block mb-2 ">Tanggal
                                Keluar
                                *</label>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <input type="date" id="tanggal_keluar" wire:model.live="tanggal_keluar"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                @error('tanggal_masuk')
                                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                                @enderror
                            </div>
                        </td>
                    </tr>
                    @endif
                    <tr class="{{ !$withRab ? '' : 'hidden' }}">
                        <td class="font-semibold"><label for="keterangan">Keterangan</label></td>
                        <td>
                            <div class="flex mb-3">
                                <textarea id="keterangan" wire:model.live="keterangan" @disabled($listCount> 0)
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="Masukkan Keterangan" rows="4"></textarea>
                            </div>
                        </td>
                    </tr>
                    @if ($kategori == 'material')
                    <tr class="{{ !$withRab ? '' : 'hidden' }}">
                        <td class="font-semibold"><label for="lokasi">Lokasi Kegiatan</label></td>
                        <td>
                            <div class="flex mb-3">
                                <textarea id="lokasi" wire:model.live="lokasiMaterial" @disabled($listCount> 0)
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="Masukkan Lokasi" rows="4"></textarea>
                            </div>
                        </td>
                    </tr>
                    @endif
                </table>
            </x-card>
        </div>
        <div {{ $tipe=='material' ? 'hidden' : '' }}>
            <x-card title="unit kerja dan bagian">
                <table class="w-full border-separate border-spacing-y-4">
                    <tr>
                        <td class="w-1/3">
                            <label for="unit_id" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Unit Kerja *</label>
                        </td>
                        <td>
                            <select wire:model.live="unit_id" @disabled(Auth::id() !==1)
                                class="bg-gray-50 border border-gray-300 cursor-not-allowed  text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="">Pilih Unit Kerja</option>
                                @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->nama }}
                                </option>
                                @endforeach
                            </select>
                            @error('unit_id')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <td class="w-1/3">
                            <label for="sub_unit_id" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Sub Unit *</label>
                        </td>
                        <td>
                            <select wire:model.live="sub_unit_id" @disabled(!$unit_id && $listCount> 0)
                                class="bg-gray-50 border border-gray-300 {{ !$unit_id ? 'cursor-not-allowed ' : '' }}
                                text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block
                                w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="">Pilih Sub Unit Kerja</option>
                                @if ($unit_id)
                                @foreach ($subUnits as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->nama }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                            @error('sub_unit_id')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror
                        </td>
                    </tr>
                </table>
            </x-card>
        </div>
    </div>
    @if ($tipe == 'peminjaman')
    <livewire:list-peminjaman-form :peminjaman="$permintaan" :last="$last" :tipe="$tipePeminjaman">
        @else
        @if ($tipe === 'material')
        <livewire:list-permintaan-material :permintaan="$permintaan" :last="$last" />
        @else
        <livewire:list-permintaan-form :permintaan="$permintaan" :last="$last" :kategori_id="$kategori_id">
            @endif
            @endif
</div>