<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdvertiserController extends Controller
{
	protected $request;

	/**
	 * AdvertiserController constructor.
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;
		$this->middleware('auth');
		$this->middleware('user-type:advertiser');
	}
	/**
	 * @param array $custom
	 *
	 * @return array
	 */
	protected function rules($custom = [])
	{
		return array_merge([

		], $custom);
	}
}
