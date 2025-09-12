<?php

namespace App\Http\Controllers;

use App\Models\RsUser;
use Illuminate\Http\Request;

class RsUserController extends Controller
{
    public function index()
    {
        $query = RsUser::query()
            ->leftJoin('irms_site', 'rsusers.rssite', '=', 'irms_site.rssite')
            ->select(
                'rsusers.*',
                'irms_site.rssite_desc'
            );

        if (request()->has('search') && request('search') !== null) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('rsusers.name', 'like', "%{$search}%")
                  ->orWhere('rsusers.userid', 'like', "%{$search}%")
                  ->orWhere('rsusers.email', 'like', "%{$search}%");
            });
        }

        $users = $query->get();

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
            'userid' => 'required|unique:rsusers,userid|max:8',
            'name' => 'nullable|max:255',
            'password' => 'required|max:255',
            'email' => 'required|email|max:255',
            'gender' => 'nullable|max:10',
            'profile_pic_url' => 'nullable|file|mimes:jpg,jpeg,png|max:2048'
        ]);

        if($request->hasFile('profile_pic_url')){
            $file = $request->file('profile_pic_url');
            $filename = $validated['userid'] . '.png';
            $file->move(public_path('uploads/user-profile'), $filename);
            $validated['profile_pic_url'] = 'uploads/user-profile/' . $filename;
        } else {
            $validated['profile_pic_url'] = 'uploads/user-profile/noprofile.png';
        }

        $validated['password'] = bcrypt($validated['password']);

        RsUser::create($validated);

        return redirect('/irms/manage-users')->with('Success', 'Add users successfully!');
    }
}
