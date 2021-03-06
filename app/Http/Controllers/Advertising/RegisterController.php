<?php

namespace App\Http\Controllers\Advertising;

use App\Advertising\Advertiser;
use App\Advertising\AdvertiserInvite;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
  protected $request;

  /**
   * RegisterController constructor.
   *
   * @throws \Exception
   */
  public function __construct(Request $request)
  {
	$this->request = $request;
	$this->middleware('guest');
	$this->middleware('advertising-enabled');
  }

  /**
   * @param AdvertiserInvite $advertiserInvite
   */
  public function show(AdvertiserInvite $advertiserInvite)
  {
	return view('advertising.register')
	  ->with([
		'invite' => $advertiserInvite,
	  ]);
  }

  /**
   * @param AdvertiserInvite $advertiserInvite
   *
   * @throws \Exception
   */
  public function store(AdvertiserInvite $advertiserInvite)
  {
	$data = $this->request->validate(self::rules());

	$userable = new Advertiser([
	  'name' => $data['name'],
	]);
	$userable->save();

	$user                    = new User();
	$user->email             = $advertiserInvite->email;
	$user->confirmed         = true;
	$user->confirmation_code = null;
	$user->password          = Hash::make($data['password']);

	$user->userable()->associate($userable);
	$user->save();

	Auth::guard()->login($user);

	$advertiserInvite->delete();

	return back();
  }

  /**
   * @param array $custom
   *
   * @return array
   */
  protected function rules($custom = [])
  {
	return array_merge([
	  'name'     => 'required|string',
	  'password' => 'required|string|min:6|confirmed',
	  'terms'    => 'required',
	], $custom);
  }
}
