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
        if (auth()->user()->level != 1) {
            abort(401, 'Unauthorized');
        }
        // Call the stored procedure to get users
        // $users = \DB::select('EXEC sp_view_users');
        $sites = \DB::table('irms_site')->get();
        $levels = RsLevel::orderBy('level')->get();

        // Get the current user's site description
        $site = IrmsSite::where('rssite', auth()->user()->rssite)->first();
        $site_desc = $site ? $site->rssite_desc : auth()->user()->rssite;

        // If you want to support AJAX, you may need to convert $users to an array
        // if (request()->ajax()) {
        //     return response()->json([
        //         'users' => $users
        //     ]);
        // }

        // return view('irms.irms-layouts.manage-users', compact('users', 'sites', 'site_desc', 'levels'));
        return view('irms.irms-layouts.manage-users', compact('sites', 'site_desc', 'levels'));

    }

    public function userlist()
    {
        $users = \DB::select('EXEC sp_view_users');
        return view('irms.irms-tables.user-list', compact('users'))->render();
    }

    public function show($userid)
    {
        $user = \DB::select('EXEC sp_select_user ?', [$userid]);

        if (!$user) {
            return response()->json([
                'error' => 'User not found'
            ], 404);
        }

        $permissionsFile = storage_path('app/navigation_permissions.json');

        $permissions = [];

        if (file_exists($permissionsFile)) {
            $permissions = json_decode(
                file_get_contents($permissionsFile),
                true
            ) ?? [];
        }

        $hasQuantityMove = in_array(
            $userid,
            $permissions['quantity_move'] ?? []
        );

        return response()->json([
            'user' => $user[0],
            'permissions' => [
                'quantity_move' => $hasQuantityMove
            ]
        ]);
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
            $msg = 'User ID already exists.';
            if ($request->ajax()) {
                return response()->json(['message' => $msg], 422);
            }
            return redirect()->back()->withInput()->withErrors(['error' => $msg]);
        }

        if ($request->hasFile('profile_pic_url')) {
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

        $msg = "$userid user successfully!";
        if ($request->ajax()) {
            return response()->json(['message' => $msg]);
        }
        return redirect('/irms/manage-users')->with('success', $msg);
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

    // public function update(Request $request)
    // {
    //     $userid = $request->input('userid');
    //     $validated = $request->validate([
    //         'rssite' => 'required|max:8',
    //         'name' => 'nullable|max:255',
    //         'email' => 'required|email|max:255',
    //         'department' => 'nullable|max:255',
    //         'section' => 'nullable|max:255',
    //         'position' => 'nullable|max:255',
    //         'gender' => 'nullable|max:10',
    //         'profile_pic_url' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
    //         'level' => 'nullable|integer',
    //         'password' => 'nullable|max:255'
    //     ]);

    //     // Handle profile picture upload
    //     if ($request->hasFile('profile_pic_url')) {
    //         $file = $request->file('profile_pic_url');
    //         $filename = uniqid() . '_' . $userid . '.' . $file->getClientOriginalExtension();
    //         $file->move(public_path('uploads/user-profile'), $filename);
    //         $profile_pic_url = 'uploads/user-profile/' . $filename;

    //         // Update only the profile_pic_url using Eloquent
    //         RsUser::where('userid', $userid)->update(['profile_pic_url' => $profile_pic_url]);
    //     }

    //     $updated_by = auth()->user()->userid ?? 'system';
    //     $level = $request->input('level');
    //     $password = $request->input('password');
    //     $hashedPassword = $password ? bcrypt($password) : null;

    //     try {
    //         \DB::statement('EXEC sp_update_user ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [
    //             $validated['rssite'],
    //             $userid,
    //             $validated['name'],
    //             $validated['email'],
    //             $validated['department'],
    //             $validated['section'],
    //             $validated['position'],
    //             $validated['gender'] ?? null,
    //             $level,
    //             $hashedPassword,
    //             $updated_by
    //         ]);
    //         return redirect()->route('rsusers.index')->with('success', 'User updated successfully!');
    //     } catch (\Exception $e) {
    //         return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
    //     }
    // }

    public function update(Request $request)
{
    // Only Superadmin can update users and navigation access
    if (auth()->user()->level != 1) {
        abort(403, 'Unauthorized');
    }

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

        $file->move(
            public_path('uploads/user-profile'),
            $filename
        );

        $profile_pic_url = 'uploads/user-profile/' . $filename;

        RsUser::where('userid', $userid)
            ->update([
                'profile_pic_url' => $profile_pic_url
            ]);
    }

    $updated_by = auth()->user()->userid ?? 'system';

    $level = $request->input('level');

    $password = $request->input('password');

    $hashedPassword = $password
        ? bcrypt($password)
        : null;

    try {

        /*
        | UPDATE USER
        */

        \DB::statement('EXEC sp_update_user ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [
            $validated['rssite'],
            $userid,
            $validated['name'],
            $validated['email'],
            $validated['department'],
            $validated['section'],
            $validated['position'],
            $validated['gender'] ?? null,
            $level,
            $hashedPassword,
            $updated_by
        ]);


        /*
        | NAVIGATION ACCESS
        */

        $permissionsFile = storage_path(
            'app/navigation_permissions.json'
        );

        // Create the file if it does not exist
        if (!file_exists($permissionsFile)) {

            file_put_contents(
                $permissionsFile,
                json_encode([
                    'quantity_move' => []
                ], JSON_PRETTY_PRINT)
            );
        }

        // Read existing permissions
        $permissions = json_decode(
            file_get_contents($permissionsFile),
            true
        );

        // Make sure the structure exists
        if (!is_array($permissions)) {
            $permissions = [];
        }

        if (!isset($permissions['quantity_move'])) {
            $permissions['quantity_move'] = [];
        }


        /*
        | CHECK QUANTITY MOVE
        */

        $navigationAccess = $request->input(
            'navigation_access',
            []
        );

        $hasQuantityMove = in_array(
            'quantity_move',
            $navigationAccess
        );


        /*
        | REMOVE USER FIRST
        */

        $permissions['quantity_move'] = array_values(
            array_diff(
                $permissions['quantity_move'],
                [$userid]
            )
        );


        /*
        |--------------------------------------------------------------------------
        | ADD USER IF CHECKED
        |--------------------------------------------------------------------------
        */

        if ($hasQuantityMove) {

            $permissions['quantity_move'][] = $userid;

        }


        /*
        |--------------------------------------------------------------------------
        | SAVE JSON
        |--------------------------------------------------------------------------
        */

        file_put_contents(
            $permissionsFile,
            json_encode(
                $permissions,
                JSON_PRETTY_PRINT
            )
        );


        return redirect()
            ->route('rsusers.index')
            ->with(
                'success',
                'User updated successfully!'
            );

    } catch (\Exception $e) {

        return redirect()
            ->back()
            ->withInput()
            ->withErrors([
                'error' => $e->getMessage()
            ]);
    }
}
}
