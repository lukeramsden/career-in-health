@extends('layouts.app')
@section('content')
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
            
            <div class="col-12">
                <div class="card-columns m-0">
                    @foreach($advert->applications as $application)
                        <div class="card card-custom">
                            <div class="card-body">
                                @if($application->custom_cover_letter)
                                    {{ $application->custom_cover_letter }}
                                @else
                                    <span class="text-muted font-italic">No cover letter</span>
                                @endif
                            </div>
                            <div class="card-footer">
                                {{ $application->updated_at->diffForHumans() }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection