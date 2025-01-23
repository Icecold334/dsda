<?php

namespace App\Livewire;

use App\Models\Stok;
use Livewire\Component;
use App\Models\StokDisetujui;
use App\Models\PengirimanStok;
use App\Models\TransaksiStok;
use Illuminate\Support\Facades\DB;

class ShowStok extends Component
{

    public $barang;
    public $stok;
    public $noteModalVisible = false;

    public $selectedItemHistory;

    public function mount()
    {
        $stok =  $this->stok->map(
            function ($item) {
                return [
                    'id' => $item->id,
                    'merk' => $item->merkStok,
                    'jumlah' => $item->jumlah,
                    'lokasi' => $item->lokasiStok,
                    'bagian' => $item->bagianStok,
                    'posisi' => $item->posisiStok,
                    'stok' => 1
                ];
            }
        );


        $darurat = TransaksiStok::whereHas('merkStok.barangStok', function ($query) {
            $query->where('id', $this->barang->id);
        })
            ->where('tipe', 'Penggunaan Langsung')
            ->where('status', '1')
            ->get()->map(
                function ($item) {
                    return [
                        'id' => $item->id,
                        'merk' => $item->merkStok,
                        'jumlah' => $item->jumlah,
                        'lokasi' => $item->lokasi_penerimaan,
                        'bagian' => null,
                        'posisi' => null,
                        'stok' => 0
                    ];
                }
            );

        $this->stok = $stok->merge($darurat);
    }



    public function historyStok($id)
    {
        $stok = Stok::find($id);
        // dd($stok);

        // Ambil data dari model Pengiriman
        $pengiriman = PengirimanStok::query()
            ->where('merk_id', $stok->merk_id)
            ->where('lokasi_id', $stok->lokasi_id)
            ->when($stok->bagian_id, function ($query) use ($stok) {
                return $query->where('bagian_id', $stok->bagian_id);
            })
            ->when($stok->posisi_id, function ($query) use ($stok) {
                return $query->where('posisi_id', $stok->posisi_id);
            })
            ->whereHas('detailPengirimanStok', function ($query) {
                return $query->where('status', 1);
            })
            ->get();

        $pengiriman = $pengiriman->isNotEmpty() ? $pengiriman->map(function ($data) {
            return [
                'id' => $data->id,
                'merk_id' => $data->merk_id,
                'merk' => $data,
                'jumlah' => $data->jumlah_diterima,
                'tanggal' => $data->created_at->format('Y-m-d H:i:s'),
                'type' => 'in',
            ];
        }) : collect([]);



        $transaksi = TransaksiStok::select('id', 'merk_id', 'jumlah', 'created_at as tanggal', DB::raw("'in' as type"))
            ->where('merk_id', $stok->merk_id)
            ->where('lokasi_id', $stok->lokasi_id)
            ->where('status', 1)
            ->get()
            ->toArray();

        // Ambil data dari model StokDisetujui
        $stokDisetujui = StokDisetujui::where('merk_id', $stok->merk_id)
            ->where('lokasi_id', $stok->lokasi_id)
            ->when($stok->bagian_id, function ($query) use ($stok) {
                return $query->where('bagian_id', $stok->bagian_id);
            })
            ->when($stok->posisi_id, function ($query) use ($stok) {
                return $query->where('posisi_id', $stok->posisi_id);
            })
            ->whereHas('permintaan.detailPermintaan', function ($query) {
                return $query->where('status', 1);
            })
            ->get();

        $stokDisetujui = $stokDisetujui->isNotEmpty() ? $stokDisetujui->map(function ($data) {
            return [
                'id' => $data->id,
                'merk_id' => $data->merk_id,
                'merk' => $data,
                'jumlah' => $data->jumlah_disetujui,
                'tanggal' => $data->created_at->format('Y-m-d H:i:s'),
                'type' => 'out',
            ];
        }) : collect([]);

        // Gabungkan data
        $history = $pengiriman->merge($stokDisetujui);


        // dd($history);
        $this->selectedItemHistory = $history;
        $this->noteModalVisible = true;
    }
    public function render()
    {
        return view('livewire.show-stok');
    }
}
