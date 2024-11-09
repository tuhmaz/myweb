<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;

class PermissionManagement extends Component
{
    public $name;
    public $permissions;

    public function mount()
    {
        $this->permissions = Permission::all();
    }

    public function createPermission()
    {
        $this->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $this->name]);

        $this->resetInputFields();
        $this->permissions = Permission::all();
    }

    public function deletePermission($id)
    {
        Permission::find($id)->delete();
        $this->permissions = Permission::all();
    }

    private function resetInputFields()
    {
        $this->name = '';
    }

    public function render()
    {
        return view('dashboard.permissions.index')->layout('layouts.layoutMaster');;
    }
}
