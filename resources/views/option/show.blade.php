<x-body>
    <div class="flex justify-between">
        <div class="text-4xl font-regular mb-6 text-primary-600 ">{{ $formattedRole }}</div>
        <div>
            <div class="flex space-x-2">
                <a href="{{ route('option.index') }}"
                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
                <div>
                    {{-- <livewire: :model="$option" /> --}}
                    <button
                        class="text-primary-900 bg-white border-2 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>

    </div>
</x-body>
