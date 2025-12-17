<div>
    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
            {{ session('error') }}
        </div>
    @endif

    @if (session()->has('info'))
        <div class="mb-4 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded">
            {{ session('info') }}
        </div>
    @endif

    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900 uppercase">KONFIRMASI ADENDUM RAB</h1>
        <div>
            <a class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"
                href="{{ route('rab.show', ['rab' => $rab->id]) }}">
                <i class="fas fa-arrow-left mr-1"></i>Kembali
            </a>
        </div>
    </div>

    @if($adendum->is_approved)
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <p class="text-green-800">
                <i class="fa-solid fa-check-circle mr-2"></i>
                Adendum ini sudah dikonfirmasi pada {{ $adendum->approved_at->format('d F Y H:i') }}
            </p>
        </div>
    @endif

    <!-- Informasi RAB -->
    <x-card title="Informasi RAB" class="mb-3">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Jenis Pekerjaan</label>
                <p class="mt-1 text-gray-900 font-semibold">{{ $rab->jenis_pekerjaan }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Lokasi</label>
                <p class="mt-1 text-gray-900 font-semibold">{{ $rab->lokasi }}</p>
            </div>
        </div>
    </x-card>

    <!-- Permintaan Adendum -->
    <x-card title="Permintaan Adendum dari Kasatpel" class="mb-3">
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Perubahan</label>
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <p class="text-gray-900">{{ $adendum->keterangan }}</p>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Dibuat oleh</label>
                <p class="text-gray-900 font-semibold">{{ $adendum->user->name }}</p>
        </div>
            <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Permintaan</label>
                <p class="text-gray-900 font-semibold">{{ $adendum->created_at->format('d F Y H:i') }}</p>
            </div>
        </div>
    </x-card>

    <!-- Perubahan Material -->
    <x-card title="Perubahan Material" class="mb-3">
        @if($adendum->list->count() > 0)
            <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
                <thead>
                    <tr class="text-white uppercase">
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-[20%] rounded-l-lg">Nama Barang</th>
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold w-[30%]">Spesifikasi</th>
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Jumlah Lama</th>
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Jumlah Baru</th>
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Selisih</th>
                        <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($adendum->list as $item)
                        <tr class="{{ $item->action === 'delete' ? 'bg-red-50 hover:bg-red-100' : ($item->action === 'add' ? 'bg-green-50 hover:bg-green-100' : 'bg-yellow-50 hover:bg-yellow-100') }} hover:shadow-lg transition duration-200 rounded-2xl">
                            <td class="py-3 px-6 font-semibold">{{ $item->merkStok->barangStok->nama ?? '-' }}</td>
                            <td class="py-3 px-6 font-semibold">
                                {{ $item->merkStok->nama }} 
                                @if($item->merkStok->tipe) - {{ $item->merkStok->tipe }} @endif
                                @if($item->merkStok->ukuran) - {{ $item->merkStok->ukuran }} @endif
                            </td>
                            <td class="py-3 px-6 font-semibold text-center">
                                {{ $item->jumlah_lama > 0 ? number_format($item->jumlah_lama) : '-' }}
                            </td>
                            <td class="py-3 px-6 font-semibold text-center">
                                @if($item->action === 'delete')
                                    <span class="text-red-600 font-semibold">Dihapus</span>
                                @else
                                    {{ number_format($item->jumlah_baru) }}
                                @endif
                            </td>
                            <td class="py-3 px-6 font-semibold text-center">
                                @if($item->action === 'add')
                                    <span class="text-green-600 font-semibold">+{{ number_format($item->jumlah_baru) }}</span>
                                @elseif($item->action === 'delete')
                                    <span class="text-red-600 font-semibold">-{{ number_format($item->jumlah_lama) }}</span>
                                @else
                                    @php
                                        $selisih = $item->jumlah_baru - $item->jumlah_lama;
                                    @endphp
                                    <span class="{{ $selisih > 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                        {{ $selisih > 0 ? '+' : '' }}{{ number_format($selisih) }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-6 font-semibold text-center">
                                @if($item->action === 'add')
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Tambah</span>
                                @elseif($item->action === 'edit')
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Ubah</span>
                                @elseif($item->action === 'delete')
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Hapus</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-box-open text-4xl mb-4"></i>
                <p>Tidak ada perubahan material</p>
        </div>
        @endif
    </x-card>

    @if(!$adendum->is_approved)
        <x-card title="Konfirmasi">
            <div class="flex justify-end gap-4">
                <a href="{{ route('rab.show', ['rab' => $rab->id]) }}"
                    class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                    Batal
                </a>
                <button onclick="document.getElementById('rejectModal').classList.remove('hidden')"
                    class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200">
                    <i class="fa-solid fa-times mr-2"></i>Tolak
                </button>
                <button wire:click="approveAdendum" wire:loading.attr="disabled"
                    class="px-6 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 transition duration-200">
                    <span wire:loading.remove wire:target="approveAdendum">
                        <i class="fa-solid fa-check mr-2"></i>Setujui & Terapkan
                    </span>
                    <span wire:loading wire:target="approveAdendum">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Memproses...
                    </span>
                </button>
            </div>
        </x-card>

        <!-- Modal Reject -->
        <div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Adendum</h3>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Penolakan</label>
                        <textarea wire:model="keteranganReject" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-primary-500 focus:border-primary-500"
                            placeholder="Jelaskan alasan penolakan..."></textarea>
                        @error('keteranganReject') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end gap-2">
                        <button onclick="document.getElementById('rejectModal').classList.add('hidden')"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>
                        <button wire:click="rejectAdendum"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            Tolak
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="mt-6">
            <a href="{{ route('rab.show', ['rab' => $rab->id]) }}"
                class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                Kembali ke RAB
            </a>
        </div>
    @endif
</div>
