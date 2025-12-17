<x-body>
  <div class="flex justify-between py-2 mb-3">

    <h1 class="text-2xl font-bold text-primary-900 uppercase">DETAIL {{ $RKB }}</h1>
    <div>
      @if (!is_null($rab->status) && $rab->status !== 0)
      <livewire:download-rab :rab='$rab'>
  @endif

        <a href="/rab"
          class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
    </div>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div>
      <x-card title="data umum" class="mb-3">
        <table class="w-full">
          <!-- Program -->
          <tr class="font-semibold ">
            <td class="w-1/3">Program</td>
            <td>{{ $rab->program->program }}</td>
          </tr>

          <!-- Nama Kegiatan -->
          <tr class="font-semibold">
            <td>Nama Kegiatan</td>
            <td>{{ $rab->kegiatan->kegiatan }}</td>
          </tr>

          <!-- Sub Kegiatan -->
          <tr class="font-semibold">
            <td>Sub Kegiatan</td>
            <td>{{ $rab->subKegiatan->sub_kegiatan }}</td>
          </tr>

          <!-- Rincian Sub Kegiatan -->
          <tr class="font-semibold">
            <td>Aktivitas Sub Kegiatan</td>
            <td>{{ $rab->aktivitasSubKegiatan->aktivitas }}</td>
          </tr>

          <!-- Kode Rekening -->
          <tr class="font-semibold">
            <td>Kode Rekening</td>
            <td>{{ $rab->uraianRekening->uraian }}</td>
          </tr>
          <!-- Jenis Pekerjaan -->
          <tr class="font-semibold">
            <td>Jenis Pekerjaan</td>
            <td>{{ $rab->jenis_pekerjaan }}</td>
          </tr>
          @if ($rab->saluran_jenis)
        <tr class="font-semibold">
        <td>Jenis Saluran</td>
        <td class="capitalize">Saluran Drainase {{ $rab->saluran_jenis }}</td>
        </tr>
        <tr class="font-semibold">
        <td>Nama Saluran</td>
        <td>{{ $rab->saluran_nama }}</td>
        </tr>

      @endif

          <!-- Status -->
          <tr class="font-semibold">
            <td>Status</td>
            <td>
              <span
                class="bg-{{ $rab->status_warna }}-600 text-{{ $rab->status_warna }}-100 text-xs font-medium me-2 px-2.5 py-0.5 rounded-full">
                {{ $rab->status_teks }}
              </span>
            </td>
          </tr>

          <!-- Keterangan (jika status == 0) -->
          <tr class="font-semibold {{ $rab->status !== 0 ? 'hidden' : '' }}">
            <td>Keterangan</td>
            <td>{{ $rab->keterangan }}</td>
          </tr>

          <!-- Tanggal Mulai -->
          <tr class="font-semibold">
            <td>Waktu Pengerjaan</td>
            <td>{{ $rab->mulai->format('d F Y') }} - {{ $rab->selesai->format('d F Y') }} ({{ $rab->lamaPengerjaan }})
            </td>
          </tr>
          @if ($rab->p && $rab->l && $rab->k)
        <tr class="font-semibold">
        <td>Volume Pekerjaan (Panjang, Lebar, Kedalaman)</td>
        <td class="capitalize">{{ $rab->p }}, {{ $rab->l }}, {{ $rab->k }}</td>
        </tr>
      @endif
          {{--
          <!-- Tanggal Selesai -->
          <tr class="font-semibold">
            <td>Tanggal Selesai</td>
            <td></td>
          </tr> --}}

          <!-- Lokasi -->
          <tr class="font-semibold">
            <td>Lokasi</td>
            <td>
              @if ($rab->kelurahan)
          Kelurahan {{ $rab->kelurahan->nama }},
          Kecamatan {{ $rab->kelurahan->kecamatan->kecamatan }} –
        @endif
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

      <!-- Card Overview History Adendum -->
      <x-card title="Overview History Adendum" class="mt-3">
        @if($totalAdendums > 0)
          <div class="space-y-4">
            <!-- Statistik Adendum -->
            <div class="grid grid-cols-3 gap-4">
              <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                <div class="text-sm text-blue-600 font-medium">Total Adendum</div>
                <div class="text-2xl font-bold text-blue-900 mt-1">{{ $totalAdendums }}</div>
              </div>
              <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                <div class="text-sm text-green-600 font-medium">Disetujui</div>
                <div class="text-2xl font-bold text-green-900 mt-1">{{ $approvedAdendums }}</div>
              </div>
              <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                <div class="text-sm text-yellow-600 font-medium">Menunggu</div>
                <div class="text-2xl font-bold text-yellow-900 mt-1">{{ $pendingAdendums }}</div>
              </div>
            </div>

            <!-- Statistik History -->
            @if($totalHistories > 0)
              <div class="border-t border-gray-200 pt-4">
                <div class="text-sm font-semibold text-gray-700 mb-3">Ringkasan History</div>
                <div class="grid grid-cols-3 gap-3">
                  <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">Dibuat: <strong>{{ $createCount }}</strong></span>
                  </div>
                  <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">Disetujui: <strong>{{ $approveCount }}</strong></span>
                  </div>
                  <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">Ditolak: <strong>{{ $rejectCount }}</strong></span>
                  </div>
                </div>
              </div>
            @endif

            <!-- Tombol Lihat History dan Buat Adendum -->
            <div class="pt-4 border-t border-gray-200 space-y-2">
              <a href="{{ route('rab.adendum.history', ['rab' => $rab->id]) }}"
                class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition duration-200">
                <i class="fa-solid fa-history mr-2"></i>
                Lihat Detail History Adendum
              </a>
              
              @php
                $user = auth()->user();
                $isKasatpel = $user->hasRole('Kepala Satuan Pelaksana');
                $isDisetujui = $rab->status === 2;
                $hasPendingAdendum = $rab->pendingAdendums->count() > 0;
                $isRabCreator = $rab->user_id === $user->id;
                $pendingAdendum = $rab->pendingAdendums->first();
                
                // Cek apakah pembuat RAB adalah Kasie/Kepala Seksi Perencanaan
                $rabCreator = $rab->user;
                $isKasieCreator = $rabCreator && (
                    $rabCreator->hasRole('Kepala Seksi') 
                    // || 
                    // $rabCreator->hasRole('Kepala Seksi Perencanaan') ||
                    // $rabCreator->roles->contains(function ($role) {
                    //     return str_contains($role->name, 'Kepala Seksi') || str_contains($role->name, 'Kasie');
                    // })
                );
              @endphp
              
              <!-- Tombol Konfirmasi Adendum untuk Pembuat RAB (termasuk Kasie yang membuat RAB) -->
              @if($isRabCreator && $hasPendingAdendum && $pendingAdendum)
                <a href="{{ route('rab.adendum.approve', ['rab' => $rab->id, 'adendum' => $pendingAdendum->id]) }}"
                  class="relative inline-flex items-center justify-center w-full px-4 py-2.5 bg-orange-600 text-white font-medium rounded-lg hover:bg-orange-700 transition duration-200 animate-pulse">
                  <i class="fa-solid fa-file-circle-check mr-2"></i>
                  Konfirmasi Adendum RAB
                  @if($rab->pendingAdendums->count() > 0)
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                      {{ $rab->pendingAdendums->count() }}
                    </span>
                  @endif
                </a>
              @endif
              
              @if($isKasatpel && $isDisetujui && !$hasPendingAdendum)
                <a href="{{ route('rab.adendum', ['rab' => $rab->id]) }}"
                  class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition duration-200">
                  <i class="fa-solid fa-file-pen mr-2"></i>
                  Buat Adendum RAB
                </a>
              @endif
            </div>
          </div>
        @else
          <div class="text-center py-6">
            <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
              <i class="fa-solid fa-file-circle-question text-2xl text-gray-400"></i>
            </div>
            <p class="text-gray-600 text-sm mb-4">Belum ada adendum untuk RAB ini</p>
            
            @php
              $user = auth()->user();
              $isKasatpel = $user->hasRole('Kepala Satuan Pelaksana');
              $isDisetujui = $rab->status === 2;
              $hasPendingAdendum = $rab->pendingAdendums->count() > 0;
            @endphp
            
            @if($isKasatpel && $isDisetujui && !$hasPendingAdendum)
              <a href="{{ route('rab.adendum', ['rab' => $rab->id]) }}"
                class="inline-flex items-center justify-center px-4 py-2.5 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition duration-200">
                <i class="fa-solid fa-file-pen mr-2"></i>
                Buat Adendum RAB
              </a>
            @endif
          </div>
        @endif
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