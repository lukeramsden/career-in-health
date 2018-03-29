@extends('layouts.app')
@section('content')
    @php($owner = $company === Auth::user()->company)
    <div class="container">
        <div class="company form-container has-top-bar">
            <div class="row">
                <div class="col-md-12 form-section py-3">
                    <div class="row no-side-padding edit-button-overlay-container">
                        <div class="media col-12">
                            <div class="py-2">
                                <img class="mr-3 company-picture" src="{{ $company->picture() }}" alt="Profile picture">
                            </div>
                            <div class="media-body">
                                <h1 class="display-3 mb-0 mt-3">{{ $company->name }}</h1>
                                @isset($company->headline)
                                    <p class="h5 mb-0 mt-1"><b>{{ $company->headline }}</b></p>
                                @endisset
                                @isset($company->location)
                                    <p class="h5 mb-0 mt-1"><b>{{ $company->location }}</b></p>
                                @endisset
                                @isset($company->description)
                                    <p class="my-2">{{ $company->description }}</p>
                                @endisset
                            </div>
                        </div>
                        @if($owner)
                            <div class="edit-button-overlay">
                                <a href="{{ route('company.edit') }}" class="btn btn-outline-primary btn-block mx-0">EDIT</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
