@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Profile

                        @if($profile == Auth::user()->profile)
                            <div class="btn-group btn-group-sm" role="group" style="float:right">
                                <a href="{{ route('profile.edit') }}" class="btn btn-primary">EDIT PROFILE</a>
                                <a href="{{ route('profile.work.edit') }}" class="btn    btn-primary">EDIT WORK EXPERIENCE</a>
                                <a href="{{ route('profile.references.edit') }}" class="btn    btn-primary">EDIT REFERENCES</a>
                                <a href="{{ route('profile.certifications.edit') }}" class="btn    btn-primary">EDIT CERTIFICATIONS</a>
                            </div>
                        @endif
                    </div>

                    <div class="card-body">
                        @if(session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
    
                        <img src="{{ $profile->picture() }}" class="img-thumbnail mx-auto d-block" width="200" alt="User's profile picture">
                        <h2 style="text-align: center">{{ $profile->first_name . ' ' . $profile->last_name }}</h2>
                        <h5 style="text-align: center">{{ $profile->headline }}</h5>
                        <h5 style="text-align: center">{{ $profile->location }}</h5>
                        <p style="text-align: center">{{ $profile->description }}</p>

                        <ul class="list-group">
                            @foreach($profile->jobTypes as $job)
                                <li class="list-group-item">{{ $job->name }}</li>
                            @endforeach
                        </ul>
                        
                        <hr>
                        <p><b>Work Experience</b></p>
                        <hr>

                        @foreach($profile->work as $work)
                            <p>{{ $work->job_title }}</p>
                            <p>{{ $work->company_name }}</p>
                            <p>{{ $work->start_date }}</p>
                            @isset($work->end_date)
                                <p>{{ $work->end_date }}</p>
                            @endisset

                            @empty($work->end_date)
                                <p><b>Currently Working Here</b></p>
                            @endempty
                            
                            @foreach($work->references as $reference)
                                <p><em>{{ $reference->person_name }}</em> at <em>{{ $reference->person_company }}</em></p>
                            @endforeach
                            <hr>
                        @endforeach

                        <hr>
                        <p><b>References</b></p>
                        <hr>
                        
                        @foreach($profile->references as $reference)
                            <p>{{ $reference->person_name }}</p>
                            <p>{{ $reference->person_company }}</p>
                            <p>{{ $reference->person_relation }}</p>
                            <p>{{ $reference->person_contact }}</p>
                            @if(isset($reference->work))
                                <p><em>{{ $reference->work->job_title }}</em> at <em>{{ $reference->work->company_name }}</em></p>
                            @endif
                            <hr>
                        @endforeach
    
                        <hr>
                        <p><b>Certifications</b></p>
                        <hr>
                        
                        @foreach($profile->certifications as $certification)
                            <p>
                                {{ $certification->name }}
                                <a href="{{ route('profile.certifications.download', ['certification' => $certification]) }}" class="btn btn-secondary">Download</a>
                            </p>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
