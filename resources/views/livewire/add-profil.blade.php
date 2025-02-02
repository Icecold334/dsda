<div x-data = "signatureHandler">
    <table class="w-full border-0 border-separate border-spacing-y-4">

        @if ($tipe == 'profile')
            <tr>
                <td>

                    <label for="name">Nama</label>
                </td>
                <td>
                    <input type="text" id="name" wire:model.live="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Name" required />
                    @error('name')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
                <td rowspan="2" class="align-top text-right">
                    <label for="ttd" class="block font-semibold mb-2">Foto Profil</label>
                    <div class="flex flex-col items-end mt-2">
                        <!-- Foto Preview Container -->
                        <div x-data="{ open: false }" class="relative inline-block w-24 h-24 border rounded-lg">
                            <!-- Trigger untuk membuka modal -->
                            <div class="w-full h-full flex justify-center items-center bg-gray-200 rounded-lg cursor-pointer relative"
                                @click="open = true; document.body.classList.add('overflow-hidden')">
                                @if ($img)
                                    <button
                                        class="absolute -top-2 -right-3 z-30 bg-red-500 hover:bg-red-700 transition duration-200 text-white rounded-full p-1 leading-none w-5 h-5 text-xs flex items-center justify-center shadow"
                                        wire:click="removeImg"
                                        @click.stop="document.body.classList.remove('overflow-hidden'); open = false;">
                                        &times;
                                    </button>
                                @endif
                                <!-- Gambar preview -->
                                <img src="{{ is_string($img) ? asset('storage/usersFoto/' . $img) : ($img ? $img->temporaryUrl() : asset('img/default-pic.png')) }}"
                                    alt="Preview Image" class="w-full h-full object-cover object-center rounded-lg">

                            </div>
                            <!-- Modal untuk Preview Full -->
                            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-300"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="fixed inset-0 bg-black bg-opacity-75 flex flex-col items-center justify-center z-50"
                                @click="open = false; document.body.classList.remove('overflow-hidden')"
                                @keydown.escape.window="open = false; document.body.classList.remove('overflow-hidden')">

                                <!-- Gambar Full Preview -->
                                @if ($img)
                                    <img src="{{ is_string($img) ? asset('storage/usersFoto/' . $img) : ($img ? $img->temporaryUrl() : asset('img/default-pic.png')) }}"
                                        alt="Preview Image" class="max-w-[70rem] max-h-[70rem] mb-4" @click.stop="">

                                    <!-- Tombol Unduh -->
                                    <a href="{{ is_string($img) ? asset('storage/usersFoto/' . $img) : $img->temporaryUrl() }}"
                                        download="{{ is_string($img) ? pathinfo($img, PATHINFO_BASENAME) : $img->getClientOriginalName() }}"
                                        class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200 cursor-pointer">
                                        Unduh
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Tombol Unggah -->
                        <div class="mt-4">
                            <input type="file" wire:model.live="img" accept="image/*" class="hidden" id="imgUpload">
                            <label for="imgUpload"
                                class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200 cursor-pointer">
                                + Unggah Foto
                            </label>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td>

                    <label for="nip">NIP</label>
                </td>
                <td>
                    <input type="text" id="nip" wire:model.live="nip"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="NIP" required />
                    @error('nip')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>

            @if ($ttd)
                <tr>
                    <td colspan="3">
                        <div class="flex flex-col items-end mt-2">
                            <label for="ttd" class="block font-semibold mb-2">Tanda Tangan</label>
                            <!-- Foto Preview Container -->
                            <div x-data="{ open: false }" class="relative inline-block w-24 h-24 border rounded-lg">
                                <!-- Trigger untuk membuka modal -->
                                <div class="w-full h-full flex justify-center items-center bg-gray-200 rounded-lg cursor-pointer relative"
                                    @click="open = true; document.body.classList.add('overflow-hidden')">
                                    @if ($ttd)
                                        <button
                                            class="absolute -top-2 -right-3 z-30 bg-red-500 hover:bg-red-700 transition duration-200 text-white rounded-full p-1 leading-none w-5 h-5 text-xs flex items-center justify-center shadow"
                                            wire:click="removeTTD"
                                            @click.stop="document.body.classList.remove('overflow-hidden'); open = false;">
                                            &times;
                                        </button>
                                    @endif
                                    <!-- Gambar preview -->
                                    <img src="{{ is_string($ttd) ? asset('storage/usersTTD/' . $ttd) : ($ttd ? $ttd->temporaryUrl() : asset('img/default-pic.png')) }}"
                                        alt="Preview Image" class="w-full h-full object-cover object-center rounded-lg">

                                </div>
                                <!-- Modal untuk Preview Full -->
                                <div x-show="open" x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-300"
                                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                    class="fixed inset-0 bg-black bg-opacity-75 flex flex-col items-center justify-center z-50"
                                    @click="open = false; document.body.classList.remove('overflow-hidden')"
                                    @keydown.escape.window="open = false; document.body.classList.remove('overflow-hidden')">

                                    <!-- Gambar Full Preview -->
                                    @if ($ttd)
                                        <img src="{{ is_string($ttd) ? asset('storage/usersTTD/' . $ttd) : ($ttd ? $ttd->temporaryUrl() : asset('img/default-pic.png')) }}"
                                            alt="Preview Image" class="max-w-[70rem] max-h-[70rem] mb-4" @click.stop="">

                                        <!-- Tombol Unduh -->
                                        <a href="{{ is_string($ttd) ? asset('storage/usersTTD/' . $ttd) : $ttd->temporaryUrl() }}"
                                            download="{{ is_string($ttd) ? pathinfo($ttd, PATHINFO_BASENAME) : $ttd->getClientOriginalName() }}"
                                            class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white font-medium rounded-lg text-sm px-5 py-2.5 transition duration-200 cursor-pointer">
                                            Unduh
                                        </a>
                                    @endif
                                </div>
                            </div>
                    </td>
                </tr>
            @else
                <!-- Label -->
                <tr>
                    <td colspan="3" class="p-4 border-b border-gray-200 text-right">
                        <label for="ttd" class="block text-lg font-medium text-gray-700 mb-4">
                            Buat Tanda Tangan
                        </label>
                        <div class="flex justify-end space-x-4">
                            <button id="clearButton"
                                class="px-4 py-2 text-white bg-red-600 rounded-md hover:bg-red-700">
                                Bersihkan
                            </button>
                            <div>
                    </td>
                </tr>

                <!-- Canvas -->
                <tr>
                    <td colspan="3" class="p-4">
                        <div class="flex justify-end items-center">
                            <!-- Canvas di kanan -->
                            <div class="border border-gray-300 w-64 h-64 rounded-md">
                                <canvas id="myCanvas" wire:ignore class="w-full h-full"></canvas>
                            </div>
                        </div>
                    </td>
                </tr>
            @endif

            {{-- @dump($ttd) --}}

            <!-- Toolbar -->
            {{-- <tr>
                <td colspan="3" class="p-4">
                    <div class="flex items-center justify-center space-x-4">
                        <button id="pencilTool"
                            class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-100 focus:ring focus:ring-blue-500">Pencil</button>
                        <button id="brushTool"
                            class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-100 focus:ring focus:ring-blue-500">Brush</button>
                        <button id="eraserTool"
                            class="px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-100 focus:ring focus:ring-blue-500">Eraser</button>
                        <input type="color" id="colorPicker"
                            class="h-10 w-10 border rounded-md focus:ring focus:ring-blue-500" />
                        <select id="brushSize"
                            class="h-10 px-2 py-1 border border-gray-300 rounded-md focus:ring focus:ring-blue-500">
                            <option value="1">1px</option>
                            <option value="3">3px</option>
                            <option value="5">5px</option>
                        </select>
                    </div>
                </td>
            </tr> --}}

            <!-- Color Palette -->
            {{-- <tr>
                <td colspan="3" class="p-4">
                    <div class="flex justify-center space-x-3">
                        <div class="w-8 h-8 border-2 border-white rounded-full cursor-pointer bg-black"></div>
                        <div class="w-8 h-8 border-2 border-white rounded-full cursor-pointer bg-red-500"></div>
                        <div class="w-8 h-8 border-2 border-white rounded-full cursor-pointer bg-green-500"></div>
                        <div class="w-8 h-8 border-2 border-white rounded-full cursor-pointer bg-blue-500"></div>
                    </div>
                </td>
            </tr> --}}

            <!-- Buttons -->
            {{-- <tr>
                <td colspan="3" class="p-4">
                    <div class="flex justify-center space-x-4">
                        <button id="clearButton" class="px-4 py-2 text-white bg-red-600 rounded-md hover:bg-red-700">
                            Clear
                        </button>
                    </div>
                    <button id="saveButton" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                        Save
                    </button>
                </td>
            </tr> --}}

            @push('scripts')
                <script type="module">
                    document.addEventListener('livewire:init', () => {
                        // Tangkap event dari Livewire untuk reset canvas jika ttd dihapus
                        Livewire.on('resetCanvas', () => {
                            // Tunggu hingga canvas benar-benar tersedia di DOM
                            setTimeout(() => {
                                const canvas = document.getElementById("myCanvas");
                                if (canvas) {
                                    initializeCanvas();
                                    // console.log('Canvas berhasil di-reset');
                                } else {
                                    // console.error('Canvas tidak ditemukan!');
                                }
                            }, 100); // Beri jeda waktu jika perlu
                        });
                    });

                    initializeCanvas();

                    function initializeCanvas() {
                        const canvas = document.getElementById("myCanvas");
                        const clearButton = document.getElementById("clearButton");

                        if (!canvas || !clearButton) return;

                        canvas.width = canvas.parentElement.offsetWidth;
                        canvas.height = 256;

                        const ctx = canvas.getContext("2d");
                        ctx.clearRect(0, 0, canvas.width, canvas.height); // Bersihkan kanvas saat diinisialisasi ulang

                        let isDrawing = false;
                        let selectedTool = "brush";
                        ctx.globalCompositeOperation = "source-over"; // Default blending mode
                        ctx.lineWidth = 5; // Default brush size
                        ctx.strokeStyle = "#000000"; // Default brush color

                        function startDrawing(event) {
                            isDrawing = true;
                            ctx.beginPath();
                            draw(event);
                        }

                        function draw(event) {
                            if (!isDrawing) return;
                            const rect = canvas.getBoundingClientRect();
                            const x = event.clientX - rect.left;
                            const y = event.clientY - rect.top;
                            ctx.lineTo(x, y);
                            ctx.stroke();
                        }

                        function stopDrawing() {
                            isDrawing = false;
                            ctx.beginPath();
                        }

                        // Pastikan event listener tidak terduplikasi
                        canvas.removeEventListener("mousedown", startDrawing);
                        canvas.removeEventListener("mousemove", draw);
                        canvas.removeEventListener("mouseup", stopDrawing);

                        // Tambah ulang event listener
                        canvas.addEventListener("mousedown", startDrawing);
                        canvas.addEventListener("mousemove", draw);
                        canvas.addEventListener("mouseup", stopDrawing);

                        // Clear Canvas Button
                        clearButton.removeEventListener("click", clearCanvas);
                        clearButton.addEventListener("click", clearCanvas);

                        function clearCanvas() {
                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                        }
                    }

                    // const canvas = document.getElementById("myCanvas");
                    // canvas.width = canvas.parentElement.offsetWidth;
                    // canvas.height = 256;

                    // const ctx = canvas.getContext("2d");
                    // let isDrawing = false;

                    // // Tool Logic
                    // // let selectedTool = "pencil";
                    // // Set Default Tool to Brush
                    // let selectedTool = "brush";
                    // ctx.globalCompositeOperation = "source-over"; // Default blending mode
                    // ctx.lineWidth = 5; // Default brush size
                    // ctx.strokeStyle = "#000000"; // Default brush color

                    // function startDrawing(event) {
                    //     isDrawing = true;
                    //     ctx.beginPath();
                    //     draw(event);
                    // }

                    // function draw(event) {
                    //     if (!isDrawing) return;
                    //     const rect = canvas.getBoundingClientRect();
                    //     const x = event.clientX - rect.left;
                    //     const y = event.clientY - rect.top;
                    //     ctx.lineTo(x, y);
                    //     ctx.stroke();
                    // }

                    // function stopDrawing() {
                    //     isDrawing = false;
                    //     ctx.beginPath();
                    // }

                    // canvas.addEventListener("mousedown", startDrawing);
                    // canvas.addEventListener("mousemove", draw);
                    // canvas.addEventListener("mouseup", stopDrawing);

                    // // Clear Canvas
                    // document.getElementById("clearButton").addEventListener("click", () => {
                    //     ctx.clearRect(0, 0, canvas.width, canvas.height);
                    // });

                    // Tool Handlers
                    // document.getElementById("pencilTool").addEventListener("click", () => {
                    //     selectedTool = "pencil";
                    //     ctx.globalCompositeOperation = "source-over";
                    //     ctx.lineWidth = 1;
                    // });

                    // document.getElementById("brushTool").addEventListener("click", () => {
                    //     selectedTool = "brush";
                    //     ctx.globalCompositeOperation = "source-over";
                    //     ctx.lineWidth = 5;
                    // });

                    // document.getElementById("eraserTool").addEventListener("click", () => {
                    //     selectedTool = "eraser";
                    //     ctx.globalCompositeOperation = "destination-out";
                    // });

                    // // Color and Brush Size
                    // document.getElementById("colorPicker").addEventListener("input", (event) => {
                    //     ctx.strokeStyle = event.target.value;
                    // });

                    // document.getElementById("brushSize").addEventListener("change", (event) => {
                    //     ctx.lineWidth = event.target.value;
                    // });

                    // Save Canvas
                    // document.getElementById("saveButton").addEventListener("click", () => {
                    //     const link = document.createElement("a");
                    //     link.download = "signature.png";
                    //     link.href = canvas.toDataURL();
                    //     link.click();
                    // });
                </script>
            @endpush

            <!-- Select for LokasiStok -->
            {{-- <tr>
                <td>
                    <label for="lokasi_stok">Lokasi Stok</label>
                </td>
                <td>
                    @dump($lokasi_stok)
                    @dump($errors->first())
                    <select id="lokasi_stok" wire:model.live="lokasi_stok"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required>
                        <option value="">Pilih Lokasi Stok</option>
                        @foreach ($lokasistoks as $lokasi)
                            <option value="{{ $lokasi->id }}">
                                {{ $lokasi->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('lokasi_stok')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr> --}}

            {{-- <!-- Select for UnitKerja -->
            <tr>
                <td>
                    <label for="unit_kerja">Unit Kerja</label>
                </td>
                <td>
                    @dump($unit_kerja)
                    <select id="unit_kerja" wire:model.live="unit_kerja"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required>
                        <option value="">Pilih Unit Kerja</option>
                        @foreach ($unitkerjas as $unit)
                            @if ($unit->parent_id == null)
                                <!-- Parent Unit -->
                                <option value="{{ $unit->id }}">{{ $unit->nama }}</option>

                                <!-- Child Units -->
                                @foreach ($unitkerjas->where('parent_id', $unit->id) as $childUnit)
                                    <option value="{{ $childUnit->id }}">--- {{ $childUnit->nama }}</option>
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                    @error('unit_kerja')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr> --}}

            {{-- <tr>
                <td>

                    <label for="perusahaan">Perusahaan</label>
                </td>
                <td>
                    <input type="text" id="perusahaan" wire:model.live="perusahaan"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Perusahaan" required />
                    @error('perusahaan')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>

                    <label for="alamat">Alamat</label>
                </td>
                <td>
                    <textarea id="alamat" wire:model.live="alamat"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Masukkan Alamat" rows="2"></textarea>
                    @error('alamat')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>

                    <label for="provinsi">Provinsi</label>
                </td>
                <td>
                    <input type="text" id="provinsi" wire:model.live="provinsi"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Provinsi" required />
                    @error('provinsi')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>

                    <label for="kota">Kota</label>
                </td>
                <td>
                    <input type="text" id="kota" wire:model.live="kota"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Kota" required />
                    @error('kota')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr> --}}
        @endif
        @if ($tipe == 'phone')
            <tr>
                <td>

                    <label for="no_wa">Silahkan Masukan Nomor WhatsApp Anda yang Baru</label>
                </td>
            </tr>
            <tr>
                <td>

                    <label for="no_wa">No. WhatsApp Lama</label>
                </td>
                <td>
                    <span class="text-gray-900 text-sm">{{ $no_wa ?? '----' }}</span>
                </td>
            </tr>
            <tr>
                <td>

                    <label for="new_wa">No. WhatsApp Baru</label>
                </td>
                <td>

                    <input type="text" id="new_wa" wire:model.live="new_wa"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Misal : 08123456789" required />
                    @error('new_wa')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
        @endif
        @if ($tipe == 'email')
            <tr>
                <td>

                    <label for="email">Silahkan Masukan Email Anda yang Baru</label>
                </td>
            </tr>
            <tr>
                <td>

                    <label for="email">Email Lama</label>
                </td>
                <td>
                    <span class="text-gray-900 text-sm">{{ $email }}</span>
                </td>
            </tr>
            <tr>
                <td>

                    <label for="new_email">Email Baru</label>
                </td>
                <td>

                    <input type="text" id="new_email" wire:model.live="new_email"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="" required />
                    @error('new_email')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
        @endif
        @if ($tipe == 'password')
            <tr>
                <td colspan="2">
                    <label for="pass">Silahkan Masukan Password Lama dan Password Baru Anda pada kolom yang
                        tersedia</label>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="pass">Password Lama</label>
                </td>
                <td>

                    <input type="password" id="old_password" wire:model.live="old_password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="" required />
                    @error('old_password')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>

                    <label for="password">Password Baru</label>
                </td>
                <td>

                    <input type="password" id="password" wire:model.live="password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="" required />
                    @error('password')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>

                    <label for="password_confirmation">Ulangi Password Baru</label>
                </td>
                <td>

                    <input type="password" id="password_confirmation " wire:model.live="password_confirmation"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="" required />
                    @error('password_confirmation ')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
        @endif
        @if ($tipe == 'user')
            <tr>
                <td>

                    <label for="name">Nama</label>
                </td>
                <td colspan="2">

                    <input type="text" id="name" wire:model.live="name"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Name" required />
                    @error('name')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>

            <!-- Select for LokasiStok -->
            <tr>
                <td>
                    <label for="lokasi_stok">Lokasi Stok</label>
                </td>
                <td colspan="2">
                    <select id="lokasi_stok" wire:model.live="lokasi_stok"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required>
                        <option value="">Pilih Lokasi Stok</option>
                        @foreach ($lokasistoks as $lokasi)
                            <option value="{{ $lokasi->id }}">
                                {{ $lokasi->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('lokasi_stok')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>

            <!-- Select for UnitKerja -->
            <tr>
                <td>
                    <label for="unit_kerja">Unit Kerja</label>
                </td>
                <td colspan="2">
                    <select id="unit_kerja" wire:model.live="unit_kerja"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required>
                        <option value="">Pilih Unit Kerja</option>
                        @foreach ($unitkerjas as $unit)
                            @if (Auth::user()->id == 1)
                                @if ($unit->parent_id === null)
                                    <!-- Parent Unit -->
                                    <option value="{{ $unit->id }}">{{ $unit->nama }}</option>

                                    <!-- Child Units -->
                                    @foreach ($unitkerjas->where('parent_id', $unit->id) as $childUnit)
                                        <option value="{{ $childUnit->id }}">--- {{ $childUnit->nama }}</option>
                                    @endforeach
                                @endif
                            @else
                                <option value="{{ $unit->id }}">{{ $unit->nama }}</option>
                            @endif
                        @endforeach
                    </select>
                    @error('unit_kerja')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>

            <tr>
                <td>

                    <label for="keterangan">Keterangan</label>
                </td>
                <td colspan="2">
                    <textarea id="keterangan" wire:model.live="keterangan"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                        placeholder="Masukkan keterangan" rows="2"></textarea>
                    @error('keterangan')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror

                </td>
            </tr>
            <tr>
                <td>

                    <label for="username">Username</label>
                </td>
                <td colspan="2">
                    <input type="text" id="username" wire:model.live="username"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Username" required />
                    @error('username')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>

                    <label for="email">Email</label>
                </td>
                <td colspan="2">
                    <input type="email" id="email" wire:model.live="email"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Email" required />
                    @error('email')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>

                    <label for="password">Password</label>
                </td>
                <td colspan="2">

                    <input type="password" id="password" wire:model.live="password"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Password" required />
                    @error('password')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>

                    <label for="password_confirmation">Ulangi Password</label>
                </td>
                <td colspan="2">

                    <input type="password" id="password_confirmation " wire:model.live="password_confirmation"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        placeholder="Ulangi Password" required />
                    @error('password_confirmation ')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
            <tr>
                <td>
                    <label for="roles">Jabatan</label>
                </td>
                <td>
                    <div>
                        @foreach ($roles as $role)
                            <div class="flex items-center mb-2">
                                <input type="checkbox" id="role_{{ $role->id }}" value="{{ $role->name }}"
                                    wire:model.live="selectedRoles"
                                    class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                                <label for="role_{{ $role->id }}"
                                    class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">
                                    {{-- {{ ucwords(str_replace('_', ' ', $role->name)) }} --}}
                                    {{ formatRole($role->name) }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('selectedRoles')
                        <span class="text-sm text-red-500 font-semibold">{{ $message }}</span>
                    @enderror
                </td>
            </tr>
        @endif

    </table>
    <div class="flex justify-end">
        @if ($tipe == 'user')
            @if ($id)
                <button type="button"
                    onclick="confirmRemove('Apakah Anda yakin ingin menghapus user ini?', () => @this.call('removeProfil'))"
                    {{-- wire:click="removeProfil" --}}
                    class="text-danger-900 bg-danger-100 hover:bg-danger-600 px-5 py-2.5 me-2 mb-2 hover:text-white rounded-md border transition duration-200"
                    data-tooltip-target="tooltip-delete-{{ $id }}"><i
                        class="fa-solid fa-trash"></i></button>
                <div id="tooltip-delete-{{ $id }}" role="tooltip"
                    class="absolute z-10 invisible inline-block px-5 py-2.5 me-2 mb-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Hapus Pengguna ini
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            @endif
        @endif
        <button type="button"
            @if ($tipe == 'profile' && empty($ttd)) @click="generateTTD" @else wire:click="saveProfil" @endif
            class="text-primary-900 bg-primary-100 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Simpan</button>
        @can('pengguna_verifikasi_pengguna')
            @if ($tipe == 'user' && $other && !$other?->email_verified_at)
                <button type="button"
                    onclick="confirmRemove('Apakah Anda yakin ingin memverifikasi akun ini?', () => @this.call('verify'))"
                    class="text-warning-900 bg-warning-300 hover:bg-warning-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Verifikasi</button>
            @endif
        @endcan

    </div>
    @push('scripts')
        <script type="module">
            document.addEventListener('alpine:init', () => {
                Alpine.data('signatureHandler', () => ({
                    generateTTD() {
                        const canvas = document.getElementById("myCanvas");
                        const ttd = canvas.toDataURL('image/png');

                        // Hanya kirim TTD jika ada perubahan
                        if (ttd) {
                            this.$wire.dispatch('upload', {
                                detail: ttd
                            });
                        }

                        // Tambahkan log jika perlu
                        // console.log('Tanda tangan dikirim ke Livewire:', ttd);
                    }
                }));
            });
        </script>
    @endpush
</div>
