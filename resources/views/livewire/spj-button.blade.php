<td class="text-left" colspan="100%">
    <div>
        <button onclick="showFileModal({{ $permintaan->id }})"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-800">
            + Unggah File SPJ
        </button>
        <input type="file" id="file-{{ $permintaan->id }}" class="hidden" accept="application/pdf" wire:model.live="file">
    </div>
</td>

@push('scripts')
    <script type="module">
        window.showFileModal = function(id) {
            Swal.fire({
                title: 'Unggah File SPJ',
                html: `
    <button id="open-file" class="swal2-confirm swal2-styled">Pilih File</button>
    <span id="file-name" style="display: block; margin-top: 10px;"></span>
    <textarea id="keterangan" placeholder="Masukkan keterangan..." rows="3"
        style="width: 100%; margin-top: 10px; padding: 5px; border: 1px solid #ccc; border-radius: 5px;"></textarea>
    <div id="swal-error" style="color: red; font-size: 0.8rem; margin-top: 0.5rem;"></div>
`,
                showCancelButton: true,
                confirmButtonText: 'Kirim',
                cancelButtonText: 'Batal',
                didOpen: () => {
                    const hiddenInput = document.getElementById('file-' + id);
                    const openFileBtn = document.getElementById('open-file');
                    const fileNameSpan = document.getElementById('file-name');

                    openFileBtn.addEventListener('click', () => hiddenInput.click());

                    hiddenInput.addEventListener('change', (e) => {
                        const file = e.target.files[0];
                        if (file) {
                            fileNameSpan.textContent = file.name;
                            document.getElementById('swal-error').textContent = '';
                        }
                    });
                },
                preConfirm: () => {
                    const fileInput = document.getElementById('file-' + id);
                    const file = fileInput.files[0];
                    const errorEl = document.getElementById('swal-error');

                    if (!file) {
                        errorEl.textContent = "Silakan unggah file terlebih dahulu.";
                        return false;
                    }

                    const validExt = ['pdf'];
                    const ext = file.name.split('.').pop().toLowerCase();

                    if (!validExt.includes(ext)) {
                        errorEl.textContent = "Hanya file PDF yang diperbolehkan.";
                        return false;
                    }

                    if (file.size > 2 * 1024 * 1024) {
                        errorEl.textContent = "Ukuran file maksimal 2MB.";
                        return false;
                    }

                    return true;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const keterangan = document.getElementById('keterangan').value;
                    setTimeout(() => {
                        @this.set('keterangan', keterangan);
                        @this.call('simpan');
                    }, 300);
                }
            });
        }
    </script>
@endpush
