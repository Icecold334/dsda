<div class="p-4">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-bold">KALENDER ASET</h2>
        <div class="flex space-x-2">
            <select wire:model.live="selectedFilter" class="border rounded-lg px-3 py-2">
                <option value="all">Tampilkan Semua</option>
                <option value="agenda">Agenda</option>
                <option value="journal">Jurnal</option>
                <option value="transaction">Transaksi</option>
                <option value="history">Riwayat</option>
            </select>
            <select wire:model.live="month" class="border rounded-lg px-3 py-2">
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endforeach
            </select>
            <select wire:model.live="year" class="border rounded-lg px-3 py-2">
                @for ($y = now()->year - 2; $y <= now()->year + 1; $y++)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endfor
            </select>
        </div>
    </div>

    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <thead>
            <tr class="bg-blue-500 text-white">
                <th class="border border-gray-300 px-4 py-2">HARI</th>
                <th class="border border-gray-300 px-4 py-2">TGL</th>
                <th class="border border-gray-300 px-4 py-2">AGENDA</th>
                <th class="border border-gray-300 px-4 py-2">JURNAL</th>
                <th class="border border-gray-300 px-4 py-2">TRANSAKSI</th>
                <th class="border border-gray-300 px-4 py-2">RIWAYAT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($days as $day)
                <tr class="text-sm {{ $loop->even }}">
                    <td
                        class="border border-gray-300 px-4 py-2 text-center 
                    {{ $day['day_name'] === 'Minggu' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                        {{ $day['day_name'] }}
                    </td>
                    <td
                        class="border border-gray-300 px-4 py-2 text-center 
                    {{ $day['day_name'] === 'Minggu' ? 'bg-red-300 text-red-800' : 'bg-green-300 text-green-800' }}">
                        {{ $day['day_num'] }}
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        @foreach ($agendas as $agenda)
                            @php
                                $showAgenda = false;

                                if ($agenda->tipe === 'mingguan' && $agenda->hari == $day['day_index']) {
                                    $showAgenda = true;
                                }

                                if ($agenda->tipe === 'bulanan' && $agenda->hari == $day['day_num']) {
                                    $showAgenda = true;
                                }

                                if (
                                    $agenda->tipe === 'tahunan' &&
                                    $agenda->hari == $day['day_num'] &&
                                    $agenda->bulan == $day['month_index']
                                ) {
                                    $showAgenda = true;
                                }

                                if (
                                    $agenda->tipe === 'tanggal_tertentu' &&
                                    $agenda->tanggal == $day['date_strtotime']
                                ) {
                                    $showAgenda = true;
                                }
                            @endphp

                            @if ($showAgenda)
                                <div class="bg-blue-100 p-2 rounded-lg mb-1">
                                    <div class="font-bold">{{ $agenda->aset->nama }}</div>
                                    <div class="text-sm text-gray-600">
                                        {{ $agenda->formatted_tipe }} : {{ $agenda->keterangan }}
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </td>

                    <td class="border border-gray-300 px-4 py-2">
                        @foreach ($journals->where('tanggal', $day['date_strtotime']) as $journal)
                            <div class="bg-red-100 p-2 rounded-lg mb-1">
                                <div class="font-bold">{{ $journal->aset->nama }}</div>
                                <div class="text-sm text-gray-600">
                                    {{ $journal->keterangan }}
                                </div>
                            </div>
                        @endforeach
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        @foreach ($transactions->where('tanggal', $day['date_strtotime']) as $transaction)
                            <div class="bg-green-100 p-2 rounded-lg mb-1">
                                <div class="font-bold">{{ $transaction->aset->nama }}</div>
                                <div class="text-sm text-gray-600">
                                    {{ $transaction->keterangan }}
                                </div>
                            </div>
                        @endforeach
                    </td>
                    <td class="border border-gray-300 px-4 py-2">
                        @foreach ($histories->where('tanggal', $day['date_strtotime']) as $history)
                            <div class="bg-yellow-100 p-2 rounded-lg mb-1">
                                <div class="font-bold">{{ $history->aset->nama }}</div>
                                <div class="text-sm text-gray-600">
                                    {{ $history->keterangan }}
                                </div>
                            </div>
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
