<?php

namespace App\Http\Controllers\Irms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\IrmsSite;

class RsSiteController extends Controller
{
    //
    public function index()
    {
        if (auth()->user()->level != 1) {
            abort(403, 'Unauthorized');
            // return response()->view('irms.irms-errors.unauthorized', [], 403);
        }
        $sites = IrmsSite::orderBy('create_date', 'asc')->get();
        return view('irms.irms-layouts.manage-sites', compact('sites'));
    }

    public function show($rssite)
    {
        $site = IrmsSite::where('rssite', $rssite)->firstOrFail();
        return view('irms.irms-layouts.site-details', compact('site'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'rssite' => 'required|unique:irms_site,rssite|max:10',
            'rssite_desc' => 'required|max:100',
            'address' => 'nullable|max:255',
            'logo_pic_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_link' => 'nullable|url|max:255',
        ]);

        if($request->hasFile('logo_pic_url')) {
            $file = $request->file('logo_pic_url');
            $filename = uniqid() . '_' . $validated['rssite'] . '.png';
            $file->move(public_path('uploads/sites-img'), $filename);
            $logoPath = 'uploads/sites-img/' . $filename;
        } else {
            $logoPath = null;
        }

        IrmsSite::create([
            'rssite' => $validated['rssite'],
            'rssite_desc' => $validated['rssite_desc'],
            'address' => $validated['address'] ?? null,
            'logo_pic_url' => $logoPath,
            'site_link' => $validated['site_link'] ?? null,
            'create_date' => now(),
        ]);

        return redirect()->route('sites.index')->with('success', 'Site created successfully!');
    }
}
