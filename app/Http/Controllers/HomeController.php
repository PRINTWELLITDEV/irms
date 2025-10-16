<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\IrmsSite;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $sites = IrmsSite::whereNotNull('logo_pic_url')
            ->where('logo_pic_url', '!=', '')
            ->orderby('rssite_desc' , 'desc')
            ->get();

        return view('home', compact('sites'));
    }
}
