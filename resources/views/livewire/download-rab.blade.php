<div class="relative inline-block" x-data="{ open: false }">
    <!-- Tombol Utama -->
    <button @click="open = !open"
        class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200 flex items-center">
        Unduh {{ $Rkb }}
        <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd"></path>
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div x-show="open" @click.away="open = false" x-transition
        class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg border border-gray-200 z-50">
        <div class="py-1">
            <button wire:click="download(true)" @click="open = false"
                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                <i class="fas fa-signature mr-2"></i>
                Unduh dengan TTD
            </button>
            <button wire:click="download(false)" @click="open = false"
                class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center">
                <i class="fas fa-file-alt mr-2"></i>
                Unduh tanpa TTD
            </button>
        </div>
    </div>
</div>