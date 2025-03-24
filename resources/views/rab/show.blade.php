<x-body>
  <div class="flex justify-between py-2 mb-3">

    <h1 class="text-2xl font-bold text-primary-900 uppercase">DETAIL Rencana Anggaran Biaya</h1>
    <div>
      <a href="/rab"
        class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>

    </div>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div>
      <x-card title="data umum" class="mb-3">
        <table class="w-full">

          <tr class="font-semibold">
            <td>Nama Kegiatan</td>
            <td>
              {{ $rab->nama }}
            </td>
          </tr>
          <tr class="font-semibold">
            <td>Status</td>
            <td>
              <span
                class="bg-{{ $rab->status_warna }}-600 text-{{ $rab->status_warna }}-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                {{ $rab->status_teks }}
              </span>
            </td>
          </tr>
          <tr class="font-semibold">
            <td>Tanggal Mulai</td>
            <td>
              {{ $rab->mulai->format('d F Y') }}
            </td>
          </tr>
          <tr class="font-semibold">
            <td>Tanggal Selesai</td>
            <td>
              {{ $rab->selesai->format('d F Y') }}
            </td>
          </tr>
          <tr class="font-semibold">
            <td>Lokasi</td>
            <td>
              {{ $rab->lokasi }}
            </td>
          </tr>
        </table>
      </x-card>
    </div>
    <div>
      <x-card title="Lampiran">
        @forelse ($rab->lampiran as $attachment)
        <div class="flex items-center justify-between border-b-4 p-2 rounded my-1">
          <span class="flex items-center space-x-3">
            @php
            $fileType = pathinfo($attachment->path, PATHINFO_EXTENSION);
            @endphp
            <span class="text-primary-600">
              @if (in_array($fileType, ['png', 'jpg', 'jpeg', 'gif']))
              <i class="fa-solid fa-image text-green-500"></i>
              @elseif($fileType == 'pdf')
              <i class="fa-solid fa-file-pdf text-red-500"></i>
              @elseif(in_array($fileType, ['doc', 'docx']))
              <i class="fa-solid fa-file-word text-blue-500"></i>
              @else
              <i class="fa-solid fa-file text-gray-500"></i>
              @endif
            </span>

            <!-- File name with underline on hover and a link to the saved file -->
            <span>
              <a href="{{ asset('storage/lampiranRab/' . $attachment->path) }}" target="_blank"
                class="text-gray-800 hover:underline">
                {{ basename($attachment->path) }}
              </a>
            </span>
          </span>
        </div>
        @empty
        <div class="flex justify-center text-xl font-semibold">
          Tidak ada lampiran
        </div>
        @endforelse

      </x-card>
    </div>
    <div class="col-span-2">
      <x-card title="daftar permintaan">
        <livewire:list-rab :rab_id='$rab->id'>
          <livewire:approval-rab :rab='$rab'>
      </x-card>
    </div>
  </div>
</x-body>