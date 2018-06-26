<?php

namespace App\Http\Controllers;

use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->middleware('auth');
        $this->middleware('user-type:company');
        $this->middleware('company-created')->except(['create', 'store']);
    }

    public function create()
    {
        return view('company.create');
    }

    public function store()
    {
        $data = $this->request->validate([
            'name' => 'required|string|unique:companies',
            'usersToInvite' => 'array',
            'usersToInvite.*' => 'email|distinct|unique:users,email'
        ]);

        $user = Auth::user();
        $company = new Company();
        $company->created_by_user_id = $user->id;
        $company->fill($data);
        $company->save();
        $user->userable->company_id = $company->id;
        $user->userable->save();

        return redirect(route('dashboard'));
    }
}
