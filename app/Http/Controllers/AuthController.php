<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\RsUser;
use App\Models\IrmsSite;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'rssite'   => 'required|string',
            'userid'   => 'required|string',
            'password' => 'required|string',
        ]);

        $user = RsUser::where('rssite', $request->rssite)
            ->where('userid', $request->userid)
            ->where('password', $request->password) // ⚠️ plain text for now
            ->first();

        if ($user) {
            session([
                'user' => [
                    'rssite' => $user->rssite,
                    'name'   => $user->name,
                    'userid' => $user->userid,
                    'profile_pic_url'  => $user->profile_pic_url ? $user->profile_pic_url : 'uploads/user-profile/noprofile.png',
                ]
            ]);

            return response()->json([
                'success'  => true,
                'redirect' => route('irms.dashboard'),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials.',
        ], 401);
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login.page');
    }

    public function showLoginPage()
    {
        $sites = IrmsSite::all(); // or ->orderBy('name')->get();
        return view('Login', compact('sites'));
    }
}
