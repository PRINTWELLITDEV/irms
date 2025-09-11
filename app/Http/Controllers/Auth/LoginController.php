<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\RsUser;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login'); // resources/views/auth/login.blade.php
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'userid'   => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Find user by userid
        $user = RsUser::where('userid', $credentials['userid'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            return $this->doLogin($request, $user);
        }

        // ❌ Login failed
        throw ValidationException::withMessages([
            'userid' => [trans('auth.failed')],
        ]);
    }

    /**
     * Perform login and redirect.
     */
    protected function doLogin(Request $request, RsUser $user)
    {
        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();

        session([
            'user' => [
                'rssite' => $user->rssite,
                'name'   => $user->name,
                'userid' => $user->userid,
                'profile_pic_url'  => $user->profile_pic_url ? $user->profile_pic_url : 'uploads/user-profile/noprofile.png',
            ]
        ]);


        return redirect()->intended(route('dashboard'));
    }

    /**
     * Logout the user.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
