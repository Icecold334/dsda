<x-body>
    <div class="flex justify-between py-2 mb-3">
        <div>
            <h1 class="text-2xl font-bold text-primary-900">
                <i class="fas fa-edit"></i> Edit Permintaan (Admin)
            </h1>
            <p class="text-gray-600 text-sm mt-1">Edit permintaan tanpa batasan sebagai admin</p>
        </div>
        <div>
            <a href="{{ url()->previous() }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Admin Warning Alert -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    <strong>Mode Admin:</strong> Anda sedang mengedit dalam mode admin tanpa batasan status atau
                    kepemilikan.
                </p>
            </div>
        </div>
    </div>

    <!-- Permintaan Info Card -->
    <x-card title="Informasi Permintaan" class="mb-6">
        <div class="flex justify-between items-start mb-4">
            <h6 class="text-lg font-semibold text-primary-900">
                <i class="fas fa-info-circle mr-2"></i>Detail Permintaan
            </h6>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                {{ $permintaan->status == 'approved' ? 'bg-green-100 text-green-800' :
    ($permintaan->status == 'pending' ? 'bg-yellow-100 text-yellow-800' :
        ($permintaan->status == 'rejected' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                {{ $permintaan->status_teks ?? 'Tidak diketahui' }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-3">
                <div>
                    <span class="text-sm font-medium text-gray-700">Kode Permintaan:</span>
                    <p class="text-sm text-gray-900 mt-1">{{ $permintaan->nodin }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700">Pemohon:</span>
                    <p class="text-sm text-gray-900 mt-1">{{ $permintaan->user->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700">Unit Asal:</span>
                    <p class="text-sm text-gray-900 mt-1">{{ $permintaan->user->unitKerja->nama ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="space-y-3">
                <div>
                    <span class="text-sm font-medium text-gray-700">Tanggal Dibuat:</span>
                    <p class="text-sm text-gray-900 mt-1">{{ $permintaan->created_at->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700">Terakhir Diupdate:</span>
                    <p class="text-sm text-gray-900 mt-1">{{ $permintaan->updated_at->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <span class="text-sm font-medium text-gray-700">Status RAB:</span>
                    <p class="text-sm text-gray-900 mt-1">
                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                            {{ $permintaan->rab_id ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $permintaan->rab_id ? 'Dengan RAB' : 'Tanpa RAB' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </x-card>

    <!-- Edit Form -->
    @livewire('admin-edit-permintaan', ['permintaan' => $permintaan])
</x-body>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Flash message auto-hide
            setTimeout(function () {
                $('.alert:not(.alert-warning)').fadeOut();
            }, 5000);
        });
    </script>
@endpush