@extends('layouts.app')
@section('content')
    {{--@foreach($address->getMedia('images') as $image)--}}
        {{--<div class="carousel-item {{ $loop->first ? 'active':'' }}">--}}
            {{--<img class="d-block h-100" src="{{ $image->getUrl() }}">--}}
        {{--</div>--}}
    {{--@endforeach--}}
    <div class="container-fluid my-5">
        <div class="row">
            <div class="col-12 col-lg-10 offset-lg-1">
                <div class="card-custom mb-4">
                    <div class="card-body">
                        {{--<a href="{{ route('company.show', [$address->company]) }}" class="card-subtitle">--}}
                            {{--<h4>{{$address->company->name}}</h4>--}}
                        {{--</a>--}}
                        <h2 class="card-title">{{$address->name}}</h2>
                        <h4>{{ $address->location->name }}</h4>
                        <div id="small-details">
                            {{--<div>--}}
                                {{--<p><span class="badge badge-primary badge-pill p-2 px-3">{{ $advert->getType() }}</span></p>--}}
                            {{--</div>--}}
                            {{--<div>--}}
                                {{--<p><span class="oi oi-map-marker mr-3"></span>{{ $advert->address->location->name }}</p>--}}
                            {{--</div>--}}
                            {{--<div>--}}
                                {{--<p>--}}
                                    {{--@money($advert->min_salary * 100, 'GBP') - @money($advert->max_salary * 100, 'GBP')--}}
                                {{--</p>--}}
                            {{--</div>--}}
                            {{--<div>--}}
                                {{--<p><span class="oi oi-calendar"></span> <span class="text-muted">Last Updated</span> {{ $advert->updated_at->diffForHumans() }}</p>--}}
                            {{--</div>--}}
                        </div>
                    </div>
                </div>
            
                <div class="card card-custom">
                    <div class="card-body p-0">
                        <div class="carousel" style="">
                            @foreach($address->getMedia('images') as $image)
                                <div class="carousel-item text-center" style="height: 32rem">
                                    <img class="mx-auto" style="max-width:100%;height:100%;vertical-align: middle;" src="{{ $image->getUrl() }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
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
    </script>
@endsection
