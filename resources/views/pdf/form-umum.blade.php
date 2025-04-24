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
    $adaKdo = collect($items)->contains(fn($item) => !empty($item['nama_kdo']));
    $adaApprove = collect($items)->contains(fn($item) => !empty($item['jumlah_approve']));
    $adaDriver = collect($items)->contains(fn($item) => !empty($item['driver_name']));
    $adaVoucher = collect($items)->contains(fn($item) => !empty($item['voucher_name']));
    $adaBarang = collect($items)->contains(fn($item) => !empty($item['nama_barang']));
    $adaJumlah = collect($items)->contains(fn($item) => !empty($item['jumlah']));
    $adaKeterangan = collect($items)->contains(fn($item) => !empty($item['keterangan']));
    $adaStatus = collect($items)->contains(fn($item) => !empty($item['status_kdo']));
    $adaAsetApprove = collect($items)->contains(fn($item) => !empty($item['approved_aset_name']));
    $adaRuangApprove = collect($items)->contains(fn($item) => !empty($item['approved_ruang_name']));
    $adaKdoApprove = collect($items)->contains(fn($item) => !empty($item['approved_Kdo_name']));
    $adaJumlahOrang = collect($items)->contains(fn($item) => !empty($item['jumlah_orang']));
    $adaWaktu = collect($items)->contains(fn($item) => !empty($item['waktu']));
    $adaWaktuApprove = collect($items)->contains(fn($item) => !empty($item['approved_waktu']));

    function getStatusStyle($status)
    {
        return match ($status) {
            'dibatalkan' => 'background-color: #e0e0e0; color: #6c757d;', // Abu-abu
            'selesai' => 'background-color: #007bff; color: #fff;', // Biru
            'siap digunakan atau siap diambil' => 'background-color: #17a2b8; color: #fff;', // Biru muda
            'sudah diambil' => 'background-color: #17a2b8; color: #fff;',
            'dipinjam' => 'background-color: #17a2b8; color: #fff;',
            'diproses' => 'background-color: #ffc107; color: #000;', // Kuning
            'disetujui' => 'background-color: #28a745; color: #fff;', // Hijau
            'ditolak' => 'background-color: #dc3545; color: #fff;', // Merah
            default => 'background-color: #ffffff; color: #000;',
        };
    }
@endphp
<p><strong>No. Surat:</strong> {{ $no_surat }}</p>
<p><strong>Kategori:</strong> {{ $lokasi }}</p>
<p><strong>Tanggal:</strong> {{ $tanggal }}</p>
<p><strong>Status:</strong> <span
        style="padding: 4px 8px; border-radius: 4px; {{ getStatusStyle($status) }}">{{ $status }}</span></p>
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
                <th>Aset</th>
            @endif
            @if ($adaKdo)
                <th>KDO</th>
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
                <th>Aset Disetujui</th>
            @endif
            @if ($adaRuangApprove)
                <th>Ruang Disetujui</th>
            @endif
            @if ($adaKdoApprove)
                <th>KDO Disetujui</th>
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
                @if ($adaKdo)
                    <td>{{ $item['nama_kdo'] ?? '-' }}</td>
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
                    <td>{{ $item['status_kdo'] ?? '-' }}</td>
                @endif
                @if ($adaAsetApprove)
                    <td>{{ $item['approved_aset_name'] ?? '-' }}</td>
                @endif
                @if ($adaRuangApprove)
                    <td>{{ $item['approved_ruang_name'] ?? '-' }}</td>
                @endif
                @if ($adaKdoApprove)
                    <td>{{ $item['approved_kdo_name'] ?? '-' }}</td>
                @endif
                @if ($adaWaktu)
                    <td>{{ $item['waktu'] ?? '-' }}</td>
                @endif
                @if ($adaWaktuApprove)
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
{{-- Tabel 1: Pemohon & Persetujuan 1 --}}
<table class="no-border" style="margin-top: 50px; width: 100%;">
    <tr>
        {{-- Pemohon --}}
        <td style="text-align: center; width: 50%;">
            Mengetahui,<br>Pemohon<br><br>
            @if (!empty($ttd_pemohon))
                <img src="{{ $ttd_pemohon }}" height="40"><br>
            @endif
            ({{ $pemohon ?? 'N/A' }})
        </td>

        {{-- Persetujuan 1 --}}
        <td style="text-align: center; width: 50%;">
            Disetujui oleh,<br>{{ $jabatan_persetujuan1 ?? 'Pejabat' }}<br><br>
            @if (!empty($ttd_persetujuan1))
                <img src="{{ $ttd_persetujuan1 }}" height="40"><br>
            @endif
            ({{ $persetujuan1 ?? 'N/A' }})
        </td>
    </tr>
</table>
<br><br><br>
{{-- Tabel 2: Persetujuan 2 & Kepala Subbagian Umum --}}
<table class="no-border" style="margin-top: 80px; width: 100%;">
    <tr>
        {{-- Persetujuan 2 --}}
        <td style="text-align: center; width: 50%;">
            @if (!empty($persetujuan2))
                Disetujui oleh,<br>{{ $jabatan_persetujuan2 ?? 'Persetujuan Tambahan' }}<br><br>
                @if (!empty($ttd_persetujuan2))
                    <img src="{{ $ttd_persetujuan2 }}" height="40"><br>
                @endif
                ({{ $persetujuan2 ?? 'N/A' }})
            @else
                <br><br><br>
            @endif
        </td>

        {{-- Kepala Subbagian --}}
        <td style="text-align: center; width: 50%;">
            @if (!empty($kepala_subbagian_umum))
                Mengetahui,<br>{{ $jabatan_kepala_subbagian_umum ?? 'Kepala Subbagian Umum' }}<br><br>
                @if (!empty($ttd_kepala_subbagian_umum))
                    <img src="{{ $ttd_kepala_subbagian_umum }}" height="40"><br>
                @endif
                ({{ $kepala_subbagian_umum ?? 'N/A' }})
            @endif
        </td>
    </tr>
</table>
