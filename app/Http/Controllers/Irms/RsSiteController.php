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
            abort(401, 'Unauthorized');
            // return response()->view('irms.irms-errors.unauthorized', [], 403);
        }
        // $sites = IrmsSite::orderBy('create_date', 'asc')->get();
        // return view('irms.irms-layouts.manage-sites', compact('sites'));
        return view('irms.irms-layouts.manage-sites');
    }

    public function siteList()
    {
        $sites = IrmsSite::orderBy('create_date', 'asc')->get();
        
        return view('irms.irms-tables.site-list', compact('sites'))->render();
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
        ],[
            'rssite.required' => 'The Site Code field is required.',
            'rssite.unique' => 'The Site Code has already been taken.',
            'rssite_desc.required' => 'The Site Description field is required.',
            'logo_pic_url.image' => 'The Logo must be an image file.',
            'logo_pic_url.mimes' => 'The Logo must be a file of type: jpeg, png, jpg, gif, svg.',
            'logo_pic_url.max' => 'The Logo may not be greater than 2MB.',
            'site_link.url' => 'The Site Link must be a valid URL.',
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

        if ($request->ajax()) {
            return response()->json(['message' => 'Site created successfully!']);
        }

        return redirect()->route('sites.index')->with('success', 'Site created successfully!');
    }
}
