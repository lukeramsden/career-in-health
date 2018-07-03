@extends('layouts.app')
@section('content')
    <div class="container mb-5">
        <div class="card card-custom mt-5 mx-auto">
            <div class="card-body">
                <img src="{{ $companyUser->picture() ?? '/images/generic.png' }}" alt="Profile picture" width="200" class="img-thumbnail mx-auto d-block">
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
                                <img class="mr-3" height="120" src="{{ $companyUser->company->picture() ?? '/images/generic.png' }}">
                            </a>
                            <div class="media-body">
                                <a href="{{ route('company.show', $companyUser->company) }}">
                                    <h4 class="mt-0">{{ $companyUser->company->name }}</h4>
                                </a>
                                {{ str_limit($companyUser->company->about) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection