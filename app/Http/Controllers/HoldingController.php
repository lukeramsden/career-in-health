<?php

namespace App\Http\Controllers;

use App\Models\Subscribe;
use Illuminate\Http\Request;

class HoldingController extends Controller
{
    
    public function index()
    {
        return view('holding');
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255'
        ]);

        $subscribe = new Subscribe();
        $subscribe->email = $request->email;
        $subscribe->save();

        return redirect('/')
            ->with([
                'message' => 'Thank you for subscribing to Career In Health.'
            ]);
    }

}
