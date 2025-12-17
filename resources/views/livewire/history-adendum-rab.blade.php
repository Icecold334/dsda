<div>
    <div class="flex justify-between py-2 mb-3">
        <h1 class="text-2xl font-bold text-primary-900 uppercase">HISTORY ADENDUM RAB</h1>
        <div>
            <a class="cursor-pointer text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"
                href="{{ route('rab.show', ['rab' => $rab->id]) }}">
                <i class="fas fa-arrow-left mr-1"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Info RAB -->
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

        <!-- History List -->
    <x-card title="History Adendum">
            @if($histories->count() > 0)
                <table class="w-full border-3 border-separate border-spacing-y-4 h-5">
                    <thead>
                        <tr class="text-white uppercase">
                            <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg">Tanggal</th>
                            <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Aksi</th>
                            <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Oleh</th>
                            <th class="py-3 px-6 bg-primary-950 text-center font-semibold">Keterangan</th>
                            <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-r-lg">Aksi</th>
                            </tr>
                        </thead>
                    <tbody>
                            @foreach($histories as $history)
                            <tr class="bg-gray-50 hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                                    <td class="py-3 px-6 font-semibold text-center">
                                        <div class="text-sm text-gray-900">
                                            {{ $history->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            {{ $history->created_at->format('H:i:s') }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 font-semibold text-center">
                                        @php
                                            $actionColors = [
                                                'create' => 'bg-blue-100 text-blue-800',
                                                'approve' => 'bg-green-100 text-green-800',
                                                'reject' => 'bg-red-100 text-red-800',
                                            ];
                                            $actionLabels = [
                                                'create' => 'Dibuat',
                                                'approve' => 'Disetujui',
                                                'reject' => 'Ditolak',
                                            ];
                                            $color = $actionColors[$history->action] ?? 'bg-gray-100 text-gray-800';
                                            $label = $actionLabels[$history->action] ?? ucfirst($history->action);
                                        @endphp
                                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $color }}">
                                            {{ $label }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 font-semibold">
                                        <div class="text-sm text-gray-900">
                                            {{ $history->user->name ?? 'N/A' }}
                                        </div>
                                        @if($history->action === 'create' && $history->adendumRab)
                                            <div class="text-xs text-gray-500">
                                                Kasatpel: {{ $history->adendumRab->user->name ?? 'N/A' }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 font-semibold">
                                        <div class="text-sm text-gray-900 max-w-md truncate">
                                            {{ $history->keterangan ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <button wire:click="showDetail({{ $history->id }})"
                                            class="text-primary-600 hover:text-primary-800 px-3 py-1 rounded border border-primary-600 transition duration-200">
                                            <i class="fa-solid fa-eye mr-1"></i>Detail
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
            @else
                <div class="text-center py-16">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fa-solid fa-inbox text-3xl text-gray-400"></i>
                    </div>
                    <h4 class="text-lg font-medium text-gray-900 mb-2">
                        Belum ada history adendum
                    </h4>
                    <p class="text-gray-500">
                        History adendum akan muncul setelah adendum dibuat, disetujui, atau ditolak.
                    </p>
                </div>
            @endif
    </x-card>

    <!-- Modal Detail History -->
    @if($showModal && $selectedHistory)
        <div class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4"
            wire:click="closeModal">
            <div class="relative w-full max-w-4xl bg-white rounded-xl shadow-2xl max-h-[90vh] flex flex-col"
                wire:click.stop>
                <!-- Header Modal -->
                <div class="flex justify-between items-center p-6 border-b border-gray-200 bg-gray-50 rounded-t-xl">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">
                            Detail History Adendum
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">
                            {{ $selectedHistory->created_at->format('d F Y H:i:s') }}
                        </p>
                    </div>
                    <button wire:click="closeModal"
                        class="text-gray-400 hover:text-gray-600 hover:bg-gray-200 p-2 rounded-lg transition duration-200">
                        <i class="fa-solid fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Content Modal -->
                <div class="flex-1 overflow-auto p-6">
                    <div class="space-y-6">
                        <!-- Info Umum -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Informasi Umum</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Aksi</p>
                                    @php
                                        $actionColors = [
                                            'create' => 'bg-blue-100 text-blue-800',
                                            'approve' => 'bg-green-100 text-green-800',
                                            'reject' => 'bg-red-100 text-red-800',
                                        ];
                                        $actionLabels = [
                                            'create' => 'Dibuat',
                                            'approve' => 'Disetujui',
                                            'reject' => 'Ditolak',
                                        ];
                                        $color = $actionColors[$selectedHistory->action] ?? 'bg-gray-100 text-gray-800';
                                        $label = $actionLabels[$selectedHistory->action] ?? ucfirst($selectedHistory->action);
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-sm font-medium {{ $color }}">
                                        {{ $label }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Oleh</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $selectedHistory->user->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Keterangan</p>
                                    <p class="text-sm text-gray-900">{{ $selectedHistory->keterangan ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Tanggal</p>
                                    <p class="text-sm text-gray-900">{{ $selectedHistory->created_at->format('d F Y H:i:s') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Perubahan -->
                        @if($selectedHistory->action === 'create' || $selectedHistory->action === 'approve')
                            @php
                                $data = $selectedHistory->new_data;
                                $changes = $data['changes'] ?? [];
                            @endphp
                            @if(count($changes) > 0)
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-3">Detail Perubahan Material</h4>
                                    <div class="overflow-x-auto">
                                        <table class="w-full border border-gray-200">
                                            <thead class="bg-primary-50">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-sm font-semibold text-primary-900">Aksi</th>
                                                    <th class="px-4 py-2 text-left text-sm font-semibold text-primary-900">Material</th>
                                                    <th class="px-4 py-2 text-right text-sm font-semibold text-primary-900">Jumlah Lama</th>
                                                    <th class="px-4 py-2 text-right text-sm font-semibold text-primary-900">Jumlah Baru</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200">
                                                @foreach($changes as $change)
                                                    <tr>
                                                        <td class="px-4 py-2">
                                                            @php
                                                                $actionLabels = [
                                                                    'add' => 'Tambah',
                                                                    'edit' => 'Ubah',
                                                                    'delete' => 'Hapus',
                                                                ];
                                                                $actionColors = [
                                                                    'add' => 'bg-green-100 text-green-800',
                                                                    'edit' => 'bg-yellow-100 text-yellow-800',
                                                                    'delete' => 'bg-red-100 text-red-800',
                                                                ];
                                                                $label = $actionLabels[$change['action']] ?? ucfirst($change['action']);
                                                                $color = $actionColors[$change['action']] ?? 'bg-gray-100 text-gray-800';
                                                            @endphp
                                                            <span class="px-2 py-1 rounded text-xs font-medium {{ $color }}">
                                                                {{ $label }}
                                                            </span>
                                                        </td>
                                                        <td class="px-4 py-2 text-sm text-gray-900">
                                                            {{ $change['merk_nama'] ?? 'N/A' }}
                                                        </td>
                                                        <td class="px-4 py-2 text-right text-sm text-gray-900">
                                                            {{ number_format($change['jumlah_lama'] ?? 0, 0, ',', '.') }}
                                                        </td>
                                                        <td class="px-4 py-2 text-right text-sm text-gray-900">
                                                            {{ number_format($change['jumlah_baru'] ?? 0, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        @endif

                        @if($selectedHistory->action === 'reject')
                            @php
                                $oldData = $selectedHistory->old_data;
                                $changes = $oldData['changes'] ?? [];
                            @endphp
                            @if(count($changes) > 0)
                                <div>
                                    <h4 class="font-semibold text-gray-900 mb-3">Perubahan yang Ditolak</h4>
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                        <p class="text-sm text-red-800 mb-3">
                                            <strong>Alasan Penolakan:</strong> {{ $selectedHistory->keterangan }}
                                        </p>
                                        <div class="overflow-x-auto">
                                            <table class="w-full border border-red-200">
                                                <thead class="bg-red-50">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left text-sm font-semibold text-red-900">Aksi</th>
                                                        <th class="px-4 py-2 text-left text-sm font-semibold text-red-900">Material</th>
                                                        <th class="px-4 py-2 text-right text-sm font-semibold text-red-900">Jumlah Lama</th>
                                                        <th class="px-4 py-2 text-right text-sm font-semibold text-red-900">Jumlah Baru</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-red-200">
                                                    @foreach($changes as $change)
                                                        <tr>
                                                            <td class="px-4 py-2">
                                                                @php
                                                                    $actionLabels = [
                                                                        'add' => 'Tambah',
                                                                        'edit' => 'Ubah',
                                                                        'delete' => 'Hapus',
                                                                    ];
                                                                    $label = $actionLabels[$change['action']] ?? ucfirst($change['action']);
                                                                @endphp
                                                                <span class="px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                                                    {{ $label }}
                                                                </span>
                                                            </td>
                                                            <td class="px-4 py-2 text-sm text-gray-900">
                                                                {{ $change['merk_nama'] ?? 'N/A' }}
                                                            </td>
                                                            <td class="px-4 py-2 text-right text-sm text-gray-900">
                                                                {{ number_format($change['jumlah_lama'] ?? 0, 0, ',', '.') }}
                                                            </td>
                                                            <td class="px-4 py-2 text-right text-sm text-gray-900">
                                                                {{ number_format($change['jumlah_baru'] ?? 0, 0, ',', '.') }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="flex justify-end items-center p-6 border-t border-gray-200 bg-gray-50 rounded-b-xl">
                    <button wire:click="closeModal"
                        class="px-6 py-2.5 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 font-medium">
                        <i class="fa-solid fa-times mr-2"></i>Tutup
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
