<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

use App\Models\IrmsSite;
use App\Models\RsUser;
use App\Models\RsLevel;

class RsUserController extends Controller
{
    public function index()
    {
        if (auth()->user()->level != 1 && auth()->user()->level != 2) {
            abort(401, 'Unauthorized');
        }
        // Call the stored procedure to get users
        $users = \DB::select('EXEC sp_view_users');
        $sites = \DB::table('irms_site')->get();
        $levels = RsLevel::orderBy('level')->get();

        // Get the current user's site description
        $site = IrmsSite::where('rssite', auth()->user()->rssite)->first();
        $site_desc = $site ? $site->rssite_desc : auth()->user()->rssite;

        // If you want to support AJAX, you may need to convert $users to an array
        if (request()->ajax()) {
            return response()->json([
                'users' => $users
            ]);
        }

        return view('irms.irms-layouts.manage-users', compact('users', 'sites', 'site_desc', 'levels'));
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
            'department' => 'nullable|max:255',
            'section' => 'nullable|max:255',
            'position' => 'nullable|max:255',
            'gender' => 'nullable|max:10',
            'profile_pic_url' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'level' => 'nullable|integer'
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
            $profile_pic_url = null;
        }

        $userid = $validated['userid'];
        $hashedPassword = bcrypt($validated['password']);
        $create_date = now();
        $created_by = auth()->user()->userid ?? 'system';

        \DB::statement('EXEC sp_add_user ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [
            $validated['rssite'],
            $validated['userid'],
            $validated['name'],
            $hashedPassword,
            $validated['email'],
            $validated['department'],
            $validated['section'],
            $validated['position'],
            $validated['gender'] ?? null,
            $profile_pic_url,
            $create_date,
            $created_by,
            $validated['level'] ?? null,
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

    // public function edit(Request $request)
    // {
    //     return view('users.edit', compact('rsUser'));
    // }

    public function update(Request $request)
    {
        $userid = $request->input('userid');
        $validated = $request->validate([
            'rssite' => 'required|max:8',
            'name' => 'nullable|max:255',
            'email' => 'required|email|max:255',
            'department' => 'nullable|max:255',
            'section' => 'nullable|max:255',
            'position' => 'nullable|max:255',
            'gender' => 'nullable|max:10',
            'profile_pic_url' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'level' => 'nullable|integer',
            'password' => 'nullable|max:255'
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_pic_url')) {
            $file = $request->file('profile_pic_url');
            $filename = uniqid() . '_' . $userid . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/user-profile'), $filename);
            $profile_pic_url = 'uploads/user-profile/' . $filename;
        } else {
            // Get the existing value
            $existing = RsUser::where('userid', $userid)->value('profile_pic_url');
            // Remove domain and public path if present
            $profile_pic_url = preg_replace('#^https?://[^/]+/irms/public/#', '', $existing);
            // Remove leading slash if present
            $profile_pic_url = ltrim($profile_pic_url, '/');
        }

        $updated_by = auth()->user()->userid ?? 'system';
        $level = $request->input('level');
        $password = $request->input('password');
        $hashedPassword = $password ? bcrypt($password) : null;

        try {
            \DB::statement('EXEC sp_update_user ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [
                $validated['rssite'],
                $userid,
                $validated['name'],
                $validated['email'],
                $validated['department'],
                $validated['section'],
                $validated['position'],
                $validated['gender'] ?? null,
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
