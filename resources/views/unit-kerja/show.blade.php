<x-body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Detail Unit Kerja</span>
                        <a href="{{ route('unit-kerja.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                    </div>

                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th width="200">Nama Unit Kerja</th>
                                <td>{{ $unitKerja->nama }}</td>
                            </tr>
                            <tr>
                                <th>Parent Unit Kerja</th>
                                <td>{{ $unitKerja->parent_id ? $unitKerja->parent->nama : 'Unit Kerja Utama' }}</td>
                            </tr>
                            <tr>
                                <th>Keterangan</th>
                                <td>{{ $unitKerja->keterangan }}</td>
                            </tr>
                            <tr>
                                <th>Dibuat Pada</th>
                                <td>{{ $unitKerja->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <th>Diperbarui Pada</th>
                                <td>{{ $unitKerja->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        </table>

                        <div class="mt-3 d-flex">
                            @can('unit_kerja.update')
                                <a href="{{ route('unit-kerja.edit', $unitKerja->id) }}"
                                    class="btn btn-warning me-2">Edit</a>
                            @endcan
                            @can('unit_kerja.delete')
                                <form action="{{ route('unit-kerja.destroy', $unitKerja->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus unit kerja ini?')">Hapus</button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-body>