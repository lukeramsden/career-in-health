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
                                <p class="my-2">{{ $profile->description }}</p>
                                <div>
                                    @foreach($profile->jobTypes as $jobType)
                                        <span class="badge badge-pill badge-primary">{{ $jobType->name }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @if($owner)
                            <div class="col-1">
                                <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-block mx-0">EDIT</a>
                            </div>
                        @endif
                    </div>
                    <hr class="my-3 mx-5">
                    <div class="row">
                        <div class="col-md-6 border-right border-secondary">
                            <ul class="list-group list-group-flush list-work-experience mx-5">
                                @foreach($profile->work as $work)
                                    <li class="list-group-item text-truncate">
                                        <div class="m-0 d-block">
                                            <span class="font-weight-bold">{{ $work->job_title }}</span> <span class="font-italic text-muted">at</span> <span class="font-weight-bold">{{ $work->company_name }}</span>
                                        </div>
                                        <div class="mt-2 d-block">
                                            @isset($work->end_date)
                                                <span class="font-italic text-muted">From</span> <span
                                                class="font-weight-bold">{{ date("F jS, Y", strtotime($work->start_date)) }}</span> <span
                                                class="font-italic text-muted">To</span> <span class="font-weight-bold">{{ date("F jS, Y", strtotime($work->end_date)) }}</span>
                                            @endisset
                                            
                                            @empty($work->end_date)
                                                <span class="font-italic text-muted">Started</span> <span class="font-weight-bold">{{ date("F jS, Y", strtotime($work->start_date)) }}</span> <span class="badge badge-secondary">Currently Working Here</span>
                                            @endempty
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-6"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
