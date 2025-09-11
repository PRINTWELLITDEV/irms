<?php
 
namespace App\Http\Controllers;
 
use App\Models\RsUser;
 
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
    public function getRememberTokenName()
    {
        return null; // disables remember_token usage
    }
}