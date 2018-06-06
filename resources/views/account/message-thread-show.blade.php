@extends('layouts.app')
@section('content')
    <div class="container mt-lg-5">
        <div class="card card-custom mx-lg-5">
            <div class="card-header">
                <b>Company:</b> <a href="{{ route('company.show', [$advert->company]) }}">{{ $advert->company->name }}</a>
                <br>
                <b>Advert:</b> <a href="{{ route('advert.show', [$advert]) }}">{{ $advert->title }}</a>
                
                @if(\App\AdvertApplication::hasApplied(Auth::user(), $advert))
                    <hr>
                    <b>You have applied to this advert!</b>
                    <br>
                    <small class="text-muted">
                        {{ str_limit(
                            \App\AdvertApplication::getApplication(
                                Auth::user(),
                                $advert
                            )->custom_cover_letter ?? 'No cover letter', 100) }}
                    </small>
                @endif
            </div>
            <div class="card-body">
                @foreach($messages as $message)
                    {{ $message }}
                @endforeach
            </div>
        </div>
    
    </div>
@endsection