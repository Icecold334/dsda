<div>
    <div class="flex justify-end items-center bg-primary-200 rounded-lg mb-3">
        <a href="{{ route('aset.index') }}"
            class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
            Riwayat</a>
    </div>
    <div class="grid grid-cols-1 gap-3">
        @foreach ($histories as $history)
            <div
                class="w-full p-6 bg-white border hover:bg-gray-100 transition duration-200 border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                <div class="flex justify-between">
                    <div>
                        <table class="w-full text-sm">
                            <tr>
                                <td style="width: 50%">Sejak Tanggal</td>
                                <td>{{ date('j F Y', $history->tanggal) }}</td>
                            </tr>
                            <tr>
                                <td>Penanggung Jawab</td>
                                <td>{{ $history->person->nama }}</td>
                            </tr>
                            <tr>
                                <td>Lokasi</td>
                                <td>{{ $history->lokasi->nama ?? '---' }}</td>
                                <!-- Example of using a fallback if the location isn't set -->
                            </tr>
                            <tr>
                                <td>Jumlah</td>
                                <td>{{ $history->jumlah ?? '---' }}</td>
                                <!-- Example of using a fallback for quantity -->
                            </tr>
                            <tr>
                                <td>Kondisi</td>
                                <td>{{ $history->kondisi ?? '---' }}</td>
                                <!-- Example of using a fallback for condition -->
                            </tr>
                            <tr>
                                <td>Kelengkapan</td>
                                <td>{{ $history->kelengkapan ?? '---' }}</td>
                                <!-- Example of using a fallback for completeness -->
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>{{ $history->keterangan ?? '-' }}</td>
                                <!-- Example of using a fallback for description -->
                            </tr>
                        </table>
                    </div>
                    <div>
                        <div class="flex">
                            <button
                                class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"><i
                                    class="fa-solid fa-trash"></i></button>
                            <button
                                class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"><i
                                    class="fa-solid fa-pen"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
