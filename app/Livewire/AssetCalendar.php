<?php

namespace App\Livewire;

use App\Models\Agenda;
use App\Models\Jurnal;
use App\Models\History;
use Livewire\Component;
use App\Models\Keuangan;
use Illuminate\Support\Carbon;

class AssetCalendar extends Component
{
    public $month;
    public $year;
    public $selectedFilter = 'all'; // Default filter (Semua)
    public $days = [];
    public $agendas = [];
    public $journals = [];
    public $transactions = [];
    public $histories = [];

    public function mount()
    {
        $this->month = date('n'); // Bulan saat ini
        $this->year = date('Y'); // Tahun saat ini
        $this->loadData();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['month', 'year', 'selectedFilter'])) {
            $this->loadData();
        }
    }

    public function loadData()
    {
        $startOfMonth = Carbon::create($this->year, $this->month, 1)->startOfDay()->timestamp; // Timestamp awal bulan
        $endOfMonth = Carbon::create($this->year, $this->month, 1)->endOfMonth()->endOfDay()->timestamp; // Timestamp akhir bulan

        $this->days = $this->generateDays(Carbon::createFromTimestamp($startOfMonth), Carbon::createFromTimestamp($endOfMonth));
        // dd($this->days);

        // Reset semua data
        $this->agendas = collect();
        $this->journals = collect();
        $this->transactions = collect();
        $this->histories = collect();

        // Logika pemuatan data berdasarkan selectedFilter
        switch ($this->selectedFilter) {
            case 'agenda':
                $this->agendas = Agenda::get()->map(function ($agenda) {
                    $agenda->formatted_tipe = match ($agenda->tipe) {
                        'mingguan' => 'Mingguan',
                        'bulanan' => 'Bulanan',
                        'tahunan' => 'Tahunan',
                        'tanggal_tertentu' => 'Tanggal Tertentu',
                        default => ucfirst($agenda->tipe),
                    };
                    return $agenda;
                });
                break;

            case 'journal':
                $this->journals = Jurnal::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->get();
                break;

            case 'transaction':
                $this->transactions = Keuangan::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->get();
                break;

            case 'history':
                $this->histories = History::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->get();
                break;

            case 'all':
            default:
                $this->agendas = Agenda::get()->map(function ($agenda) {
                    $agenda->formatted_tipe = match ($agenda->tipe) {
                        'mingguan' => 'Mingguan',
                        'bulanan' => 'Bulanan',
                        'tahunan' => 'Tahunan',
                        'tanggal_tertentu' => 'Tanggal Tertentu',
                        default => ucfirst($agenda->tipe),
                    };
                    return $agenda;
                });

                $this->journals = Jurnal::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->get();
                $this->transactions = Keuangan::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->get();
                $this->histories = History::whereBetween('tanggal', [$startOfMonth, $endOfMonth])->get();
                break;
        }
    }


    private function generateDays($start, $end)
    {
        setlocale(LC_TIME, 'id_ID.UTF-8');
        \Carbon\Carbon::setLocale('id');

        // Map nama hari ke angka
        $dayMap = [
            'Senin' => 1,
            'Selasa' => 2,
            'Rabu' => 3,
            'Kamis' => 4,
            'Jumat' => 5,
            'Sabtu' => 6,
            'Minggu' => 7,
        ];

        $days = [];
        while ($start->lte($end)) {
            $dayName = $start->translatedFormat('l'); // Nama hari (Bahasa Indonesia)
            $fullDate = $start->toDateString();

            $days[] = [
                'day_name' => $dayName, // Nama hari
                'day_num' => $start->format('j'), // Tanggal
                'full_date' => $fullDate, // Tanggal dalam format string (YYYY-MM-DD)
                'date_strtotime' => strtotime($fullDate), // UNIX timestamp dari full_date
                'day_index' => $dayMap[$dayName] ?? null, // Angka hari (1 untuk Senin, 7 untuk Minggu)
                'month_index' => (int) $start->format('n'), // Angka bulan (1 untuk Januari, 12 untuk Desember)
            ];

            $start->addDay();
        }
        return $days;
    }


    public function render()
    {
        return view('livewire.asset-calendar');
    }
}