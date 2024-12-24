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
        $this->syncPermissionsToRole();
    }

    public function selectAllForCategory($category)
    {
        if (isset($this->permissions[$category])) {
            $role = Role::findOrFail($this->roleId);

            // Tambahkan semua permission ke selectedPermissions
            foreach ($this->permissions[$category] as $permission) {
                if (!in_array($permission, $this->selectedPermissions)) {
                    $this->selectedPermissions[] = $permission;
                }
            }

            // Sinkronkan permissions ke role
            $permissionIds = Permission::whereIn('name', $this->permissions[$category])->pluck('id')->toArray();
            $role->permissions()->syncWithoutDetaching($permissionIds);
        }
    }

    public function resetAllForCategory($category)
    {
        if (isset($this->permissions[$category])) {
            $role = Role::findOrFail($this->roleId);

            // Hapus permissions kategori dari selectedPermissions
            $this->selectedPermissions = array_diff(
                $this->selectedPermissions,
                $this->permissions[$category]
            );

            // Hapus permissions dari role
            $permissionIds = Permission::whereIn('name', $this->permissions[$category])->pluck('id')->toArray();
            $role->permissions()->detach($permissionIds);
        }
    }

    public function isCategoryFullySelected($category)
    {
        if (isset($this->permissions[$category])) {
            return empty(array_diff($this->permissions[$category], $this->selectedPermissions));
        }

        return false;
    }

    public function syncPermissionsToRole()
    {
        $role = Role::findOrFail($this->roleId);
        $permissionIds = Permission::whereIn('name', $this->selectedPermissions)->pluck('id')->toArray();
        $role->permissions()->sync($permissionIds);
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
