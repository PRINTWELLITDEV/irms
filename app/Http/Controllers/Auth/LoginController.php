<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Models\RsUser;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showhomeForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
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

        try {
            if ($user && Hash::check($credentials['password'], $user->password)) {
                return $this->doLogin($request, $user);
            }
        } catch (\RuntimeException $e) {
            // Optionally log the error: \Log::error($e);
            // Fall through to show the same error as invalid credentials
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

        Session::save();

        // 🔥 manually update sessions table
        $sessionId = Session::getId();

        DB::table('sessions')
        ->where('id', $sessionId)
        ->update([
            'rssite'  => $user->rssite,
            // 'rsuserid' => (string) $user->getAttribute('userid'),
        ]);

        // ✅ store checkbox preference in a cookie (30 days)
        if ($request->filled('remember')) {
            Cookie::queue('remember_checked', true, 60 * 24 * 30); // 30 days
        } else {
            Cookie::queue(Cookie::forget('remember_checked'));
        }


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
