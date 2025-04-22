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

<h3 style="text-align: center;">
    PEMERINTAH PROVINSI DKI JAKARTA<br>
    DINAS SUMBER DAYA AIR
</h3>
<p style="text-align: center;">
    Jalan Kembangan Raya No. 2 Blok B Lt. 6 Telp. (021) 58356234
</p>

<h4 style="text-align: center;">{{ $judul }}</h4>
@php
    $adaAset = collect($items)->contains(fn($item) => !empty($item['nama_aset']));
    $adaApprove = collect($items)->contains(fn($item) => !empty($item['jumlah_approve']));
    $adaDriver = collect($items)->contains(fn($item) => !empty($item['driver_name']));
    $adaVoucher = collect($items)->contains(fn($item) => !empty($item['voucher_name']));
    $adaBarang = collect($items)->contains(fn($item) => !empty($item['nama_barang']));
    $adaJumlah = collect($items)->contains(fn($item) => !empty($item['jumlah']));
    $adaKeterangan = collect($items)->contains(fn($item) => !empty($item['keterangan']));
    $adaStatus = collect($items)->contains(fn($item) => !empty($item['status']));
    $adaAsetApprove = collect($items)->contains(fn($item) => !empty($item['approved_aset_name']));
    $adaJumlahOrang = collect($items)->contains(fn($item) => !empty($item['jumlah_orang']));
    $adaWaktu = collect($items)->contains(fn($item) => !empty($item['waktu']));
    $adaWaktuApprove = collect($items)->contains(fn($item) => !empty($item['approved_waktu']));
@endphp
<p><strong>No. Surat:</strong> {{ $no_surat }}</p>
<p><strong>Kategori:</strong> {{ $lokasi }}</p>
<p><strong>Tanggal:</strong> {{ $tanggal }}</p>
@if (!empty($kdo_aset))
    <p><strong>KDO:</strong> {{ $kdo_aset }}</p>
    <p><strong>Tanggal Masuk:</strong> {{ $tanggal_masuk }}</p>
    <p><strong>Tanggal Keluar:</strong> {{ $tanggal_keluar }}</p>
@endif
@if (!empty($ruang))
    <p><strong>Ruang :</strong> {{ $ruang }}</p>
    <p><strong>Jumlah Peserta:</strong> {{ $jumlah_peserta }}</p>
@endif
<p><strong>Unit:</strong> {{ $unit }}</p>
<p><strong>Sub Unit:</strong> {{ $sub_unit }}</p>
<p><strong>Keterangan:</strong> {{ $keterangan }}</p>
<p><strong>Daftar Permintaan</p>
<table>
    <thead>
        <tr>
            <th>No.</th>
            @if ($adaBarang)
                <th>Barang</th>
            @endif
            @if ($adaAset)
                <th>Aset/KDO</th>
            @endif
            @if ($adaJumlah)
                <th>Jumlah</th>
            @endif
            @if ($adaApprove)
                <th>Jumlah Disetujui</th>
            @endif
            @if ($adaDriver)
                <th>Nama Driver</th>
            @endif
            @if ($adaVoucher)
                <th>Nama Voucher</th>
            @endif
            @if ($adaKeterangan)
                <th>Keterangan</th>
            @endif
            @if ($adaStatus)
                <th>Status</th>
            @endif
            @if ($adaAsetApprove)
                <th>Aset/KDO Disetujui</th>
            @endif
            @if ($adaWaktu)
                <th>Waktu</th>
            @endif
            @if ($adaWaktuApprove)
                <th>Waktu Disetujui</th>
            @endif
            @if ($adaJumlahOrang)
                <th>Jumlah Orang</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                @if ($adaBarang)
                    <td>{{ $item['nama_barang'] ?? '-' }}</td>
                @endif
                @if ($adaAset)
                    <td>{{ $item['nama_aset'] ?? '-' }}</td>
                @endif
                @if ($adaJumlah)
                    <td>{{ $item['jumlah'] ?? '-' }}</td>
                @endif
                @if ($adaApprove)
                    <td>{{ $item['jumlah_approve'] ?? '-' }}</td>
                @endif
                @if ($adaDriver)
                    <td>{{ $item['driver_name'] ?? '-' }}</td>
                @endif
                @if ($adaVoucher)
                    <td>{{ $item['voucher_name'] ?? '-' }}</td>
                @endif
                @if ($adaKeterangan)
                    <td>{{ $item['keterangan'] ?? '-' }}</td>
                @endif
                @if ($adaStatus)
                    <td>{{ $item['status'] ?? '-' }}</td>
                @endif
                @if ($adaAsetApprove)
                    <td>{{ $item['approved_aset_name'] ?? '-' }}</td>
                @endif
                @if ($adaWaktu)
                    <td>{{ $item['waktu'] ?? '-' }}</td>
                @endif
                @if ($adaWaktu)
                    <td>{{ $item['approved_waktu'] ?? '-' }}</td>
                @endif
                @if ($adaJumlahOrang)
                    <td>{{ $item['jumlah_orang'] ?? '-' }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
<br><br>
<br><br>


{{-- Tanda Tangan --}}
<table class="no-border" style="margin-top: 50px; width: 100%;">

    <tr>
        <td style="text-align: center;">
            Pemohon<br><br>
            @if (!empty($ttd_pemohon))
                <img src="{{ $ttd_pemohon }}" height="40"><br>
            @endif
            ({{ $pemohon ?? 'N/A' }})
        </td>
        <td style="text-align: center;">
            Persetujuan<br><br>
            @if (!empty($ttd_persetujuan1))
                <img src="{{ $ttd_persetujuan1 }}" height="40"><br>
            @endif
            ({{ $persetujuan1 ?? 'N/A' }})
        </td>
    </tr>
    @if (!empty($persetujuan2))
        <tr>
            <td colspan="2" style="text-align: center; padding-top: 50px;">
                Persetujuan <br><br>
                @if (!empty($ttd_persetujuan2))
                    <img src="{{ $ttd_persetujuan2 }}" height="40"><br>
                @endif
                ({{ $persetujuan2 ?? 'N/A' }})
            </td>
        </tr>
    @endif
</table>
