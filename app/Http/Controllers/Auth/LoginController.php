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
            // 👇 FIXED: redirect to IRMS instead of dashboard
            return redirect()->route('irms.dashboard');
        }

        return view('auth.login');
    }

    public function showLoginForm()
    {
        if (Auth::check()) {
            // 👇 FIXED: redirect to IRMS instead of dashboard
            return redirect()->route('irms.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'userid' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = RsUser::where('userid', $credentials['userid'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['userid' => 'Invalid user ID or password']);
        }

        Auth::login($user, $request->filled('remember'));
        $request->session()->regenerate();

        Session::put('user', [
            'rssite' => $user->rssite,
            'name' => $user->name,
            'userid' => $user->userid,
        ]);

        DB::table('sessions')
            ->where('id', Session::getId())
            ->update(['rssite' => $user->rssite]);

        return redirect()->route('irms.dashboard');
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
                'name' => $user->name,
                'userid' => $user->userid,
                'profile_pic_url' => $user->profile_pic_url
                    ? $user->profile_pic_url
                    : 'uploads/user-profile/noprofile.png',
            ]
        ]);

        Session::save();

        $sessionId = Session::getId();

        DB::table('sessions')
            ->where('id', $sessionId)
            ->update([
                'rssite' => $user->rssite,
            ]);

        // ✅ FIXED: redirect to IRMS instead of undefined 'dashboard'
        return redirect()->intended(route('irms.dashboard'));
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
