<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // تأكد من أنك تستخدم النموذج الصحيح للمستخدم
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // إنشاء المستخدم Admin
        $adminUser = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // يمكنك تغيير كلمة المرور لاحقًا
        ]);

        // تعيين دور الـ Admin للمستخدم
        $adminRole = Role::where('name', 'Admin')->first();

        if ($adminRole) {
            $adminUser->assignRole($adminRole);
        }

        // جلب جميع الصلاحيات وتعيينها للمستخدم
        $permissions = Permission::all();

        if ($permissions) {
            $adminUser->givePermissionTo($permissions);
        }
    }
}
