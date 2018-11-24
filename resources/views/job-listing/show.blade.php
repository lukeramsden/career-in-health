@extends('layouts.app', ['title' => str_limit($jobListing->title, 40)])
@section('content')
    @php($isOwner = optional($currentUser)->isValidCompany() && $currentUser->userable->company->id === $jobListing->company_id)
    <div class="container-fluid p-0 m-0">
        <div class="row m-0 p-4" id="job_listing-show-row">
            <div class="col-12">
                <div class="card card-custom card-listing">
                    <div class="card-body">
                        <a href="{{ route('company.show', [$jobListing->company]) }}"
                           class="card-subtitle">
                            {{$jobListing->company->name}} {!!verified_badge($jobListing->company)!!}
                        </a>
                        <h4 class="card-title">{{$jobListing->jobRole->name}}</h4>
                        <h5>{{ $jobListing->title }}</h5>
                        <h6>{{ $jobListing->setting_name }}</h6>
                        <div id="small-details">
                            <div>
                                <p>
                                    <span class="badge badge-primary badge-pill p-2 px-3">{{ $jobListing->type_name }}</span>
                                </p>
                            </div>
                            <div>
                                <p>
                                    <span class="oi oi-map-marker mr-3"></span>{{ $jobListing->address->location->name }}
                                </p>
                            </div>
                            <div>
                                <p>
                                    @money($jobListing->min_salary * 100, 'GBP') -
                                    @money($jobListing->max_salary * 100, 'GBP')
                                </p>
                            </div>
                            <div>
                                <p><span class="oi oi-calendar"></span> <span class="text-muted">Last Updated</span> {{ $jobListing->last_edited->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 order-md-last">
                <div class="card card-custom card-listing"
                     id="card-listing-contact-details">
                    <div class="card-body p-0">
                        @isset($jobListing->company->phone)
                            <div class="p-3">
                                <h5 class="align-middle">
                                    <span class="oi oi-phone text-muted"></span>
                                    <span class="text-muted">Phone:</span>
                                    <span>{{ $jobListing->company->phone }}</span>
                                </h5>
                            </div>
                        @endisset
                        
                        @isset($jobListing->company->contact_email)
                            <div class="p-3">
                                <h5 class="align-middle">
                                    <span class="oi oi-envelope-closed text-muted"></span> <span
                                        class="text-muted">Email:</span>
                                    <span>{{ $jobListing->company->contact_email }}</span></h5>
                            </div>
                        @endisset
                        
                        @if($jobListing->isOpen())
                            <div class="btn-group-vertical btn-group-full">
                                @guest
                                    <a href="{{ route('register') }}"
                                       class="btn p-3 btn-block btn-action">Sign Up To Apply</a>
                                @endguest
                                @auth
                                    @if($isOwner)
                                        <a href="{{ route('job-listing.view-applications', $jobListing) }}"
                                           class="btn p-3 btn-block btn-link">View Applications</a>
                                    @elseif($currentUser->isValidCompany())
                                        <button type="button"
                                                disabled
                                                class="btn p-3 btn-block btn-secondary">You can't
                                            apply
                                        </button>
                                    @else
                                        @usertype('employee')
                                        @if(!\App\JobListingApplication::hasApplied($currentUser->userable, $jobListing))
                                            <a href="{{ route('job-listing.application.create', [$jobListing]) }}"
                                               class="btn p-3 btn-block btn-action">Apply</a>
                                        @else
                                            <button type="button"
                                                    disabled
                                                    class="btn p-3 btn-block btn-secondary">Already
                                                Applied!
                                            </button>
                                        @endif
                                        
                                        @if($currentUser->userable->isJobListingSaved($jobListing))
                                            <a href="{{ route('employee.unsave-job-listing', $jobListing) }}"
                                               class="btn p-3 btn-block btn-primary">Remove
                                                From Saved Listings</a>
                                        @else
                                            <a href="{{ route('employee.save-job-listing', $jobListing) }}"
                                               class="btn p-3 btn-block btn-primary">Save Job
                                                Listing</a>
                                        @endif
                                        @endusertype
                                    @endif
                                @endauth
                            </div>
                        @else
                            <div class="p-3">
                                <p class="mb-0"><b>This listing has been closed.</b></p>
                                <p class="mb-1 font-italic">{{ $jobListing->close_reason ?? 'No reason was provided.' }}</p>
                            </div>
                        @endif
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