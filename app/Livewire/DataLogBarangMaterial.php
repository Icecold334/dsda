<?php

namespace App\Livewire;

use TCPDF;
use Carbon\Carbon;
use App\Models\Stok;
use App\Models\User;
use Livewire\Component;
use App\Models\UnitKerja;
use App\Models\LokasiStok;
use App\Models\PengirimanStok;
use App\Models\PermintaanMaterial;
use App\Models\DetailPermintaanMaterial;

class DataLogBarangMaterial extends Component
{
    public $sudin, $Rkb, $RKB, $isSeribu, $noteModalVisible, $selectedItemHistory, $list,  $withRab;
    public $modalVisible = false;
    public $detailList = [], $dataSelected;
    public $tanggalDipilih;
    public $jenisDipilih;
    public $filterFromDate;
    public $filterToDate;
    public $filterMonth;
    public $filterYear;
    public $filterJenis;
    public function downloadDoc($params)
    {
        $type = $params['type'];
        $withSign = $params['withSign'];
        $no = $params['no'];
        switch ($type) {
            case 'spb':
                return $this->spb($withSign, $no);
            case 'sppb':
                return $this->sppb($withSign, $no);
            case 'suratJalan':
                return $this->suratJalan($withSign, $no);
            case 'bast':
                return $this->bast($withSign);
        }
    }

    public function mount()
    {
        $this->applyFilters();
    }

    public function updated($property)
    {
        $this->applyFilters();
    }

    public function applyFilters()
    {
        $permintaan = PermintaanMaterial::whereHas('detailPermintaan', function ($detail) {
            $detail->where('status', '>=', 2)
                ->whereHas('user.unitKerja', function ($unit) {
                    $unit->where('parent_id', $this->unit_id)
                        ->orWhere('id', $this->unit_id);
                });
        });

        // Konversi filter tanggal ke timestamp untuk dicocokkan
        if ($this->filterFromDate && $this->filterToDate) {
            $from = strtotime($this->filterFromDate);
            $to = strtotime($this->filterToDate . ' 23:59:59');
            $permintaan->whereHas(
                'detailPermintaan',
                fn($q) =>
                $q->whereBetween('tanggal_permintaan', [$from, $to])
            );
        } elseif ($this->filterMonth && $this->filterYear) {
            $start = strtotime("{$this->filterYear}-{$this->filterMonth}-01");
            $end = strtotime(date("Y-m-t", $start) . ' 23:59:59');
            $permintaan->whereHas(
                'detailPermintaan',
                fn($q) =>
                $q->whereBetween('tanggal_permintaan', [$start, $end])
            );
        } elseif ($this->filterYear) {
            $start = strtotime("{$this->filterYear}-01-01");
            $end = strtotime("{$this->filterYear}-12-31 23:59:59");
            $permintaan->whereHas(
                'detailPermintaan',
                fn($q) =>
                $q->whereBetween('tanggal_permintaan', [$start, $end])
            );
        }

        $permintaan = $permintaan->get()
            ->groupBy(function ($item) {
                $timestamp = optional($item->detailPermintaan->first())->tanggal_permintaan;
                return $timestamp ? Carbon::createFromTimestamp($timestamp)->format('Y-m-d') . '|' . $item->detailPermintaan->gudang_id : null;
            })
            ->map(function ($items, $key) {
                [$tanggal, $gudang_id] = explode('|', $key);
                return [
                    'tanggal' => $tanggal,
                    'nomor' => $items->first()->detailPermintaan->nodin,
                    'uuid' => fake()->uuid,
                    'jenis' => 0,
                    'gudang_id' => $gudang_id,
                    'gudang_nama' => LokasiStok::find($gudang_id)->nama,
                    'jumlah' => $items->sum('jumlah'),
                ];
            })
            ->values();

        $pengiriman = PengirimanStok::whereHas('detailPengirimanStok', function ($q) {
            $q->where('status', 1);

            if ($this->filterFromDate && $this->filterToDate) {
                $from = strtotime($this->filterFromDate);
                $to = strtotime($this->filterToDate . ' 23:59:59');

                $q->whereBetween('tanggal', [$from, $to]);
            } elseif ($this->filterMonth && $this->filterYear) {
                $start = strtotime("{$this->filterYear}-{$this->filterMonth}-01");
                $end = strtotime("last day of {$this->filterYear}-{$this->filterMonth}") + 86399;

                $q->whereBetween('tanggal', [$start, $end]);
            } elseif ($this->filterYear) {
                $start = strtotime("{$this->filterYear}-01-01");
                $end = strtotime("{$this->filterYear}-12-31 23:59:59");

                $q->whereBetween('tanggal', [$start, $end]);
            }
        });



        $pengiriman = $pengiriman->get()
            ->groupBy(function ($item) {
                $timestamp = optional($item->detailPengirimanStok->first())->tanggal;

                return $timestamp ? Carbon::createFromTimestamp($timestamp)->format('Y-m-d') . '|' . $item->lokasi_id : null;
            })
            ->map(function ($items, $key) {
                [$tanggal, $lokasi_id] = explode('|', $key);
                return [
                    'tanggal' => $tanggal,
                    'nomor' => $items->first()->detailPengirimanStok->kode_pengiriman_stok,
                    'uuid' => fake()->uuid,
                    'jenis' => 1,
                    'gudang_id' => $lokasi_id,
                    'gudang_nama' => LokasiStok::find($lokasi_id)->nama,
                    'jumlah' => $items->sum('jumlah'),
                ];
            })
            ->values();

        $permintaan = collect($permintaan)->map(function ($item) {
            $item['jenis'] = 0;
            return $item;
        });

        $pengiriman = collect($pengiriman)->map(function ($item) {
            $item['jenis'] = 1;
            return $item;
        });

        $list = $permintaan->merge($pengiriman);

        if ($this->filterJenis !== null && $this->filterJenis !== '') {
            $list = $list->filter(fn($item) => $item['jenis'] == $this->filterJenis);
        }

        $this->list = $list->sortByDesc('tanggal')->values();
    }

    public function resetFilters()
    {
        $this->filterFromDate = null;
        $this->filterToDate = null;
        $this->filterMonth = null;
        $this->filterJenis = null;
        $this->filterYear = null;

        $this->applyFilters();
    }
    public function selectedTanggal($tanggal = null, $jenis = null, $gudangId = null)
    {
        $this->modalVisible = true;
        $this->tanggalDipilih = Carbon::parse($tanggal)->translatedFormat('l, d F Y');
        $this->jenisDipilih = $jenis;
        // dd($tanggal);

        if ($jenis == 0) {
            $this->detailList = PermintaanMaterial::whereHas('detailPermintaan', function ($dp) use ($gudangId) {
                return $dp->where('gudang_id', $gudangId);
            })->whereHas('detailPermintaan', function ($query) {
                $query->where('status', '>=', 2)
                    ->whereHas('user.unitKerja', function ($unit) {
                        $unit->where('parent_id', $this->unit_id)
                            ->orWhere('id', $this->unit_id);
                    });
            })
                ->get();

            $this->dataSelected = collect($this->detailList)->first()->detailPermintaan;
        } else {
            $this->detailList = PengirimanStok::whereHas('detailPengirimanStok', function ($dp) use ($gudangId) {
                return $dp->where('lokasi_id', $gudangId);
            })
                ->whereHas('detailPengirimanStok', function ($query) {
                    $query->where('status', 1);
                })
                ->get();
        }
    }

    public function spb($sign = false, $spb)
    {
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        // Set margin (Left, Top, Right)
        $pdf->SetMargins(20, 5, 20);
        $pdf->SetCreator('Sistem Permintaan Bahan');
        $pdf->SetAuthor('Dinas SDA');
        $pdf->SetTitle('Surat Permintaan Barang Material');

        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10, '', '',);
        $ttdPath = storage_path('app/public/ttdPengiriman/nurdin.png');

        $permintaan = DetailPermintaanMaterial::where('nodin', $spb)->first();
        $unit_id = $this->unit_id;
        $permintaan->unit = UnitKerja::find($unit_id);

        $kasatpel =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('id', $unit_id);
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Kepala Satuan Pelaksana%');
            })->first();
        $pemel =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('parent_id', $unit_id)->where('nama', 'like', '%Pemeliharaan%');
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Kepala Seksi%');
            })->first();

        $Rkb = $this->Rkb;
        $RKB = $this->RKB;
        $sudin = $this->sudin;
        $isSeribu = $this->isSeribu;
        if ($isSeribu) {
            $withRab = $permintaan->permintaanMaterial->first()->rab_id;
        } else {
            $withRab = $permintaan->rab_id;
        }
        // dd(
        //     !$withRab ? 'pdf.nodin' : ($this->isSeribu ? 'pdf.spb1000' : 'pdf.spb')
        // );
        $html = view(!$withRab ? 'pdf.nodin' : ($this->isSeribu ? 'pdf.spb1000' : 'pdf.spb'), compact('ttdPath', 'permintaan', 'kasatpel', 'pemel', 'Rkb', 'RKB', 'sudin', 'isSeribu', 'sign'))->render();

        $pdf->writeHTML($html, true, false, true, false, '');
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->Output('', 'S');
        }, 0 ? 'Nota Dinas.pdf' : 'Surat Permintaan Barang.pdf');
    }

    public function suratJalan($sign, $spb)
    {
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetMargins(20, 5, 20);

        $pdf->SetCreator('Sistem Permintaan Barang');
        $pdf->SetAuthor('Dinas SDA');
        $pdf->SetTitle('Surat Jalan');

        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 11);

        // optional kalau ada ttd atau cap
        $ttdPath = storage_path('app/public/ttdPengiriman/nurdin.png');

        $permintaan = DetailPermintaanMaterial::where('nodin', $spb)->first();

        $unit_id = $this->unit_id;
        $permintaan->unit = UnitKerja::find($unit_id);
        $kasatpel =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('id', $unit_id);
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Kepala Satuan Pelaksana%');
            })->first();
        $penjaga =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('id', $unit_id);
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Penjaga Gudang%');
            })->where('lokasi_id', $permintaan->gudang_id)->first();
        $pengurus =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('id', $unit_id);
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Pengurus Barang%');
            })->first();
        $Rkb = $this->Rkb;
        $RKB = $this->RKB;
        $sudin = $this->sudin;
        $isSeribu = $this->isSeribu;
        $html = view('pdf.surat-jalan', compact('permintaan', 'kasatpel', 'penjaga', 'pengurus', 'ttdPath', 'Rkb', 'RKB', 'sudin', 'isSeribu', 'sign'))->render();

        $pdf->writeHTML($html, true, false, true, false, '');
        // return 1;
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->Output('', 'S');
        }, 'Surat-Jalan.pdf');
    }

    public function sppb($sign, $spb)
    {
        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetMargins(20, 5, 20);

        $pdf->SetCreator('Sistem Permintaan Barang');
        $pdf->SetAuthor('Dinas SDA');
        $pdf->SetTitle('SPPB');

        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 10);

        // optional kalau ada ttd atau cap
        $ttdPath = storage_path('app/public/ttdPengiriman/nurdin.png');

        $permintaan = DetailPermintaanMaterial::where('nodin', $spb)->first();
        $unit_id = $this->unit_id;
        $permintaan->unit = UnitKerja::find($unit_id);
        $kasatpel =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('id', $unit_id);
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Kepala Satuan Pelaksana%');
            })->first();
        $penjaga =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('id', $unit_id);
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Penjaga Gudang%');
            })->where('lokasi_id', $permintaan->gudang_id)->first();
        $pengurus =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('id', $unit_id);
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Pengurus Barang%');
            })->first();

        $kasubag =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('parent_id', $unit_id)->where('nama', 'like', '%Tata Usaha%');
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Kepala Subbagian%');
            })->first();
        $Rkb = $this->Rkb;
        $RKB = $this->RKB;
        $sudin = $this->sudin;
        $isSeribu = $this->isSeribu;
        $html = view('pdf.sppb', compact('permintaan', 'kasatpel', 'penjaga', 'sign', 'pengurus', 'ttdPath', 'kasubag', 'Rkb', 'RKB', 'sudin', 'isSeribu'))->render();

        $pdf->writeHTML($html, true, false, true, false, '');
        // return 1;
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->Output('', 'S');
        }, 'SPPB.pdf');
    }
    public function render()
    {
        return view('livewire.data-log-barang-material');
    }
}
