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
    /* line-height: 1.6; */
  }

  .tembusan {
    font-size: 10px;
    margin-top: 30px;
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

{{-- NOMOR --}}
<div style="text-align: right"><strong>No</strong> : {{ $permintaan->nodin }}</div>

{{-- JUDUL --}}
<h3 class="center">SURAT PERMINTAAN BARANG</h3>

{{-- PARAGRAF UTAMA --}}
<p>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Sehubungan dengan kebutuhan pelaksanaan pada Seksi Pemeliharaan Suku Dinas Sumber Daya
  Air
  Kabupaten Administrasi Kepulauan
  Seribu, dengan ini saya mengajukan permohonan penyediaan bahan material dengan rincian sebagai berikut:
</p>

{{-- TABEL --}}
<table class="bahan">
  <thead>
    <tr>
      <th width="30" align="center">NO</th>
      <th width="100" align="center">NAMA</th>
      <th width="180" align="center">SPESIFIKASI</th>
      <th width="80" align="center">VOLUME</th>
      <th width="45" align="center">RKB</th>
      <th width="100" align="center">KETERANGAN</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($permintaan->permintaanMaterial as $item)
    <tr>
      <td width="30" class="center">{{ $loop->iteration }}</td>
      <td width="100">{{ $item->merkStok->barangStok->nama }}</td>
      <td width="180">{{ $item->merkStok->nama ?? 'Tanpa merk' }} - {{
        $item->merkStok->tipe ?? 'Tanpa tipe' }} -
        {{ $item->merkStok->ukuran?? 'Tanpa ukuran' }}
      </td>
      <td width="80" align="right">{{ $item->jumlah }} {{ $item->merkStok->barangStok->satuanBesar->nama }}</td>
      <td width="45">{{ $item->rab->jenis_pekerjaan }}</td>
      <td width="100">{{ $item->deskripsi ??'-' }}</td>
    </tr>
    @endforeach
  </tbody>
</table>

{{-- FOOTER + TTD --}}
<br><br><br><br>
<table width="100%">
  <tr>
    <td align="center">
      Mengetahui,<br>
      Kepala Seksi Pemeliharaan<br><br><br>
      @if ($sign)
      <img src="/storage/ttdPengiriman/nurdin.png" width="100" height="50"><br><br>
      @else
      <br><br><br><br>
      @endif
      <b>{{ $pemel->name }}</b><br>
      NIP. {{ $pemel->nip }}
    </td>
    <td align="center">
      Jakarta, {{ $permintaan->created_at->locale('id')->translatedFormat('d F Y') }}<br>
      Kepala Satuan Pelaksana<br>
      Kecamatan {{ $kasatpel->kecamatan->kecamatan }}<br><br>
      @if ($sign)
      <img src="/storage/ttdPengiriman/nurdin.png" width="100" height="50"><br><br>
      @else
      <br><br><br><br>
      @endif
      <b>{{ $kasatpel->name }}</b><br>
      NIP. {{ $kasatpel->nip }}
    </td>
  </tr>
</table>