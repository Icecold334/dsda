<?php

namespace App\Livewire;

use App\Models\Stok;
use Livewire\Component;
use App\Models\PengirimanStok;
use App\Models\DetailPengirimanStok;
use Illuminate\Support\Facades\Auth;

class ApprovalButton extends Component
{
    public $id;
    public function approval($table)
    {
        if ($table == 'detail-pengiriman-stok') {
            $detailPengiriman = DetailPengirimanStok::find($this->id);
            if (!$detailPengiriman) {
                session()->flash('error', 'Data pengiriman tidak ditemukan.');
                return;
            }

            $user = Auth::user();


            // Mengisi persetujuan sesuai role
            if ($user->getRoleNames()[0] == 'user') {
                $detailPengiriman->user_id = $user->id;
            } elseif ($user->getRoleNames()[0] == 'superadmin') {
                $detailPengiriman->super_id = $user->id;
            } elseif ($user->getRoleNames()[0] == 'admin') {
                $detailPengiriman->admin_id = $user->id;
            } else {
                session()->flash('error', 'Role pengguna tidak valid untuk persetujuan.');
                return;
            }

            // Simpan data persetujuan
            $detailPengiriman->save();

            // Cek apakah kedua persetujuan (user dan super/admin) sudah terisi
            if ($detailPengiriman->super_id && $detailPengiriman->admin_id) {
                // Menambahkan stok
                $pengirimanItems = PengirimanStok::where('detail_pengiriman_id', $detailPengiriman->id)->get();

                foreach ($pengirimanItems as $pengiriman) {
                    // Menambahkan stok sesuai lokasi, bagian, dan posisi
                    $stok = Stok::firstOrCreate(
                        [
                            'merk_id' => $pengiriman->merk_id,
                            'lokasi_id' => $pengiriman->lokasi_id,
                            'bagian_id' => $pengiriman->bagian_id,
                            'posisi_id' => $pengiriman->posisi_id,
                        ],
                        ['jumlah' => 0]  // Atur stok awal jika belum ada
                    );

                    $stok->jumlah += $pengiriman->jumlah;
                    $stok->save();
                }

                session()->flash('success', 'Persetujuan berhasil, stok telah diperbarui.');
            } else {
                session()->flash('info', 'Persetujuan berhasil, menunggu persetujuan lainnya.');
            }
        } else {
            session()->flash('error', 'Tabel tidak valid untuk persetujuan.');
        }
    }

    public function render()
    {
        return view('livewire.approval-button');
    }
}
