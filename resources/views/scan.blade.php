<x-body>
    <h1>Hasil Scan Aset</h1>

    <p><strong>System Code:</strong> {{ $aset->systemcode }}</p>
    <p><strong>Nama Aset:</strong> {{ $aset->nama }}</p>
    <p><strong>Keterangan:</strong> {{ $aset->merk->nama }}</p>

    <hr>

    <h2>Informasi Pengguna</h2>
    <p><strong>User ID:</strong> {{ $user->id }}</p>
    <p><strong>Nama Pengguna:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
</x-body>
