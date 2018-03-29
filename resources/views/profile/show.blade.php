@extends('layouts.app')
@section('content')
    @php($owner = $profile === Auth::user()->profile)
    <div class="container">
        <div class="profile form-container has-top-bar">
            <div class="row">
                <div class="col-md-12 form-section py-3">
                    <div class="row no-side-padding edit-button-overlay-container">
                        <div class="media col-12">
                            <div class="py-2">
                                <img class="mr-3 profile-picture" src="{{ $profile->picture() }}" alt="Profile picture">
                            </div>
                            <div class="media-body">
                                <h1 class="display-3 mb-0 mt-3">{{ $profile->first_name . ' ' . $profile->last_name }}</h1>
                                @isset($profile->headline)
                                    <p class="h5 mb-0 mt-1"><b>{{ $profile->headline }}</b></p>
                                @endisset
                                @isset($profile->location)
                                    <p class="h5 mb-0 mt-1"><span class="text-muted">From</span> <b>{{ $profile->location }}</b></p>
                                @endisset
                                @isset($profile->description)
                                    <p class="my-2">{{ $profile->description }}</p>
                                @endisset
                                <div>
                                    @foreach($profile->jobTypes as $jobType)
                                        <span class="badge badge-primary">{{ $jobType->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @if($owner)
                            <div class="edit-button-overlay">
                                <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-block mx-0">EDIT</a>
                            </div>
                        @endif
                    </div>
                    <hr class="my-3 mx-3">
                    <div class="row">
                        <div class="col-md-6 border-right border-secondary edit-button-overlay-container">
                            @foreach($profile->work as $work)
                                <div class="work-experience mx-5 my-0">
                                    @if (!$loop->first)
                                        <hr class="my-1">
                                    @endif
                                    <div class="m-0 d-block">
                                        <span class="font-weight-bold">{{ $work->job_title }}</span> <span class="font-italic text-muted">at</span> <span class="font-weight-bold">{{ $work->company_name }}</span>
                                    </div>
                                        <div class="m-0 mt-2 d-block">
                                            @isset($work->end_date)
                                                <span class="font-italic text-muted">From</span> <span
                                                class="font-weight-bold">{{ date("F jS, Y", strtotime($work->start_date)) }}</span> <span
                                                class="font-italic text-muted">To</span> <span class="font-weight-bold">{{ date("F jS, Y", strtotime($work->end_date)) }}</span>
                                            @endisset
                                            
                                            @empty($work->end_date)
                                                <span class="font-italic text-muted">Started</span> <span class="font-weight-bold">{{ date("F jS, Y", strtotime($work->start_date)) }}</span> <span class="badge badge-secondary">Currently Working Here</span>
                                            @endempty
                                        </div>
                                </div>
                            @endforeach
                            
                            @if($owner)
                                    <div class="edit-button-overlay" style="top: 0; right: 10px;">
                                    <a href="{{ route('profile.work.edit') }}" class="btn btn-outline-primary btn-block mx-0">EDIT</a>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 edit-button-overlay-container">
                            @foreach($profile->references as $reference)
                                <div class="work-experience mx-5 my-0">
                                    @if (!$loop->first)
                                        <hr class="my-1">
                                    @endif
                                    <div class="m-0 d-block">
                                        <span class="font-weight-bold">{{ $reference->person_name }}</span> <span class="font-italic text-muted">at</span> <span class="font-weight-bold">{{ $reference->person_company }}</span>
                                    </div>
                                    <div class="m-0 mt-2 d-block">
                                        <span class="font-weight-bold">{{ $reference->person_relation }}</span>
                                        @isset($reference->work)
                                            <span>({{ $reference->work->company_name }})</span>
                                        @endisset
                                    </div>
                                    <div style="margin-bottom: 6px;"></div>
                                </div>
                            @endforeach
                            
                            @if($owner)
                                <div class="edit-button-overlay" style="top: 0; right: 10px;">
                                    <a href="{{ route('profile.references.edit') }}" class="btn btn-outline-primary btn-block mx-0">EDIT</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
