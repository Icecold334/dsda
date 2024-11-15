<x-body>
    {{-- <h1>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Beatae, voluptatum, corrupti eveniet iste, fuga
        commodi qui minus maxime rem hic eos molestiae officia eligendi earum? Nostrum modi illo doloremque totam.</h1> --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-3">

        <div class="col-span-2">
            <div>
                <x-card title="aset aktif" class="mb-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
                            <div class="flex justify-center items-center mb-3">
                                <div class="flex justify-center items-center">
                                    <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-white pe-1">Jumlah
                                        Aset</h5>
                                </div>
                            </div>
                            <!-- Donut Chart -->
                            <div class="py-6" id="jumlah-donut-chart"></div>
                            @push('scripts')
                                <script type="module">
                                    const getChartOptions = () => {
                                        return {
                                            series: {!! $data_jumlah !!},
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
                                            labels: {!! $label_jumlah !!},
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
                                                        return value + "k"
                                                    },
                                                },
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

                                    if (document.getElementById("jumlah-donut-chart") && typeof ApexCharts !== 'undefined') {
                                        const chart = new ApexCharts(document.getElementById("jumlah-donut-chart"), getChartOptions());
                                        chart.render();
                                    }
                                </script>
                            @endpush
                        </div>
                        <div class="w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
                            <div class="flex justify-center items-center mb-3">
                                <h5 class="text-lg font-bold leading-none text-gray-900 dark:text-white pe-1">Nilai Aset
                                </h5>
                            </div>
                            <!-- Donut Chart -->
                            <div class="py-6" id="nilai-donut-chart"></div>
                            @push('scripts')
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
                                                        return value + "k"
                                                    },
                                                },
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
                            @endpush
                        </div>
                    </div>
                </x-card>
            </div>
            <div>
                <x-card title="nilai aset" class="mb-3">
                    <div class=" w-full h-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
                        <div id="data-labels-chart"></div>
                    </div>
                    @push('scripts')
                        <script type="module">
                            // Ambil data yang dikirim dari controller
                            const label = [{!! $categories !!}]; // Data label, bulan-tahun
                            const nilaiAwal = [{!! $nilaiPerolehan !!}];
                            const nilaiSusut = [{!! $nilaiPenyusutan !!}];

                            // Contoh: Cetak data di console untuk melihat isinya
                            // console.log("Label (Bulan):", label);
                            // console.log("Nilai Awal:", nilaiAwal);
                            // console.log("Nilai Susut:", nilaiSusut);
                            const options = {
                                // enable and customize data labels using the following example, learn more from here: https://apexcharts.com/docs/datalabels/
                                tooltip: {
                                    enabled: true,
                                    x: {
                                        show: true,
                                    },
                                    y: {
                                        show: true,
                                        formatter: function(value) {
                                            return rupiah(value);
                                        }
                                    },
                                },
                                dataLabels: {
                                    enabled: false,
                                    // offsetX: 10,
                                    style: {
                                        cssClass: 'text-xs text-white font-medium'
                                    },
                                },
                                grid: {
                                    show: true,
                                    strokeDashArray: 4,
                                    padding: {
                                        left: 16,
                                        right: 16,
                                        top: -26
                                    },
                                },
                                series: [{
                                        name: "Nilai Perolehan",
                                        // data: @json($nilaiPerolehan),
                                        data: nilaiAwal,
                                        color: "#2a95e2",
                                    },
                                    {
                                        name: "Nilai Sesudah Penyusutan",
                                        // data: @json($nilaiPenyusutan),
                                        data: nilaiSusut,
                                        color: "#dc3545",
                                    },
                                ],
                                chart: {
                                    height: "100%",
                                    maxWidth: "100%",
                                    type: "area",
                                    fontFamily: "Inter, sans-serif",
                                    dropShadow: {
                                        enabled: false,
                                    },
                                    toolbar: {
                                        show: false,
                                    },
                                },
                                tooltip: {
                                    enabled: true,
                                    x: {
                                        show: false,
                                    },
                                },
                                legend: {
                                    show: true
                                },
                                fill: {
                                    type: "gradient",
                                    gradient: {
                                        opacityFrom: 0.55,
                                        opacityTo: 0,
                                        shade: "#1C64F2",
                                        gradientToColors: ["#1C64F2"],
                                    },
                                },
                                stroke: {
                                    width: 6,
                                },
                                xaxis: {
                                    // categories: @json($categories),
                                    categories: label,
                                    labels: {
                                        show: true,
                                    },
                                    axisBorder: {
                                        show: false,
                                    },
                                    axisTicks: {
                                        show: false,
                                    },
                                },
                                yaxis: {
                                    show: true,
                                    labels: {
                                        formatter: function(value) {
                                            return rupiah(value);
                                        }
                                    }
                                },
                            }

                            if (document.getElementById("data-labels-chart")) {
                                const chart = new ApexCharts(document.getElementById("data-labels-chart"), options);
                                chart.render();
                            }
                        </script>
                    @endpush

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
                    <a href="#"
                        class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                        Lihat Kalender Aset
                    </a>
                </x-card>
            </div>
            <div>
                <x-card title="jurnal terakhir" class="mb-3">
                    <div class="relative">
                        @forelse ($jurnals as $jurnal)
                            <div class="p-2 hover:bg-gray-100 border-b border-gray-200">
                                <!-- Container setiap item dengan efek hover -->
                                <div class="text-lg">
                                    <strong><a href="{{ route('aset.show', $jurnal->aset->id) }}"
                                            class="text-primary-900 hover:underline">
                                            {{ $jurnal->aset->nama }}
                                        </a></strong>
                                </div>
                                <div>
                                    <div class="text-sm">
                                        <strong>{{ $jurnal->formatted_date }}</strong>
                                    </div>
                                </div>
                                <div class="text-sm">
                                    {{ $jurnal->keterangan }}
                                </div>
                            </div>
                        @empty
                            <div class="p-2 text-center">
                                <p>Tidak ada Jurnal</p> <!-- Message if $jurnals is empty -->
                            </div>
                        @endforelse
                    </div>
                </x-card>
            </div>
        </div>
        <div>
            <div>
                <x-card title="rangkuman" class="mb-3">
                    <div class="relative">
                        <table>
                            <tbody>
                                <tr>
                                    <td class="text-sm px-2 py-1">Jumlah Aset Aktif</td>
                                    <td class="text-sm px-5">{{ $count_aset }} item</td>
                                </tr>
                                <tr>
                                    <td class="text-sm px-2 py-1">Total Nilai Awal</td>
                                    <td class="text-sm px-5">{{ $totalHargaFormatted }}</td>
                                </tr>
                                <tr>
                                    <td class="text-sm px-2 py-1">Total Nilai Penyusutan</td>
                                    <td class="text-sm  px-5" data-tooltip-target="tooltip-nilai-penyusutan">
                                        {{ $totalPenyusutanFormatted }}</td>
                                    <div id="tooltip-nilai-penyusutan" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        Belum Termasuk Penyusutan Per-Akhir Bulan Ini
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                </tr>
                                <tr>
                                    <td class="text-sm px-2 py-1">Total Nilai Sekarang</td>
                                    <td class="text-sm px-5" data-tooltip-target="tooltip-nilai-sekarang">
                                        {{ $totalNilaiNow }}</td>
                                    <div id="tooltip-nilai-sekarang" role="tooltip"
                                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        Belum Termasuk Penyusutan Per-Akhir Bulan Ini
                                        <div class="tooltip-arrow" data-popper-arrow></div>
                                    </div>
                                </tr>
                                <tr>
                                    <td class="text-sm px-2 py-1">Penyusutan Akhir Bulan</td>
                                    <td class="text-sm px-5">{{ $PenyusutanBulanFormatted }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </x-card>
            </div>
            <div>
                <x-card title="aset terbaru" class="mb-3">
                    <div class="relative">
                        @forelse ($asets_limit as $aset)
                            <div class="p-2 hover:bg-gray-100 border-b border-gray-200">
                                <!-- Container setiap item dengan efek hover -->
                                <div class="flex justify-between">
                                    <div class="flex">
                                        <img class="w-10 h-10 object-cover object-center rounded-sm"
                                            src="{{ asset($aset->foto ? 'storage/asetImg/' . $aset->foto : 'img/default-pic-thumb.png') }}"
                                            alt="">
                                        <div class="ml-4">
                                            <div class="text-sm">
                                                {{ $aset->formatted_date }}
                                            </div>
                                            <div class="text-md">
                                                <strong> <a href="{{ route('aset.show', $aset->id) }}"
                                                        class="text-primary-900 hover:underline">
                                                        {{ $aset->nama }}
                                                    </a></strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="py-3">
                                        <a href="{{ route('aset.show', ['aset' => $aset->id]) }}"
                                            class=" text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300 "
                                            data-tooltip-target="tooltip-aset-{{ $aset->id }}">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <div id="tooltip-aset-{{ $aset->id }}" role="tooltip"
                                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                            Lihat Detail Aset
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-2 text-center">
                                <p>Tidak ada Aset</p> <!-- Message if $asets is empty -->
                            </div>
                        @endforelse
                    </div>
                    <hr><br>
                    <div class="text-center">
                        <a href="#"
                            class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Lihat
                            Aset</a>
                        <a href="#"
                            class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Tambah
                            Aset</a>
                    </div>
                </x-card>
            </div>
            <div>
                <x-card title="riwayat terbaru" class="mb-3">
                    <div class="relative">
                        @forelse ($histories as $histori)
                            <div class="p-2 hover:bg-gray-100 border-b border-gray-200">
                                <div class="flex justify-between">
                                    <div>
                                        <!-- Container setiap item dengan efek hover -->
                                        <div class="text-sm text-gray-500">
                                            {{ $histori->formatted_date }} , {{ $histori->aset->nama }}
                                        </div>
                                        <div class="text-sm">
                                            <strong><a href="{{ route('aset.show', $histori->aset->id) }}"
                                                    class="text-primary-900 hover:underline">
                                                    {{ $histori->lokasi->nama }}</a></strong>
                                        </div>
                                    </div>
                                    <div class="py-3">
                                        <a href="{{ route('aset.show', ['aset' => $histori->id]) }}"
                                            class=" text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300 "
                                            data-tooltip-target="tooltip-riwayat-{{ $histori->id }}">
                                            <i class="fa-solid fa-clock-rotate-left"></i>
                                        </a>
                                        <div id="tooltip-riwayat-{{ $histori->id }}" role="tooltip"
                                            class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                            Lihat Detail Riwayat
                                            <div class="tooltip-arrow" data-popper-arrow></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-2 text-center">
                                <p>Tidak ada histori</p> <!-- Message if $historis is empty -->
                            </div>
                        @endforelse
                    </div>
                </x-card>
            </div>
            <div>
                <x-card title="transaksi terbaru" class="mb-3">
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
                                            <a href="{{ route('aset.show', $transaksi->aset->id) }}" <a
                                                href="{{ route('aset.show', $transaksi->aset->id) }}"
                                                class="text-primary-900 hover:underline">
                                                {{ $transaksi->nominal }}</a>
                                        </div>
                                    </div>
                                    <div class="py-3">
                                        <a href="{{ route('aset.show', ['aset' => $transaksi->id]) }}"
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
</x-body>
