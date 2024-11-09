<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Notifications\RoleAssigned;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['login', 'store']);
    }
	
    public function index(Request $request)
    {
      $users = DB::table('users')->get();
         $roles = Role::all();

         $users = User::with('roles')
            ->when($request->get('role'), function ($query) use ($request) {
                $query->whereHas('roles', function ($query) use ($request) {
                    $query->where('name', $request->get('role'));
                });
            })
            ->when($request->get('search'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->get('search') . '%')
                          ->orWhere('email', 'like', '%' . $request->get('search') . '%');
                });
            })
            ->paginate(10);

        return view('dashboard.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('dashboard.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('dashboard.users.edit', compact('user'));
    }



    public function permissions_roles(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('dashboard.users.permissions-roles', compact('user', 'roles', 'permissions'));
    }




    public function update(Request $request, User $user)
    {
		Log::info('Update Request', [
        'headers' => $request->headers->all(),
        'body' => $request->all(),
    ]);
        // التحقق من الحقول الأساسية
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:15',
            'job_title' => 'nullable|string|max:100',
            'gender' => 'nullable|in:male,female',
            'country' => 'nullable|string|max:100',
            'social_links' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_online' => 'boolean',
        ]);

        // تحديث المعلومات الأساسية
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->job_title = $request->job_title;
        $user->gender = $request->gender;
        $user->country = $request->country;
        $facebookUsername = $request->input('facebook_username');
         $user->social_links = 'https://facebook.com/' . $facebookUsername;
        $user->bio = $request->bio;


        // معالجة وتحديث الصورة الرمزية
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::delete($user->profile_photo_path);
            }
            $path = $request->file('profile_photo')->store('profile_photos');
            $user->profile_photo_path = $path;
        }
        if ($request->hasFile('profile_photo')) {
          $path = $request->file('profile_photo')->store('profile-photos', 'public');
          $user->profile_photo_path = $path;
          $user->save();
      }

        // حفظ التحديثات
        $user->save();

        return redirect()->route('users.index')->with('success', 'User information updated successfully.');
    }



    public function show(User $user)
{
    return view('dashboard.users.show', compact('user'));
}



    public function updatePermissionsRoles(Request $request, User $user)
    {
        // التحقق من الحقول التي يمكن أن تكون موجودة أو لا
        $request->validate([
            'roles' => 'sometimes|array',
            'permissions' => 'sometimes|array',
        ]);

        // الحصول على الأدوار والصلاحيات الحالية للمستخدم
        $currentRoles = $user->roles->pluck('name')->toArray();
        $currentPermissions = $user->permissions->pluck('name')->toArray();

        // مزامنة الأدوار
        $newRoles = $request->roles ?? [];
        $user->syncRoles($newRoles);

        // مزامنة الصلاحيات
        $newPermissions = $request->permissions ?? [];
        $user->syncPermissions($newPermissions);

        // تسجيل الأنشطة وإرسال الإشعارات للأدوار الجديدة والمزالة
        $this->logAndNotifyRoleChanges($user, $currentRoles, $newRoles);

        // تسجيل الأنشطة وإرسال الإشعارات للصلاحيات الجديدة والمزالة
        $this->logAndNotifyPermissionChanges($user, $currentPermissions, $newPermissions);

        return redirect()->route('users.index')->with('success', 'User roles and permissions updated successfully.');
    }



    private function logAndNotifyRoleChanges(User $user, array $currentRoles, array $newRoles)
    {
        $removedRoles = array_diff($currentRoles, $newRoles);
        $addedRoles = array_diff($newRoles, $currentRoles);

        foreach ($removedRoles as $role) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->log("Removed role '{$role}' from user '{$user->name}'");

            $user->notify(new RoleAssigned($role, null, 'removed'));
            Log::info("Notification sent for role {$role} removed from user {$user->name}");
        }

        foreach ($addedRoles as $role) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->log("Assigned role '{$role}' to user '{$user->name}'");

            $user->notify(new RoleAssigned($role, null, 'assigned'));
            Log::info("Notification sent for role {$role} assigned to user {$user->name}");
        }
    }

    private function logAndNotifyPermissionChanges(User $user, array $currentPermissions, array $newPermissions)
    {
        $removedPermissions = array_diff($currentPermissions, $newPermissions);
        $addedPermissions = array_diff($newPermissions, $currentPermissions);

        foreach ($removedPermissions as $permission) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->log("Removed permission '{$permission}' from user '{$user->name}'");

            $user->notify(new RoleAssigned(null, $permission, 'removed'));
            Log::info("Notification sent for permission {$permission} removed from user {$user->name}");
        }

        foreach ($addedPermissions as $permission) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->log("Assigned permission '{$permission}' to user '{$user->name}'");

            $user->notify(new RoleAssigned(null, $permission, 'assigned'));
            Log::info("Notification sent for permission {$permission} assigned to user {$user->name}");
        }
    }

}
