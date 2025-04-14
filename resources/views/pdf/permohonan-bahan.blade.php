<style>
  body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 11px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  th,
  td {
    border: 1px solid #000;
    padding: 4px;
  }
</style>

<p style="text-align:right">Jakarta, {{ $tanggal }}</p>

<p><strong>Nomor:</strong> 091/PBM/MKS/III/2025<br>
  <strong>Hal:</strong> Permohonan Bahan Material
</p>

<p>Kepada<br>
  Yth. Kepala Suku Dinas Sumber Daya Air<br>
  Kota Administrasi Jakarta Timur</p>

<p>Sehubungan dengan Sub Kegiatan Operasi dan Pemeliharaan Sistem Drainase Perkotaan Tahun Anggaran 2025
  di Wilayah Kota Administrasi Jakarta Timur, bersama ini saya mengajukan Permohonan Bahan Material untuk lokasi sebagai
  berikut:</p>

<p><strong>Lokasi Pekerjaan:</strong> {{ $lokasi }}<br>
  <strong>Detail Lokasi:</strong> {{ $detailLokasi }}<br>
  <strong>Kecamatan:</strong> {{ $kecamatan }}
</p>

<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Nama Barang</th>
      <th>Volume</th>
      <th>Satuan</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($barang as $i => $b)
    <tr>
      <td>{{ $i + 1 }}</td>
      <td>{{ $b['nama'] }}</td>
      <td>{{ $b['volume'] }}</td>
      <td>{{ $b['satuan'] }}</td>
    </tr>
    @endforeach
  </tbody>
</table>

<br><br>
<table style="border:none">
  <tr>
    <td style="border:none; width:50%; text-align:center">
      Mengetahui,<br>
      Kepala Seksi Pemeliharaan Drainase<br><br><br><br>
      <strong>{{ $mengetahui }}</strong>
    </td>
    <td style="border:none; width:50%; text-align:center">
      Pemohon,<br>
      Kepala Satuan Pelaksana<br><br><br><br>
      <strong>{{ $pemohon }}</strong>
    </td>
  </tr>
</table>