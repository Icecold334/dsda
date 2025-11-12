<div>
    <table class="w-full border-0 border-separate border-spacing-y-4">

        @if ($tipe == 'stok')
        <!-- Form for updating Stok -->
        <tr>
            <td>
                <label for="nama_stok">Nama Stok</label>
            </td>
            <td>
                <input type="text" id="stok" wire:model.live.debounce.500ms="stok"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    placeholder="Nama Stok" required />
                @error('stok')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        <tr>
            <td>
                <label for="tipe">Tipe</label>
            </td>
            <td>
                <input type="text" id="tipe_stok" wire:model.live.debounce.500ms="tipe_stok"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    placeholder="Tipe Stok" required />
                @error('tipe_stok')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        <tr>
            <td>
                <label for="ukuran">Ukuran</label>
            </td>
            <td>
                <input type="text" id="ukuran" wire:model.live.debounce.500ms="ukuran"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    placeholder="Ukuran Stok" />
                @error('ukuran')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        @else
        <!-- Form for updating Barang -->
        <tr>
            <td>
                <label for="nama_barang">Nama Barang</label>
            </td>
            <td>
                <input type="text" id="barang" wire:model.live.debounce.500ms="barang"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    placeholder="Nama Barang" required />
                @error('barang')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        <tr>
            <td>
                <label for="kode_barang">Kode Barang</label>
            </td>
            <td>
                <input type="text" id="kode_barang" wire:model.live.debounce.500ms="kode_barang"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    placeholder="Kode Barang" required />
                @error('kode_barang')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        <tr>
            <td>
                <label for="jenis">Jenis Barang</label>
            </td>
            <td>
                <select id="jenis" wire:model.live.debounce.500ms="jenis"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih Jenis Barang</option>
                    @foreach ($jenis_stok as $jenis)
                    <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                    @endforeach
                </select>
                @error('jenis')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        <tr>
            <td>
                <label for="jenis">Satuan</label>
            </td>
            <td>
                <select id="jenis" wire:model.live.debounce.500ms="satuan"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih Satuan</option>
                    @foreach ($satuans as $satuan)
                    <option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                    @endforeach
                </select>
                @error('satuan')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        <tr>
            <td>
                <label for="jenis">Minimal</label>
            </td>
            <td>
                <input type="number" wire:model.live.debounce.500ms="minimal"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    placeholder="Minimal stok" required />
            </td>
        </tr>
        @if ($this->jenis == 3)
        <tr>
            <td>
                <label for="kategori">Kategori Stok</label>
            </td>
            <td>
                <select id="kategori" wire:model.live.debounce.500ms="kategori"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Pilih Kategori Stok</option>
                    @foreach ($kategori_stok as $kategori)
                    <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                    @endforeach
                </select>
                @error('kategori')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        @endif
        <tr>
            <td>
                <label for="description">Deskripsi</label>
            </td>
            <td>
                <textarea id="description" wire:model.live.debounce.500ms="description"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                    placeholder="Deskripsi Barang" rows="3"></textarea>
                @error('description')
                <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                @enderror
            </td>
        </tr>
        @endif

    </table>
    <div class="flex justify-end mt-4">
        {{-- @if ($id)
        <button type="button"
            onclick="confirmRemove('Apakah Anda yakin ingin menghapus Barang/Stok ini?', () => @this.call('remove'))"
            class="text-danger-900 bg-danger-100 hover:bg-danger-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Hapus</button>
        @endif --}}
        <button type="button" wire:click="save"
            class="text-blue-600 bg-blue-100 hover:bg-blue-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 mb-2 transition duration-200">Simpan</button>
    </div>
</div>