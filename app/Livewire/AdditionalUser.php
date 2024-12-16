<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\Auth;

class AdditionalUser extends Component
{
    public $search = ''; // Variabel untuk pencarian
    public $users = []; // Menyimpan hasil pencarian

    public function mount()
    {
        $this->loadUsers(); // Muat data awal saat komponen diinisialisasi
    }

    public function updatedSearch()
    {
        $this->loadUsers(); // Panggil ulang data saat pencarian diubah
    }

    public function loadUsers()
    {
        $user = Auth::user();
        $userUnitId = $user->unit_id;
        $unit = UnitKerja::find($userUnitId);
        $parentUnitId = $unit && $unit->parent_id ? $unit->parent_id : $userUnitId;

        $Users = User::query()
            ->whereNotIn('id', [1, $user->id]);

        if ($user->id != 1) {
            $Users->whereHas('unitKerja', function ($unitQuery) use ($parentUnitId) {
                $unitQuery->where('parent_id', $parentUnitId)
                    ->orWhere('id', $parentUnitId);
            });
        }

        if (!empty($this->search)) {
            $Users->where(function ($query) {
                $query->where('name', 'LIKE', "%{$this->search}%")
                    ->orWhere('nip', 'LIKE', "%{$this->search}%")
                    ->orWhere('email', 'LIKE', "%{$this->search}%")
                    ->orWhere('username', 'LIKE', "%{$this->search}%")
                    ->orWhereHas('unitKerja', function ($subQuery) {
                        $subQuery->where('nama', 'LIKE', "%{$this->search}%");
                    })
                    ->orWhereHas('lokasiStok', function ($subQuery) {
                        $subQuery->where('nama', 'LIKE', "%{$this->search}%");
                    })
                    ->orWhereHas('roles', function ($roleQuery) {
                        $roleQuery->where('name', 'LIKE', "%{$this->search}%");
                    });
            });
        }

        $this->users = $Users->get()->filter(function ($user) {
            return !$user->hasRole('guest');
        })->map(function ($user) {
            $roles = $user->getRoleNames();
            $user->formatted_roles = implode(', ', $roles->toArray());
            return $user;
        });
    }
    public function render()
    {
        return view('livewire.additional-user');
    }
}
