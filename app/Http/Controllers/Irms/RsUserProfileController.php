<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use App\Models\IrmsSite;
use App\Models\RsUser;
use App\Models\RsLevel;
use Illuminate\Http\Request;

class RsUserProfileController extends Controller
{
    public function show($userid)
    {
        if ( (auth()->user()->level > 3) && auth()->user()->level == null) {
            abort(401, 'Unauthorized');
        }

        $user = RsUser::where('userid', $userid)->firstOrFail();
        $site = IrmsSite::where('rssite', $user->rssite)->first();
        $siteDesc = $site ? $site->rssite_desc : $user->rssite;
        $siteAddress = $site ? $site->address : 'N/A';
        $leveldesc = RsLevel::where('level', $user->level)->value('description');
        return view('irms.irms-layouts.user-profile', compact('user', 'siteDesc', 'siteAddress', 'leveldesc'));
    }

    public function update(Request $request, $userid)
    {
        $user = RsUser::where('userid', $userid)->firstOrFail();

        $validated = $request->validate([
            'name' => 'nullable|max:255',
            'gender' => 'nullable|max:10',
            'department' => 'nullable|max:50',
            'section' => 'nullable|max:50',
            'position' => 'nullable|max:50',
            'profile_pic_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle profile picture upload separately with Eloquent
        if ($request->hasFile('profile_pic_url')) {
            $file = $request->file('profile_pic_url');
            $filename = uniqid() . '_' . $userid . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/user-profile'), $filename);
            RsUser::where('userid', $userid)->update(['profile_pic_url' => 'uploads/user-profile/' . $filename]);
        }

        // Call sp_update_profile with only the fields in the form
        \DB::statement('EXEC sp_update_profile ?, ?, ?, ?, ?, ?, ?, ?',
            [
                $user->rssite,
                $userid,
                $validated['name'] ?? null,
                $validated['gender'] ?? null,
                $validated['department'] ?? null,
                $validated['section'] ?? null,
                $validated['position'] ?? null,
                auth()->user()->userid ?? 'system'
            ]
        );

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
    
    public function changePassword(Request $request, $userid)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = RsUser::where('userid', $userid)->firstOrFail();

        if (!\Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect.'], 422);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully.']);
    }
}
