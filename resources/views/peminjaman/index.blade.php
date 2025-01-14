<x-body>
    <div class="mx-auto p-4">
        <h2 class="text-lg font-bold">KALENDER PEMINJAMAN ASET</h2>

        <div class="flex items-center justify-between mb-4">
            <button id="prevMonth" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-l">
                Sebelumnya
            </button>
            <h2 id="currentMonth" class="text-xl font-bold p-8"></h2>
            <button id="nextMonth" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-r">
                Selanjutnya
            </button>
        </div>

        <div class="grid grid-cols-7 bg-blue-500 text-white">
            <div class="text-center font-bold border px-4 py-2">Minggu</div>
            <div class="text-center border font-bold px-4 py-2">Senin</div>
            <div class="text-center border font-bold px-4 py-2">Selasa</div>
            <div class="text-center border font-bold px-4 py-2">Rabu</div>
            <div class="text-center border font-bold px-4 py-2">Kamis</div>
            <div class="text-center border font-bold px-4 py-2">Jumat</div>
            <div class="text-center border font-bold px-4 py-2">Sabtu</div>
        </div>

        <div id="calendarDays" class="grid grid-cols-7 text-lg"></div>
    </div>

    <script>
        const calendarDays = document.getElementById('calendarDays');
        const currentMonthHeader = document.getElementById('currentMonth');
        const prevMonthButton = document.getElementById('prevMonth');
        const nextMonthButton = document.getElementById('nextMonth');

        // Data peminjaman dari PHP
        const peminjaman = @json($peminjaman);
        console.log(peminjaman);

        let currentDate = new Date();

        // Fungsi untuk memformat tanggal sesuai keinginan (j F Y)
        function formatDate(date) {
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }

        function generateCalendar(year, month) {
            const firstDay = (new Date(year, month)).getDay();
            const daysInMonth = 32 - new Date(year, month, 32).getDate();

            calendarDays.innerHTML = '';
            currentMonthHeader.textContent = new Date(year, month).toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });

            for (let i = 0; i < firstDay; i++) {
                const emptyDay = document.createElement('div');
                calendarDays.appendChild(emptyDay);
            }

            for (let i = 1; i <= daysInMonth; i++) {
                const day = document.createElement('div');
                day.classList.add('text-left', 'p-8', 'border', 'relative');

                // Cek jika hari adalah Minggu
                const dayOfWeek = new Date(year, month, i).getDay();
                if (dayOfWeek === 0) {
                    day.classList.add('bg-red-500', 'font-bold', 'text-white');
                }

                // Cek jika ada peminjaman pada tanggal ini
                const currentDay = new Date(year, month, i);
                const formattedDate = formatDate(currentDay); // Gunakan formatDate di sini
                const bookedAssets = peminjaman.filter(p => {
                    const tanggalPeminjaman = new Date(p.tanggal_peminjaman * 1000); // Ubah detik ke milidetik
                    return formatDate(tanggalPeminjaman) === formattedDate;
                });

                day.textContent = i; // Menampilkan tanggal di dalam hari

                // Jika ada peminjaman, tambahkan badge di bawah tanggal
                if (bookedAssets.length > 0) {
                    
                    // Menampilkan badge dengan nama aset yang dipinjam
                    const badgesContainer = document.createElement('div');
                    badgesContainer.classList.add('mt-2', 'flex', 'flex-wrap', 'gap-1');

                    bookedAssets.forEach(asset => {
                        const badge = document.createElement('span');
                        badge.classList.add('badge', 'bg-green-500', 'text-white', 'text-xs', 'px-2', 'py-1', 'rounded');
                        badge.textContent = "Peminjaman " + asset.nama; // Menampilkan nama aset
                        badgesContainer.appendChild(badge);
                    });

                    day.appendChild(badgesContainer);
                }

                calendarDays.appendChild(day);
            }
        }

        generateCalendar(currentDate.getFullYear(), currentDate.getMonth());

        prevMonthButton.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
        });

        nextMonthButton.addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            generateCalendar(currentDate.getFullYear(), currentDate.getMonth());
        });
    </script>
</x-body>
