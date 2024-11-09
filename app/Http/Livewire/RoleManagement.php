<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleManagement extends Component
{
    public $name;
    public $rolePermissions = [];
    public $roles;
    public $permissions;

    public function mount()
    {
        $this->roles = Role::with('permissions')->get();
        $this->permissions = Permission::all();
    }

    public function createRole()
    {
        $this->validate([
            'name' => 'required|unique:roles,name',
            'rolePermissions' => 'required|array',
        ]);

        $role = Role::create(['name' => $this->name]);
        $role->syncPermissions($this->rolePermissions);

        $this->resetInputFields();
        $this->roles = Role::with('permissions')->get();
    }

    public function deleteRole($id)
    {
        Role::find($id)->delete();
        $this->roles = Role::with('permissions')->get();
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->rolePermissions = [];
    }

    public function render()
    {
        return view('dashboard.roles.index')
            ->layout('layouts.layoutMaster');
    }
    public function create()
{
    $permissions = Permission::all();
    return view('dashboard.roles.create', compact('permissions'));
}

}
