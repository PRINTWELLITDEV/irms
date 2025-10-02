<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RsUserStoreRequest extends FormRequest
{
    public function authorize() { return auth()->check(); }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'userid' => 'required|string|max:50|unique:rsuser,userid',
            'email' => 'nullable|email|max:255',
            'profile_pic' => 'nullable|image|max:2048',
            // ...other rules...
        ];
    }
}
