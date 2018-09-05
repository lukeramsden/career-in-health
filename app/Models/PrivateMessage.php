<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PrivateMessage extends Model
{
	protected $fillable = ['body', 'job_listing_id'];

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

	/**
	 * @return Company|Employee
	 */
	public function sender()
	{
		return $this->direction === 'to_employee' ? $this->company : $this->employee;
	}

	/**
	 * @return Company|Employee
	 */
	public function receiver()
	{
		return $this->direction === 'to_company' ? $this->company : $this->employee;
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
