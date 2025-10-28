<style>
  body {
    font-family: helvetica, sans-serif;
    font-size: 11px;
  }

  table.meta td {
    padding: 2px 4px;
    vertical-align: top;
  }

  table.bahan {
    border-collapse: collapse;
    width: 100%;
    margin-top: 10px;
  }

  table.bahan,
  .bahan th,
  .bahan td {
    border: 1px solid #000;
    padding: 6px;
  }

  .center {
    text-align: center;
  }

  .header-text {
    text-align: center;
    font-weight: bold;
    font-size: 14px;
  }

  .tembusan {
    font-size: 10px;
    margin-top: 30px;
  }

  .header-table {
    width: 100%;
    padding-top: 8px;
    padding-bottom: 8px;
    margin-bottom: 6px;
  }

  .header-logo img {
    width: 80px;
  }

  .header-subtext {
    font-weight: normal;
    font-size: 10px;
  }

  .underline {
    border-top: 2px solid black;
    width: 100%;
    margin-top: 2px;
  }

  .meta-table td {
    padding: 2px 0;
    vertical-align: top;
  }
</style>
{{-- <div style="background-color: #ffc; padding: 10px; border: 1px solid red; font-size: 12px; color: black; z-index: 9999;">
  <p><b>DEBUGGING DATA:</b></p>
  <p><b>User ID dari Permintaan:</b> {{ $permintaan->user_id ?? 'KOSONG' }}</p>
  <p><b>Nama User:</b> {{ $permintaan->user?->name ?? 'RELASI USER GAGAL/NULL' }}</p>
  <p><b>Unit ID dari User:</b> {{ $permintaan->user?->unit_id ?? 'RELASI USER GAGAL / TDK ADA unit_id' }}</p>
  <p><b>Nama Unit:</b> {{ $permintaan->user?->unitKerja?->nama ?? 'RELASI UNIT GAGAL/NULL' }}</p>
  <p><b>Alamat Unit:</b> {{ $permintaan->user?->unit?->alamat ?? 'ALAMAT UNIT GAGAL/NULL' }}</p>
</div> --}}

<table class="header-table">
  <tr>
    <td class="header-logo" width="15%">
      <img src="{{ public_path('img/dki-logo.svg') }}" alt="Logo DKI">
    </td>
    <td class="header-text" width="85%">
      <span style="font-size: 12px">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA<br>
        DINAS SUMBER DAYA AIR <br></span>
     <strong style="font-size: 14px">SUKU DINAS SUMBER DAYA AIR <br>
        {{ (Str::contains($permintaan->user?->unitKerja?->nama, 'Kepulauan Seribu')) ? 'KABUPATEN' : 'KOTA' }} ADMINISTRASI {{ Str::upper($permintaan->user?->unitKerja?->nama) }}</strong>
      <div class="header-subtext">
        
        @php
          $unitAlamat = null;
          $unit = $permintaan->user?->unitKerja;

          if ($unit) {
              // 1. Cek dulu alamat unit itu sendiri
              if (!empty($unit->alamat)) {
                  $unitAlamat = $unit->alamat;
              } 
              // 2. Jika kosong, cek alamat parent-nya
              else if ($unit->parent && !empty($unit->parent->alamat)) {
                  $unitAlamat = $unit->parent->alamat;
              }
          }
        @endphp

        {{-- Cetak alamat yang ditemukan --}}
        {{ $unitAlamat }}
        
        <br>
        J A K A R T A
      </div>
    </td>
  </tr>
</table>
<div class="underline"></div>

<table width="100%" style="margin-top: 20px;">
  <tr>
    <td style="width: 100%; vertical-align: top;text-align: right">
      Jakarta, {{ $permintaan->created_at->translatedFormat('d F Y') }}
    </td>
  </tr>
  <tr>
    <!-- Kiri: metadata -->
    <td style="width: 30%; vertical-align: top;">
      <table class="meta-table">
        <tr>
          <td width="20%">Nomor</td>
          <td>: {{ $permintaan->nodin }}</td>
        </tr>
        <tr>
          <td>Sifat</td>
          <td>: Penting</td>
        </tr>
        <tr>
          <td>Lampiran</td>
          <td>: {{ count($permintaan->lampiran) && 0 ? count($permintaan->lampiran) . ' (satu) berkas' : '-' }}</td>
        </tr>
        <tr>
          <td>Hal</td>
          <td>: Surat Permintaan Barang</td>
        </tr>
      </table>
    </td>

    <!-- Kanan: alamat tujuan -->
    <td style="width: 70%; vertical-align: top; text-align: left;">
      <p style="margin-left: 30px;">
        Kepada <br>

        @php
          $namaUnit = $permintaan->user?->unitKerja?->nama ?? '';
          
          // Logika: JIKA nama unit TIDAK dimulai dengan "Suku Dinas..."
          if (!Str::startsWith($namaUnit, 'Suku Dinas Sumber Daya Air')) {
            // Tambahkan "Suku Dinas..." di depannya
            $namaUnit = 'Suku Dinas Sumber Daya Air ' . $namaUnit;
          }
        @endphp

        Yth. Kepala {{ $namaUnit }} <br>
        di Jakarta
      </p>
    </td>
  </tr>
</table>

<br>

<p>
  Sehubungan dengan kebutuhan pelaksanaan kegiatan {{ $permintaan->nama }}, dengan ini saya mengajukan permohonan
  penyediaan bahan material dengan rincian sebagai berikut:
</p>

<table class="bahan">
  <thead>
    <tr>
      <th width="30" align="center">NO</th>
      <th width="120" align="center">NAMA</th>
      <th width="280" align="center">SPESIFIKASI</th>
      <th width="100" align="center">VOLUME</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($permintaan->permintaanMaterial as $item)
    <tr>
      <td width="30" class="center">{{ $loop->iteration }}</td>
      <td width="120">{{ $item->merkStok->barangStok->nama }}</td>
      <td width="280">{{ $item->merkStok->nama ?? 'Tanpa merk' }} - {{ $item->merkStok->tipe ?? 'Tanpa tipe' }} -
      {{ $item->merkStok->ukuran ?? 'Tanpa ukuran' }}
      </td>
      <td width="100" align="right">{{ $item->jumlah }} {{ $item->merkStok->barangStok->satuanBesar->nama }}</td>
    </tr>
  @endforeach
  </tbody>
</table>

<p>
  Adapun bahan material tersebut diperlukan untuk {{ $permintaan->keterangan }} di {{ $lokasiLengkap }}.
  Demikian permohonan ini kami sampaikan. Atas perhatian dan kerjasamanya, saya ucapkan terima kasih.
</p>


<br>

<table width="100%" style="margin-top: 20px;">
  <tr>
    <td width="50%"></td>
    <td width="50%" align="center">
      Jakarta, {{ $permintaan->created_at->translatedFormat('d F Y') }}
    </td>
  </tr>
</table>

<br><br>

<table width="100%">
  <tr>
    @if (!Str::contains($pemohonRole, 'Kepala Seksi'))
    <td align="center" width="50%">
      Mengetahui,<br>
      Kepala Seksi Pemeliharaan<br><br>

      {{-- PERBAIKAN: Cek TTD Kepala Seksi Pemeliharaan --}}
      @if ($sign && $pemelDone && isset($pemel) && $pemel && $pemel->ttd && file_exists(public_path('storage/usersTTD/' . $pemel->ttd)))
      <img src="{{ public_path('storage/usersTTD/' . $pemel->ttd) }}" width="100" height="50"><br><br>
    @else
      <br><br><br><br>
    @endif

      <b>{{ $pemel->name ?? '-' }}</b><br>
      NIP. {{ $pemel->nip ?? '-' }}
    </td>
  @else
    <td align="center" width="50%">
      <!-- Kosong jika pemohon adalah Kepala Seksi -->
    </td>
  @endif

    <td align="center" width="50%">
      {{ $pemohonRole }}<br>
      @if (Str::contains($pemohonRole, 'Kepala Seksi'))
      Bidang {{ $pemohon->unitKerja->nama }}
    @elseif(Str::contains($pemohonRole, 'Kepala Satuan Pelaksana'))
      Kecamatan {{ $pemohon->kecamatan->kecamatan ?? '-' }}
    @endif
      <br><br>

      {{-- PERBAIKAN: Cek TTD Pemohon --}}
      @if ($sign && isset($pemohon) && $pemohon && $pemohon->ttd && file_exists(public_path('storage/usersTTD/' . $pemohon->ttd)))
      <img src="{{ public_path('storage/usersTTD/' . $pemohon->ttd) }}" width="100" height="50"><br><br>
    @else
      <br><br><br><br>
    @endif

      <b>{{ $pemohon->name ?? '-' }}</b><br>
      NIP. {{ $pemohon->nip ?? '-' }}
    </td>
  </tr>
</table>

<br><br><br><br>

<div class="tembusan">
  Tembusan:<br>
  
  @php
    $namaUnit = $permintaan->user?->unitKerja?->nama ?? '';
    
    // Logika: JIKA nama unit TIDAK dimulai dengan "Suku Dinas..."
    if (!Str::startsWith($namaUnit, 'Suku Dinas Sumber Daya Air')) {
      // Tambahkan "Suku Dinas..." di depannya
      $namaUnit = 'Suku Dinas Sumber Daya Air ' . $namaUnit;
    }
  @endphp

  Kepala Subbagian Tata Usaha {{ $namaUnit }}
</div>