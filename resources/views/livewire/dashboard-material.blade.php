<div>
    <h1 class="text-2xl font-bold text-primary-900 mb-3">
        @if (auth()->user()->unitKerja)
        <div>
            {{ auth()->user()->unitKerja->nama }}
        </div>
        @endif
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-3">
        <div class="col-span-2 ">


            <x-card title="Grafik Keluar Masuk Barang 14 Hari terakhir" class="mb-4">
                <div id="chart-masuk-keluar"></div>
                @push('scripts')
                <script type="module">
                    const masukData = @json($masukData);
                                const keluarData = @json($keluarData);
                                const tanggalLabels = @json($tanggalLabels);
                        
                                new ApexCharts(document.querySelector("#chart-masuk-keluar"), {
                                    chart: {
                                        type: 'line',
                                        height: 320,
                                        toolbar: { show: false }
                                    },
                                    series: [
                                        {
                                            name: 'Masuk',
                                            data: masukData,
                                            color: '#28a745' // hijau
                                        },
                                        {
                                            name: 'Keluar',
                                            data: keluarData,
                                            color: '#007bff' // biru
                                        },
                                    ],
                                    xaxis: {
                                        type: 'category',
                                        categories: tanggalLabels,
                                        labels: { rotate: -45 }
                                    },
                                    yaxis: {
                                        labels: {
                                            formatter: val => val.toLocaleString('id-ID')
                                        }
                                    },
                                    stroke: {
                                        curve: 'smooth',
                                        width: 2
                                    },
                                    markers: {
                                        size: 4
                                    },
                                    tooltip: {
                                        y: {
                                            formatter: val => val + ' unit'
                                        }
                                    },
                                    legend: {
                                        position: 'top',
                                        horizontalAlign: 'left',
                                        fontFamily: 'Inter, sans-serif'
                                    }
                                }).render();
                </script>
                @endpush
            </x-card>


        </div>

        <div class="col-span-1 ">
            <x-card title="Jumlah Stok per Barang" class="mb-4">
                <div id="chart-stok-barang"></div>
                @push('scripts')
                <script type="module">
                    new ApexCharts(document.querySelector("#chart-stok-barang"), {
                                                chart: {
                                                    type: 'pie',
                                                    height: 350
                                                },
                                                series: @json($stokValues),
                                                labels: @json($stokLabels),
                                                dataLabels: {
                                                    enabled: true
                                                },
                                                legend: {
                                                    show:false,
                                                    position: 'bottom',
                                                    fontFamily: 'Inter, sans-serif'
                                                }
                                            }).render();
                </script>
                @endpush
            </x-card>


        </div>
    </div>
    <div class="col-span-3">
        <x-card title="Permintaan Terbaru" class="mb-4">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>No. SPB</th>
                        <th>Pemohon</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($permintaanTerbaru as $index => $p)
                    <tr>
                        <td class="text-center font-semibold">{{ $index + 1 }}</td>
                        <td class="text-center">{{ $p->nodin }}</td>
                        <td>{{ $p->user->name ?? '-' }}</td>
                        <td class="text-center">{{ $p->created_at->translatedformat('d F Y') }}</td>
                        <td class="text-center"><span
                                class="inline-block px-2 py-1 text-xs font-semibold text-white bg-{{ $p->status_color }}-600 rounded-full">
                                {{ $p->status_label }}
                            </span> </td>
                        <td class="text-center">
                            <a href="{{ url('/permintaan/permintaan/' . $p->id) }}"
                                class="text-blue-600 hover:underline  font-medium">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </x-card>
    </div>
</div>