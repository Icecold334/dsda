<x-body>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">DETAIL PERMINTAAN</h1>
        <div>
            <a href="/permintaan/{{ $permintaan->jenis_id == 3 ? 'umum' : ($permintaan->jenis_id == 2 ? 'spare-part' : 'material') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div>
            <x-card title="data umum" class="mb-3">
                <table class="w-full">
                    <tr class="font-semibold">
                        <td>Kode Permintaan</td>
                        <td>{{ $permintaan->kode_permintaan }}</td>
                    </tr>
                    <tr class="font-semibold">
                        <td>Status</td>
                        <td> <span
                                class="
        bg-{{ $permintaan->cancel === 1
            ? 'secondary'
            : ($permintaan->cancel === 0 && $permintaan->proses === 1
                ? 'primary'
                : ($permintaan->cancel === 0 && $permintaan->proses === null
                    ? 'info'
                    : ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status === null
                        ? 'warning'
                        : ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status === 1
                            ? 'success'
                            : 'danger')))) }}-600
        text-{{ $permintaan->cancel === 1
            ? 'secondary'
            : ($permintaan->cancel === 0 && $permintaan->proses === 1
                ? 'primary'
                : ($permintaan->cancel === 0 && $permintaan->proses === null
                    ? 'info'
                    : ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status === null
                        ? 'warning'
                        : ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status === 1
                            ? 'success'
                            : 'danger')))) }}-100
        text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                                {{ $permintaan->cancel === 1
                                    ? 'dibatalkan'
                                    : ($permintaan->cancel === 0 && $permintaan->proses === 1
                                        ? 'selesai'
                                        : ($permintaan->cancel === 0 && $permintaan->proses === null
                                            ? 'siap diambil'
                                            : ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status === null
                                                ? 'diproses'
                                                : ($permintaan->cancel === null && $permintaan->proses === null && $permintaan->status === 1
                                                    ? 'disetujui'
                                                    : 'ditolak')))) }}
                            </span>

                        </td>
                    </tr>
                    @if ($permintaan->status === 0)
                        <tr class="font-semibold">
                            <td>Keterangan</td>
                            <td>{{ $permintaan->persetujuan->where('status', 0)->last()->keterangan }}</td>
                        </tr>
                    @endif
                    <tr class="font-semibold">
                        <td>Tanggal Permintaan</td>
                        <td>{{ date('j F Y', $permintaan->tanggal_permintaan) }}</td>
                    </tr>
                    <tr class="font-semibold">
                        <td>Unit Kerja</td>
                        <td>{{ $permintaan->unit->nama }}</td>
                    </tr>
                    <tr class="font-semibold">
                        <td>Sub-Unit</td>
                        <td>{{ $permintaan->subUnit->nama ?? '---' }}</td>
                    </tr>
                </table>
            </x-card>
        </div>
        <div>
            <x-card title="keterangan" class="mb-3">
                <div class="font-normal">
                    {{ $permintaan->keterangan }}
                </div>
            </x-card>
        </div>
        <div class="col-span-2">
            <x-card title="daftar permintaan">

                <livewire:list-permintaan-form :permintaan="$permintaan">
                    <livewire:approval-permintaan :permintaan="$permintaan">
            </x-card>
        </div>
    </div>
</x-body>
