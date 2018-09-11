@extends('layouts.app')
@section('content')
    @php($isOwner = optional(Auth::user())->isValidCompany() && Auth::user()->userable->company->id === $jobListing->company_id)
    <div class="container-fluid p-0 m-0">
        <div class="row m-0 p-4" id="job_listing-show-row">
            <div class="col-12">
                <div class="card card-custom card-job_listing">
                    <div class="card-body">
                        <a href="{{ route('company.show', [$jobListing->company]) }}" class="card-subtitle">
                            {{$jobListing->company->name}} {!!verified_badge($jobListing->company)!!}
                        </a>
                        <h4 class="card-title">{{$jobListing->jobRole->name}}</h4>
                        <h5>{{ $jobListing->title }}</h5>
                        <h6>{{ $jobListing->getSetting() }}</h6>
                        <div id="small-details">
                            <div>
                                <p><span class="badge badge-primary badge-pill p-2 px-3">{{ $jobListing->getType() }}</span></p>
                            </div>
                            <div>
                                <p><span class="oi oi-map-marker mr-3"></span>{{ $jobListing->address->location->name }}</p>
                            </div>
                            <div>
                                <p>
                                    @money($jobListing->min_salary * 100, 'GBP') - @money($jobListing->max_salary * 100, 'GBP')
                                </p>
                            </div>
                            <div>
                                <p><span class="oi oi-calendar"></span> <span class="text-muted">Last Updated</span> {{ $jobListing->last_edited->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 order-md-last">
                <div class="card card-custom card-job_listing" id="card-job_listing-contact-details">
                    <div class="card-body">
                        @isset($jobListing->company->phone)
                            <div class="mb-4">
                                <h5 class="align-middle"><span class="oi oi-phone text-muted"></span> <span class="text-muted">Phone:</span> <span>{{ $jobListing->company->phone }}</span></h5>
                            </div>
                        @endisset
                        
                        @isset($jobListing->company->contact_email)
                            <div class="mb-4">
                                <h5 class="align-middle"><span class="oi oi-envelope-closed text-muted"></span> <span class="text-muted">Email:</span> <span>{{ $jobListing->company->contact_email }}</span></h5>
                            </div>
                        @endisset
                        
                        <div class="btn-group btn-group-vertical btn-group-full btn-group-square">
                            @guest
                                <a href="{{ route('register') }}" class="btn btn-block btn-action">Sign Up To Apply</a>
                            @endguest
                                @auth
                                    @if($isOwner)
                                        <a href="{{ route('job-listing.show.applications', [$jobListing]) }}"
                                           class="btn btn-block btn-link">View Applications</a>
                                    @elseif(Auth::user()->isValidCompany())
                                        <button type="button" disabled class="btn btn-block btn-secondary">You can't
                                            apply
                                        </button>
                                    @else
                                        @usertype('employee')
                                            @if(!\App\JobListingApplication::hasApplied(Auth::user()->userable, $jobListing))
                                                <a href="{{ route('job-listing.application.create', [$jobListing]) }}"
                                                   class="btn btn-block btn-action">Apply</a>
                                            @else
                                                <button type="button" disabled class="btn btn-block btn-secondary">Already
                                                    Applied!
                                                </button>
                                            @endif
                                        @endusertype
            
                                        <a href="{{ route('account.private-message.show-employee', [$jobListing]) }}"
                                           class="btn btn-block btn-primary">Send a Message</a>
                                    
                                        @usertype('employee')
                                            @if(Auth::user()->userable->isJobListingSaved($jobListing))
                                                <a href="{{ route('employee.unsave-job-listing', $jobListing) }}"
                                                   class="btn btn-block btn-sm btn-secondary">Remove From Saved Listings</a>
                                            @else
                                                <a href="{{ route('employee.save-job-listing', $jobListing) }}"
                                                   class="btn btn-block btn-sm btn-golden">Save Job Listing</a>
                                            @endif
                                        @endusertype
                                    @endif
                                @endauth
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-8">
                <div class="card card-custom">
                    <div class="card-body">
                        <p>{{ $jobListing->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection