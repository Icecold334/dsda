<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\UnitKerja;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class UsersIndex extends Component
{
    use WithPagination;

    public $search = '';
    public $role = '';
    public $unit = '';
    public $verified = '';
    public $selectedUsers = [];
    public $selectAll = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'role' => ['except' => ''],
        'unit' => ['except' => ''],
        'verified' => ['except' => ''],
    ];

    public function mount()
    {
        $this->search = request('search', '');
        $this->role = request('role', '');
        $this->unit = request('unit', '');
        $this->verified = request('verified', '');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingRole()
    {
        $this->resetPage();
    }

    public function updatingUnit()
    {
        $this->resetPage();
    }

    public function updatingVerified()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'role', 'unit', 'verified']);
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedUsers = $this->getUsers()->pluck('id')->toArray();
        } else {
            $this->selectedUsers = [];
        }
    }

    public function getUsers()
    {
        $query = User::with(['unitKerja', 'lokasiStok', 'kecamatan', 'roles']);

        // Search functionality
        if (!empty($this->search)) {
            $search = $this->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if (!empty($this->role)) {
            $query->whereHas('roles', function ($q) {
                $q->where('id', $this->role);
            });
        }

        // Filter by unit
        if (!empty($this->unit)) {
            $query->where('unit_id', $this->unit);
        }

        // Filter by verification status
        if ($this->verified !== '') {
            if ($this->verified == '1') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        return $query->orderBy('name')->paginate(15);
    }

    public function render()
    {
        $users = $this->getUsers();
        $roles = Role::orderBy('name')->get();
        $units = UnitKerja::orderBy('nama')->get();

        return view('livewire.users-index', compact('users', 'roles', 'units'));
    }
}
