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

{{-- HEADER --}}
<table class="header-table" style="width: 100%;">
  <tr>
    <td class="header-logo" width="28%">
      <img src="{{ public_path('img/dki-logo.svg') }}" alt="Logo DKI" width="80">
    </td>
    <td class="header-text">
      PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA<br>
      DINAS SUMBER DAYA AIR<br>
      SUKU DINAS SUMBER DAYA AIR<br>
      KOTA ADMINISTRASI {{ strtoupper(str_replace('Suku Dinas Sumber Daya Air Kota Administrasi ', '',
      $permintaan->unit->nama)) }}<br>
      <div style="font-weight: normal; font-size: 11px;">
        {{ $permintaan->unit->alamat }}
      </div>
      <div>J A K A R T A</div>
    </td>
  </tr>
</table>

<hr style="margin: 10px 0;">

<h3 class="center" style="text-decoration: underline;">SURAT JALAN</h3>

<p>
  @if ($permintaan->rab)
  <strong>Nomor</strong> : {{ $permintaan->rab->program->kode ?? '846846866' }}<br>
  <strong>Jenis Pekerjaan</strong> : {{ $permintaan->rab->kegiatan->kegiatan ?? '……………..……' }}<br>
  <strong>Lokasi Pekerjaan</strong> : {{ $permintaan->rab->lokasi ?? '……………….' }}<br>
  @else
  <strong>Nomor</strong> : {{ $permintaan->nodin ?? '846846866' }}<br>
  <strong>Jenis Pekerjaan</strong> : {{ $permintaan->nama ?? '……………..……' }}<br>
  <strong>Lokasi Pekerjaan</strong> : {{ $permintaan->lokasi ?? '……………….' }}<br>
  @endif
  <strong>Pemohon</strong> : {{ $kasatpel->name ?? '…………..' }} <em>Selaku Ketua Satuan Pelaksana Kecamatan {{
    $permintaan->user->kecamatan->kecamatan ?? '-' }}</em><br>
  <strong>Nopol Kendaraan</strong> : {{ $permintaan->nopol ?? '………….' }}
</p>

{{-- TABEL --}}
<table class="bahan">
  <thead>
    <tr>
      <th width="30" align="center">NO</th>
      <th width="100" align="center">NAMA</th>
      <th width="300" align="center">SPESIFIKASI</th>
      <th width="100" align="center">VOLUME</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($permintaan->permintaanMaterial as $item)
    <tr>
      <td width="30" class="center">{{ $loop->iteration }}</td>
      <td width="100">{{ $item->merkStok->barangStok->nama }}</td>
      <td width="300">{{ $item->merkStok->nama ?? 'Tanpa merk' }} - {{
        $item->merkStok->tipe ?? 'Tanpa tipe' }} -
        {{ $item->merkStok->ukuran?? 'Tanpa ukuran' }}</td>
      <td width="100" align="right">{{ $item->jumlah }} {{ $item->merkStok->barangStok->satuanBesar->nama }}</td>
    </tr>
    @endforeach
  </tbody>
</table>

<p style="margin-top: 20px;">
  Jakarta, {{ $permintaan->created_at->translatedFormat('d F Y') }}
</p>

{{-- TANDA TANGAN --}}
<table class="no-border footer-ttd">

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
</table>
<br><br>
<br><br>
<table class="no-border footer-ttd">

  <tr>
    <td width="50%">
      Keamanan<br><br><br><br>
      @if(file_exists(public_path('storage/ttdPengiriman/' . $permintaan->ttd_security)))
      <img src="{{ public_path('storage/ttdPengiriman/' . $permintaan->ttd_security) }}" height="40"><br>
      @endif
      <strong>{{ $permintaan->security }}</strong>
    </td>

    <td width="50%">
      Mengetahui,<br>
      Pengurus Barang Suku Dinas {{ Str::lower(strtoupper(str_replace('Suku Dinas ', '',
      $permintaan->unit->nama))) }}<br><br>
      @if(file_exists($ttdPath ?? ''))
      <img src="{{ $ttdPath }}" height="40"><br>
      @endif
      <strong>{{ $pengurus->name }}</strong><br>
      NIP {{ $pengurus->nip ?? '....................' }}
    </td>
  </tr>
</table>