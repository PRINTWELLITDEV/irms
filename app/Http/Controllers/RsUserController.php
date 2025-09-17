<?php

namespace App\Http\Controllers;

use App\Models\RsUser;
use App\Models\IrmsSite;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class RsUserController extends Controller
{
    public function index()
    {
        // Call the stored procedure to get users
        $users = \DB::select('EXEC sp_view_users');
        $sites = \DB::table('irms_site')->get();

        // If you want to support AJAX, you may need to convert $users to an array
        if (request()->ajax()) {
            return response()->json([
                'users' => $users
            ]);
        }
        return view('irms.irms-layouts.manage-users', compact('users', 'sites'));
    }
    
    public function show($userid)
    {
        $user = \DB::select('EXEC sp_select_user ?', [$userid]);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        return response()->json($user[0]);
    }

    public function getRememberTokenName()
    {
        return null; // disables remember_token usage
    }


    public function create()
    {
        return view('rsusers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rssite' => 'required|max:8',
            'userid' => 'required|max:8',
            'name' => 'nullable|max:255',
            'password' => 'required|max:255',
            'email' => 'required|email|max:255',
            'gender' => 'nullable|max:10',
            'profile_pic_url' => 'nullable|file|mimes:jpg,jpeg,png|max:2048'
        ]);

        if($request->hasFile('profile_pic_url')){
            $file = $request->file('profile_pic_url');
            $filename = uniqid() . '_' . $validated['userid'] . '.png';
            $file->move(public_path('uploads/user-profile'), $filename);
            $profile_pic_url = 'uploads/user-profile/' . $filename;
        } else {
            $profile_pic_url = 'uploads/user-profile/noprofile.png';
        }

        $userid = $validated['userid'];
        $hashedPassword = bcrypt($validated['password']);
        $create_date = now();
        $created_by = auth()->user()->userid ?? 'system';
        $level = 1; // Set default level to 1

        \DB::statement('EXEC sp_add_user ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [
            $validated['rssite'],
            $validated['userid'],
            $validated['name'],
            $hashedPassword,
            $validated['email'],
            $validated['gender'],
            $profile_pic_url,
            $create_date,
            $created_by,
            $level
        ]);

        return redirect('/irms/manage-users')->with('success', "$userid user successfully!");
    }
    
    public function view($userid)
    {
        $users = \DB::select('EXEC sp_view_users');
        $selectedUser = \DB::select('EXEC sp_select_user ?', [$userid]);
        $sites = \DB::table('irms_site')->get();
        return view('irms.irms-layouts.manage-users', [
            'users' => $users,
            'selectedUser' => $selectedUser ? $selectedUser[0] : null,
            'sites' => $sites,
        ]);
    }
}
