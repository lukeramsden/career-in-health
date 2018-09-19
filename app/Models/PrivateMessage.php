<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PrivateMessage extends Model
{
	protected $fillable = [
		'body',
		'job_listing_id',
	];

	protected $appends  = [
		'created_at_diff',
	];

	protected $dates = [
		'read_at'
	];

	public function getCreatedAtDiffAttribute()
	{
		return $this->created_at->diffForHumans();
	}

	public function company()
	{
		return $this->belongsTo(Company::class);
	}

	public function employee()
	{
		return $this->belongsTo(Employee::class);
	}

	public function job_listing()
	{
		return $this->belongsTo(JobListing::class);
	}

	/**
	 * @param User|Employee|Company|null $entity
	 *
	 * @return boolean
	 * @throws \Exception
	 */
	public function wasSentTo($entity = null)
	{
		if (is_null($entity))
		{
			if (!Auth::check())
				return false;

			$entity = Auth::user();
		}

		if ($entity instanceof Employee)
			return $this->direction === 'to_employee' && $this->employee_id === $entity->id;

		elseif ($entity instanceof Company)
			return $this->direction === 'to_company' && $this->company_id === $entity->id;

		elseif ($entity instanceof User)
		{
			if ($entity->isValidCompany())
				return $this->direction === 'to_company'
					&& $this->company_id === $entity->userable->company->id;

			elseif ($entity->isEmployee())
				return $this->direction === 'to_employee'
					&& $this->employee_id === $entity->userable->id;
		}

		return false;
	}

	public function markAsRead()
	{
		if ($this->read) return;

		$this->read    = true;
		$this->read_at = now();
		$this->save();
	}

	public function markAsUnread()
	{
		if (!$this->read) return;

		$this->read    = false;
		$this->read_at = null;
		$this->save();
	}
}
