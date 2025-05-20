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
    margin-bottom: 6px;
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
    <td class="header-logo" width="20%">
      <img src="{{ public_path('img/dki-logo.svg') }}" alt="Logo DKI">
    </td>
    <td class="header-text" width="80%">
      PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA<br>
      <strong>DINAS SUMBER DAYA AIR <br>
        SUKU DINAS SUMBER DAYA AIR <br></strong>
      <strong>{{ $isSeribu ?'KABUPATEN':'KOTA' }} ADMINISTRASI {{ Str::upper($sudin) }}</strong>
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
<h3 class="center">BERITA ACARA SERAH TERIMA BARANG <br>
  DISTRIBUSI/PENGELUARAN </h3>
<div class="center"><strong>Nomor</strong> : {{ $permintaan->nodin ??
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
      <td width="1000">{{ $pengurus->name }}</td>
    </tr>
    <tr>
      <td>Jabatan</td>
      <td>:</td>
      <td>Pengurus Barang Pembantu</td>
    </tr>
    <tr>
      <td></td>
      <td></td>
      <td>{{ $isSeribu ?'Kabupaten':'Kota' }} Administrasi {{ $sudin }}</td>
    </tr>
  </table>
  <div style="text-align: justify">
    Berdasarkan Surat Perintah Penyaluran Barang (SPPB) dari Pejabat Penatausahaan Barang Suku Dinas Sumber Daya Air {{
    $isSeribu ?'Kabupaten':'Kota' }} Administrasi {{$sudin }} Nomor {{ $permintaan->nodin }}
    tanggal {{ $permintaan->created_at->translatedFormat('j') }} bulan {{
    $permintaan->created_at->translatedFormat('M') }} tahun {{
    $permintaan->created_at->translatedFormat('Y') }} telah diserahkan oleh Pengurus Barang Pembantu kepada Pemakai
    Barang Persediaan, sebagaimana daftar terlampir.
  </div>
</div>
<div>
  Daftar barang persediaan yang didistribusikan/dikeluarkan sebagai berikut:
</div>
<br><br><br>
{{-- TABEL --}}
<table class="bahan">
  <thead>
    <tr>
      <th width="30" align="center">NO</th>
      <th width="100" align="center">NAMA</th>
      <th width="250" align="center">SPESIFIKASI</th>
      <th width="100" align="center">VOLUME</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($permintaan->permintaanMaterial as $item)
    <tr>
      <td width="30" class="center">{{ $loop->iteration }}</td>
      <td width="100">{{ $item->merkStok->barangStok->nama }}</td>
      <td width="250">{{ $item->merkStok->nama ?? 'Tanpa merk' }} - {{
        $item->merkStok->tipe ?? 'Tanpa tipe' }} -
        {{ $item->merkStok->ukuran?? 'Tanpa ukuran' }}</td>
      <td width="100" align="right">{{ $item->jumlah }} {{ $item->merkStok->barangStok->satuanBesar->nama }}</td>
    </tr>
    @endforeach
  </tbody>
</table>

<p style="margin-top: 20px;text-align: right">
  Jakarta, {{ $permintaan->created_at->translatedFormat('d F Y') }}
</p>

{{-- TANDA TANGAN --}}
<table class="no-border footer-ttd">

  <tr>
    <td width="50%">
      Yang Menyerahkan<br>
      Pengurus Barang Pembantu <br><br>
      @if ($sign)
      <img src="/storage/ttdPengiriman/nurdin.png" height="40"><br>
      @else
      <br><br><br>
      @endif
      <strong>{{ $pengurus->name }}</strong><br>
      NIP {{ $pengurus->nip ?? '....................' }}
    </td>
    <td width="50%">
      Yang Menerima<br>
      Pemakai Persediaan<br><br>
      @if ($sign)
      <img src="{{ $permintaan->status === 3 || true ? '/storage/ttdPengiriman/nurdin.png':'' }}" height="40"><br>
      @else
      <br><br><br>
      @endif
      <strong>{{ $permintaan->user->name }}</strong><br>
      NIP {{ $permintaan->user->nip ?? '....................' }}
    </td>


  </tr>
</table>