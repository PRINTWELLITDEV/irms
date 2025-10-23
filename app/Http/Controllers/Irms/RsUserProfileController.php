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
        if (( (auth()->user()->level > 3) && auth()->user()->level == null) || (auth()->user()->userid != $userid)) {
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
            'profile_pic_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_pic_url')) {
            $file = $request->file('profile_pic_url');
            $filename = $user->userid . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/user-profile'), $filename);
            $profile_pic_url = 'uploads/user-profile/' . $filename;
        } else {
            // Use the existing value from the database
            $profile_pic_url = $user->profile_pic_url;
        }

        // Only pass values if not empty, else pass null
        $params = [
            $user->rssite,
            $user->userid,
            $validated['name'] ?: null,
            $validated['gender'] ?: null,
            $validated['department'] ?: null,
            $validated['section'] ?: null,
            $validated['position'] ?: null,
            $profile_pic_url ?: null,
            auth()->user()->userid ?? 'system'
        ];

        \DB::statement('EXEC sp_update_profile ?, ?, ?, ?, ?, ?, ?, ?', $params);

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
