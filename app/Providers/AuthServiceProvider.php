<?php

namespace App\Providers;

use App\Address;
use App\Advertising\Advert;
use App\Company;
use App\CompanyUser;
use App\JobListing;
use App\Policies\AddressPolicy;
use App\Policies\AdvertPolicy;
use App\Policies\CompanyPolicy;
use App\Policies\CompanyUserPolicy;
use App\Policies\JobListingPolicy;
use App\Policies\PrivateMessagePolicy;
use App\PrivateMessage;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
  /**
   * The policy mappings for the application.
   *
   * @var array
   */
  protected $policies = [
	Company::class        => CompanyPolicy::class,
	CompanyUser::class    => CompanyUserPolicy::class,
	Address::class        => AddressPolicy::class,
	JobListing::class     => JobListingPolicy::class,
	PrivateMessage::class => PrivateMessagePolicy::class,
	Advert::class         => AdvertPolicy::class,
  ];

  /**
   * Register any authentication / authorization services.
   *
   * @return void
   */
  public function boot()
  {
	$this->registerPolicies();

	//
  }
}
