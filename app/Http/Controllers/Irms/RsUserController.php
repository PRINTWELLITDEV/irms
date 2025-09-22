<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

use App\Models\IrmsSite;
use App\Models\RsUser;



class RsUserController extends Controller
{
    public function index()
    {
        if (auth()->user()->userid !== 'sa') {
            abort(403, 'Unauthorized');
        }
        // Call the stored procedure to get users
        $users = \DB::select('EXEC sp_view_users');
        $sites = \DB::table('irms_site')->get();

        // Get the current user's site description
        $site = IrmsSite::where('rssite', auth()->user()->rssite)->first();
        $site_desc = $site ? $site->rssite_desc : auth()->user()->rssite;

        // If you want to support AJAX, you may need to convert $users to an array
        if (request()->ajax()) {
            return response()->json([
                'users' => $users
            ]);
        }
        return view('irms.irms-layouts.manage-users', compact('users', 'sites', 'site_desc'));
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

        if (RsUser::where('userid', $validated['userid'])->exists()) {
            return redirect()->back()->withInput()->withErrors(['error' => 'User ID already exists.']);
        }

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
        $level = null;

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
        if (auth()->user()->userid !== 'sa') {
            abort(403, 'Unauthorized');
        }
        $users = \DB::select('EXEC sp_view_users');
        $selectedUser = \DB::select('EXEC sp_select_user ?', [$userid]);
        $sites = \DB::table('irms_site')->get();
        return view('irms.irms-layouts.manage-users', [
            'users' => $users,
            'selectedUser' => $selectedUser ? $selectedUser[0] : null,
            'sites' => $sites,
        ]);
    }

    public function edit(Request $request)
    {
        return view('users.edit', compact('rsUser'));
    }

    public function update(Request $request)
    {
        $userid = $request->input('userid');
        $validated = $request->validate([
            'rssite' => 'required|max:8',
            'name' => 'nullable|max:255',
            'email' => 'required|email|max:255',
            'gender' => 'nullable|max:10',
            'profile_pic_url' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'level' => 'nullable|integer',
            'password' => 'nullable|max:255'
        ]);

        // Handle profile picture upload
        if($request->hasFile('profile_pic_url')){
            $file = $request->file('profile_pic_url');
            $filename = uniqid() . '_' . $userid . '.png';
            $file->move(public_path('uploads/user-profile'), $filename);
            $profile_pic_url = 'uploads/user-profile/' . $filename;
        } else {
            // Use the existing profile picture if no new file is uploaded
            $profile_pic_url = $request->input('existing_profile_pic_url', 'uploads/user-profile/noprofile.png');
        }

        $updated_by = auth()->user()->userid ?? 'system';
        $level = $request->input('level');
        $password = $request->input('password');
        $hashedPassword = $password ? bcrypt($password) : null;

        try {
            \DB::statement('EXEC sp_update_user ?, ?, ?, ?, ?, ?, ?, ?, ?', [
                $validated['rssite'],
                $userid,
                $validated['name'],
                $validated['email'],
                $validated['gender'],
                $profile_pic_url,
                $level,
                $hashedPassword,
                $updated_by
            ]);
            return redirect()->route('rsusers.index')->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
