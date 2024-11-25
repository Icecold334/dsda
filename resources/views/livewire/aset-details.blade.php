<div>
    <!-- Riwayat -->
    <x-card title="Riwayat" class="mb-3">
        <div class="max-h-64 overflow-y-auto">
            @if ($histories->isNotEmpty())
                @foreach ($histories as $history)
                    <div class="mb-3 border rounded-md p-4 bg-gray-50 shadow-sm">
                        <div class="flex">
                            <div class="font-semibold text-gray-800 w-1/3">Sejak Tanggal</div>
                            <div class="w-2/3">{{ date('d M Y', $history->tanggal) }}</div>
                        </div>
                        <div class="flex">
                            <div class="font-semibold text-gray-800 w-1/3">Penanggung Jawab</div>
                            <div class="w-2/3">{{ $history->person->nama }}</div>
                        </div>
                        <div class="flex">
                            <div class="font-semibold text-gray-800 w-1/3">Lokasi</div>
                            <div class="w-2/3">{{ $history->lokasi->nama }}</div>
                        </div>
                        <div class="flex">
                            <div class="font-semibold text-gray-800 w-1/3">Jumlah</div>
                            <div class="w-2/3">{{ $history->jumlah }} Unit</div>
                        </div>
                        <div class="flex">
                            <div class="font-semibold text-gray-800 w-1/3">Kondisi</div>
                            <div class="w-2/3">{{ $history->kondisi }}%</div>
                        </div>
                        <div class="flex">
                            <div class="font-semibold text-gray-800 w-1/3">Kelengkapan</div>
                            <div class="w-2/3">{{ $history->kelengkapan }}%</div>
                        </div>
                        <div class="flex">
                            <div class="font-semibold text-gray-800 w-1/3">Keterangan</div>
                            <div class="w-2/3">{{ $history->keterangan }}</div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-gray-600">Tidak ada riwayat.</p>
            @endif
        </div>
    </x-card>

    <!-- Agenda -->
    <x-card title="Agenda" class="mb-3">
        <div class="max-h-64 overflow-y-auto">
            @if ($agendas->isNotEmpty())
                @foreach ($agendas as $agenda)
                    <div class="mb-3 border rounded-md p-3 bg-gray-50">
                        <div class="text-sm font-semibold text-gray-500">
                            {{ $agenda->tipe === 'tanggal_tertentu' ? 'Tanggal Tertentu' : ucfirst($agenda->tipe) }}
                        </div>
                        <div class="text-lg font-bold text-primary-700">
                            @if ($agenda->tipe === 'bulanan')
                                Setiap Tanggal {{ date('j', $agenda->tanggal) }}
                            @elseif ($agenda->tipe === 'tanggal_tertentu')
                                {{ date('d M Y', $agenda->tanggal) }}
                            @else
                                {{ $agenda->keterangan }}
                            @endif
                        </div>
                        <div class="text-sm text-gray-600">{{ $agenda->keterangan }}</div>
                    </div>
                @endforeach
            @else
                <p class="text-gray-600">Tidak ada agenda.</p>
            @endif
        </div>
    </x-card>

    <!-- Keuangan -->
    <x-card title="Keuangan" class="mb-3">
        <div class="max-h-64 overflow-y-auto">
            @if ($keuangans->isNotEmpty())
                @foreach ($keuangans as $transaction)
                    <div class="flex items-center justify-between py-2 px-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <div
                                class="flex items-center justify-center w-8 h-8 rounded-full 
                                {{ $transaction->tipe === 'out' ? 'bg-red-100 text-red-600' : 'bg-green-100 text-green-600' }}">
                                {!! $transaction->tipe === 'out'
                                    ? '<i class="fa-solid fa-arrow-right-from-bracket"></i>'
                                    : '<i class="fa-solid fa-arrow-right-to-bracket"></i>' !!}
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-semibold text-gray-700">
                                    {{ date('d M Y', $transaction->tanggal) }}
                                </div>
                                <div class="text-sm text-gray-600">
                                    {{ $transaction->keterangan }}
                                </div>
                            </div>
                        </div>
                        <div class="text-primary-700 font-bold">
                            Rp {{ number_format($transaction->nominal, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-gray-600">Tidak ada data keuangan.</p>
            @endif
        </div>
    </x-card>

    <!-- Jurnal -->
    <x-card title="Jurnal" class="mb-3">
        <div class="max-h-64 overflow-y-auto">
            @if ($jurnals->isNotEmpty())
                @foreach ($jurnals as $jurnal)
                    <div class="mb-3 border rounded-md p-3 bg-gray-50">
                        <div><strong>Tanggal:</strong> {{ date('d M Y', $jurnal->tanggal) }}</div>
                        <div><strong>Keterangan:</strong> {{ $jurnal->keterangan }}</div>
                    </div>
                @endforeach
            @else
                <p class="text-gray-600">Tidak ada jurnal.</p>
            @endif
        </div>
    </x-card>
</div>
