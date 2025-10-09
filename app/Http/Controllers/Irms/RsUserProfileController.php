<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use App\Models\IrmsSite;
use App\Models\RsUser;
use Illuminate\Http\Request;

class RsUserProfileController extends Controller
{
    public function show($userid)
    {
        $user = RsUser::where('userid', $userid)->firstOrFail();
        $site = IrmsSite::where('rssite', $user->rssite)->first();
        $siteDesc = $site ? $site->rssite_desc : $user->rssite;
        $siteAddress = $site ? $site->address : 'N/A';
        return view('irms.irms-layouts.user-profile', compact('user', 'siteDesc', 'siteAddress'));
    }

    public function update(Request $request, $userid)
    {
        $user = \App\Models\RsUser::where('userid', $userid)->firstOrFail();

        $validated = $request->validate([
            'name' => 'nullable|max:255',
            'gender' => 'nullable|max:10',
            'department' => 'nullable|max:50',
            'section' => 'nullable|max:50',
            'position' => 'nullable|max:50',
        ]);

        // Only pass values if not empty, else pass null
        $params = [
            $user->rssite,
            $user->userid,
            $validated['name'] ?: null,
            $validated['gender'] ?: null,
            $validated['department'] ?: null,
            $validated['section'] ?: null,
            $validated['position'] ?: null,
            auth()->user()->userid ?? 'system'
        ];

        \DB::statement('EXEC sp_update_profile ?, ?, ?, ?, ?, ?, ?, ?', $params);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }
}
