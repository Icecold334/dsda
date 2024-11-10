        <button onclick="confirmApproval()"
            class="text-primary-900 bg-primary-100 border border-primary-600 hover:bg-primary-600 hover:text-white  font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 transition duration-200">Setujui</button>
        @push('scripts')
            <script>
                // document.addEventListener('DOMContentLoaded', function() {
                function confirmApproval() {
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Anda akan menyetujui pengiriman ini!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Setujui'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            @this.call('approval', 'detail-pengiriman-stok');
                        }
                    });
                }
                // })
            </script>
        @endpush
