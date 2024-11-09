<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public function index()
    {
        // جلب جميع الأدوار مع المستخدمين المرتبطين بها
        $roles = Role::with('users')->get();

        // جلب جميع المستخدمين مع الأدوار المرتبطة بهم
        $users = User::with('roles')->get();

        // إرجاع المتغيرات بصيغة JSON
        return response()->json([
            'roles' => $roles,
            'users' => $users
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
        ]);

        // إنشاء الدور
        $role = Role::create(['name' => $request->name]);

        // مزامنة الصلاحيات المرتبطة بالدور
        $role->syncPermissions($request->permissions);

        // إرجاع رسالة النجاح والدور المضاف
        return response()->json(['message' => 'Role created successfully', 'role' => $role], 201);
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        return response()->json($role);
    }

    public function edit($id)
    {
        // هذه الدالة عادة تستخدم لعرض نموذج التعديل في الواجهة.
        // لكن بالنسبة لـ API، يمكن استخدام show لجلب البيانات وتعديلها من خلال `update`.
        return response()->json(['message' => 'Edit action is not needed for API, please use `show` for details.']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'sometimes|array',
        ]);

        // العثور على الدور بواسطة المعرف (ID)
        $role = Role::findOrFail($id);

        // تحديث اسم الدور
        $role->name = $request->input('name');
        $role->save();

        // مزامنة الصلاحيات إذا تم تقديمها
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json(['message' => 'Role updated successfully', 'role' => $role]);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }
}
