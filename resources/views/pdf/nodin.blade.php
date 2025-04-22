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
</style>

<h3 class="center">NOTA DINAS</h3>

<table class="meta" width="100%">
  <tr>
    <td width="10%">Kepada</td>
    <td width="90%">: Kepala Suku Dinas {{ str_replace('Suku Dinas','',$permintaan->unit->nama) }}</td>
  </tr>
  <tr>
    <td>Dari</td>
    <td>: Kepala Satuan Pelaksana {{ $permintaan->unit->nama }}</td>
  </tr>
  <tr>
    <td>Nomor</td>
    <td>: {{ $permintaan->nodin }}</td>
  </tr>
</table>

<p>
  Sehubungan dengan {{$permintaan->nama}} dalam rangka {{ $permintaan->keterangan }}
  di {{$permintaan->lokasi}} maka dimohonkan untuk diberikannya material sebagai
  berikut:
</p>

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

<p>
  Demikian Nota Dinas ini disampaikan. Atas arahan dan perhatiannya diucapkan terima kasih.
</p>

<br><br>
<table width="100%">
  <tr>
    <td></td>
    <td align="center">
      Jakarta, {{ $permintaan->created_at->locale('id')->translatedFormat('d F Y') }}<br>
      Kepala Satuan Pelaksana<br>
      {{ $permintaan->unit->nama }}<br><br>

      <img src="/storage/ttdPengiriman/nurdin.png" width="100" height="50"><br><br>

      <b>{{ $kasatpel->name }}</b><br>
      NIP. {{ $kasatpel->nip }}
    </td>
  </tr>
</table>