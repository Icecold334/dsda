<?php

namespace App\Console\Commands;

use App\Models\Kelurahan;
use Illuminate\Console\Command;

class AddMissingKelurahan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kelurahan:add-missing 
                            {--dry-run : Tampilkan perubahan tanpa menyimpan ke database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menambahkan kelurahan yang belum ada berdasarkan seeder lama';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $verbose = $this->getOutput()->isVerbose();

        if ($dryRun) {
            $this->info('MODE DRY-RUN: Tidak ada perubahan yang akan disimpan ke database');
        }

        $this->info('Memulai proses penambahan kelurahan yang belum ada...');

        // Data kelurahan dari seeder lama
        $kelurahanSeederLama = $this->getKelurahanSeederLama();

        $addedCount = 0;
        $skippedCount = 0;

        foreach ($kelurahanSeederLama as $kelurahanData) {
            // Cek apakah kelurahan dengan nama dan kecamatan_id yang sama sudah ada
            $exists = Kelurahan::where('nama', $kelurahanData['nama'])
                ->where('kecamatan_id', $kelurahanData['kecamatan_id'])
                ->exists();

            if (!$exists) {
                if (!$dryRun) {
                    // Jika belum ada dan bukan dry-run, tambahkan kelurahan baru
                    Kelurahan::create([
                        'kecamatan_id' => $kelurahanData['kecamatan_id'],
                        'nama' => $kelurahanData['nama'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                $this->line("✓ " . ($dryRun ? 'Akan menambahkan' : 'Menambahkan') . ": {$kelurahanData['nama']} (Kecamatan ID: {$kelurahanData['kecamatan_id']})");
                $addedCount++;
            } else {
                if ($verbose) {
                    $this->line("- Sudah ada: {$kelurahanData['nama']} (Kecamatan ID: {$kelurahanData['kecamatan_id']})");
                }
                $skippedCount++;
            }
        }

        $this->info("\nProses selesai!");

        if ($dryRun) {
            $this->info("Kelurahan yang akan ditambahkan: {$addedCount}");
        } else {
            $this->info("Kelurahan yang ditambahkan: {$addedCount}");
        }

        $this->info("Kelurahan yang sudah ada: {$skippedCount}");
        $this->info("Total kelurahan diproses: " . ($addedCount + $skippedCount));

        if ($dryRun && $addedCount > 0) {
            $this->info("\nJalankan tanpa --dry-run untuk menyimpan perubahan ke database.");
        }
    }

    /**
     * Data kelurahan dari seeder lama
     */
    private function getKelurahanSeederLama()
    {
        return [
            // Jakarta Pusat - Gambir (kecamatan_id = 1)
            ['kecamatan_id' => 1, 'nama' => 'Gambir'],
            ['kecamatan_id' => 1, 'nama' => 'Kebon Kelapa'],
            ['kecamatan_id' => 1, 'nama' => 'Petojo Selatan'],
            ['kecamatan_id' => 1, 'nama' => 'Duri Pulo'],
            ['kecamatan_id' => 1, 'nama' => 'Cideng'],
            ['kecamatan_id' => 1, 'nama' => 'Petojo Utara'],

            // Jakarta Pusat - Tanah Abang (kecamatan_id = 2)
            ['kecamatan_id' => 2, 'nama' => 'Bendungan Hilir'],
            ['kecamatan_id' => 2, 'nama' => 'Karet Tengsin'],
            ['kecamatan_id' => 2, 'nama' => 'Kebon Melati'],
            ['kecamatan_id' => 2, 'nama' => 'Kebon Kacang'],
            ['kecamatan_id' => 2, 'nama' => 'Kampung Bali'],
            ['kecamatan_id' => 2, 'nama' => 'Petamburan'],
            ['kecamatan_id' => 2, 'nama' => 'Gelora'],

            // Jakarta Pusat - Menteng (kecamatan_id = 3)
            ['kecamatan_id' => 3, 'nama' => 'Cikini'],
            ['kecamatan_id' => 3, 'nama' => 'Gondangdia'],
            ['kecamatan_id' => 3, 'nama' => 'Kebon Sirih'],
            ['kecamatan_id' => 3, 'nama' => 'Menteng'],
            ['kecamatan_id' => 3, 'nama' => 'Pegangsaan'],

            // Jakarta Pusat - Senen (kecamatan_id = 4)
            ['kecamatan_id' => 4, 'nama' => 'Bungur'],
            ['kecamatan_id' => 4, 'nama' => 'Kenari'],
            ['kecamatan_id' => 4, 'nama' => 'Kramat'],
            ['kecamatan_id' => 4, 'nama' => 'Kwitang'],
            ['kecamatan_id' => 4, 'nama' => 'Paseban'],
            ['kecamatan_id' => 4, 'nama' => 'Senen'],

            // Jakarta Pusat - Cempaka Putih (kecamatan_id = 5)
            ['kecamatan_id' => 5, 'nama' => 'Cempaka Putih Barat'],
            ['kecamatan_id' => 5, 'nama' => 'Cempaka Putih Timur'],
            ['kecamatan_id' => 5, 'nama' => 'Rawasari'],

            // Jakarta Pusat - Johar Baru (kecamatan_id = 6)
            ['kecamatan_id' => 6, 'nama' => 'Galur'],
            ['kecamatan_id' => 6, 'nama' => 'Johar Baru'],
            ['kecamatan_id' => 6, 'nama' => 'Kampung Rawa'],
            ['kecamatan_id' => 6, 'nama' => 'Tanah Tinggi'],

            // Jakarta Pusat - Kemayoran (kecamatan_id = 7)
            ['kecamatan_id' => 7, 'nama' => 'Cempaka Baru'],
            ['kecamatan_id' => 7, 'nama' => 'Gunung Sahari Selatan'],
            ['kecamatan_id' => 7, 'nama' => 'Harapan Mulia'],
            ['kecamatan_id' => 7, 'nama' => 'Kebon Kosong'],
            ['kecamatan_id' => 7, 'nama' => 'Kemayoran'],
            ['kecamatan_id' => 7, 'nama' => 'Serdang'],
            ['kecamatan_id' => 7, 'nama' => 'Sumur Batu'],
            ['kecamatan_id' => 7, 'nama' => 'Utan Panjang'],

            // Jakarta Pusat - Sawah Besar (kecamatan_id = 8)
            ['kecamatan_id' => 8, 'nama' => 'Gunung Sahari Utara'],
            ['kecamatan_id' => 8, 'nama' => 'Kartini'],
            ['kecamatan_id' => 8, 'nama' => 'Karang Anyar'],
            ['kecamatan_id' => 8, 'nama' => 'Mangga Dua Selatan'],
            ['kecamatan_id' => 8, 'nama' => 'Pasar Baru'],

            // Jakarta Utara - Penjaringan (kecamatan_id = 9)
            ['kecamatan_id' => 9, 'nama' => 'Penjaringan'],
            ['kecamatan_id' => 9, 'nama' => 'Pluit'],
            ['kecamatan_id' => 9, 'nama' => 'Pejagalan'],
            ['kecamatan_id' => 9, 'nama' => 'Kapuk Muara'],
            ['kecamatan_id' => 9, 'nama' => 'Kamal Muara'],

            // Jakarta Utara - Pademangan (kecamatan_id = 10)
            ['kecamatan_id' => 10, 'nama' => 'Ancol'],
            ['kecamatan_id' => 10, 'nama' => 'Pademangan Barat'],
            ['kecamatan_id' => 10, 'nama' => 'Pademangan Timur'],

            // Jakarta Utara - Tanjung Priok (kecamatan_id = 11)
            ['kecamatan_id' => 11, 'nama' => 'Tanjung Priok'],
            ['kecamatan_id' => 11, 'nama' => 'Sungai Bambu'],
            ['kecamatan_id' => 11, 'nama' => 'Papanggo'],
            ['kecamatan_id' => 11, 'nama' => 'Warakas'],
            ['kecamatan_id' => 11, 'nama' => 'Kebon Bawang'],
            ['kecamatan_id' => 11, 'nama' => 'Sunter Agung'],
            ['kecamatan_id' => 11, 'nama' => 'Sunter Jaya'],

            // Jakarta Utara - Koja (kecamatan_id = 12)
            ['kecamatan_id' => 12, 'nama' => 'Koja Utara'],
            ['kecamatan_id' => 12, 'nama' => 'Koja Selatan'],
            ['kecamatan_id' => 12, 'nama' => 'Tugu Selatan'],
            ['kecamatan_id' => 12, 'nama' => 'Tugu Utara'],
            ['kecamatan_id' => 12, 'nama' => 'Rawa Badak Selatan'],
            ['kecamatan_id' => 12, 'nama' => 'Rawa Badak Utara'],
            ['kecamatan_id' => 12, 'nama' => 'Lagoa'],

            // Jakarta Utara - Cilincing (kecamatan_id = 13)
            ['kecamatan_id' => 13, 'nama' => 'Cilincing'],
            ['kecamatan_id' => 13, 'nama' => 'Kali Baru'],
            ['kecamatan_id' => 13, 'nama' => 'Marunda'],
            ['kecamatan_id' => 13, 'nama' => 'Rorotan'],
            ['kecamatan_id' => 13, 'nama' => 'Semper Barat'],
            ['kecamatan_id' => 13, 'nama' => 'Semper Timur'],
            ['kecamatan_id' => 13, 'nama' => 'Sukapura'],

            // Jakarta Utara - Kelapa Gading (kecamatan_id = 14)
            ['kecamatan_id' => 14, 'nama' => 'Kelapa Gading Barat'],
            ['kecamatan_id' => 14, 'nama' => 'Kelapa Gading Timur'],
            ['kecamatan_id' => 14, 'nama' => 'Pegangsaan Dua'],

            // Jakarta Barat - Cengkareng (kecamatan_id = 15)
            ['kecamatan_id' => 15, 'nama' => 'Cengkareng Barat'],
            ['kecamatan_id' => 15, 'nama' => 'Cengkareng Timur'],
            ['kecamatan_id' => 15, 'nama' => 'Duri Kosambi'],
            ['kecamatan_id' => 15, 'nama' => 'Kapuk'],
            ['kecamatan_id' => 15, 'nama' => 'Kedaung Kali Angke'],
            ['kecamatan_id' => 15, 'nama' => 'Rawa Buaya'],

            // Jakarta Barat - Grogol Petamburan (kecamatan_id = 16)
            ['kecamatan_id' => 16, 'nama' => 'Grogol'],
            ['kecamatan_id' => 16, 'nama' => 'Jelambar'],
            ['kecamatan_id' => 16, 'nama' => 'Jelambar Baru'],
            ['kecamatan_id' => 16, 'nama' => 'Tanjung Duren Utara'],
            ['kecamatan_id' => 16, 'nama' => 'Tanjung Duren Selatan'],
            ['kecamatan_id' => 16, 'nama' => 'Tomang'],
            ['kecamatan_id' => 16, 'nama' => 'Wijaya Kusuma'],

            // Jakarta Barat - Taman Sari (kecamatan_id = 17)
            ['kecamatan_id' => 17, 'nama' => 'Glodok'],
            ['kecamatan_id' => 17, 'nama' => 'Keagungan'],
            ['kecamatan_id' => 17, 'nama' => 'Krukut'],
            ['kecamatan_id' => 17, 'nama' => 'Mangga Besar'],
            ['kecamatan_id' => 17, 'nama' => 'Maphar'],
            ['kecamatan_id' => 17, 'nama' => 'Pinangsia'],
            ['kecamatan_id' => 17, 'nama' => 'Taman Sari'],
            ['kecamatan_id' => 17, 'nama' => 'Tangki'],

            // Jakarta Barat - Tambora (kecamatan_id = 18)
            ['kecamatan_id' => 18, 'nama' => 'Angke'],
            ['kecamatan_id' => 18, 'nama' => 'Duri Selatan'],
            ['kecamatan_id' => 18, 'nama' => 'Duri Utara'],
            ['kecamatan_id' => 18, 'nama' => 'Jembatan Besi'],
            ['kecamatan_id' => 18, 'nama' => 'Jembatan Lima'],
            ['kecamatan_id' => 18, 'nama' => 'Kali Anyar'],
            ['kecamatan_id' => 18, 'nama' => 'Krendang'],
            ['kecamatan_id' => 18, 'nama' => 'Pekojan'],
            ['kecamatan_id' => 18, 'nama' => 'Roa Malaka'],
            ['kecamatan_id' => 18, 'nama' => 'Tambora'],
            ['kecamatan_id' => 18, 'nama' => 'Tanah Sereal'],

            // Jakarta Barat - Kalideres (kecamatan_id = 19)
            ['kecamatan_id' => 19, 'nama' => 'Kalideres'],
            ['kecamatan_id' => 19, 'nama' => 'Kamal'],
            ['kecamatan_id' => 19, 'nama' => 'Pegadungan'],
            ['kecamatan_id' => 19, 'nama' => 'Semanan'],
            ['kecamatan_id' => 19, 'nama' => 'Tegal Alur'],

            // Jakarta Barat - Kebon Jeruk (kecamatan_id = 20)
            ['kecamatan_id' => 20, 'nama' => 'Duri Kepa'],
            ['kecamatan_id' => 20, 'nama' => 'Kebon Jeruk'],
            ['kecamatan_id' => 20, 'nama' => 'Kedoya Selatan'],
            ['kecamatan_id' => 20, 'nama' => 'Kedoya Utara'],
            ['kecamatan_id' => 20, 'nama' => 'Kelapa Dua'],
            ['kecamatan_id' => 20, 'nama' => 'Sukabumi Selatan'],
            ['kecamatan_id' => 20, 'nama' => 'Sukabumi Utara'],

            // Jakarta Barat - Palmerah (kecamatan_id = 21)
            ['kecamatan_id' => 21, 'nama' => 'Jati Pulo'],
            ['kecamatan_id' => 21, 'nama' => 'Kemanggisan'],
            ['kecamatan_id' => 21, 'nama' => 'Kota Bambu Selatan'],
            ['kecamatan_id' => 21, 'nama' => 'Kota Bambu Utara'],
            ['kecamatan_id' => 21, 'nama' => 'Palmerah'],
            ['kecamatan_id' => 21, 'nama' => 'Slipi'],

            // Jakarta Barat - Kembangan (kecamatan_id = 22)
            ['kecamatan_id' => 22, 'nama' => 'Joglo'],
            ['kecamatan_id' => 22, 'nama' => 'Kembangan Selatan'],
            ['kecamatan_id' => 22, 'nama' => 'Kembangan Utara'],
            ['kecamatan_id' => 22, 'nama' => 'Meruya Selatan'],
            ['kecamatan_id' => 22, 'nama' => 'Meruya Utara'],
            ['kecamatan_id' => 22, 'nama' => 'Srengseng'],

            // Jakarta Selatan - Kebayoran Baru (kecamatan_id = 23)
            ['kecamatan_id' => 23, 'nama' => 'Gunung'],
            ['kecamatan_id' => 23, 'nama' => 'Kramat Pela'],
            ['kecamatan_id' => 23, 'nama' => 'Gandaria Utara'],
            ['kecamatan_id' => 23, 'nama' => 'Cipete Utara'],
            ['kecamatan_id' => 23, 'nama' => 'Pulo'],
            ['kecamatan_id' => 23, 'nama' => 'Melawai'],
            ['kecamatan_id' => 23, 'nama' => 'Petogogan'],
            ['kecamatan_id' => 23, 'nama' => 'Rawa Barat'],
            ['kecamatan_id' => 23, 'nama' => 'Selong'],
            ['kecamatan_id' => 23, 'nama' => 'Senayan'],

            // Jakarta Selatan - Kebayoran Lama (kecamatan_id = 24)
            ['kecamatan_id' => 24, 'nama' => 'Cipulir'],
            ['kecamatan_id' => 24, 'nama' => 'Grogol Selatan'],
            ['kecamatan_id' => 24, 'nama' => 'Grogol Utara'],
            ['kecamatan_id' => 24, 'nama' => 'Kebayoran Lama Selatan'],
            ['kecamatan_id' => 24, 'nama' => 'Kebayoran Lama Utara'],
            ['kecamatan_id' => 24, 'nama' => 'Pondok Pinang'],

            // Jakarta Selatan - Cilandak (kecamatan_id = 25)
            ['kecamatan_id' => 25, 'nama' => 'Cilandak Barat'],
            ['kecamatan_id' => 25, 'nama' => 'Cipete Selatan'],
            ['kecamatan_id' => 25, 'nama' => 'Gandaria Selatan'],
            ['kecamatan_id' => 25, 'nama' => 'Lebak Bulus'],
            ['kecamatan_id' => 25, 'nama' => 'Pondok Labu'],

            // Jakarta Selatan - Pesanggrahan (kecamatan_id = 26)
            ['kecamatan_id' => 26, 'nama' => 'Bintaro'],
            ['kecamatan_id' => 26, 'nama' => 'Pesanggrahan'],
            ['kecamatan_id' => 26, 'nama' => 'Petukangan Selatan'],
            ['kecamatan_id' => 26, 'nama' => 'Petukangan Utara'],
            ['kecamatan_id' => 26, 'nama' => 'Ulujami'],

            // Jakarta Selatan - Pasar Minggu (kecamatan_id = 27)
            ['kecamatan_id' => 27, 'nama' => 'Jati Padang'],
            ['kecamatan_id' => 27, 'nama' => 'Pejaten Barat'],
            ['kecamatan_id' => 27, 'nama' => 'Pejaten Timur'],
            ['kecamatan_id' => 27, 'nama' => 'Kalibata'],
            ['kecamatan_id' => 27, 'nama' => 'Pasar Minggu'],
            ['kecamatan_id' => 27, 'nama' => 'Ragunan'],

            // Jakarta Selatan - Jagakarsa (kecamatan_id = 28)
            ['kecamatan_id' => 28, 'nama' => 'Cipedak'],
            ['kecamatan_id' => 28, 'nama' => 'Jagakarsa'],
            ['kecamatan_id' => 28, 'nama' => 'Lenteng Agung'],
            ['kecamatan_id' => 28, 'nama' => 'Srengseng Sawah'],
            ['kecamatan_id' => 28, 'nama' => 'Tanjung Barat'],
            ['kecamatan_id' => 28, 'nama' => 'Cilandak Timur'],
            ['kecamatan_id' => 28, 'nama' => 'Ciganjur'],

            // Jakarta Selatan - Mampang Prapatan (kecamatan_id = 29)
            ['kecamatan_id' => 29, 'nama' => 'Bangka'],
            ['kecamatan_id' => 29, 'nama' => 'Kuningan Barat'],
            ['kecamatan_id' => 29, 'nama' => 'Mampang Prapatan'],
            ['kecamatan_id' => 29, 'nama' => 'Pela Mampang'],
            ['kecamatan_id' => 29, 'nama' => 'Tegal Parang'],

            // Jakarta Selatan - Pancoran (kecamatan_id = 30)
            ['kecamatan_id' => 30, 'nama' => 'Cikoko'],
            ['kecamatan_id' => 30, 'nama' => 'Duren Tiga'],
            ['kecamatan_id' => 30, 'nama' => 'Kalibata'],
            ['kecamatan_id' => 30, 'nama' => 'Pancoran'],
            ['kecamatan_id' => 30, 'nama' => 'Rawa Jati'],

            // Jakarta Selatan - Tebet (kecamatan_id = 31)
            ['kecamatan_id' => 31, 'nama' => 'Bukit Duri'],
            ['kecamatan_id' => 31, 'nama' => 'Kebon Baru'],
            ['kecamatan_id' => 31, 'nama' => 'Manggarai'],
            ['kecamatan_id' => 31, 'nama' => 'Manggarai Selatan'],
            ['kecamatan_id' => 31, 'nama' => 'Tebet Barat'],
            ['kecamatan_id' => 31, 'nama' => 'Tebet Timur'],
            ['kecamatan_id' => 31, 'nama' => 'Menteng Dalam'],

            // Jakarta Selatan - Setiabudi (kecamatan_id = 32)
            ['kecamatan_id' => 32, 'nama' => 'Guntur'],
            ['kecamatan_id' => 32, 'nama' => 'Karet'],
            ['kecamatan_id' => 32, 'nama' => 'Karet Kuningan'],
            ['kecamatan_id' => 32, 'nama' => 'Karet Semanggi'],
            ['kecamatan_id' => 32, 'nama' => 'Kuningan Timur'],
            ['kecamatan_id' => 32, 'nama' => 'Menteng Atas'],
            ['kecamatan_id' => 32, 'nama' => 'Pasar Manggis'],
            ['kecamatan_id' => 32, 'nama' => 'Setiabudi'],

            // Jakarta Timur - Matraman (kecamatan_id = 33)
            ['kecamatan_id' => 33, 'nama' => 'Kebon Manggis'],
            ['kecamatan_id' => 33, 'nama' => 'Pal Meriam'],
            ['kecamatan_id' => 33, 'nama' => 'Kayu Manis'],
            ['kecamatan_id' => 33, 'nama' => 'Pisangan Baru'],
            ['kecamatan_id' => 33, 'nama' => 'Utan Kayu Selatan'],
            ['kecamatan_id' => 33, 'nama' => 'Utan Kayu Utara'],

            // Jakarta Timur - Pulo Gadung (kecamatan_id = 34)
            ['kecamatan_id' => 34, 'nama' => 'Cipinang'],
            ['kecamatan_id' => 34, 'nama' => 'Cipinang Besar Selatan'],
            ['kecamatan_id' => 34, 'nama' => 'Jati'],
            ['kecamatan_id' => 34, 'nama' => 'Jatinegara Kaum'],
            ['kecamatan_id' => 34, 'nama' => 'Pisangan Timur'],
            ['kecamatan_id' => 34, 'nama' => 'Pulo Gadung'],
            ['kecamatan_id' => 34, 'nama' => 'Rawamangun'],

            // Jakarta Timur - Jatinegara (kecamatan_id = 35)
            ['kecamatan_id' => 35, 'nama' => 'Bali Mester'],
            ['kecamatan_id' => 35, 'nama' => 'Bidaracina'],
            ['kecamatan_id' => 35, 'nama' => 'Cipinang Besar Selatan'],
            ['kecamatan_id' => 35, 'nama' => 'Cipinang Besar Utara'],
            ['kecamatan_id' => 35, 'nama' => 'Cipinang Cempedak'],
            ['kecamatan_id' => 35, 'nama' => 'Jatinegara'],
            ['kecamatan_id' => 35, 'nama' => 'Rawa Bunga'],
            ['kecamatan_id' => 35, 'nama' => 'Kampung Melayu'],

            // Jakarta Timur - Duren Sawit (kecamatan_id = 36)
            ['kecamatan_id' => 36, 'nama' => 'Duren Sawit'],
            ['kecamatan_id' => 36, 'nama' => 'Pondok Bambu'],
            ['kecamatan_id' => 36, 'nama' => 'Pondok Kelapa'],
            ['kecamatan_id' => 36, 'nama' => 'Pondok Kopi'],
            ['kecamatan_id' => 36, 'nama' => 'Malaka Jaya'],
            ['kecamatan_id' => 36, 'nama' => 'Malaka Sari'],
            ['kecamatan_id' => 36, 'nama' => 'Klender'],

            // Jakarta Timur - Kramat Jati (kecamatan_id = 37)
            ['kecamatan_id' => 37, 'nama' => 'Kramat Jati'],
            ['kecamatan_id' => 37, 'nama' => 'Tengah'],
            ['kecamatan_id' => 37, 'nama' => 'Cawang'],
            ['kecamatan_id' => 37, 'nama' => 'Dukuh'],
            ['kecamatan_id' => 37, 'nama' => 'Cililitan'],
            ['kecamatan_id' => 37, 'nama' => 'Batu Ampar'],
            ['kecamatan_id' => 37, 'nama' => 'Balekambang'],

            // Jakarta Timur - Makasar (kecamatan_id = 38)
            ['kecamatan_id' => 38, 'nama' => 'Makasar'],
            ['kecamatan_id' => 38, 'nama' => 'Pinang Ranti'],
            ['kecamatan_id' => 38, 'nama' => 'Kebon Pala'],
            ['kecamatan_id' => 38, 'nama' => 'Halim Perdana Kusuma'],
            ['kecamatan_id' => 38, 'nama' => 'Cipinang Melayu'],

            // Jakarta Timur - Cipayung (kecamatan_id = 39)
            ['kecamatan_id' => 39, 'nama' => 'Cipayung'],
            ['kecamatan_id' => 39, 'nama' => 'Cilangkap'],
            ['kecamatan_id' => 39, 'nama' => 'Setu'],
            ['kecamatan_id' => 39, 'nama' => 'Bambu Apus'],
            ['kecamatan_id' => 39, 'nama' => 'Ceger'],
            ['kecamatan_id' => 39, 'nama' => 'Munjul'],
            ['kecamatan_id' => 39, 'nama' => 'Pondok Ranggon'],
            ['kecamatan_id' => 39, 'nama' => 'Lubang Buaya'],

            // Jakarta Timur - Ciracas (kecamatan_id = 40)
            ['kecamatan_id' => 40, 'nama' => 'Ciracas'],
            ['kecamatan_id' => 40, 'nama' => 'Susukan'],
            ['kecamatan_id' => 40, 'nama' => 'Cibubur'],
            ['kecamatan_id' => 40, 'nama' => 'Kelapa Dua Wetan'],
            ['kecamatan_id' => 40, 'nama' => 'Rambutan'],

            // Jakarta Timur - Pasar Rebo (kecamatan_id = 41)
            ['kecamatan_id' => 41, 'nama' => 'Pasar Rebo'],
            ['kecamatan_id' => 41, 'nama' => 'Gedong'],
            ['kecamatan_id' => 41, 'nama' => 'Baru'],
            ['kecamatan_id' => 41, 'nama' => 'Cijantung'],
            ['kecamatan_id' => 41, 'nama' => 'Kalisari'],

            // Jakarta Timur - Cakung (kecamatan_id = 42)
            ['kecamatan_id' => 42, 'nama' => 'Cakung Barat'],
            ['kecamatan_id' => 42, 'nama' => 'Cakung Timur'],
            ['kecamatan_id' => 42, 'nama' => 'Rawa Terate'],
            ['kecamatan_id' => 42, 'nama' => 'Jatinegara'],
            ['kecamatan_id' => 42, 'nama' => 'Penggilingan'],
            ['kecamatan_id' => 42, 'nama' => 'Pulo Gebang'],
            ['kecamatan_id' => 42, 'nama' => 'Ujung Menteng'],

            // Kepulauan Seribu - Kepulauan Seribu Utara (kecamatan_id = 43)
            ['kecamatan_id' => 43, 'nama' => 'Pulau Kelapa'],
            ['kecamatan_id' => 43, 'nama' => 'Pulau Harapan'],
            ['kecamatan_id' => 43, 'nama' => 'Pulau Untung Jawa'],

            // Kepulauan Seribu - Kepulauan Seribu Selatan (kecamatan_id = 44)
            ['kecamatan_id' => 44, 'nama' => 'Pulau Tidung'],
            ['kecamatan_id' => 44, 'nama' => 'Pulau Pari'],
            ['kecamatan_id' => 44, 'nama' => 'Pulau Panggang'],
        ];
    }
}
