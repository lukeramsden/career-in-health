<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->middleware('auth');
    }

    public function showEmail()
    {
        return view('account.manage-email')
            ->with([
                'user' => Auth::user()
            ]);
    }

    public function updateEmail()
    {
        $user = Auth::user();

        $data = $this->request->validate([
            'email' => [
                'required', 'string', 'confirmed',
                'email', 'max:255', Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'required|string'
        ]);

        if(!Hash::check($data['password'], $user->password))
            return back()
                ->exceptInput('password')
                ->withErrors([
                    'password' => 'Password incorrect.'
                ]);

        $user->email = $data['email'];
        $user->save();

        toast()->success('Email Updated!');
        return back();
    }
    
    public function showPassword()
    {
        return view('account.manage-password')
            ->with([
                'user' => Auth::user()
            ]);
    }

    public function updatePassword()
    {
        $user = Auth::user();

        $data = $this->request->validate([
            'password' => 'required|string|min:6',
            'new_password' => 'required|string|confirmed|min:6',
        ]);

        if(!Hash::check($data['password'], $user->password))
            return back()
                ->exceptInput('password')
                ->withErrors([
                    'password' => 'Password incorrect.'
                ]);

        $user->password = Hash::make($data['new_password']);
        $user->save();

        toast()->success('Password Updated!');
        return back();
    }
}
