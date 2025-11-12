<td class="text-left" colspan="100%">
    <div>
        <button onclick="showPengembalianModal({{ $permintaan->id }})"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-800">
            Kembalikan
        </button>
        <input type="file" id="foto-{{ $permintaan->id }}" class="hidden" accept="image/*"
            wire:model.live.debounce.500ms="fotoPengembalian">
    </div>
</td>

@push('scripts')
<script type="module">
    window.showPengembalianModal = function(id) {
            Swal.fire({
                title: 'Unggah Foto Pengembalian',
                html: `
    <button id="open-file" class="swal2-confirm swal2-styled">Pilih Foto</button>
    <span id="file-name" style="display: block; margin-top: 10px;"></span>
    <textarea id="keterangan" placeholder="Masukkan keterangan pengembalian..." rows="3"
        style="width: 100%; margin-top: 10px; padding: 5px; border: 1px solid #ccc; border-radius: 5px;"></textarea>
    <div id="swal-error" style="color: red; font-size: 0.8rem; margin-top: 0.5rem;"></div>
`,
                showCancelButton: true,
                confirmButtonText: 'Kirim',
                cancelButtonText: 'Batal',
                didOpen: () => {
                    const hiddenInput = document.getElementById('foto-' + id);
                    const openFileBtn = document.getElementById('open-file');
                    const fileNameSpan = document.getElementById('file-name');

                    openFileBtn.addEventListener('click', () => {
                        hiddenInput.click();
                    });

                    hiddenInput.addEventListener('change', (e) => {
                        const file = e.target.files[0];
                        if (file) {
                            fileNameSpan.textContent = file.name;
                        }
                    });
                },
                preConfirm: () => {
                    const file = document.getElementById('foto-' + id).files[0];
                    if (!file) {
                        document.getElementById('swal-error').textContent =
                            "Silakan unggah foto terlebih dahulu.";
                        return false;
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const keterangan = document.getElementById('keterangan').value;
                    setTimeout(() => {
                        @this.set('keteranganPengembalian', keterangan);
                        @this.call('simpanPengembalian');
                    }, 300);
                }
            });
        }
</script>
@endpush