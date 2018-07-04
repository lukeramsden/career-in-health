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
            <div class="col-12 col-md-6">
                <div class="card card-custom mb-4">
                    <div class="card-body">
                        @isset($company->about)
                            <p>{!! nl2br(e($company->about)) !!}</p>
                        @endisset
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                @if(isset($company->phone) && isset($company->contact_email))
                    <div class="card card-custom mb-4">
                        <div class="card-body">
                            @isset($company->phone)
                                {{-- TODO: add this to company migration/controller/etc --}}
                                <h5><span class="oi oi-phone text-muted"></span> <span class="text-muted">Phone:</span> <span>{{ $company->phone }}</span></h5>
                            @endisset
                
                            @isset($company->contact_email)
                                {{-- TODO: add this to company migration/controller/etc --}}
                                <h5><span class="oi oi-envelope-closed text-muted"></span> <span class="text-muted">Email:</span> <span>{{ $company->contact_email }}</span></h5>
                            @endisset
                        </div>
                    </div>
                @endif
                
                <div class="card card-custom mb-4">
                    <div class="card-body">
                        @foreach($company->users as $user)
                            <div class="media">
                                <a href="{{ route('company-user.show', $user) }}">
                                    <img class="mr-3" height="80" src="{{ $user->picture() ?? '/images/generic.png' }}">
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
        </div>
    </div>
@endsection