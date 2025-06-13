<style>
  table.bahan {
    border-collapse: collapse;
    width: 100%;
  }

  table.bahan,
  table.bahan th,
  table.bahan td {
    border: 1px solid #000;
  }

  table.bahan th {
    font-weight: bold;
    text-align: center;
    background-color: #f2f2f2;
  }

  table.bahan td {
    padding: 6px;
    vertical-align: middle;
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
    margin-bottom: 6px;
  }

  .header-logo img {
    width: 80px;
  }

  .header-text {
    text-align: center;
    /* font-weight: bold; */
    font-size: 10px;
  }

  .header-subtext {
    font-weight: normal;
    font-size: 10px;
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
    <td class="header-logo" width="20%">
      <img src="{{ public_path('img/dki-logo.svg') }}" alt="Logo DKI">
    </td>
    <td class="header-text" width="80%">
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
<div class="underline"></div>
<h4 style="text-align:center;">{{ Str::upper($RKB) }}</h4>

<table border="0" cellspacing="2" cellpadding="2" width="100%">
  <tr>
    <td width="30%">PROGRAM</td>
    <td width="2%">:</td>
    <td width="68%">{{ $rab->program->kode }} {{ $rab->program->program }}</td>
  </tr>
  <tr>
    <td>NAMA KEGIATAN</td>
    <td>:</td>
    <td>{{ $rab->kegiatan->kode }} {{ $rab->kegiatan->kegiatan }}</td>
  </tr>
  <tr>
    <td>SUB KEGIATAN</td>
    <td>:</td>
    <td>{{ $rab->subKegiatan->kode }} {{ $rab->subKegiatan->sub_kegiatan }}</td>
  </tr>
  <tr>
    <td style="white-space: normal;">{!! nl2br(e('RINCIAN SUB KEGIATAN')) !!}</td>
    <td>:</td>
    <td>{{ $rab->aktivitasSubKegiatan->kode }} {{ $rab->aktivitasSubKegiatan->aktivitas }}</td>
  </tr>
  <tr>
    <td>UNIT</td>
    <td>:</td>
    <td style="white-space: normal;">{!! nl2br(e($rab->unit->nama)) !!}</td>
  </tr>
  <tr>
    <td>JENIS PEKERJAAN</td>
    <td>:</td>
    <td>{{ $rab->jenis_pekerjaan }}</td>
  </tr>
  <tr>
    <td>KODE REKENING</td>
    <td>:</td>
    <td>{{ $rab->uraianRekening->kode }} {{ $rab->uraianRekening->uraian }}</td>
  </tr>
  <tr>
    <td>TAHUN ANGGARAN</td>
    <td>:</td>
    <td>{{ $rab->created_at->year }}</td>
  </tr>
  <tr>
    <td>LOKASI KEGIATAN</td>
    <td>:</td>
    <td>{{ $rab->lokasi }}</td>
  </tr>
</table>

<br><br>

<table class="bahan" cellpaddind="1">
  <thead>
    <tr>
      <th width="30" align="center">NO</th>
      <th width="100" align="center">NAMA</th>
      <th width="250" align="center">SPESIFIKASI</th>
      <th width="100" align="center">VOLUME</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($rab->list as $i => $item)
    <tr>
      <td width="30" align="center">{{ $loop->iteration }}</td>
      <td width="100">{{ $item->merkStok->barangStok->nama }}</td>
      <td width="250">{{ $item->merkStok->nama ?? 'Tanpa merk' }} - {{
        $item->merkStok->tipe ?? 'Tanpa tipe' }} -
        {{ $item->merkStok->ukuran?? 'Tanpa ukuran' }}</td>
      <td width="100" align="right">{{ $item->jumlah }} {{ $item->merkStok->barangStok->satuanBesar->nama }}</td>
    </tr>
    @endforeach
  </tbody>
</table>

<br><br><br>

<table width="100%" cellpadding="4">
  <tr>
    <td width="50%" align="center">
      Mengetahui,<br>
      Kepala Suku Dinas Sumber Daya Air<br>
      {{ str_replace('Suku Dinas Sumber Daya Air ', '', $rab->unit->nama) }}<br><br>
      <img src="/storage/ttdPengiriman/nurdin.png" width="100" height="50"><br><br>
      <b>{{ $kasudin->name }}</b><br>
      NIP. {{ $kasudin->nip }}
    </td>
    <td width="50%" align="center">
      Kepala Seksi Perencanaan<br>
      Suku Dinas Sumber Daya Air<br>
      {{ str_replace('Suku Dinas Sumber Daya Air ', '', $rab->unit->nama) }}<br><br>
      <img src="/storage/ttdPengiriman/nurdin.png" width="100" height="50"><br><br>
      <b>{{ $kasi->name }}</b><br>
      NIP. {{ $kasi->nip }}
    </td>
  </tr>
</table>