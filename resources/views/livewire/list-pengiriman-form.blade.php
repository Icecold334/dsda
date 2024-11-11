<div>
    <table class="w-full  border-3 border-separate border-spacing-y-4 ">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold ">NAMA BARANG</th>
                {{-- <th class="py-3 px-6 bg-primary-950 text-center font-semibold">KODE</th> --}}
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">LOKASI *</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">BAGIAN</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">POSISI</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-1/6">JUMLAH *</th>
                {{-- <th class="py-3 px-6 bg-primary-950 text-center font-semibold">BUKTI</th> --}}
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $index => $item)
                <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                    <!-- Merk Column -->
                    <td class="px-6 py-3 font-semibold"></td>
                    <td class="px-6 py-3 font-semibold">{{ $item['merk'] }}</td>

                    <!-- Lokasi Dropdown -->
                    <td class="px-6 py-3">
                        <select wire:change="updateLokasi({{ $index }}, $event.target.value)"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Pilih Lokasi</option>
                            @foreach ($lokasis as $lokasi)
                                <option value="{{ $lokasi->id }}" @if ($item['lokasi_id'] == $lokasi->id) selected @endif>
                                    {{ $lokasi->nama }}
                                </option>
                            @endforeach
                        </select>
                    </td>

                    <!-- Bagian Dropdown -->
                    <td class="px-6 py-3">
                        <select wire:change="updateBagian({{ $index }}, $event.target.value)"
                            class="bg-gray-50 border border-gray-300 {{ empty($item['lokasi_id']) ? 'cursor-not-allowed' : '' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            @disabled(empty($item['lokasi_id']))>
                            <option value="">Pilih Bagian</option>
                            @forelse ($item['bagians'] as $bagian)
                                <option value="{{ $bagian->id }}">
                                    {{ $bagian->nama }}
                                </option>
                            @empty
                                <option disabled selected>Tidak ada bagian tersedia</option>
                            @endforelse
                        </select>
                    </td>


                    <!-- Posisi Dropdown -->
                    <td class="px-6 py-3">
                        <select wire:change="updatePosisi({{ $index }}, $event.target.value)"
                            class="bg-gray-50 border border-gray-300 {{ empty($item['bagian_id']) ? 'cursor-not-allowed' : '' }} text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                            @disabled(empty($item['bagian_id']))>
                            <option value="">Pilih Posisi</option>
                            @forelse ($item['posisis'] as $posisi)
                                <option value="{{ $posisi->id }}">
                                    {{ $posisi->nama }}
                                </option>
                            @empty
                                <option disabled selected>Tidak ada posisi tersedia</option>
                            @endforelse
                        </select>
                    </td>


                    <!-- Jumlah Input -->
                    <td class="px-6 py-3">
                        <div class="flex items-center">
                            <input type="number" wire:model.live="list.{{ $index }}.jumlah"
                                wire:change="updateJumlah({{ $index }}, $event.target.value)"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-l-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                min="1" max="{{ $item['max_jumlah'] }}" placeholder="Jumlah">
                            <span
                                class="bg-gray-50 border border-gray-300 border-l-0 rounded-r-lg px-3 py-2.5 text-gray-900 text-sm">
                                {{ $item['merk_id'] ? optional(App\Models\MerkStok::find($item['merk_id'])->barangStok->satuanBesar)->nama : 'Satuan' }}
                            </span>
                        </div>
                        @if (isset($errorsList[$index]))
                            <p class="text-red-600 text-xs mt-1">{{ $errorsList[$index] }}</p>
                        @else
                            <p class="text-black text-xs mt-1">Jumlah maksimal : {{ $item['max_jumlah'] }}</p>
                        @endif
                    </td>

                    <!-- Remove Button -->
                    <td class="text-center py-3">
                        <button wire:click="removeFromList({{ $index }})"
                            class="text-danger-900 border-danger-600 text-xl border bg-danger-100 hover:bg-danger-600 hover:text-white font-medium rounded-lg px-3 py-1 transition duration-200">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>

        @push('scripts')
            <script>
                window.addEventListener('error', event => {
                    let timerInterval;
                    Swal.fire({
                        title: "Gagal!",
                        html: "Lengkapi Data!",
                        timer: 1500,
                        icon: 'error',
                        timerProgressBar: true,
                        showConfirmButton: false,
                        willClose: () => {
                            clearInterval(timerInterval);
                        }
                    }).then((result) => {});

                })
                window.addEventListener('merkExist', event => {
                    let timerInterval;
                    Swal.fire({
                        title: "Ops!",
                        html: "Barang sudah ada dalam daftar!",
                        timer: 1000,
                        icon: 'warning',
                        timerProgressBar: true,
                        showConfirmButton: false,
                        willClose: () => {
                            clearInterval(timerInterval);
                        }
                    }).then((result) => {});

                })
            </script>
        @endpush
    </table>

    @if ($vendor_id != null && count($list) > 0)
        <button wire:click='savePengiriman'
            class="text-primary-900 bg-primary-100 border border-primary-600 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Simpan</button>
    @endif


</div>
