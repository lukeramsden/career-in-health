@extends('layouts.app')
@section('content')
    <div class="container mb-5">
        <div class="card card-custom mt-5 mx-auto">
            <div class="card-body">
                <img src="{{ $company->picture() ?? '/images/generic.png' }}" alt="Profile picture" width="200" class="img-thumbnail mx-auto d-block">
                <div class="text-center">
                    <h1 class="mt-3">{{ $company->name }}</h1>
                    <h5><b>{{ $company->location }}</b></h5>
                    <h5>{{ $company->headline }}</h5>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12 col-md-6">
                <div class="card card-custom mb-4">
                    <div class="card-body">
                        @isset($company->description)
                            <p>{!! nl2br(e($company->description)) !!}</p>
                        @endisset
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card card-custom mb-4">
                    <div class="card-body">
                        @isset($company->phone)
                            <h5><span class="oi oi-phone text-muted"></span> <span class="text-muted">Phone:</span> <span>{{ $company->phone }}</span></h5>
                        @endisset
                        
                        @isset($company->contact_email)
                            <h5><span class="oi oi-envelope-closed text-muted"></span> <span class="text-muted">Email:</span> <span>{{ $company->contact_email }}</span></h5>
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection