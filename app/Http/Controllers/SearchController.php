<?php

namespace App\Http\Controllers;

use App\JobListing;
use App\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
	protected $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	protected function rules($custom = [])
	{
		$rules = [
			'what'  => 'required|string',
			'where' => 'required|integer|exists:locations,id',

			'radius'           => 'nullable|integer|min:5|max:50',
			'min_salary'       => 'nullable|integer|min:0|max:150000|less_than_field:max_salary',
			'max_salary'       => 'nullable|integer|min:1|max:150000|greater_than_field:min_salary',
			'setting_filter'   => 'nullable|array',
			'setting_filter.*' => 'integer|distinct',
			'type_filter'      => 'nullable|array',
			'type_filter.*'    => 'integer|distinct',
		];

		return array_merge($rules, $custom);
	}

//	public function search()
//	{
//		$data = $this->request->validate(self::rules([]));
//		$town = Location::remember(1440)->find($data['where']);
//
//		$q = Searchy::search('job_listings')
//					->fields(['job_listings.title', 'job_roles.name'])
//					->select('job_listings.*', 'job_roles.name as job_name')
//					->query($data['what'])
//					->getQuery()
//					->leftJoin('job_roles', 'job_listings.job_role', '=', 'job_roles.id')
//					->having('relevance', '>', '50');
//
//		if (isset($data['radius']) && $data['radius'] < 50)
//		{
//			$q = $q->whereHas('address', function ($q) use ($town, $data)
//			{
//				$q->whereHas('location', function ($q) use ($town, $data)
//				{
//					$q->whereRaw('(3959 * acos(cos(radians(?)) * cos(radians( latitude )) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) < ?', [
//						$town->latitude,
//						$town->longitude,
//						$town->latitude,
//						$data['radius'],
//					]);
//				});
//			});
//		}
//
//		if (isset($data['job_roles']))
//			$q->whereIn('job_role', $data['job_roles']);
//
//		if (isset($data['min_salary']))
//			$q->where('max_salary', '>', $data['min_salary']);
//
//		if (isset($data['max_salary']) && $data['max_salary'] < 150000)
//			$q->where('min_salary', '<', $data['max_salary']);
//
//		if (isset($data['setting_filter']))
//			$q->whereIn('setting', $data['setting_filter']);
//
//		if (isset($data['type_filter']))
//			$q->whereIn('type', $data['type_filter']);
//
//
//		$results = $q->where('published', '1')
//					 ->orderBy('max_salary', 'desc')
//					 ->simplePaginate(10);
//
//		/** @var Paginator $results */
//		$jobListingIds = array_map(
//			returns('id'),
//			$results->items()
//		);
//
//		if (count($jobListingIds) > 0)
//			JobListing::whereIn('id', $jobListingIds)->increment('search_impressions');
//
//		if (Auth::check() && Auth::user()->isEmployee())
//			Auth::user()->userable()->increment('times_searched');
//
//		$results = new \Illuminate\Pagination\Paginator(
//			JobListing::with('address', 'address.location', 'jobRole', 'company')
//					  ->whereIn('id', $jobListingIds)
//					  ->get(),
//			$results->perPage(),
//			$results->currentPage(), [
//				'path'  => \Request::url(),
//				'query' => [
//					'page' => $results->currentPage(),
//				],
//			]
//		);
//
//		return view('search')
//			->with([
//				'results' => $results,
//				'town'    => $town,
//			]);
//
//	}

	public function search()
	{
		$data    = $this->request->validate(self::rules());
		$town    = Location::remember(1440)->find($data['where']);
		$results = JobListing::with('address', 'address.location', 'jobRole', 'company');

		if (isset($data['radius']) && $data['radius'] < 50)
		{
			$results = $results->whereHas('address', function ($q) use ($town, $data)
			{
				$q->whereHas('location', function ($q) use ($town, $data)
				{
					$q->whereRaw('(3959 * acos(cos(radians(?)) * cos(radians( latitude )) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) < ?', [
						$town->latitude,
						$town->longitude,
						$town->latitude,
						$data['radius'],
					]);
				});
			});
		}

		if (isset($data['what']))
		{
			$results->whereHas('jobRole', function ($q) use ($data)
			{
				$q->where('name', 'LIKE', "%{$data['what']}%");
			});
		}

		if (isset($data['min_salary']))
			$results->where('max_salary', '>', $data['min_salary']);

		if (isset($data['max_salary']) && $data['max_salary'] < 150000)
			$results->where('min_salary', '<', $data['max_salary']);

		if (isset($data['setting_filter']))
			$results->whereIn('setting', $data['setting_filter']);

		if (isset($data['type_filter']))
			$results->whereIn('type', $data['type_filter']);

		$results = $results
			->wherePublished(true)
			->orderBy('max_salary', 'desc')
			->paginate(10);

		$jobListingIds = map_to($results->items(), 'id');

		if (count($jobListingIds) > 0)
			JobListing::whereIn('id', $jobListingIds)->increment('search_impressions');

		if (Auth::check() && Auth::user()->isEmployee())
			Auth::user()->userable()->increment('times_searched');

		return view('search')
			->with([
				'results' => $results,
				'town'    => $town,
			]);
	}
}
