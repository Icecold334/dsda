<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionShow extends Component
{
    public $roleId; // Current role ID
    public $formattedRole; // Display-friendly role name
    public $permissions = []; // All permissions grouped by category
    public $selectedPermissions = []; // Permissions selected by the user

    public function updatedSelectedPermissions()
    {
        // Find the role by ID
        $role = Role::findOrFail($this->roleId);

        // Convert selected permission names to their IDs
        $permissionIds = Permission::whereIn('name', $this->selectedPermissions)->pluck('id')->toArray();

        // dd($permissionIds);
        // Sync the permissions to the role
        $role->permissions()->sync($permissionIds);
    }

    public function selectAllForCategory($category)
    {
        // Pastikan kategori ada
        if (isset($this->permissions[$category])) {
            // Tambahkan semua permission di kategori ke selectedPermissions jika belum ada
            foreach ($this->permissions[$category] as $permission) {
                if (!in_array($permission, $this->selectedPermissions)) {
                    $this->selectedPermissions[] = $permission;
                }
            }
        }
    }

    public function resetAllForCategory($category)
    {
        // Pastikan kategori ada
        if (isset($this->permissions[$category])) {
            // Hapus semua permission dalam kategori dari selectedPermissions
            $this->selectedPermissions = array_diff(
                $this->selectedPermissions,
                $this->permissions[$category]
            );
        }
    }

    public function isCategoryFullySelected($category)
    {
        // Periksa jika semua izin dalam kategori ada di selectedPermissions
        if (isset($this->permissions[$category])) {
            return empty(array_diff($this->permissions[$category], $this->selectedPermissions));
        }

        return false;
    }

    public function mount($option)
    {
        $role = Role::findOrFail($option);
        // dd($role);
        $this->roleId = $role->id;

        // $list = ['aset_new', 'aset_edit'];

        // Retrieve permissions grouped by category (prefix before the underscore)
        // $permissions = Permission::all()->groupBy(function ($permission) {
        //     return ucfirst(explode('_', $permission->name)[0]); // Extract the category and capitalize the first letter
        // });
        $permissions = Permission::where('type', true)->get()->groupBy(function ($permission) {
            return ucfirst(explode('_', $permission->name)[0]); // Extract the category and capitalize the first letter
        });

        // dd($permissions);

        // Structure permissions into categories and actions
        foreach ($permissions as $category => $actions) {
            $this->permissions[$category] = $actions->pluck('name')->toArray();
        }

        // Load existing permissions for the role
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray();
    }


    public function render()
    {
        return view('livewire.permission-show');
    }
}
