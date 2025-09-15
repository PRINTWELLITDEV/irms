<?php

namespace App\Http\Controllers;

use App\Models\RsUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RsUserController extends Controller
{
    public function index()
    {
        // $query = RsUser::query()
        //     ->leftJoin('irms_site', 'rsusers.rssite', '=', 'irms_site.rssite')
        //     ->select(
        //         'rsusers.*',
        //         'irms_site.rssite_desc'
        //     );

        // if (request()->has('search') && request('search') !== null) {
        //     $search = request('search');
        //     $query->where(function($q) use ($search) {
        //         $q->where('rsusers.name', 'like', "%{$search}%")
        //           ->orWhere('rsusers.userid', 'like', "%{$search}%")
        //           ->orWhere('rsusers.email', 'like', "%{$search}%");
        //     });
        // }

        // $users = $query->get();
        // Call the stored procedure to get users
        $users = \DB::select('EXEC sp_view_users');

        // If you want to support AJAX, you may need to convert $users to an array
        if (request()->ajax()) {
            return response()->json([
                'users' => $users
            ]);
        }

        return view('irms.irms-layouts.manage-users', compact('users'));
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

        // Handle profile picture upload
        if($request->hasFile('profile_pic_url')){
            $file = $request->file('profile_pic_url');
            // $filename = $validated['userid'] . '.png';
            $filename = uniqid() . '_' . $validated['userid'] . '.png';
            $file->move(public_path('uploads/user-profile'), $filename);
            $profile_pic_url = 'uploads/user-profile/' . $filename;
        } else {
            $profile_pic_url = 'uploads/user-profile/noprofile.png';
        }

        // Hash the password
        $hashedPassword = bcrypt($validated['password']);

        // Call the stored procedure to add user
        \DB::statement('EXEC sp_add_user ?, ?, ?, ?, ?, ?, ?', [
            $validated['rssite'],
            $validated['userid'],
            $validated['name'],
            $hashedPassword,
            $validated['email'],
            $validated['gender'],
            $profile_pic_url
        ]);

        return redirect('/irms/manage-users')->with('Success', 'Add users successfully!');
    }
}
