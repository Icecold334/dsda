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
    font-size: 13px;
  }

  .tembusan {
    font-size: 10px;
    margin-top: 30px;
  }

  .no-border {
    border-collapse: collapse;
    width: 100%;
    margin-top: 20px;
  }

  .no-border td {
    border: none;
    text-align: center;
    vertical-align: top;
    padding: 10px;
  }

  .footer-ttd img {
    margin-bottom: 4px;
  }
</style>
<style>
  body {
    font-family: helvetica, sans-serif;
    font-size: 11px;
  }

  .header-table {
    width: 100%;
    /* border-bottom: 2px solid #000; */
    padding-top: 8px;
    padding-bottom: 8px;
    /* margin-bottom: 6px; */
  }

  .header-logo img {
    width: 80px;
  }

  .header-text {
    text-align: center;
    /* font-weight: bold; */
    font-size: 13px;
  }

  .header-subtext {
    font-weight: normal;
    font-size: 11px;
    /* margin-top: 4px; */
  }

  .header-kodepos {
    text-align: right;
    font-size: 10px;
    /* margin-top: -10px; */
  }

  .underline {
    border-top: 2px solid black;
    width: 100%;
    margin-top: 2px;
  }
</style>

<table class="header-table">
  <tr>
    <td class="header-logo" width="13%">
      <img src="{{ public_path('img/dki-logo.svg') }}" alt="Logo DKI">
    </td>
    <td class="header-text" width="87%">
      <span style="font-size: 12px">PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA<br>
        DINAS SUMBER DAYA AIR <br></span>
      <strong style="font-size: 14px">SUKU DINAS SUMBER DAYA AIR <br>
        {{ $isSeribu ?'KABUPATEN':'KOTA' }} ADMINISTRASI {{ Str::upper($sudin) }}</strong>
      <div class="header-subtext">
        {{ $permintaan->unit->alamat }}
        <br>
        J A K A R T A
      </div>
      {{-- <div>J A K A R T A</div> --}}
    </td>
  </tr>
</table>
<hr style="border-top: 4px solid black; margin: 2px 0;">
<h3 class="center">SURAT PERINTAH PENYALURAN BARANG <br> (SPPB)</h3>
<div class="center"><strong>Nomor</strong> : {{ $permintaan->sppb ??
  '846846866' }}<br></div>
<div style="text-align: justify;line-height: 20px">
  Pada hari ini {{ $permintaan->created_at->translatedFormat('l') }} tanggal {{
  $permintaan->created_at->translatedFormat('j') }} bulan
  {{
  $permintaan->created_at->translatedFormat('M') }} tahun {{
  $permintaan->created_at->translatedFormat('Y') }}, yang bertanda tangan di bawah ini: <br>
  <table>
    <tr>
      <td width="60">Nama</td>
      <td width="8">:</td>
      <td width="1000">{{ $kasubag->name }}</td>
    </tr>
    <tr>
      <td>Jabatan</td>
      <td>:</td>
      <td>Pejabat Penatausahaan Barang (PPB) Suku Dinas Sumber Daya Air</td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td>{{ $isSeribu ?'Kabupaten':'Kota' }} Administrasi {{ $sudin }}</td>
    </tr>
  </table>
  <div style="text-align: justify">
    Berdasarkan Surat Permintaan Barang (SPB) dari Kepala Satuan Pelaksana Kecamatan {{
    $permintaan->user->kecamatan->kecamatan ?? '-' }} Nomor {{ $permintaan->nodin }}
    tanggal {{ $permintaan->created_at->translatedFormat('j') }} bulan {{
    $permintaan->created_at->translatedFormat('M') }} tahun {{
    $permintaan->created_at->translatedFormat('Y') }} dengan ini diperintahkan kepada Pengurus
    Barang
    Pembantu Suku Dinas Sumber Daya Air {{ $isSeribu ?'Kabupaten':'Kota' }}
    Administrasi {{ $sudin }} untuk mendistribusikan/mengeluarkan barang persediaan, sebagaimana daftar terlampir.
  </div>
</div>
<div>
  Daftar barang persediaan yang didistribusikan/dikeluarkan sebagai berikut:
</div>
{{-- <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
  @if ($permintaan->rab)
  <tr>
    <td style="width: 25%; vertical-align: top;"><strong>Jenis Pekerjaan</strong></td>
    <td style="width: 2%; vertical-align: top;">:</td>
    <td style="white-space: normal; vertical-align: top;">
      {{ $permintaan->rab->jenis_pekerjaan ?? '……………..……' }}
    </td>
  </tr>
  <tr>
    <td style="vertical-align: top;"><strong>Lokasi Pekerjaan</strong></td>
    <td style="vertical-align: top;">:</td>
    <td style="white-space: normal; vertical-align: top;">
      {{ $permintaan->rab->lokasi ?? '……………….' }}
    </td>
  </tr>
  @else
  <tr>
    <td style="width: 25%; vertical-align: top;"><strong>Jenis Pekerjaan</strong></td>
    <td style="width: 2%; vertical-align: top;">:</td>
    <td style="white-space: normal; vertical-align: top;">
      {{ $permintaan->nama ?? '……………..……' }}
    </td>
  </tr>
  <tr>
    <td style="vertical-align: top;"><strong>Lokasi Pekerjaan</strong></td>
    <td style="vertical-align: top;">:</td>
    <td style="white-space: normal; vertical-align: top;">
      {{ $permintaan->lokasi ?? '……………….' }}
    </td>
  </tr>
  @endif

  <tr>
    <td style="vertical-align: top;"><strong>Pemohon</strong></td>
    <td style="vertical-align: top;">:</td>
    <td style="white-space: nowrap; vertical-align: top;">
      {{ $kasatpel->name ?? '…………..' }}
      <em>Selaku Ketua Satuan Pelaksana Kecamatan {{ $permintaan->user->kecamatan->kecamatan ?? '-' }}</em>
    </td>
  </tr>
  <tr>
    <td style="vertical-align: top;"><strong>Nopol Kendaraan</strong></td>
    <td style="vertical-align: top;">:</td>
    <td style="white-space: normal; vertical-align: top;">
      {{ $permintaan->nopol ?? '………….' }}
    </td>
  </tr>
</table> --}}
<br>
{{-- TABEL --}}
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
      <td width="280">{{ $item->merkStok->nama ?? 'Tanpa merk' }} - {{
        $item->merkStok->tipe ?? 'Tanpa tipe' }} -
        {{ $item->merkStok->ukuran?? 'Tanpa ukuran' }}</td>
      <td width="100" align="right">{{ $item->jumlah }} {{ $item->merkStok->barangStok->satuanBesar->nama }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
<br>
<div>
  Demikian Surat Perintah Penyaluran Barang ini dibuat dalam rangkap 2 (dua) untuk digunakan sebagaimana mestinya.
</div>

<p style="margin-top: 20px;text-align: right">
</p>

{{-- TANDA TANGAN --}}
{{-- <table class="no-border footer-ttd">

  <tr>
    <td width="50%">
      Pemohon<br>
      Ketua Satuan Pelaksana<br>
      Kecamatan {{ $permintaan->user->kecamatan->kecamatan ?? '-' }}<br><br>
      <img src="/storage/ttdPengiriman/nurdin.png" height="40"><br>
      <strong>{{ $kasatpel->name }}</strong><br>
      NIP {{ $kasatpel->nip ?? '....................' }}
    </td>
    <td width="50%">
      Driver<br><br><br><br>
      @if(file_exists(public_path('storage/ttdPengiriman/' . $permintaan->ttd_driver)))
      <img src="{{ public_path('storage/ttdPengiriman/' . $permintaan->ttd_driver) }}" height="40"><br>
      @endif
      <strong>{{ $permintaan->driver }}</strong>
    </td>

  </tr>
</table> --}}
<br><br>
<br><br>
<table class="no-border footer-ttd">

  <tr>
    <td width="50%">
      {{-- Keamanan<br><br><br><br>
      @if(file_exists(public_path('storage/ttdPengiriman/' . $permintaan->ttd_security)))
      <img src="{{ public_path('storage/ttdPengiriman/' . $permintaan->ttd_security) }}" height="40"><br>
      @endif
      <strong>{{ $permintaan->security }}</strong> --}}
    </td>

    <td width="50%">
      Jakarta, {{ $permintaan->created_at->translatedFormat('d F Y') }} <br>
      Pejabat Penatausahaan Barang Suku Dinas {{ Str::ucfirst(str_replace('Suku Dinas ', '',
      $permintaan->unit->nama)) }}<br><br>
      @if ($sign)
      {{-- <img src="/storage/ttdPengiriman/nurdin.png" height="40"><br> --}}
      <img src="{{ asset('storage/usersTTD/' . $kasubag->ttd) }}" width="100" height="50"><br><br>
      @else
      <br><br><br>
      @endif
      <strong>{{ $kasubag->name }}</strong><br>
      NIP {{ $kasubag->nip ?? '....................' }}
    </td>
  </tr>
</table>