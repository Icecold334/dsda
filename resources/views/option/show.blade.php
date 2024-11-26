<x-body>
    <div class="flex justify-between">
        {{-- <div class="text-5xl font-regular mb-6 text-primary-600 ">{{ $option->name }}</div> --}}
        <div>
            <a href="{{ route('option.index') }}"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Kembali</a>
            {{-- <a data-tooltip-target="tooltip-Edit" href="{{ route('roles.edit', ['roles' => $roles->id]) }}"
                class="text-primary-900 bg-white border-2 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"><i
                    class="fa-solid fa-pen"></i></a>
            <div id="tooltip-Edit" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Edit roles
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
            <a data-tooltip-target="tooltip-Nonaktif"
                class="text-primary-900 bg-white border-2 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200"
                data-modal-target="nonaktifModal" data-modal-toggle="nonaktifModal">
                <i class="fa-solid fa-boxes-packing"></i>
            </a>
            <div id="tooltip-Nonaktif" role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Nonaktifkan roles ini, yaitu saat roles ini dijual atau tidak dimiliki lagi.
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div> --}}
        </div>
    </div>
</x-body>
