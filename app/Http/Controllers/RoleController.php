<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\UserController;
use App\Models\User;

class RoleController extends Controller
{
    public function index()
    {
            // جلب جميع الأدوار مع المستخدمين المرتبطين بها
    $roles = Role::with('users')->get();

    // جلب جميع المستخدمين مع الأدوار المرتبطة بهم
    $users = User::with('roles')->get();

    // تمرير المتغيرات إلى العرض
    return view('dashboard.roles.index', compact('roles', 'users'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('dashboard.roles.create', compact('permissions'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|unique:roles,name',
        'permissions' => 'required|array',
    ]);

    // إذا كانت البيانات سليمة، احفظ الدور في قاعدة البيانات
    $role = Role::create(['name' => $request->name]);

    // احفظ الصلاحيات المرتبطة بالدور
    $role->syncPermissions($request->permissions);

    // إعادة التوجيه إلى صفحة الأدوار مع رسالة نجاح
    return redirect()->route('roles.index')->with('success', 'Role created successfully.');
}




    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('dashboard.roles.edit', compact('role', 'permissions'));
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
    public function update(Request $request, $id)
    {
        // التحقق من المدخلات
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'sometimes|array',
        ]);

        // العثور على الدور بواسطة المعرف (ID)
        $role = Role::findOrFail($id);

        // تحديث اسم الدور
        $role->name = $request->input('name');
        $role->save();

        // مزامنة الصلاحيات (permissions) إذا تم تقديمها
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }
}
