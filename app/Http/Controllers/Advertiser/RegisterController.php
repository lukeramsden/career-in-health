<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
	protected $request;

	/**
	 * RegisterController constructor.
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;

		$this->middleware('guest');
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

	public function show($code)
	{
	}

	public function store($code)
	{

	}
}
