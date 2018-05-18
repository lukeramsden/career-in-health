<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showEmail()
    {
        return view('account.manage-email')
            ->with(['user' => Auth::user()]);
    }

    public function updateEmail(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'email' => ['required', 'string', 'confirmed', 'email', 'max:255', Rule::unique('users')->ignore($user->id)]
        ]);

        $user->email = $data['email'];
        $user->save();

        toast()->success('Email Updated!');
        return back();
    }
}
