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
  SUKU DINAS SUMBER DAYA AIR KOTA ADMINISTRASI JAKARTA BARAT</h3>

<p style="text-align: center;">Jalan Kembangan Raya No. 2 Blok B Lt. 6 Telp. (021) 58356234</p>

<h4 style="text-align: center;">SURAT JALAN</h4>

<p>No. SDSDA: {{ $no_surat }}</p>
<p><strong>LOKASI:</strong> {{ $lokasi }}<br>
  <strong>No:</strong> 80385 PBB<br>
  <strong>Nama:</strong> Barang
</p>

<table>
  <thead>
    <tr>
      <th>No.</th>
      <th>JENIS BARANG</th>
      <th>VOLUME</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>1</td>
      <td>{{ $nama_barang }}</td>
      <td>{{ $volume }}</td>
    </tr>
  </tbody>
</table>

<p style="margin-top: 20px;">Jakarta, {{ $tanggal }}</p>



<table class="no-border" style="margin-top: 40px; width: 100%;">
  <tr>
    <td style="text-align: center;">
      Yang Menerima<br><br><br><br>
      ({{ $penerima }})
    </td>
    <td style="text-align: center;">
      Yang Mengeluarkan<br><br><br>
      @if(file_exists($ttd_pengeluar ?? ''))
      <img src="{{ $ttd_pengeluar }}" height="40">
      @endif
      <br>({{ $pengeluar }})
    </td>
  </tr>
</table>

{{-- Bagian Mengetahui --}}
<div style="text-align: center; margin-top: 60px;">
  Mengetahui<br>
  Pengurus Barang<br>
  Suku Dinas Sumber Daya Air<br><br><br>
  <b>{{ $pengurus }}</b><br>
  1997/1101021
</div>