<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class SocialAuthController extends Controller
{
  public function redirectToGoogle()
  {
    return Socialite::driver('google')->redirect();
  }


  public function handleGoogleCallback()
  {
    try {
      $googleUser = Socialite::driver('google')->stateless()->user();

      // حاول تسجيل البيانات للتحقق من استقبالها من Google
      Log::info('Google User:', [
        'name' => $googleUser->getName(),
        'email' => $googleUser->getEmail(),
        'google_id' => $googleUser->getId(),
      ]);

      $user = User::where('email', $googleUser->getEmail())
        ->orWhere('google_id', $googleUser->getId())
        ->first();

      if (!$user) {
        // حاول تسجيل بيانات إنشاء المستخدم
        Log::info('Creating new user for Google User:', [
          'name' => $googleUser->getName(),
          'email' => $googleUser->getEmail(),
        ]);

        $user = User::create([
          'name' => $googleUser->getName(),
          'email' => $googleUser->getEmail(),
          'google_id' => $googleUser->getId(),
          'password' => bcrypt('password'), // كلمة مرور مؤقتة
        ]);
      }

      // تسجيل دخول المستخدم
      Auth::login($user);

      return redirect()->intended('/');
    } catch (\Exception $e) {
      Log::error('Authentication failed:', ['error' => $e->getMessage()]);
      return redirect('/login')->withErrors(['message' => 'Authentication failed, please try again.']);
    }
  }
}
