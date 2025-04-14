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

<p>
  Sehubungan dengan Sub Kegiatan Operasi dan Pemeliharaan Sistem Drainase Perkotaan Tahun Anggaran 2025 di Wilayah Kota
  Administrasi Jakarta Timur, bersama ini saya mengajukan Permohonan Bahan Material untuk lokasi sebagai berikut:
</p>

<p><strong>Lokasi Pekerjaan:</strong> {{ $lokasi }}<br>
  <strong>Detail Lokasi:</strong> {{ $detailLokasi }}<br>
  <strong>Kecamatan:</strong> {{ $kecamatan }}
</p>

<table>
  <thead>
    <tr>
      <th style="width: 40px;">No</th>
      <th>Nama Barang</th>
      <th>Volume</th>
      <th>Satuan</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($barang as $i => $b)
    <tr>
      <td align="center" style="width: 40px;">{{ $i + 1 }}</td>
      <td>{{ $b['nama'] }}</td>
      <td align="center">{{ $b['volume'] }}</td>
      <td align="center">{{ $b['satuan'] }}</td>
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
    <td style="border:none; width:50%; text-align:center;">
      <strong>Pemohon,</strong><br>
      Kepala Satuan Pelaksana<br>
      Kecamatan Makasar<br>
      Suku Dinas Sumber Daya Air<br>
      Kota Administrasi Jakarta Timur<br><br>

      {{-- Blok tanda tangan + nama --}}
      <table align="center" style="border:none; margin-top: 10px;">
        <tr>
          <td style="border:none; text-align:center;">
            @if(file_exists($ttd_pemohon))
            <img src="{{ $ttd_pemohon }}" width="120" style="margin-bottom: 5px;" alt="TTD">
            @endif
          </td>
        </tr>
        <tr>
          <td style="border:none; text-align:center;">
            <strong>{{ $pemohon }}</strong><br>
            NIP 46546465
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>