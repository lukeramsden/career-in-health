@extends('layouts.app')
@section('content')
    @php($owner = $profile === Auth::user()->profile)
    <div class="container">
        <div class="profile form-container has-top-bar">
            <div class="row">
                <div class="col-md-12 form-section py-3">
                    <div class="row no-side-padding">
                    
                    <div class="media col-11">
                        <div class="py-2">
                            <img class="mr-3 profile-picture" src="{{ $profile->picture() }}" alt="Profile picture">
                        </div>
                        <div class="media-body">
                            <h1 class="display-3 mb-0 mt-3">{{ $profile->first_name . ' ' . $profile->last_name }}</h1>
                            <p class="h5 mb-0 mt-1"><b>{{ $profile->headline }}</b></p>
                            <p class="h5 mb-0 mt-1"><span class="text-muted">From</span> <b>{{ $profile->location }}</b></p>
                            <p>{{ $profile->description }}</p>
                        </div>
                    </div>
                    @if($owner)
                        <div class="col-1">
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-block mx-0">EDIT</a>
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
