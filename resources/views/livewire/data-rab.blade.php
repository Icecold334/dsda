<div>
    <div wire:loading wire:target='downloadExcel'>
        <livewire:loading>
    </div>
    <div class="flex justify-between py-2 mb-3">

        <h1 class="text-2xl font-bold text-primary-900 ">Daftar Rencana Anggaran Biaya
            @if (auth()->user()->unitKerja)
            {{-- {{ auth()->user()->unitKerja->parent ? auth()->user()->unitKerja->parent->nama :
            auth()->user()->unitKerja->nama }} --}}
            {{ auth()->user()->unitKerja->nama }}
            @endif


        </h1>
        <div>
            <a href="{{ route('rab.create') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">+
                Tambah RAB</a>

        </div>
    </div>
    <table class="w-full  border-3 border-separate border-spacing-y-4 ">
        <thead>
            <tr class="text-white">
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold rounded-l-lg"></th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">NAMA</th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">MULAI </th>
                <th class="py-3 px-6 bg-primary-950 text-center font-semibold">SELESAI</th>
                <th class="py-3 px-6 bg-primary-950 text-center w-[8%] font-semibold rounded-r-lg"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rabs as $rab)
            <tr class="bg-gray-50  hover:bg-gray-200 hover:shadow-lg transition duration-200 rounded-2xl">
                <td class="py-3 px-6"></td>
                <td class="py-3 px-6 font-semibold">
                    <div>{{ $rab->nama }}</div>
                </td>
                <td class="py-3 px-6 font-semibold">
                    <div>{{ $rab->mulai->format('d F Y') }}</div>
                </td>
                <td class="py-3 px-6 font-semibold">
                    <div>{{ $rab->selesai->format('d F Y') }}</div>
                </td>
                <td class="py-3 px-6">
                    <a href="{{ route('rab.show', ['rab' => $rab->id]) }}"
                        class=" text-primary-950 px-3 py-3 rounded-md border hover:bg-slate-300 "
                        data-tooltip-target="tooltip-stok-{{ $rab->id }}">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                    <div id="tooltip-stok-{{ $rab->id }}" role="tooltip"
                        class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Lihat RAB
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Tidak ada data barang yang memiliki stok</td>
            </tr>
            @endforelse

        </tbody>
    </table>

    {{ $rabs->onEachSide(1)->links() }}
</div>