<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;


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
        // Protect all routes except login and register
        $this->middleware('auth:sanctum')->except(['login', 'store']);
    }

    public function index(Request $request)
    {
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

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
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

        // Generate JWT Token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User created successfully.',
            'data' => $user,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User logged in successfully.',
            'data' => $user,
            'token' => $token
        ]);
    }

    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'data' => $user->load('roles')
        ]);
    }

    public function update(Request $request, User $user)
    {
		Log::info('Update Request', [
        'headers' => $request->headers->all(),
        'body' => $request->all(),
    ]);
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

        $user->update($request->only([
            'name', 'email', 'phone', 'job_title', 'gender', 'country', 'social_links', 'bio'
        ]));

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path && Storage::exists($user->profile_photo_path)) {
                Storage::delete($user->profile_photo_path);
            }
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $user->profile_photo_path = $path;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully.',
            'data' => $user
        ]);
    }

    public function updatePermissionsRoles(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'sometimes|array',
            'permissions' => 'sometimes|array',
        ]);

        $currentRoles = $user->roles->pluck('name')->toArray();
        $currentPermissions = $user->permissions->pluck('name')->toArray();

        $newRoles = $request->roles ?? [];
        $user->syncRoles($newRoles);

        $newPermissions = $request->permissions ?? [];
        $user->syncPermissions($newPermissions);

        $this->logAndNotifyRoleChanges($user, $currentRoles, $newRoles);
        $this->logAndNotifyPermissionChanges($user, $currentPermissions, $newPermissions);

        return response()->json([
            'success' => true,
            'message' => 'User roles and permissions updated successfully.'
        ]);
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

            Log::info("Notification sent for role {$role} removed from user {$user->name}");
        }

        foreach ($addedRoles as $role) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->log("Assigned role '{$role}' to user '{$user->name}'");

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

            Log::info("Notification sent for permission {$permission} removed from user {$user->name}");
        }

        foreach ($addedPermissions as $permission) {
            activity()
                ->causedBy(auth()->user())
                ->performedOn($user)
                ->log("Assigned permission '{$permission}' to user '{$user->name}'");

            Log::info("Notification sent for permission {$permission} assigned to user {$user->name}");
        }
    }
}