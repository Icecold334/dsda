<style>
  body {
    font-family: helvetica, sans-serif;
  }

  table {
    border-collapse: collapse;
    width: 100%;
    margin-top: 10px;
  }

  td,
  th {
    border: 1px solid #000;
    padding: 5px;
    text-align: center;
  }

  .no-border td {
    border: none;
  }
</style>

<h3 style="text-align: center;">PEMERINTAH PROVINSI DAERAH KHUSUS IBU KOTA JAKARTA<br>
  DINAS SUMBER DAYA AIR<br>
  {{ $permintaan->unit->nama }}</h3>

{{-- <p style="text-align: center;">Jalan Kembangan Raya No. 2 Blok B Lt. 6 Telp. (021) 58356234</p> --}}

<h4 style="text-align: center;">SURAT JALAN</h4>

<p>Nomor Nota Dinas: {{ $permintaan->nodin ?? '-' }}</p>
<p><strong>LOKASI:</strong> {{ $permintaan->lokasi }}<br>
  <strong>Nomor Polisi:</strong> {{ $permintaan->nopol }}<br>
  <strong>Nama:</strong> Barang
</p>

<table>
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
      <td width="30" align="center">{{ $loop->iteration }}</td>
      <td width="100">{{ $item->merkStok->barangStok->nama }}</td>
      <td width="300">{{ $item->merkStok->nama ?? 'Tanpa merk' }} - {{
        $item->merkStok->tipe ?? 'Tanpa tipe' }} -
        {{ $item->merkStok->ukuran?? 'Tanpa ukuran' }}</td>
      <td width="100" align="right">{{ $item->jumlah }} {{ $item->merkStok->barangStok->satuanBesar->nama }}</td>
    </tr>
    @endforeach
  </tbody>
</table>

<p style="margin-top: 20px;">Jakarta, {{ $permintaan->created_at->format('d F Y') }}</p>



<table class="no-border" style="margin-top: 40px; width: 100%;">
  <tr>
    <td style="text-align: center;">
      Yang Mengambil<br><br><br>
      @php
      $path = public_path('storage/ttdPengiriman/'.str_replace(url('/'), '', $permintaan->ttd_driver ?? ''));
      @endphp
      @if(file_exists($path ?? ''))
      <img src="{{ $path }}" height="40">
      @endif

      <br>
      ({{ $permintaan->driver }})
    </td>
    <td style="text-align: center;">
      Yang Mengeluarkan<br><br><br>
      @php
      $path = public_path('storage/usersTTD/'.str_replace(url('/'), '', $penjaga->ttd ?? ''));
      @endphp
      @if(file_exists($path ?? ''))
      <img src="{{ $path }}" height="40">
      @endif
      <br>({{ $penjaga->name }})
    </td>
  </tr>
</table>
<br><br><br>
<table class="no-border" style="margin-top: 40px; width: 100%;">
  <tr>
    <td style="text-align: center;">
      Keamanan<br><br><br>
      @php
      $path = public_path('storage/ttdPengiriman/'.str_replace(url('/'), '', $permintaan->ttd_security ?? ''));
      @endphp
      @if(file_exists($path ?? ''))
      <img src="{{ $path }}" height="40">
      @endif

      <br>
      ({{ $permintaan->security }})
    </td>
    <td style="text-align: center;">
      Yang Menerima<br><br><br>
      @php
      $path = public_path('storage/usersTTD/'.str_replace(url('/'), '', $kasatpel->ttd ?? ''));
      @endphp
      @if(file_exists($path ?? ''))
      <img src="{{ $path }}" height="40">
      @endif
      <br>({{ $kasatpel->name }})
    </td>
  </tr>
</table>
<br>
{{-- Bagian Mengetahui --}}
<div style="text-align: center; margin-top: 60px;">
  Mengetahui<br>
  Pengurus Barang<br>
  Suku Dinas Sumber Daya Air<br><br><br>
  @if(file_exists($ttdPath ?? ''))
  <img src="{{ $ttdPath }}" height="40">
  @endif <br>
  <b>{{ $pengurus->name }}</b><br>
  1997/1101021
</div>