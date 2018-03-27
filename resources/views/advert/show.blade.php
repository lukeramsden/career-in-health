@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">{{ $advert->title }}</h2>
                        <p class="card-text">{{ $advert->description }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-right sticky-top">
                    <div class="card-header text-left">
                        <h5 class="text-muted">{{ $advert->jobType->name }} at {{ $advert->company->name }}</h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <h5>{{ $advert->address->location->name }}</h5>
                        </li>
                        <li class="list-group-item">
                            <h5>
                                @money($advert->min_salary * 100, 'GBP') - @money($advert->max_salary * 100, 'GBP')
                            </h5>
                        </li>
                        <li class="list-group-item">
                            <h5>{{ $advert->getSetting() }}</h5>
                          
                        </li>
                        <li class="list-group-item">
                            <h5>{{ $advert->getType() }}</h5>
                        </li>
                    </ul>
                    <a href="{{ route('advert_apply', ['advert' => $advert]) }}" class="btn btn-action">Apply</a>
                    <p class="text-muted"><span class="font-weight-bold text-info">{{ $advert->applications()->count() }}</span> {{ $advert->applications()->count() == 1 ? 'person has' : 'people have' }} already applied!</p>
                    <div class="card-footer">
                        <p class="card-text"><small class="text-muted">Last updated {{ $advert->updated_at->diffForHumans() }}</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

