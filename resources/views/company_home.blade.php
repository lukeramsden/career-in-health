@extends('layouts.app')
@section('content')

    <div class="container mt-5">
        <h4 class="mb-3">Recently Updated Applications...</h4>
        @foreach($applications as $application)
            <div class="mb-4">
                <p class="my-0"><b>{{ $application->advert->title }}</b></p>
                <p class="my-0">
                    <a href="{{ route('profile', ['profile' => $application->user->profile]) }}">{{ $application->user->profile->first_name . ' ' . $application->user->profile->last_name }}</a>
                    {{ $application->custom_cover_letter or 'Cover letter is empty' }}
                </p>
            </div>
        @endforeach
    </div>

@endsection
