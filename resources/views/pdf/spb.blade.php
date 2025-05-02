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
{{-- HEADER + LOGO --}}
<table class="header-table">
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
      <div style="font-weight: normal; font-size: 11px; ">
        {{ $permintaan->unit->alamat }}
      </div>
      <div>
        J A K A R T A
      </div>
    </td>
  </tr>
</table>

<br>

{{-- NOMOR --}}
<p class="meta"><strong>No</strong> : nomor</p>

{{-- JUDUL --}}
<h3 class="center">SURAT PERMINTAAN BARANG</h3>

{{-- PARAGRAF UTAMA --}}
<p>
  Berdasarkan dengan RAB kegiatan Nomor <strong>{{ $permintaan->rab->kegiatan->kode }}</strong>,
  <strong>{{ $permintaan->rab->kegiatan->kegiatan }}</strong> di <strong>{{ $permintaan->rab->lokasi }}</strong>,
  dengan ini saya mengajukan permohonan penyediaan bahan material dengan rincian sebagai berikut:
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

{{-- FOOTER + TTD --}}
<br><br>
<table width="100%">
  <tr>
    <td align="center">
      Mengetahui,<br>
      Kepala Seksi Pemeliharaan<br><br><br>

      <img src="/storage/ttdPengiriman/nurdin.png" width="100" height="50"><br><br>

      <b>{{ $pemel->name }}</b><br>
      NIP. {{ $pemel->nip }}
    </td>
    <td align="center">
      Jakarta, {{ $permintaan->created_at->locale('id')->translatedFormat('d F Y') }}<br>
      Kepala Satuan Pelaksana<br>
      Kecamatan {{ $kasatpel->kecamatan->kecamatan }}<br><br>

      <img src="/storage/ttdPengiriman/nurdin.png" width="100" height="50"><br><br>

      <b>{{ $kasatpel->name }}</b><br>
      NIP. {{ $kasatpel->nip }}
    </td>
  </tr>
</table>