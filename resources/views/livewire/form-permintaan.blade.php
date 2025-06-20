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
                                <option value="0">Tanpa {{ $Rkb }}</option>
                                <option value="1">Menggunakan {{ $Rkb }}</option>

                            </select>
                            @error('withRab')
                            <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                            @enderror


                        </td>
                    </tr>
                    <tr class="{{ $withRab && !$isSeribu ?'':'hidden' }}">
                        <td class="w-1/3">
                            <label for="rab_id" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Pilih Jenis Pekerjaan *</label>
                        </td>
                        <td>
                            <livewire:searchable-select wire:model.live="rab_id" :options="$rabs"
                                label="jenis_pekerjaan" />

                        </td>
                    </tr>
                    <tr class="{{ $withRab && !$isSeribu ?'hidden':'' }}">
                        <td class="">
                            <label for="jenis" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Saluran *</label>
                        </td>
                        <td>
                            <select wire:model.live="withSaluran" @disabled($listCount)
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">-- Tidak pada saluran --</option>
                                {{-- <option value="mikro">-- Tidak pada saluran --</option> --}}
                                <option value="tersier">Saluran Drainase Tersier</option>
                                <option value="sekunder">Saluran Drainase Sekunder</option>
                                <option value="primer">Saluran Drainase Primer</option>

                            </select>
                        </td>
                    </tr>
                    {{-- @dump(!(!$withRab && !$isSeribu) || is_null($withSaluran)) --}}
                    <tr class="{{ !(!$withRab  && !$isSeribu) || is_null($withSaluran) ? 'hidden':'' }}">
                        <td>
                            <label for="jenis" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Pilih Saluran *</label>
                        </td>
                        <td>
                            <select wire:model.live="saluran_id" @disabled($listCount)
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                                <option value="">-- Pilih Saluran --</option>
                                @foreach ($saluranSelected as $item)
                                <option value="{{ $item['idPhb'] ?? $item['idAliran'] ?? $item['idPrimer'] }}">
                                    {{$item['namaPhb'] ?? $item['namaSungai'] }}
                                </option>
                                @endforeach

                            </select>
                        </td>
                    </tr>
                    @if ($rab_id && !$isSeribu)
                    <tr class="">
                        <td class="w-1/3">
                            <label for="rab_id" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Program *</label>
                        </td>
                        <td>
                            <input type="text" disabled
                                class="bg-gray-50 border  border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Jenis Pekerjaan" value="{{ $rab->program->program }}">

                        </td>
                    </tr>
                    <tr class="">
                        <td class="w-1/3">
                            <label for="rab_id" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Kegiatan *</label>
                        </td>
                        <td>
                            <input type="text" disabled
                                class="bg-gray-50 border  border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Jenis Pekerjaan" value="{{ $rab->kegiatan->kegiatan }}">

                        </td>
                    </tr>
                    <tr class="">
                        <td class="w-1/3">
                            <label for="rab_id" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Sub Kegiatan *</label>
                        </td>
                        <td>
                            <input type="text" disabled
                                class="bg-gray-50 border  border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Jenis Pekerjaan" value="{{ $rab->subKegiatan->sub_kegiatan }}">

                        </td>
                    </tr>
                    <tr class="">
                        <td class="w-1/3">
                            <label for="rab_id" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Aktivitas Sub Kegiatan *</label>
                        </td>
                        <td>
                            <input type="text" disabled
                                class="bg-gray-50 border  border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Jenis Pekerjaan" value="{{ $rab->aktivitasSubKegiatan->aktivitas }}">

                        </td>
                    </tr>
                    <tr class="">

                        <td class="font-semibold"><label for="lokasi">Lokasi Kegiatan</label></td>
                        <td>
                            <select disabled
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">

                                <option>
                                    {{ $rab->kelurahan->kecamatan->kecamatan }}
                                </option>
                            </select>

                        </td>
                    </tr>
                    <tr class="">
                        <td></td>
                        <td>
                            <select disabled
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">

                                <option>
                                    {{ $rab->kelurahan->nama }}
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr class="">
                        <td></td>
                        <td>
                            <textarea rows="2" disabled
                                placeholder="Detail lokasi tambahan (misal: Jl. ABC No. 123, dekat Lapangan)"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ $rab->lokasi }}</textarea>

                        </td>
                    </tr>
                    @endif
                    <tr class="{{ !$withRab?'':'hidden' }}">
                        <td class="w-1/3">
                            <label for="namaKegiatan" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Jenis Pekerjaan *</label>
                        </td>
                        <td>
                            <input type="text" wire:model.live="namaKegiatan"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Jenis Pekerjaan">
                        </td>
                    </tr>
                    <tr class="">
                        <td class="w-1/3">
                            <label for="nodin" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Nomor SPB *</label>
                        </td>
                        <td>
                            <input type="text" wire:model.live="nodin"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                placeholder="Nomor SPB">
                        </td>
                    </tr>
                    <tr>
                        <td class="w-1/3">
                            <label for="gudang_id" class="block mb-2  font-semibold text-gray-900 dark:text-white">
                                Pilih Lokasi Gudang *</label>
                        </td>
                        <td>
                            <livewire:searchable-select wire:model.live="gudang_id" :options="$gudangs" label='nama' />

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
                                Pekerjaan
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
                            <livewire:searchable-select wire:model.live="kecamatan_id" :options="$kecamatans"
                                label='kecamatan' />

                        </td>
                    </tr>
                    <tr class="{{ !$withRab ? '' : 'hidden' }}">
                        <td></td>
                        <td>
                            <select wire:model.live="kelurahan_id" @disabled(!$kecamatan_id)
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                                <option value="">Pilih Kelurahan</option>
                                @foreach ($kelurahans as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->nama }}
                                </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr class="{{ !$withRab ? '' : 'hidden' }}">
                        <td></td>
                        <td>
                            <textarea wire:model.live="lokasiMaterial" rows="2"
                                placeholder="Detail lokasi tambahan (misal: Jl. ABC No. 123, dekat Lapangan)"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"></textarea>

                        </td>
                    </tr>
                    @endif
                </table>
            </x-card>
        </div>
        <div>
            @if ($tipe=='material' )
            <x-card title="Lampiran Permintaan">
                <livewire:upload-surat-kontrak>
            </x-card>
            @else

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
            @endif
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