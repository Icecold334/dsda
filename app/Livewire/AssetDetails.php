<?php

namespace App\Livewire;

use App\Models\Agenda;
use App\Models\Jurnal;
use App\Models\Lokasi;
use App\Models\Person;
use App\Models\History;
use Livewire\Component;
use App\Models\Keuangan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AssetDetails extends Component
{
    public $type; // Type: history, agenda, keuangan, jurnal
    public $items = []; // Data yang akan ditampilkan
    public $asetId;
    public $modalData = []; // Data untuk modal
    public $modalId = null; // ID untuk edit mode
    public $isModalOpen = false; // Modal state

    public $isMingguan = false;
    public $isBulanan = false;
    public $isTahunan = false;
    public $isTanggalTertentu = false;

    public $tahun;
    public $bulan;
    public $hari;

    public $nama;
    public $person;
    public $person_id;
    public $lokasi;
    public $lokasi_id;

    public $showSuggestionsPerson;
    public $suggestionsPerson;
    public $showSuggestionsLokasi;
    public $suggestionsLokasi;

    public $maxDays = 31; // Default jumlah hari untuk semua bulan

    public function mount($type, $aset)
    {
        $this->type = $type;
        $this->asetId = $aset->id;

        $this->loadData();
    }

    public function focusPerson()
    {
        $this->searchQueryPerson();
    }

    public function searchQueryPerson()
    {
        // dd('aa');
        $this->showSuggestionsPerson = true;

        // Ambil data dari database berdasarkan query
        $this->suggestionsPerson = Person::where('nama', 'like', '%' . $this->person . '%')
            // ->limit(5)
            ->get()
            ->toArray();
        $this->nama = $this->person;

        $exactMatchPerson = Person::where('nama', $this->person)->first();

        if ($exactMatchPerson) {
            // Jika ada kecocokan, isi vendor_id dan kosongkan suggestions
            $this->selectSuggestionPerson($exactMatchPerson->id, $exactMatchPerson->nama);
        }
    }

    public function selectSuggestionPerson($PersonId, $PersonName)
    {
        // Ketika saran dipilih, isi input dengan nilai tersebut
        $this->person_id = $PersonId;
        $this->person = $PersonName;
        $this->suggestionsPerson = [];
        $this->hideSuggestionsPerson();
    }

    public function hideSuggestionsPerson()
    {
        $this->showSuggestionsPerson = false;
    }

    public function focusLokasi()
    {
        $this->searchQueryLokasi();
    }

    public function searchQueryLokasi()
    {
        // dd('aa');
        $this->showSuggestionsLokasi = true;

        // Ambil data dari database berdasarkan query
        $this->suggestionsLokasi = Lokasi::where('nama', 'like', '%' . $this->lokasi . '%')
            // ->limit(5)
            ->get()
            ->toArray();
        $this->nama = $this->lokasi;

        $exactMatchLokasi = Lokasi::where('nama', $this->lokasi)->first();

        if ($exactMatchLokasi) {
            // Jika ada kecocokan, isi vendor_id dan kosongkan suggestions
            $this->selectSuggestionLokasi($exactMatchLokasi->id, $exactMatchLokasi->nama);
        }
    }

    public function selectSuggestionLokasi($lokasiId, $lokasiName)
    {
        // Ketika saran dipilih, isi input dengan nilai tersebut
        $this->lokasi_id = $lokasiId;
        $this->lokasi = $lokasiName;
        $this->suggestionsLokasi = [];
        $this->hideSuggestionsLokasi();
    }

    public function hideSuggestionsLokasi()
    {
        $this->showSuggestionsLokasi = false;
    }

    public function loadData()
    {
        $this->items = match ($this->type) {
            'history' => History::where('aset_id', $this->asetId)->get(),
            'agenda' => Agenda::where('aset_id', $this->asetId)->get(),
            'keuangan' => Keuangan::where('aset_id', $this->asetId)->get(),
            'jurnal' => Jurnal::where('aset_id', $this->asetId)->get(),
            default => [],
        };
    }

    public function openModal($id = null)
    {
        $this->modalId = $id;

        if ($id) {
            // Edit mode: Ambil data dari database
            $model = $this->getModelInstance()::find($id);
            if ($model) {
                $this->modalData = $model->toArray();

                // Atur boolean berdasarkan tipe (untuk agenda)
                if ($this->type === 'agenda') {
                    $this->setAgendaType($this->modalData['tipe'] ?? '');
                }
            } else {
                $this->modalData = [];
            }
        } else {
            // Tambah mode: Inisialisasi data default
            $this->modalData = $this->initializeModalData();
        }

        $this->isModalOpen = true;
    }

    public function updatedModalData($value, $key)
    {
        if ($key === 'tipe') {
            // Atur visibilitas berdasarkan tipe agenda
            $this->setAgendaType($value);
        }

        // Perbarui jumlah hari jika bulan dipilih
        if ($key === 'bulan' && $this->isTahunan = true) {
            $this->updateMaxDays((int)$value);
        }
    }

    private function updateMaxDays($bulan)
    {
        $year = date('Y'); // Tahun default
        if ($bulan >= 1 && $bulan <= 12) {
            $this->maxDays = cal_days_in_month(CAL_GREGORIAN, $bulan, $year);
        } else {
            $this->maxDays = 31; // Default jika bulan tidak valid
        }

        // Reset hari jika melebihi jumlah maksimum hari
        if (!empty($this->modalData['hari']) && $this->modalData['hari'] > $this->maxDays) {
            $this->modalData['hari'] = '';
        }
    }

    private function setAgendaType($tipe)
    {
        // Reset semua properti boolean
        $this->resetBooleans();

        // Atur boolean yang sesuai
        match ($tipe) {
            'mingguan' => $this->isMingguan = true,
            'bulanan' => $this->isBulanan = true,
            'tahunan' => $this->isTahunan = true,
            'tanggal_tertentu' => $this->isTanggalTertentu = true,
            default => null,
        };

        // Debugging
        logger("Updated tipe to {$tipe}. Booleans:", [
            'isMingguan' => $this->isMingguan,
            'isBulanan' => $this->isBulanan,
            'isTahunan' => $this->isTahunan,
            'isTanggalTertentu' => $this->isTanggalTertentu,
        ]);
    }

    private function resetBooleans()
    {
        $this->isMingguan = false;
        $this->isBulanan = false;
        $this->isTahunan = false;
        $this->isTanggalTertentu = false;
    }

    /**
     * Inisialisasi data default untuk modal (add mode)
     */
    private function initializeModalData()
    {
        return match ($this->type) {
            'history' => [
                'tanggal' => '',
                'person_id' => '',
                'lokasi_id' => '',
                'jumlah' => '',
                'kondisi' => '',
                'kelengkapan' => '',
                'keterangan' => '',
            ],
            'agenda' => [
                'tipe' => '',
                'tanggal' => '',
                'hari' => '',
                'keterangan' => '',
                'bulan' => '',
                'tahun' => '',
            ],
            'keuangan' => [
                'tanggal' => '',
                'tipe' => '',
                'nominal' => '',
                'keterangan' => '',
            ],
            'jurnal' => [
                'tanggal' => '',
                'keterangan' => '',
            ],
            default => [],
        };
    }

    public function closeModal()
    {
        $this->resetBooleans();
        $this->isModalOpen = false;
        $this->modalData = [];
        $this->modalId = null;
    }

    public function save()
    {
        $this->validate($this->getValidationRules());

        if ($this->type === 'history') {
            $personId = $this->getOrCreatePerson($this->person);
            $lokasiId = $this->getOrCreateLokasi($this->lokasi);

            // Tambahkan ID ke dalam modalData
            $this->modalData['person_id'] = $personId;
            $this->modalData['lokasi_id'] = $lokasiId;
        }
        // Konversi tanggal menjadi UNIX timestamp jika ada
        if (!empty($this->modalData['tanggal'])) {
            $this->modalData['tanggal'] = strtotime($this->modalData['tanggal']);
        }

        // Proses untuk tipe agenda
        if ($this->type === 'agenda') {

            if (!empty($this->modalData['tipe']) && $this->modalData['tipe'] === 'mingguan') {
                $this->modalData['tanggal'] = strtotime(now());
            }

            if (!empty($this->modalData['tipe']) && $this->modalData['tipe'] === 'bulanan') {
                $this->modalData['tanggal'] = strtotime(now());
            }

            // Jika tipe tahunan, tambahkan tahun default
            if (!empty($this->modalData['tipe']) && $this->modalData['tipe'] === 'tahunan') {
                $currentYear = date('Y'); // Gunakan tahun saat ini
                $tanggalFull = $currentYear . '-' . $this->modalData['bulan'] . '-' . $this->modalData['hari'];
                // Konversi ke UNIX timestamp
                $this->modalData['tanggal'] = strtotime($tanggalFull);
            }
        }

        $model = $this->getModelInstance();

        $model::updateOrCreate(
            ['id' => $this->modalId],
            array_merge($this->modalData, ['aset_id' => $this->asetId, 'user_id' => Auth::user()->id])
        );

        $this->closeModal();
        $this->loadData();
    }

    /**
     * Get or create Person by name.
     *
     * @param string $name
     * @return int
     */
    private function getOrCreatePerson($name)
    {
        $person = Person::firstOrCreate(['user_id' => Auth::user()->id, 'nama' => $name, 'nama_nospace' => Str::slug($name)]);
        return $person->id;
    }

    /**
     * Get or create Lokasi by name.
     *
     * @param string $name
     * @return int
     */
    private function getOrCreateLokasi($name)
    {
        $lokasi = Lokasi::firstOrCreate(['user_id' => Auth::user()->id, 'nama' => $name, 'nama_nospace' => Str::slug($name)]);
        return $lokasi->id;
    }


    public function getModelInstance()
    {
        return match ($this->type) {
            'history' => History::class,
            'agenda' => Agenda::class,
            'keuangan' => Keuangan::class,
            'jurnal' => Jurnal::class,
            default => throw new \Exception("Invalid type"),
        };
    }

    public function getValidationRules()
    {
        $rules = match ($this->type) {
            'history' => [
                'modalData.tanggal' => 'required|date',
                'person_id' => 'required|integer|max:255',
                'lokasi_id' => 'required|integer|max:255',
                'modalData.jumlah' => 'required|integer|min:1',
                'modalData.kondisi' => 'required|integer|between:0,100',
                'modalData.kelengkapan' => 'required|integer|between:0,100',
                'modalData.keterangan' => 'nullable|string|max:500',
            ],
            'agenda' => [
                'modalData.tipe' => 'required|string',
                'modalData.keterangan' => 'required|string|max:255',
            ],
            'keuangan' => [
                'modalData.tanggal' => 'required|date',
                'modalData.tipe' => 'required|in:in,out',
                'modalData.nominal' => 'required|integer|min:0',
                'modalData.keterangan' => 'nullable|string|max:500',
            ],
            'jurnal' => [
                'modalData.tanggal' => 'required|date',
                'modalData.keterangan' => 'nullable|string|max:500',
            ],
            default => [],
        };

        // Validasi tambahan untuk agenda
        if ($this->type === 'agenda') {
            switch ($this->modalData['tipe'] ?? '') {
                case 'mingguan':
                    $rules['modalData.hari'] = 'required|integer|in:1,2,3,4,5,6,7';
                    break;
                case 'bulanan':
                    $rules['modalData.bulan'] = 'required|integer|between:1,12';
                    break;
                case 'tahunan':
                    $rules['modalData.hari'] = 'required|integer|between:1,' . $this->maxDays;
                    $rules['modalData.bulan'] = 'required|integer|between:1,12';
                    break;
                case 'tanggal_tertentu':
                    $rules['modalData.tanggal'] = 'required|date';
                    break;
            }
        }

        return $rules;
    }

    public function delete($id)
    {
        $model = $this->getModelInstance()::find($id);
        // dd($model);
        if ($model) {
            $model->delete();
            $this->loadData(); // Refresh data setelah delete
            session()->flash('message', 'Data berhasil dihapus.');
        } else {
            session()->flash('error', 'Data tidak ditemukan.');
        }
    }


    public function render()
    {
        return view('livewire.asset-details', [
            'items' => $this->items,
        ]);
    }
}
