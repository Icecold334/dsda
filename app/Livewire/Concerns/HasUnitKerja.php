<?php

namespace App\Livewire\Concerns;

use App\Models\UnitKerja;

trait HasUnitKerja
{
    public ?int $unit_id = null;

    public function initializeHasUnitKerja(): void
    {
        if ($this->unit_id !== null) {
            return;
        }

        $user = auth()->user();
        if (!$user) {
            return;
        }

        $unitId = $user->unit_id;

        if ($unitId) {
            $unit = UnitKerja::find($unitId);
            // Gunakan unit pusat (parent), bukan sub-unit
            $this->unit_id = $unit?->parent_id ?? $unitId;
        } else {
            // Superadmin atau user tanpa unit → pakai unit pusat pertama
            $this->unit_id = UnitKerja::whereNull('parent_id')->value('id');
        }
    }
}
