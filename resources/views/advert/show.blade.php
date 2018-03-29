@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="form-container has-top-bar mt-5">
            <div class="row first-row">
                <div class="col-12 mt-4 px-4">
                    <div class="media">
                        <div class="media-body">
                            <h4><a href="{{ route('company', ['company' => $advert->company]) }}">{{ $advert->company->name }}</a></h4>
                            <h1>{{ $advert->jobType->name }}</h1>
                            <div class="mt-3">
                                <h4>{{ $advert->title }}</h4>
                            </div>
                            <div class="d-block my-4">
                                <div class="d-inline-block">
                                    <h5><span class="badge badge-primary badge-pill p-3">{{ $advert->getType() }}</span></h5>
                                </div>
                                <div class="d-inline-block ml-5">
                                    <h5><span class="oi oi-map-marker mr-3"></span>{{ $advert->address->location->name }}</h5>
                                </div>
                                <div class="d-inline-block ml-5">
                                    <h5>
                                        @money($advert->min_salary * 100, 'GBP') - @money($advert->max_salary * 100, 'GBP')
                                    </h5>
                                </div>
                                <div class="d-inline-block ml-5">
                                    <h5><span class="oi oi-calendar"></span> <span class="text-muted">Last Updated</span> {{ $advert->updated_at->diffForHumans() }}</h5>
                                </div>
                            </div>
                        </div>
                        @if($advert->company->picture() != null)
                            <img class="ml-3 align-self-center" height="144" src="{{ $advert->company->picture() }}" alt="Company profile picture">
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="form-container has-top-bar mt-0">
                    <div class="first-row row py-4 px-3">
                        <div class="col-12">
                            <p class="my-0 text-justify">{{ $advert->description }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-container has-top-bar mt-0" style="position: -webkit-sticky; position: sticky; top: 20px; z-index: 1020;">
                    <div class="first-row row py-4 px-3">
                        @isset($advert->company->phone)
                            <div class="col-12 my-1">
                                <h5 class="align-middle"><span class="oi oi-phone text-muted"></span> <span class="text-muted">Phone:</span> <span>{{ $advert->company->phone }}</span></h5>
                            </div>
                        @endisset
                        
                        @isset($advert->company->contact_email)
                            <div class="col-12 my-1">
                                <h5 class="align-middle"><span class="oi oi-envelope-closed text-muted"></span> <span class="text-muted">Email:</span> <span>{{ $advert->company->contact_email }}</span></h5>
                            </div>
                        @endisset
                        
                        <div class="col-12 my-3">
                            @guest
                                <a href="{{ route('register') }}" class="btn btn-block btn-action">Sign Up To Apply</a>
                            @endguest
                            @auth
                                @if(Auth::user()->isCompany())
                                    <button type="button" disabled class="btn btn-block btn-secondary">You can't apply</button>
                                @else
                                    @if(!\App\Models\AdvertApplication::alreadyApplied(Auth::user(), $advert))
                                        <a href="{{ route('advert.apply.create', ['advert' => $advert]) }}" class="btn btn-block btn-action">Apply</a>
                                    @else
                                        <button type="button" disabled class="btn btn-block btn-secondary">Already Applied!</button>
                                    @endif
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection