<?php

namespace App\Http\Controllers\Auth;

use App\Cv\Cv;
use App\Enum\IAm;
use App\Profile;
use App\User;
use App\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\Rule;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'i_am' => ['required', 'integer', Rule::in([IAm::Employee, IAm::Company]),],
            'first_name' => 'required|string|max:255',
            'last_name' => 'string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'terms' => 'required'
        ];

        if (isset($data['i_am']) && $data['i_am'] == IAm::Company) {
            $rules['company_name'] = 'required|string|max:255|unique:companies,name';
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user = new User();
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->save();

        $profile = new Profile();
        $profile->first_name = ucwords($data['first_name']);

        if(isset($data['last_name']))
            $profile->last_name = ucwords($data['last_name']);

        $user->profile()->save($profile);

        if ($data['i_am'] == IAm::Company) {
            $company = new Company();
            $company->name = ucwords($data['company_name']);
            $company->created_by_user_id = $user->id;
            $company->save();

            $user->company_id = $company->id;
//            $this->redirectTo = route('dashboard');
        } else if($data['i_am'] == IAm::Employee) {
            $cv = new Cv();
            $user->cv()->save($cv);
//            $this->redirectTo = route('cv-builder.profile');
        }

        $user->save();
        return $user;
    }
}
