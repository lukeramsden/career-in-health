<?php

namespace App\Http\Controllers;

use App\Advertising\HomePageAdvert;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    protected $request;

	/**
	 * TrackingController constructor.
	 *
	 * @param Request $request
	 */
	public function __construct(Request $request)
    {
        $this->request = $request;
    }

	/**
	 * @param HomePageAdvert $homePageAdvert
	 */
	public function homePageAdvertClick(HomePageAdvert $homePageAdvert)
	{
		$homePageAdvert->increment('stat_clicks');
		return redirect($homePageAdvert->links_to);
	}
}
