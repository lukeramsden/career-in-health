@extends('layouts.app', ['title' => $self?'Your Profile':'Profile of '.$companyUser->full_name])
@section('content')
    <div class="container mb-5">
        <div class="card card-custom mt-5 mx-auto">
            <div class="card-body">
                <img src="{{ $companyUser->picture() ?? '/images/generic.png' }}" alt="Profile picture" width="220" class="img-thumbnail mx-auto d-block">
                <div class="text-center">
                    <h1 class="mt-3">{{ $companyUser->full_name }}</h1>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card card-custom mb-4">
                    <div class="card-body">
                        <div class="media">
                            <a href="{{ route('company.show', $companyUser->company) }}">
                                <img class="mr-3" height="150" src="{{ $companyUser->company->picture() ?? '/images/generic.png' }}">
                            </a>
                            <div class="media-body">
                                <h4 class="my-0">{{ $companyUser->job_title ?? 'Unknown' }}</h4>
                                
                                <h5 class="mt-0">
                                    at
                                    <a href="{{ route('company.show', $companyUser->company) }}">
                                        {{ $companyUser->company->name }} {!!verified_badge($companyUser->company)!!}
                                    </a>
                                </h5>
                                {{-- get only first line and then str_limit it --}}
                                <p class="mt-1">{{ str_limit(strtok($companyUser->company->about, "\n")) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection