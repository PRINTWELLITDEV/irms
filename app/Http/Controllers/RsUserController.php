<?php

namespace App\Http\Controllers;

use App\Models\RsUser;
use Illuminate\Http\Request;

class RsUserController extends Controller
{
    public function index()
    {
        $query = RsUser::query();

        if (request()->has('search') && request('search') !== null) {
            $search = request('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('userid', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
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

    public function create()
    {
        return view('rsusers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
                'rssite' => 'required|max:8',
                'userid' => 'required|unique:rsusers|max:8',
                'name' => 'nullable|max:255',
                'password' => 'required|max:255',
                'email' => 'required|max:255',
                'user_type' => 'nullable|max:10',
                'gender' => 'nullable|max:10',
                'profile_pic_url' => 'nullable|file|max:255'
            ]);

        if($request->hasFile('profile_pic_url')){
            $file = $request->file('profile_pic_url');
            $path = $file->store('profile_pics', 'public');
            $validated['profile_pic_url'] = $path;
        }

        $validated['password'] = bcrypt(($validated['password']));

        RsUser::create($validated);

        return redirect('/irms/manage-users')->with('Success', 'Add users successfully!');
    }
}
