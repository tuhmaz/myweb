<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // POST [ name, email, password ]
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
            'password_confirmation' => 'required|same:password',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'data' => []
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        // User object
        $user = User::where("email", $request->email)->first();

        if (!empty($user)) {
            // User exists
            if (Hash::check($request->password, $user->password)) {
                // Password matched
                $token = $user->createToken("myAccessToken")->plainTextToken;

                return response()->json([
                    "status" => true,
                    "message" => "Login successful",
                    "token" => $token,
                    "data" => [
                        'user' => $user // قم بإضافة بيانات المستخدم هنا
                    ]
                ]);
            } else {
                return response()->json([
                    "status" => false,
                    "message" => "Password didn't match",
                    "data" => []
                ]);
            }
        } else {
            return response()->json([
                "status" => false,
                "message" => "Invalid Email value",
                "data" => []
            ]);
        }
    }
    public function profile()
    {
        $userData = auth()->user(); // تأكد من أن هذا الاستدعاء يتم بشكل صحيح

        return response()->json([
            "status" => true,
            "message" => "Profile information",
            "data" => $userData,
            "id" => auth()->user()->id // ربما تسبب في الخطأ إذا كان `auth()` لم يكن معرفًا بشكل صحيح
        ]);
    }


    public function logout()
    {
        $token = auth()->user()->currentAccessToken();
        $token->delete();

        return response()->json([
            "status" => true,
            "message" => "User Logged out successfully"
        ]);
    }
}
