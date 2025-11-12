<div>
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Master Data Program</h2>
            <p class="text-gray-600">Kelola relasi program dengan unit kerja. Hanya dapat melakukan update, tidak dapat
                tambah/hapus data.</p>
        </div>

        <!-- Filter Section -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Search Input -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Program</label>
                <input type="text" wire:model.live.debounce.500ms.debounce.300ms="search" id="search"
                    placeholder="Cari berdasarkan nama program atau kode..."
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            <!-- Unit Kerja Filter -->
            <div>
                <label for="unit-filter" class="block text-sm font-medium text-gray-700 mb-2">Filter Unit Kerja</label>
                <selectwire:model.live.debounce.500ms="selectedUnitId" id="unit-filter"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Unit Kerja</option>
                    @foreach($unitKerjas as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                    @endforeach
                    </select>
            </div>
        </div>

        <!-- Success Message -->
        @if (session()->has('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
        @endif

        <!-- Programs Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Kode Program
                        </th>
                        <th
                            class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Program
                        </th>
                        <th
                            class="px-6 py-3 border-b border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Unit Kerja Saat Ini
                        </th>
                        <th
                            class="px-6 py-3 border-b border-gray-200 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($programs as $program)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $program->kode ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $program->program }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            @if($editingProgram === $program->id)
                            <!-- Edit Mode -->
                            <select wire:model="newUnitId"
                                class="w-full px-2 py-1 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Pilih Unit Kerja</option>
                                @foreach($unitKerjas as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                                @endforeach
                            </select>
                            @error('newUnitId')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            @else
                            <!-- Display Mode -->
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $program->parent ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $program->parent ? $program->parent->nama : 'Belum Ditentukan' }}
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                            @if($editingProgram === $program->id)
                            <!-- Edit Actions -->
                            <div class="flex justify-center space-x-2">
                                <button wire:click="updateProgram"
                                    class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs font-medium">
                                    Simpan
                                </button>
                                <button wire:click="cancelEdit"
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-xs font-medium">
                                    Batal
                                </button>
                            </div>
                            @else
                            <!-- Normal Actions -->
                            <button wire:click="editProgram({{ $program->id }})"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs font-medium">
                                Edit Unit
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            @if($search || $selectedUnitId)
                            Tidak ada program yang sesuai dengan filter.
                            @else
                            Belum ada data program.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $programs->links() }}
        </div>

        <!-- Info Box -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>• Halaman ini hanya dapat diakses oleh superadmin</p>
                        <p>• Anda hanya dapat mengubah unit kerja untuk program yang sudah ada</p>
                        <p>• Tidak dapat menambah atau menghapus program dari halaman ini</p>
                        <p>• Program yang gagal terinsert dapat diassign ulang ke unit kerja yang tepat</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>