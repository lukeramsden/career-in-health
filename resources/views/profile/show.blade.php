@extends('layouts.app')
@section('content')
    <div class="container mb-5">
        <div class="card card-custom mt-5 mx-auto">
            <div class="card-body">
                <img src="{{ $profile->picture() ?? 'images/generic.png' }}" alt="Profile picture" width="200" class="img-thumbnail mx-auto d-block">
                <div class="text-center">
                    <h1 class="mt-3">{{ $profile->fullName() }}</h1>
                    <h5><b>{{ $profile->location }}</b></h5>
                    <h5>{{ $profile->headline }}</h5>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-sm-12 col-md-6">
                <div class="card card-custom mb-4">
                    <div class="card-body">
                        <p>{!! nl2br(e($profile->description)) !!}</p>
                    </div>
                </div>
            </div>
            @php($cv = $profile->user->cv)
            <div class="col-sm-12 col-md-6">
                @if($cv->education->count() > 0)
                    <div class="card card-custom mb-4">
                        <div class="card-body">
                            <h4 class="mb-4"><em>Education</em></h4>
                            
                            @foreach($cv->education as $education)
                                <div>
                                    <p class="my-1"><b>{{ $education->degree }} in {{ $education->field_of_study }}</b></p>
                                    <p class="my-1">{{ $education->school_name }} - {{ $education->location }}</p>
                                    @isset($education->end_date)
                                        <p class="my-1">{{ $education->start_date->format('F Y') }} to {{ $education->end_date->format('F Y') }}</p>
                                    @else
                                        <p class="my-1">Started {{ $education->start_date->format('F Y') }}</p>
                                    @endisset
                                </div>
                                @if(!$loop->last)
                                    <hr class="my-4">
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
                @if($cv->workExperience->count() > 0)
                    <div class="card card-custom mb-4">
                        <div class="card-body">
                    
                            <h4 class="mb-4"><em>Work Experience</em></h4>
                    
                            @foreach($cv->workExperience as $workExperience)
                                <div>
                                    <p class="my-1"><b>{{ $workExperience->job_title }}</b> at <b>{{ $workExperience->company_name }}</b></p>
                                    <p class="my-1">{{ $workExperience->location }}</p>
                                    @isset($workExperience->end_date)
                                        <p class="my-1">{{ $workExperience->start_date->format('F Y') }} to {{ $workExperience->end_date->format('F Y') }}</p>
                                    @else
                                        <p class="my-1">Started {{ $workExperience->start_date->format('F Y') }}</p>
                                    @endisset
                                    @isset($workExperience->description)
                                        <p class="my-1">{!! nl2br(e($workExperience->description)) !!}</p>
                                    @endisset
                                </div>
                                @if(!$loop->last)
                                    <hr class="my-4">
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection