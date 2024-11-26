<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class CreateRole extends Component
{
    public $name;
    // public $guard_name = 'web'; // Default guard_name

    protected $rules = [
        'name' => 'required|string|max:255|unique:roles,name',
        // 'guard_name' => 'required|string|max:255',
    ];

    public function save()
    {
        $this->validate();

        // Create a new role
        Role::create([
            'name' => $this->name,
            // 'guard_name' => $this->guard_name,
        ]);

        session()->flash('success', 'Jabatan berhasil ditambahkan.');
        return redirect()->route('option.index'); // Redirect to options page or any desired route
    }

    public function render()
    {
        return view('livewire.create-role');
    }
}
