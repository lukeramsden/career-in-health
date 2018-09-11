@extends('layouts.app')
@section('content')
    <div class="container-fluid my-2">
        <div class="row">
            <div class="col-12 col-lg-10 offset-lg-1">
                @if($address->getMedia('images')->count() > 0)
                    <div class="card card-custom mb-4">
                        <div class="card-body p-0">
                            <div class="carousel" style="">
                                @foreach($address->getMedia('images') as $image)
                                    <div class="carousel-item text-center" style="height: 28rem">
                                        <img class="mx-auto" style="max-width:100%;height:100%;vertical-align: middle;" src="{{ $image->getFullUrl() }}" alt="{{ $image->name }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <div class="col-12 col-lg-10 offset-lg-1">
                <div class="card-deck">
                    <div class="card card-custom mb-4">
                        <div class="card-body">
                            <h2 class="card-title">{{$address->name}}</h2>
                            <h4>{{ $address->location->name }}</h4>
    
                            <hr>
                            
                            @isset($address->phone)
                                <h5><span class="oi oi-phone text-muted"></span> <span class="text-muted">Phone:</span> <span>{{ $address->phone }}</span></h5>
                            @endisset
                
                            @isset($address->email)
                                <h5><span class="oi oi-envelope-closed text-muted"></span> <span class="text-muted">Email:</span> <span>{{ $address->email }}</span></h5>
                            @endisset
                        </div>
                    </div>
                    
                    @isset($address->about)
                        <div class="card card-custom mb-4">
                            <div class="card-body">
                                <h4 class="card-title"><em>About</em></h4>
                                @isset($address->about)
                                    <p>{!! nl2br(e($address->about)) !!}</p>
                                @endisset
                            </div>
                        </div>
                    @endisset
                </div>
            </div>
            
            <div class="col-12 col-lg-5 offset-lg-1 mb-4">
                <a class="link-unstyled" href="{{ route('company.show', $address->company) }}">
                    <div class="card card-custom scale-on-hover-2">
                        <div class="card-body">
                            <img src="{{ $address->company->picture() ?? '/images/generic.png' }}" alt="Profile picture" width="200" class="img-thumbnail mx-auto d-block">
                            <div class="text-center">
                                <h1 class="mt-3">{{ $address->company->name }} {!!verified_badge($address->company)!!}</h1>
                                <h5><b>{{ $address->company->location->name }}</b></h5>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
            @if($jobListings->total() > 0)
                <div class="col-12 col-lg-5 mb-4">
                    <div class="card card-custom">
                        <div class="card-body">
                            <h4 class="card-title text-center"><em>Open Positions</em></h4>
                            {!! $jobListings->appends(Request::capture()->except('page'))->render("vendor.pagination") !!}
                            @foreach($jobListings as $jobListing)
                                <a class="link-unstyled" href="{{ route('job-listing.show', $jobListing) }}">
                                    <div class="job_listing scale-on-hover-2">
                                        <h4>{{$jobListing->jobRole->name}}</h4>
                                        <h5>{{ str_limit($jobListing->title, 50) }}</h5>
                                        <h6>{{ $jobListing->getSetting() }}</h6>
                                    </div>
                                </a>
                                <hr>
                            @endforeach
                            <div class="text-center">
                                <p class="font-italic">{{ $jobListings->total() }} {{ str_plural('job listing', $jobListings->total()) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('stylesheet')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
@endsection
@section('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    
    <script>
        @if($address->getMedia('images')->count() > 0)
            $(document).ready(function(){
                $('.carousel').slick({
                    dots: true,
                    infinite: true,
                    speed: 300,
                    slidesToShow: 1,
                    arrows: true,
                    autoplay: true,
                });
            });
        @endif
    </script>
@endsection
