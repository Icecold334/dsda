<div>
    <h1 class="text-2xl font-bold text-primary-900 mb-3">
        @if (auth()->user()->unitKerja)
            <div>
                {{ auth()->user()->unitKerja->nama }}
            </div>
        @endif

    </h1>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-3">
        <div class="col-span-2">
            <div>
                <x-card title="Data Inventaris" class="mb-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
                            <div class="flex justify-center items-center mb-3">
                                <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white pe-1">Jumlah
                                    Stok
                                </h5>
                            </div>
                            <div class="py-6" id="stok-donut-chart"></div>
                            {{-- @push('scripts')
                                    <script type="module">
                                        const getChartOptions = () => {
                                            return {
                                                series: {!! $data_nilai !!},
                                                // colors: ["#1C64F2", "#16BDCA", "#FDBA8C", "#E74694"],
                                                chart: {
                                                    height: 320,
                                                    width: "100%",
                                                    type: "donut",
                                                },
                                                stroke: {
                                                    colors: ["transparent"],
                                                    lineCap: "",
                                                },
                                                plotOptions: {
                                                    pie: {
                                                        donut: {
                                                            labels: {
                                                                show: true,
                                                                name: {
                                                                    show: false,
                                                                    fontFamily: "Inter, sans-serif",
                                                                    offsetY: 20,
                                                                },
                                                                total: {
                                                                    showAlways: true,
                                                                    show: true,
                                                                    label: "Jumlah Aset",
                                                                    fontFamily: "Inter, sans-serif",
                                                                    formatter: function(w) {
                                                                        const sum = w.globals.seriesTotals.reduce((a, b) => {
                                                                            return a + b
                                                                        }, 0)
                                                                        return '$' + sum + 'k'
                                                                    },
                                                                },
                                                                value: {
                                                                    show: false,
                                                                    fontFamily: "Inter, sans-serif",
                                                                    offsetY: -20,
                                                                    formatter: function(value) {
                                                                        return value + "k"
                                                                    },
                                                                },
                                                            },
                                                            size: "50%",
                                                        },
                                                    },
                                                },
                                                grid: {
                                                    padding: {
                                                        top: -2,
                                                    },
                                                },
                                                labels: {!! $label_nilai !!},
                                                dataLabels: {
                                                    enabled: false,
                                                },
                                                legend: {
                                                    position: "bottom",
                                                    fontFamily: "Inter, sans-serif",
                                                    show: false,
                                                },
                                                yaxis: {
                                                    labels: {
                                                        formatter: function(value) {
                                                            return rupiah(value);
                                                        }
                                                    }
                                                },
                                                xaxis: {
                                                    labels: {
                                                        formatter: function(value) {
                                                            return value + "k"
                                                        },
                                                    },
                                                    axisTicks: {
                                                        show: false,
                                                    },
                                                    axisBorder: {
                                                        show: false,
                                                    },
                                                },
                                            }
                                        }

                                        if (document.getElementById("stok-donut-chart") && typeof ApexCharts !== 'undefined') {
                                            const chart = new ApexCharts(document.getElementById("stok-donut-chart"), getChartOptions());
                                            chart.render();
                                        }
                                    </script>
                                @endpush --}}
                        </div>
                        <div class="w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
                            <div class="flex justify-center items-center mb-3">
                                <h5 class="text-lg font-bold leading-none text-gray-900 dark:text-white pe-1">Jumlah
                                    Inventaris
                                </h5>
                            </div>
                            <div class="py-6" id="inventaris-donut-chart"></div>
                            {{-- @push('scripts')
                                    <script type="module">
                                        const getChartOptions = () => {
                                            return {
                                                series: {!! $data_nilai !!},
                                                // colors: ["#1C64F2", "#16BDCA", "#FDBA8C", "#E74694"],
                                                chart: {
                                                    height: 320,
                                                    width: "100%",
                                                    type: "donut",
                                                },
                                                stroke: {
                                                    colors: ["transparent"],
                                                    lineCap: "",
                                                },
                                                plotOptions: {
                                                    pie: {
                                                        donut: {
                                                            labels: {
                                                                show: true,
                                                                name: {
                                                                    show: false,
                                                                    fontFamily: "Inter, sans-serif",
                                                                    offsetY: 20,
                                                                },
                                                                total: {
                                                                    showAlways: true,
                                                                    show: true,
                                                                    label: "Jumlah Aset",
                                                                    fontFamily: "Inter, sans-serif",
                                                                    formatter: function(w) {
                                                                        const sum = w.globals.seriesTotals.reduce((a, b) => {
                                                                            return a + b
                                                                        }, 0)
                                                                        return '$' + sum + 'k'
                                                                    },
                                                                },
                                                                value: {
                                                                    show: false,
                                                                    fontFamily: "Inter, sans-serif",
                                                                    offsetY: -20,
                                                                    formatter: function(value) {
                                                                        return value + "k"
                                                                    },
                                                                },
                                                            },
                                                            size: "50%",
                                                        },
                                                    },
                                                },
                                                grid: {
                                                    padding: {
                                                        top: -2,
                                                    },
                                                },
                                                labels: {!! $label_nilai !!},
                                                dataLabels: {
                                                    enabled: false,
                                                },
                                                legend: {
                                                    position: "bottom",
                                                    fontFamily: "Inter, sans-serif",
                                                    show: false,
                                                },
                                                yaxis: {
                                                    labels: {
                                                        formatter: function(value) {
                                                            return rupiah(value);
                                                        }
                                                    }
                                                },
                                                xaxis: {
                                                    labels: {
                                                        formatter: function(value) {
                                                            return value + "k"
                                                        },
                                                    },
                                                    axisTicks: {
                                                        show: false,
                                                    },
                                                    axisBorder: {
                                                        show: false,
                                                    },
                                                },
                                            }
                                        }

                                        if (document.getElementById("nilai-donut-chart") && typeof ApexCharts !== 'undefined') {
                                            const chart = new ApexCharts(document.getElementById("nilai-donut-chart"), getChartOptions());
                                            chart.render();
                                        }
                                    </script>
                                @endpush --}}
                        </div>
                </x-card>
            </div>
            <div>
                <x-card title="agenda minggu" class="mb-3">
                    <div class="relative">
                        @forelse ($agendas as $agenda)
                            <div class="p-2 hover:bg-gray-100 border-b border-gray-200">
                                <!-- Container setiap item dengan efek hover -->
                                <div>
                                    <strong>{{ $agenda->formatted_date }}</strong>
                                </div>
                                <div class="text-sm">
                                    <a href="{{ route('aset.show', $agenda->aset->id) }}"
                                        class="text-primary-900 hover:underline">
                                        {{ $agenda->aset->nama }}
                                    </a>
                                </div>
                                <div class="text-sm">
                                    <em>{{ ucwords($agenda->tipe) }}:</em> {{ $agenda->keterangan }}
                                </div>
                            </div>
                        @empty
                            <div class="p-2 text-center">
                                <p>Tidak ada Agenda</p> <!-- Message if $agendas is empty -->
                            </div>
                        @endforelse
                    </div>
                    <hr><br>
                    <a href="/kalender-aset"
                        class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                        Lihat Kalender
                    </a>
                </x-card>
            </div>
            <div>
                <x-card title="Status KDO" class="mb-3">
                    <div class="relative overflow-y-auto max-h-80">
                        @forelse ($KDO as $kdos)
                            <div
                                class="p-2 hover:bg-gray-100 border-b border-gray-200 flex justify-between items-center">
                                <div>
                                    <div class="text-md">
                                        <strong>
                                            <a href="{{ route('aset.show', $kdos->id) }}"
                                                class="text-primary-900 hover:underline">
                                                {{ $kdos->merk->nama }} {{ $kdos->nama }} - {{ $kdos->noseri }}
                                            </a>
                                        </strong>
                                    </div>
                                    <div>
                                        <div class="text-sm">
                                            <strong class="{{ $kdos->status_class }}">
                                                {{ $kdos->status_text }}
                                            </strong>
                                        </div>
                                        <div class="text-sm">
                                            {{ $kdos->tipe }} - {{ $kdos->deskripsi }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Ikon Centang / Silang -->
                                <div class="text-xl">
                                    {{ $kdos->status_icon }}
                                </div>
                            </div>
                        @empty
                            <div class="p-2 text-center">
                                <p>Tidak ada KDO</p> <!-- Message if $jurnals is empty -->
                            </div>
                        @endforelse
                    </div>
                </x-card>
            </div>
        </div>
    </div>
    <div>
        <div>
            <x-card title="Pelayanan Umum Terbaru" class="mb-3">
                <div class="relative">
                    @forelse ($pelayanan as $layanan)
                        <div class="p-2 hover:bg-gray-100 border-b border-gray-200">
                            <!-- Container setiap item dengan efek hover -->
                            <div class="flex justify-between">
                                <div class="flex">
                                    <div class="ml-4">
                                        <div class="text-sm">
                                            <strong>{{ $layanan['formatted_date'] }}</strong>
                                        </div>
                                        <div class="text-md">
                                            <a href="/permintaan/{{ $layanan['tipe'] === 'peminjaman' ? 'peminjaman' : 'permintaan' }}/{{ $layanan['id'] }}"
                                                class="text-primary-900 hover:underline">
                                                {{ $layanan['kode'] }}
                                            </a>
                                        </div>
                                        <div class="text-sm">
                                            {{ Str::ucfirst($layanan['tipe']) }} - {{ $layanan['kategori']?->nama }}
                                        </div>
                                    </div>
                                </div>
                                <div class="flex justify-between py-3">
                                    <div>
                                        @php
                                            $statusKey = $this->getStatus($layanan);
                                            $statusText = $statusMapping[$statusKey]['text'];
                                            $statusColor = $statusMapping[$statusKey]['color'];
                                        @endphp
                                        <span
                                            class="bg-{{ $statusColor }}-600 text-{{ $statusColor }}-100 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                            {{ $statusText }}
                                        </span>
                                    </div>
                                    <div class="px-3">
                                        <a href="/permintaan/{{ $layanan['tipe'] === 'peminjaman' ? 'peminjaman' : 'permintaan' }}/{{ $layanan['id'] }}"
                                            class=" text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300 "
                                            data-tooltip-target="tooltip-layanan-{{ $layanan['id'] }}">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <div id="tooltip-layanan-{{ $layanan['id'] }}" role="tooltip"
                                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                            Lihat Detail Pelayanan
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-2 text-center">
                            <p>Tidak ada Layanan</p> <!-- Message if $asets is empty -->
                        </div>
                    @endforelse
                </div>
                <hr><br>
                <div class="text-center">
                    <a href="{{ route('permintaan-stok.index') }}"
                        class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Lihat
                        List</a>
                    <a href="/permintaan/umum"
                        class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Lihat
                        Form</a>
                </div>
            </x-card>
        </div>
        <div>
            <x-card title="Driver Tersedia" class="mb-3">
                <div class="relative">
                    @forelse ($transactions as $transaksi)
                        <div class="p-2 hover:bg-gray-100 border-b border-gray-200">
                            <div class="flex justify-between">
                                <div>
                                    <!-- Container setiap item dengan efek hover -->
                                    <div class="text-sm text-gray-500">
                                        {{ $transaksi->formatted_date }} , {{ $transaksi->aset->nama }}
                                    </div>
                                    <div class="text-sm">
                                        {!! $transaksi->tipe === 'out'
                                            ? '<span class="text-danger-600"><i class="fa-solid fa-arrow-right-from-bracket"></i></span>'
                                            : '<span class="text-success-600"><i class="fa-solid fa-arrow-right-to-bracket"></i></span>' !!}
                                        <a href="{{ route('aset.show', $transaksi->aset->id) }}"
                                            href="{{ route('aset.show', $transaksi->aset->id) }}"
                                            class="text-primary-900 hover:underline">
                                            {{ $transaksi->nominal }}</>
                                    </div>
                                </div>
                                <div class="py-3">
                                    <a href="{{ route('aset.show', ['aset' => $transaksi->aset->id, 'tab' => 'keuangan']) }}"
                                        class=" text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300 "
                                        data-tooltip-target="tooltip-transaksi-{{ $transaksi->id }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <div id="tooltip-transaksi-{{ $transaksi->id }}" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        Lihat Detail Transaksi
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-2 text-center">
                            <p>Tidak ada Transaksi</p> <!-- Message if $transaksis is empty -->
                        </div>
                    @endforelse
                </div>
            </x-card>
        </div>
    </div>
</div>
