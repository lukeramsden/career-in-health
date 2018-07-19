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
                                        <img class="mx-auto" style="max-width:100%;height:100%;vertical-align: middle;" src="{{ $image->getUrl() }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="card card-custom mb-4">
                    <div class="card-body">
                        <h2 class="card-title">{{$address->name}}</h2>
                        <h4>{{ $address->location->name }}</h4>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-lg-5 offset-lg-1 mb-4">
                <a class="link-unstyled" href="{{ route('company.show', $address->company) }}">
                    <div class="card card-custom card-link">
                        <div class="card-body">
                            <img src="{{ $address->company->picture() ?? '/images/generic.png' }}" alt="Profile picture" width="200" class="img-thumbnail mx-auto d-block">
                            <div class="text-center">
                                <h1 class="mt-3">{{ $address->company->name }}</h1>
                                <h5><b>{{ $address->company->location->name }}</b></h5>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-12 col-lg-5">
                <div class="card card-custom">
                    <div class="card-body"></div>
                </div>
            </div>
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
