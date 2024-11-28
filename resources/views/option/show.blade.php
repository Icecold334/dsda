<x-body>
    <div class="flex justify-between">
        <div class="text-4xl font-regular mb-6 text-primary-600 ">{{ $formattedRole }}</div>
        <div>
            <div class="flex space-x-2">
                <a href="{{ route('option.index') }}"
                    class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
                <div>
                    <livewire:delete-role :model="$option" />
                </div>
            </div>
        </div>
    </div>
    {{-- @dump($option->id) --}}
    <div>
        <livewire:permission-show :option="$option->id" />
    </div>
</x-body>
