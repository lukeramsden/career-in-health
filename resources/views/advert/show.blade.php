@extends('layouts.app')
@section('content')
    @php($isOwner = Auth::user()->isCompany() && Auth::user()->company_id === $advert->company_id)
    <div class="container-fluid p-0 m-0">
        <div class="row m-0 p-4" id="advert-show-row">
            <div class="col-12">
                <div class="card card-custom card-advert">
                    <div class="card-body">
                        <a href="{{ route('company.show', [$advert->company]) }}" class="card-subtitle">
                            {{$advert->company->name}}
                        </a>
                        <h4 class="card-title">{{$advert->jobRole->name}}</h4>
                        <h5>{{ $advert->title }}</h5>
                        <h6>{{ $advert->getSetting() }}</h6>
                        <div id="small-details">
                            <div>
                                <p><span class="badge badge-primary badge-pill p-2 px-3">{{ $advert->getType() }}</span></p>
                            </div>
                            <div>
                                <p><span class="oi oi-map-marker mr-3"></span>{{ $advert->address->location->name }}</p>
                            </div>
                            <div>
                                <p>
                                    @money($advert->min_salary * 100, 'GBP') - @money($advert->max_salary * 100, 'GBP')
                                </p>
                            </div>
                            <div>
                                <p><span class="oi oi-calendar"></span> <span class="text-muted">Last Updated</span> {{ $advert->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4 order-md-last">
                <div class="card card-custom card-advert" id="card-advert-contact-details">
                    <div class="card-body">
                        <div class="mb-4">
                            @isset($advert->company->phone)
                                <h5 class="align-middle"><span class="oi oi-phone text-muted"></span> <span class="text-muted">Phone:</span> <span>{{ $advert->company->phone }}</span></h5>
                            @endisset
                            
                            @isset($advert->company->contact_email)
                                <h5 class="align-middle"><span class="oi oi-envelope-closed text-muted"></span> <span class="text-muted">Email:</span> <span>{{ $advert->company->contact_email }}</span></h5>
                            @endisset
                        </div>
                    
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-block btn-action">Sign Up To Apply</a>
                        @endguest
                        @auth
                            @if($isOwner)
                                <a href="{{ route('advert.show.applications', [$advert]) }}" class="btn btn-block btn-link">View Applications</a>
                            @elseif(Auth::user()->isCompany())
                                <button type="button" disabled class="btn btn-block btn-secondary">You can't apply</button>
                            @else
                                @if(!\App\AdvertApplication::alreadyApplied(Auth::user(), $advert))
                                    <a href="{{ route('advert.application.create', [$advert]) }}" class="btn btn-block btn-action">Apply</a>
                                @else
                                    <button type="button" disabled class="btn btn-block btn-secondary">Already Applied!</button>
                                @endif
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-8">
                <div class="card card-custom">
                    <div class="card-body">
                        <p>{{ $advert->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection