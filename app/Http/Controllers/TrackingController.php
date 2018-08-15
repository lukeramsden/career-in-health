<?php

namespace App\Http\Controllers;

use App\Advert;
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
	 * @param Advert $advert
	 */
	public function advertClickThrough(Advert $advert)
	{
		$advert->increment('stat_clicks');
		return redirect($advert->links_to);
	}
}
