@extends('layouts.app')
@section('content')
    <div class="container mb-5">
        <div class="card card-custom mt-5 mx-auto">
            <div class="card-body">
                <img src="{{ $company->picture() ?? '/images/generic.png' }}" alt="Profile picture" width="200" class="img-thumbnail mx-auto d-block">
                <div class="text-center">
                    <h1 class="mt-3">{{ $company->name }}</h1>
                    <h5><b>{{ $company->location->name }}</b></h5>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            @isset($company->about)
                <div class="col-12 col-md-6">
                    <div class="card card-custom mb-4">
                        <div class="card-body">
                            <h4 class="card-title"><em>About</em></h4>
                            @isset($company->about)
                                <p>{!! nl2br(e($company->about)) !!}</p>
                            @endisset
                        </div>
                    </div>
                </div>
            @endisset
            
            @if(isset($company->phone) && isset($company->email))
                <div class="col-12 col-md-6">
                    <div class="card card-custom mb-4">
                        <div class="card-body">
                            @isset($company->phone)
                                <h5><span class="oi oi-phone text-muted"></span> <span class="text-muted">Phone:</span> <span>{{ $company->phone }}</span></h5>
                            @endisset
                
                            @isset($company->email)
                                <h5><span class="oi oi-envelope-closed text-muted"></span> <span class="text-muted">Email:</span> <span>{{ $company->email }}</span></h5>
                            @endisset
                        </div>
                    </div>
                </div>
            @endif
            
            @if($company->users()->count() > 0)
                <div class="col-12 col-md-6">
                    <div class="card card-custom mb-4">
                        <div class="card-body">
                            @foreach($company->users as $user)
                                <div class="media {{ $loop->last ?: 'mb-3' }}">
                                    <a href="{{ route('company-user.show', $user) }}">
                                        <img class="mr-3 img-thumbnail" width="80" src="{{ $user->picture() ?? '/images/generic.png' }}">
                                    </a>
                                    <div class="media-body">
                                        <h4 class="my-0">
                                            <a href="{{ route('company-user.show', $user) }}">
                                                {{ $user->full_name }}
                                            </a>
                                        </h4>
                                        
                                        <h5 class="mt-0">{{ $user->job_title ?? 'Unknown' }}</h5>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            
            @if($addresses->total() > 0)
                <div class="col-12 col-md-6">
                    <div class="card card-custom mb-4">
                        <div class="card-body">
                            {!! $addresses->appends(Request::capture()->except('page'))->render("vendor.pagination") !!}
                            @foreach($addresses as $address)
                                <a href="{{ route('address.show', $address) }}" class="link-unstyled">
                                    <div class="address scale-on-hover-2">
                                        <h4>{{$address->name}}</h4>
                                        <p>
                                            <span class="oi oi-map-marker mr-3"></span>{{ $address->location->name }}
                                            <span class="oi oi-briefcase mx-3"></span>{{ $address->adverts()->count() }} {{ str_plural('advert', $address->adverts()->count()) }}
                                        </p>
                                    </div>
                                </a>
                                <hr>
                            @endforeach
                            <div class="text-center">
                                <p class="font-italic">{{ $addresses->total() }} {{ str_plural('address', $addresses->total()) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            
        </div>
    </div>
@endsection