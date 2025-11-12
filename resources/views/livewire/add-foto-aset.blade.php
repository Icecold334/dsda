<x-card title="Foto" class="mb-3">
    <div class="flex">
        <div x-data="{ open: false }" class="relative w-2/5 px-5">
            <!-- Trigger to open modal -->
            <div class="w-60 h-60 overflow-hidden relative flex justify-center rounded-lg cursor-pointer"
                @click="open = true; document.body.classList.add('overflow-hidden')">
                @if ($img)
                <button
                    class="absolute top-2 right-2 bg-red-500 hover:bg-red-700 transition duration-200 text-white rounded-full p-1 text-lg leading-none h-8 w-8 flex items-center justify-center shadow"
                    wire:click="removeImg"
                    @click.stop="document.body.classList.remove('overflow-hidden'); open = false;">
                    &times;
                </button>
                @endif
                <img src="{{ $img ? $img->temporaryUrl() : asset('img/default-pic.png') }}" alt="Preview Image"
                    class="w-full h-full object-cover object-center">
            </div>

            <!-- Modal for full image preview -->
            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class=" fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50"
                @click="open = false; document.body.classList.remove('overflow-hidden')"
                @keydown.escape.window="open = false; document.body.classList.remove('overflow-hidden')">
                <img src="{{ $img ? $img->temporaryUrl() : asset('img/default-pic.png') }}"
                    class="max-w-full max-h-full " @click.stop="">
            </div>
        </div>



        <div class="w-3/5 px-5">
            <div class="mb-3 text-sm">
                Anda bisa mengunggah satu foto utama aset di sini.
            </div>
            <input type="file" wire:model.live.debounce.500ms="img" accept="image/*" class="hidden" id="imgUpload">
            <label for="imgUpload"
                class="text-primary-900 bg-primary-100 hover:bg-primary-600 my-2 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200 cursor-pointer">
                + Unggah Foto
            </label>
        </div>
    </div>
    @push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });

                window.addEventListener('swal:error', event => {

                    Toast.fire({
                        icon: 'error',
                        text: event.detail[0].text,
                    });
                });
            });
    </script>
    @endpush
</x-card>