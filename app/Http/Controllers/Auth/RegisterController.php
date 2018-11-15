<?php

namespace App\Http\Controllers\Auth;

use App\CompanyUser;
use App\Employee;
use App\Enum\UserType;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Webpatser\Uuid\Uuid;

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
	$this->middleware('guest')->except('confirm', 'prompt', 'resend');
  }

  /**
   * @param $confirmation_code
   */
  public function confirm($confirmation_code)
  {
	if (!$confirmation_code)
	  abort(400); // bad request

	$user = User::whereConfirmationCode($confirmation_code)->first();

	if (!$user)
	  abort(404); // not found

	$user->confirmed         = 1;
	$user->confirmation_code = null;
	$user->save();

	$this->guard()->login($user);
	toast()
	  ->success('You have successfully confirmed your email!')
	  ->info('You are now logged in.');

	if ($user->isCompany())
	  return redirect(route('company-user.edit'));

	if ($user->isEmployee())
	  return redirect(route('employee.edit'));

	return redirect(route('dashboard'));
  }

  public function prompt()
  {
	if (Auth::check() && Auth::user()->confirmed)
	{
	  toast()->info('You have already confirmed your email');
	  return redirect(route('dashboard'));
	}

	session()->reflash();
	return view('auth.confirm-email');
  }

  /**
   * @param Request $request
   * @param User    $user
   *
   * @return \Illuminate\Http\RedirectResponse
   */
  public function resend(Request $request, User $user)
  {
	$user->sendEmailConfirmationNotification();

	toast()->success('Sent!');
	session()->reflash();
	return back();
  }

  /**
   * Get a validator for an incoming registration request.
   *
   * @param  array $data
   *
   * @return \Illuminate\Contracts\Validation\Validator
   */
  protected function validator(array $data)
  {
	$rules = [
	  'i_am'       => ['required', 'string', Rule::in(array_values(UserType::all()))],
	  'first_name' => 'required|string|max:255',
	  'last_name'  => 'nullable|string|max:255',
	  'email'      => 'required|string|email|max:255|unique:users',
	  'password'   => 'required|string|min:6|confirmed',
	  'terms'      => 'required',
	];

	return Validator::make($data, $rules);
  }

  /**
   * Create a new user instance after a valid registration.
   *
   * @param  array $data
   *
   * @return \App\User
   * @throws \Exception
   */
  protected function create(array $data)
  {
	switch ($data['i_am'])
	{
	  case UserType::EMPLOYEE:
		{
		  $userable = new Employee();
		  break;
		}
	  case UserType::COMPANY_USER:
		{
		  $userable = new CompanyUser();
		  break;
		}
	  default:
		abort(400);
		return null;
	}

	$userable->first_name = $data['first_name'];
	if (isset($data['last_name']))
	  $userable->last_name = $data['last_name'];

	$userable->save();

	$user                    = new User();
	$user->email             = $data['email'];
	$user->confirmation_code = (string)Uuid::generate(4);
	$user->password          = Hash::make($data['password']);
	$user->userable()->associate($userable);
	$user->save();

	return $user;
  }

  /**
   * The user has been registered.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  mixed                    $user
   *
   * @return mixed
   */
  protected function registered(Request $request, $user)
  {
	if ($user->confirmed)
	  return null;

	if (App::environment('local'))
	{
	  $user->confirmed         = true;
	  $user->confirmation_code = null;
	  $user->save();
	  session()->flash('user_id', $user->id);
	  toast()->success('Registered!');
	  return redirect(route('dashboard'));
	}

	$this->guard()->logout();
	$user->sendEmailConfirmationNotification();
	toast()->success('Your account has been created! You need confirm your email to log in.');
	session()->flash('user_id', $user->id);
	return redirect(route('prompt-confirm-email'));
  }
}
