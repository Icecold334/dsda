<?php

namespace App\Livewire;

use App\Models\UnitKerja;
use TCPDF;
use App\Models\User;
use Livewire\Component;

class DownloadRab extends Component
{
    public $rab;

    public function download()
    {
        $pdf = new \TCPDF('P', 'mm', 'F4', true, 'UTF-8', false);
        $pdf->SetCreator('Dinas SDA');
        $pdf->SetAuthor('Dinas SDA');
        $pdf->SetTitle('REKAPITULASI KEBUTUHAN BAHAN MATERIAL');

        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);

        $rab = $this->rab;
        $unit_id = $this->unit_id;
        $rab->unit = UnitKerja::find($unit_id);
        $rab->kota =
            $kasudin =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('id', $unit_id);
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Kepala Suku%');
            })->first();
        $kasi =
            User::whereHas('unitKerja', function ($unit) use ($unit_id) {
                return $unit->where('parent_id', $unit_id)->where('nama', 'like', '%Seksi Perencanaan%');
            })->whereHas('roles', function ($role) {
                return $role->where('name', 'like', '%Kepala Seksi%');
            })->first();

        $html = view('pdf.rab', compact('rab', 'kasudin', 'kasi'))->render();

        $pdf->writeHTML($html, true, false, true, false, '');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->Output('', 'S');
        }, 'RAB.pdf');
    }



    public function render()
    {
        return view('livewire.download-rab');
    }
}
