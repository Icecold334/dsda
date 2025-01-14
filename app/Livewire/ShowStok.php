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
        // $this->historyStok(1);
    }



    public function historyStok($id)
    {
        $stok = Stok::find($id);

        // Ambil data dari model Pengiriman
        $pengiriman = PengirimanStok::select('id', 'merk_id', 'jumlah_diterima as jumlah', 'created_at as tanggal', DB::raw("'out' as type"))
            ->where('merk_id', $stok->merk_id)

            ->where('lokasi_id', $stok->lokasi_id)->where('bagian_id', $stok->bagian_id)->where('posisi_id', $stok->posisi_id)->whereHas('detailPengirimanStok', function ($query) {
                return $query->where('status', 1);
            });

        $transaksi = TransaksiStok::select('id', 'merk_id', 'jumlah', 'created_at as tanggal', DB::raw("'in' as type"))->where('merk_id', $stok->merk_id)->where('lokasi_id', $stok->lokasi_id)->where('status', 1);;

        // Ambil data dari model StokDisetujui
        $stokDisetujui = StokDisetujui::select('id', 'merk_id', 'jumlah_disetujui as jumlah', 'created_at as tanggal', DB::raw("'in' as type"))
            ->where('merk_id', $stok->merk_id)

            ->where('lokasi_id', $stok->lokasi_id)->where('bagian_id', $stok->bagian_id)->where('posisi_id', $stok->posisi_id)->whereHas('permintaan.detailPermintaan', function ($query) {
                return $query->where('status', 1);
            });

        // Gabungkan data dengan union
        $history = $pengiriman->union($stokDisetujui)->union($transaksi)->orderBy('tanggal', 'asc')->get();
        // dd($history);
        $this->selectedItemHistory = $history;
        $this->noteModalVisible = true;
    }
    public function render()
    {
        return view('livewire.show-stok');
    }
}
