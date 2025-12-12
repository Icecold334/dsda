<?php

namespace App\Livewire;

use App\Models\Rab;
use App\Models\ListRab;
use App\Models\MerkStok;
use App\Models\BarangStok;
use App\Models\AdendumRab as AdendumRabModel;
use App\Models\AdendumListRab;
use App\Models\AdendumHistory;
use App\Models\Program;
use App\Models\Kegiatan;
use App\Models\SubKegiatan;
use App\Models\AktivitasSubKegiatan;
use App\Models\UraianRekening;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class AdendumRab extends Component
{
    public $rab;
    public $rabId;
    public $list = [];
    public $barangs = [];
    public $merks = [];
    public $newBarangId;
    public $newMerkId;
    public $newJumlah;
    public $newUnit = 'Satuan';
    public $keterangan = '';
    public $ruleAdd = false;
    public $jumlahInvalid = false; // penanda ada input jumlah di bawah minimal

    // Data kegiatan (read-only untuk display)
    public $program_id, $programs = [];
    public $kegiatan_id, $kegiatans = [];
    public $sub_kegiatan_id, $sub_kegiatans = [];
    public $aktivitas_sub_kegiatan_id, $aktivitas_sub_kegiatans = [];
    public $uraian_rekening_id, $uraian_rekenings = [];
    public $kelurahan_id, $kelurahans = [];
    public $kecamatan_id, $kecamatans = [];
    public $jenis_pekerjaan;
    public $lokasi;
    public $mulai;
    public $selesai;
    public $p, $l, $k;

    public function mount($rabId)
    {
        $this->rabId = $rabId;
        $this->rab = Rab::with(['list.merkStok.barangStok', 'user.unitKerja', 'program', 'kegiatan', 'subKegiatan', 'aktivitasSubKegiatan', 'uraianRekening', 'kelurahan.kecamatan'])->findOrFail($rabId);
        
        // Cek apakah user adalah Kasatpel
        if (!Auth::user()->hasRole('Kepala Satuan Pelaksana')) {
            abort(403, 'Hanya Kasatpel yang dapat membuat adendum RAB');
        }

        // Cek apakah RAB sudah disetujui
        if ($this->rab->status !== 2) {
            abort(403, 'Hanya RAB yang sudah disetujui yang dapat dibuat adendum');
        }

        // Load data kegiatan untuk display (read-only)
        $this->loadKegiatanData();

        // Load data material RAB asli
        foreach ($this->rab->list as $item) {
            $telahDigunakan = 0;
            if ($item->merk_id && $this->rabId) {
                $telahDigunakan = $this->hitungTelahDigunakan($item->merk_id, $this->rabId);
            }
            
            $this->list[] = [
                'id' => $item->id,
                'list_rab_id' => $item->id,
                'merk' => $item->merkStok,
                'jumlah_lama' => $item->jumlah ?? 0,
                'jumlah_baru' => $item->jumlah ?? 0,
                'action' => 'no_change', // default status tidak diubah
                'telah_digunakan' => $telahDigunakan
            ];
        }

        $this->barangs = BarangStok::where('jenis_id', 1)->get();
        $this->merks = []; // Initialize merks as empty array
    }

    private function loadKegiatanData()
    {
        // Load dropdown data berdasarkan unit RAB
        $unitId = $this->rab->user->unitKerja->parent_id ?: $this->rab->user->unit_id;
        
        $this->programs = Program::where('bidang_id', $unitId)->get();
        $this->kecamatans = Kecamatan::where('unit_id', $unitId)->get();

        // Fill form dengan data RAB yang ada
        $this->program_id = $this->rab->program_id;
        $this->kegiatan_id = $this->rab->kegiatan_id;
        $this->sub_kegiatan_id = $this->rab->sub_kegiatan_id;
        $this->aktivitas_sub_kegiatan_id = $this->rab->aktivitas_sub_kegiatan_id;
        $this->uraian_rekening_id = $this->rab->uraian_rekening_id;
        $this->kelurahan_id = $this->rab->kelurahan_id;
        $this->kecamatan_id = $this->rab->kelurahan->kecamatan_id ?? null;

        $this->jenis_pekerjaan = $this->rab->jenis_pekerjaan;
        $this->lokasi = $this->rab->lokasi;
        $this->mulai = $this->rab->mulai ? $this->rab->mulai->format('Y-m-d') : null;
        $this->selesai = $this->rab->selesai ? $this->rab->selesai->format('Y-m-d') : null;
        $this->p = $this->rab->p;
        $this->l = $this->rab->l;
        $this->k = $this->rab->k;

        // Load dependent dropdowns untuk display
        if ($this->program_id) {
            $this->kegiatans = Kegiatan::where('program_id', $this->program_id)->get();
        }
        if ($this->kegiatan_id) {
            $this->sub_kegiatans = SubKegiatan::where('kegiatan_id', $this->kegiatan_id)->get();
        }
        if ($this->sub_kegiatan_id) {
            $this->aktivitas_sub_kegiatans = AktivitasSubKegiatan::where('sub_kegiatan_id', $this->sub_kegiatan_id)->get();
        }
        if ($this->aktivitas_sub_kegiatan_id) {
            $this->uraian_rekenings = UraianRekening::where('aktivitas_sub_kegiatan_id', $this->aktivitas_sub_kegiatan_id)->get();
        }
        if ($this->kecamatan_id) {
            $this->kelurahans = Kelurahan::where('kecamatan_id', $this->kecamatan_id)->get();
        }
    }

    public function updated($field)
    {
        if (!$this->newMerkId) {
            $this->newJumlah = null;
            $this->newUnit = 'Satuan';
        } else {
            $merk = MerkStok::find($this->newMerkId);
            if ($merk) {
                $this->newUnit = $merk->barangStok->satuanBesar->nama;
            }
        }

        if ($field == 'newBarangId') {
            if ($this->newBarangId) {
                $barang = BarangStok::find($this->newBarangId);
                if ($barang) {
                    $this->newUnit = $barang->satuanBesar->nama;
                    $this->newMerkId = null;
                    $this->newJumlah = null;
                    // Format merks dengan nama untuk searchable select
                    $this->merks = MerkStok::where('barang_id', $this->newBarangId)
                        ->get()
                        ->map(function ($merk) {
                            return [
                                'id' => $merk->id,
                                'nama' => $merk->nama . ($merk->tipe ? ' - ' . $merk->tipe : ''),
                            ];
                        })->toArray();
                }
            } else {
                // Reset merks jika barang di-reset
                $this->merks = [];
                $this->newMerkId = null;
                $this->newJumlah = null;
                $this->newUnit = 'Satuan';
            }
        }

        $this->checkAdd();
    }

    public function checkAdd()
    {
        $this->ruleAdd = $this->newMerkId && $this->newJumlah;
    }

    public function addToList()
    {
        $merk = MerkStok::find($this->newMerkId);
        if ($merk) {
            $this->list[] = [
                'id' => null,
                'list_rab_id' => null,
                'merk' => $merk,
                'jumlah_lama' => 0,
                'jumlah_baru' => $this->newJumlah,
                'action' => 'add',
                'telah_digunakan' => 0
            ];
        }

        $this->reset(['newBarangId', 'newMerkId', 'newJumlah', 'newUnit']);
        $this->checkAdd();
    }

    public function removeFromList($index)
    {
        $item = $this->list[$index];
        
        // Validasi: tidak bisa dihapus jika sudah digunakan
        $telahDigunakan = (int)($item['telah_digunakan'] ?? 0);
        if ($item['list_rab_id'] && $telahDigunakan > 0) {
            session()->flash('error', 'Barang tidak dapat dihapus karena sudah digunakan (' . number_format($telahDigunakan) . ' ' . ($item['merk']->barangStok->satuanBesar->nama ?? 'satuan') . ')');
            return;
        }
        
        // Jika item sudah ada di RAB asli, set action ke 'delete' (tidak dihapus dari list)
        if ($item['list_rab_id']) {
            $this->list[$index]['action'] = 'delete';
            $this->list[$index]['jumlah_baru'] = 0;
        } else {
            // Jika item baru, hapus dari list
            unset($this->list[$index]);
            $this->list = array_values($this->list);
        }
    }

    public function recoverFromList($index)
    {
        if (!isset($this->list[$index])) {
            return;
        }
        
        $item = $this->list[$index];
        
        // Kembalikan item yang sudah dihapus
        if ($item['action'] === 'delete' && $item['list_rab_id']) {
            // Kembalikan ke jumlah lama
            $this->list[$index]['action'] = 'no_change';
            $this->list[$index]['jumlah_baru'] = $item['jumlah_lama'] ?? 0;
        }
    }

    public function updateJumlah($index, $jumlahBaru)
    {
        if (isset($this->list[$index])) {
            // Pastikan telah_digunakan tetap ada
            if (!isset($this->list[$index]['telah_digunakan'])) {
                $merkId = $this->list[$index]['merk']->id ?? null;
                $this->list[$index]['telah_digunakan'] = $merkId ? $this->hitungTelahDigunakan($merkId, $this->rabId) : 0;
            }
            
            $telahDigunakan = (int)($this->list[$index]['telah_digunakan'] ?? 0);
            $jumlahBaruInt = (int)$jumlahBaru;
            
            // #region agent log
            @file_put_contents(
                'c:\\Users\\MasterOdimm\\Documents\\GitHub\\dsda\\.cursor\\debug.log',
                json_encode([
                    'sessionId' => 'debug-session',
                    'runId' => 'run1',
                    'hypothesisId' => 'H1',
                    'location' => 'AdendumRab::updateJumlah',
                    'message' => 'updateJumlah input',
                    'data' => [
                        'index' => $index,
                        'jumlahBaruInt' => $jumlahBaruInt,
                        'telahDigunakan' => $telahDigunakan,
                        'listRabId' => $this->list[$index]['list_rab_id'] ?? null,
                        'action' => $this->list[$index]['action'] ?? null,
                    ],
                    'timestamp' => round(microtime(true) * 1000)
                ]) . PHP_EOL,
                FILE_APPEND
            );
            // #endregion

            // Validasi: jumlah baru tidak boleh kurang dari telah digunakan
            if ($jumlahBaruInt < $telahDigunakan) {
                $satuan = $this->list[$index]['merk']->barangStok->satuanBesar->nama ?? 'satuan';
                session()->flash('error', 'Jumlah baru tidak boleh kurang dari jumlah yang telah digunakan (' . number_format($telahDigunakan) . ' ' . $satuan . ').');
                $this->jumlahInvalid = true;
                
                // #region agent log
                @file_put_contents(
                    'c:\\Users\\MasterOdimm\\Documents\\GitHub\\dsda\\.cursor\\debug.log',
                    json_encode([
                        'sessionId' => 'debug-session',
                        'runId' => 'run1',
                        'hypothesisId' => 'H2',
                        'location' => 'AdendumRab::updateJumlah',
                        'message' => 'jumlah below telah_digunakan blocked',
                        'data' => [
                            'index' => $index,
                            'jumlahBaruInt' => $jumlahBaruInt,
                            'telahDigunakan' => $telahDigunakan,
                        ],
                        'timestamp' => round(microtime(true) * 1000)
                    ]) . PHP_EOL,
                    FILE_APPEND
                );
                // #endregion
                return;
            }
            
            $this->list[$index]['jumlah_baru'] = $jumlahBaruInt;
            $this->jumlahInvalid = false;
            
            if ($this->list[$index]['jumlah_lama'] != $jumlahBaruInt) {
                $this->list[$index]['action'] = 'edit';
            } else {
                $this->list[$index]['action'] = 'no_change';
            }
        }
    }

    public function hitungTelahDigunakan($merkId, $rabId)
    {
        $totalTelahDigunakan = 0;

        try {
            if (!$merkId || !$rabId) {
                return 0;
            }

            // Permintaan material yang sudah dikirim/selesai
            $permintaanMaterial = \App\Models\PermintaanMaterial::where('merk_id', $merkId)
                ->where('rab_id', $rabId)
                ->whereHas('detailPermintaan', function ($query) {
                    $query->whereIn('status', [2, 3]); // 2 = dikirim, 3 = selesai
                })
                ->whereHas('stokDisetujui', function ($query) {
                    $query->where('jumlah_disetujui', '>', 0);
                })
                ->with('stokDisetujui')
                ->get();

            foreach ($permintaanMaterial as $permintaan) {
                $totalTelahDigunakan += $permintaan->stokDisetujui->sum('jumlah_disetujui');
            }

            // Detail permintaan RAB yang sudah dikirim/selesai
            $detailPermintaanRAB = \App\Models\DetailPermintaanMaterial::where('rab_id', $rabId)
                ->whereIn('status', [2, 3])
                ->whereHas('permintaanMaterial', function ($query) use ($merkId) {
                    $query->where('merk_id', $merkId)
                        ->whereHas('stokDisetujui', function ($subQuery) {
                            $subQuery->where('jumlah_disetujui', '>', 0);
                        });
                })
                ->with([
                    'permintaanMaterial' => function ($query) use ($merkId) {
                        $query->where('merk_id', $merkId)->with('stokDisetujui');
                    }
                ])
                ->get();

            foreach ($detailPermintaanRAB as $detail) {
                foreach ($detail->permintaanMaterial as $permintaan) {
                    if ($permintaan->merk_id == $merkId) {
                        $totalTelahDigunakan += $permintaan->stokDisetujui->sum('jumlah_disetujui');
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error calculating telah digunakan RAB: ' . $e->getMessage(), [
                'merk_id' => $merkId,
                'rab_id' => $rabId,
                'trace' => $e->getTraceAsString()
            ]);
        }

        return (int) $totalTelahDigunakan;
    }

    public function saveAdendum()
    {
        $this->validate([
            'keterangan' => 'required|string|min:10',
            'list' => 'required|array|min:1',
        ], [
            'keterangan.required' => 'Keterangan perubahan harus diisi',
            'keterangan.min' => 'Keterangan minimal 10 karakter',
            'list.required' => 'Minimal ada 1 material yang diubah',
        ]);

        // #region agent log
        @file_put_contents(
            'c:\\Users\\MasterOdimm\\Documents\\GitHub\\dsda\\.cursor\\debug.log',
            json_encode([
                'sessionId' => 'debug-session',
                'runId' => 'post-fix',
                'hypothesisId' => 'H3',
                'location' => 'AdendumRab::saveAdendum',
                'message' => 'saveAdendum start',
                'data' => [
                    'jumlahInvalid' => $this->jumlahInvalid,
                    'listCount' => count($this->list),
                ],
                'timestamp' => round(microtime(true) * 1000)
            ]) . PHP_EOL,
            FILE_APPEND
        );
        // #endregion

        // Cegah submit ketika sebelumnya ada input jumlah yang tidak valid
        if ($this->jumlahInvalid) {
            $this->dispatch('swal-error', ['message' => 'Perbaiki jumlah baru yang kurang dari jumlah telah digunakan sebelum menyimpan.']);

            // #region agent log
            @file_put_contents(
                'c:\\Users\\MasterOdimm\\Documents\\GitHub\\dsda\\.cursor\\debug.log',
                json_encode([
                    'sessionId' => 'debug-session',
                    'runId' => 'post-fix',
                    'hypothesisId' => 'H3',
                    'location' => 'AdendumRab::saveAdendum',
                    'message' => 'blocked submit due to jumlahInvalid flag',
                    'data' => [
                        'jumlahInvalid' => $this->jumlahInvalid
                    ],
                    'timestamp' => round(microtime(true) * 1000)
                ]) . PHP_EOL,
                FILE_APPEND
            );
            // #endregion
            return;
        }

        // Validasi tambahan untuk mencegah error database
        $validationErrors = [];
        
        foreach ($this->list as $index => $item) {
            // Skip item yang sudah dihapus (action delete) kecuali perlu validasi hapus
            if ($item['action'] === 'delete') {
                // Validasi: tidak bisa hapus jika sudah digunakan
                $telahDigunakan = (int)($item['telah_digunakan'] ?? 0);
                if ($item['list_rab_id'] && $telahDigunakan > 0) {
                    $satuan = $item['merk']->barangStok->satuanBesar->nama ?? 'satuan';
                    $namaBarang = $item['merk']->barangStok->nama ?? 'Barang';
                    $validationErrors[] = "Barang \"{$namaBarang}\" tidak dapat dihapus karena sudah digunakan (" . number_format($telahDigunakan) . " {$satuan}).";
                }
                continue;
            }
            
            // Validasi jumlah baru untuk semua item yang tidak dihapus
            $telahDigunakan = (int)($item['telah_digunakan'] ?? 0);
            $jumlahBaru = (int)($item['jumlah_baru'] ?? 0);
            $satuan = $item['merk']->barangStok->satuanBesar->nama ?? 'satuan';
            $namaBarang = $item['merk']->barangStok->nama ?? 'Barang';
            
            // Validasi: jumlah baru tidak boleh kurang dari telah digunakan
            // Berlaku untuk semua item yang memiliki list_rab_id (item yang sudah ada di RAB asli)
            if ($item['list_rab_id'] && $jumlahBaru < $telahDigunakan) {
                $validationErrors[] = "Jumlah baru untuk \"{$namaBarang}\" tidak boleh kurang dari jumlah yang telah digunakan (" . number_format($telahDigunakan) . " {$satuan}). Minimum yang diizinkan: " . number_format($telahDigunakan) . " {$satuan}.";
                
                // #region agent log
                @file_put_contents(
                    'c:\\Users\\MasterOdimm\\Documents\\GitHub\\dsda\\.cursor\\debug.log',
                    json_encode([
                        'sessionId' => 'debug-session',
                        'runId' => 'post-fix',
                        'hypothesisId' => 'H4',
                        'location' => 'AdendumRab::saveAdendum',
                        'message' => 'validation error jumlah kurang digunakan',
                        'data' => [
                            'index' => $index,
                            'jumlahBaru' => $jumlahBaru,
                            'telahDigunakan' => $telahDigunakan,
                            'listRabId' => $item['list_rab_id'],
                        ],
                        'timestamp' => round(microtime(true) * 1000)
                    ]) . PHP_EOL,
                    FILE_APPEND
                );
                // #endregion
                continue;
            }
            
            // Validasi untuk item baru (add) - jika ada telah_digunakan, jumlah baru harus >= telah_digunakan
            if (!$item['list_rab_id'] && $telahDigunakan > 0 && $jumlahBaru < $telahDigunakan) {
                $validationErrors[] = "Jumlah baru untuk \"{$namaBarang}\" tidak boleh kurang dari jumlah yang telah digunakan (" . number_format($telahDigunakan) . " {$satuan}). Minimum yang diizinkan: " . number_format($telahDigunakan) . " {$satuan}.";
                continue;
            }
        }
        
        // Jika ada error validasi, tampilkan alert dan stop proses
        if (!empty($validationErrors)) {
            $errorMessage = implode('<br>', $validationErrors);
            $this->dispatch('swal-error', ['message' => $errorMessage]);
            return;
        }

        // Filter list yang benar-benar ada perubahan
        $listWithChanges = collect($this->list)->filter(function ($item) {
            return $item['action'] !== 'no_change' && 
                   ($item['action'] === 'add' || 
                    $item['action'] === 'edit' || 
                    $item['action'] === 'delete');
        });

        if ($listWithChanges->isEmpty()) {
            session()->flash('error', 'Tidak ada perubahan yang dilakukan');
            return;
        }

        // Create Adendum RAB
        $adendum = AdendumRabModel::create([
            'rab_id' => $this->rab->id,
            'user_id' => Auth::id(),
            'keterangan' => $this->keterangan,
            'is_approved' => false,
        ]);

        // Create Adendum List RAB
        $changes = [];
        foreach ($listWithChanges as $item) {
            AdendumListRab::create([
                'adendum_rab_id' => $adendum->id,
                'list_rab_id' => $item['list_rab_id'],
                'merk_id' => $item['merk']->id,
                'jumlah_lama' => $item['jumlah_lama'],
                'jumlah_baru' => $item['jumlah_baru'],
                'action' => $item['action'],
            ]);

            $changes[] = [
                'action' => $item['action'],
                'merk_id' => $item['merk']->id,
                'merk_nama' => $item['merk']->nama ?? '',
                'jumlah_lama' => $item['jumlah_lama'],
                'jumlah_baru' => $item['jumlah_baru'],
            ];
        }

        // Catat history create adendum
        AdendumHistory::create([
            'adendum_rab_id' => $adendum->id,
            'rab_id' => $this->rab->id,
            'user_id' => Auth::id(),
            'action' => 'create',
            'old_data' => null,
            'new_data' => [
                'changes' => $changes,
                'keterangan' => $this->keterangan,
            ],
            'keterangan' => $this->keterangan,
        ]);

        // Kirim notifikasi ke pembuat RAB
        $pembuatRab = $this->rab->user;
        $mess = 'RAB <span class="font-semibold">' . $this->rab->jenis_pekerjaan . '</span> memiliki permintaan adendum dari Kasatpel yang perlu dikonfirmasi.';
        
        Notification::send($pembuatRab, new UserNotification(
            $mess,
            "/rab/{$this->rab->id}/adendum/{$adendum->id}/approve"
        ));

        session()->flash('success', 'Adendum RAB berhasil dibuat dan menunggu konfirmasi dari pembuat RAB.');
        return redirect()->route('rab.show', ['rab' => $this->rab->id]);
    }

    public function render()
    {
        return view('livewire.adendum-rab');
    }
}
