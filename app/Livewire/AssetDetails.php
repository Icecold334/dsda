<?php

namespace App\Livewire;

use App\Models\Agenda;
use App\Models\Jurnal;
use App\Models\History;
use Livewire\Component;
use App\Models\Keuangan;
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

    public function mount($type, $aset)
    {
        $this->type = $type;
        $this->asetId = $aset->id;

        $this->loadData();
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
                $currentYear = date('Y');
                $tanggalFull = $currentYear . ' ' . $this->modalData['tanggal'];
                $timestamp = strtotime($tanggalFull);

                $this->modalData['tahun'] = (int) date('Y', $timestamp);
                $this->modalData['bulan'] = (int) date('m', $timestamp);
                $this->modalData['tanggal'] = $timestamp;
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
                'modalData.person_id' => 'required|integer|max:255',
                'modalData.lokasi_id' => 'required|integer|max:255',
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
                    $rules['modalData.hari'] = 'required|interger|in:1,2,3,4,5,6,7';
                    break;
                case 'bulanan':
                    $rules['modalData.bulan'] = 'required|integer|between:1,12';
                    break;
                case 'tahunan':
                    $rules['modalData.tanggal'] = 'required|integer|between:1,' . $this->maxDays;
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
