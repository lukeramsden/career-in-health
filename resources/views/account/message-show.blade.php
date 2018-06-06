@extends('layouts.app')
@section('content')
    <div class="container-fluid mt-5">
        <div class="card card-custom w-lg-50 mx-auto mb-4">
            <div class="card-header">
                <b>From:</b> <a href="{{ route('company.show', [$message->advert->company]) }}">{{ $message->advert->company->name }}</a>
                <br>
                <b>Advert:</b> <a href="{{ route('advert.show', [$message->advert]) }}">{{ $message->advert->title }}</a>
            
                @if(\App\AdvertApplication::hasApplied(Auth::user(), $message->advert))
                    <hr>
                    <b>You have applied to this advert!</b>
                    <br>
                    <small class="text-muted">
                        {{ str_limit(
                            \App\AdvertApplication::getApplication(
                                Auth::user(),
                                $message->advert
                            )->custom_cover_letter ?? 'No cover letter', 100) }}
                    </small>
                @endif
            </div>
            <div class="card-body">
                <p class="my-0 mb-1">{{ $message->body }}</p>
            </div>
            @if($isReceiver)
                <div class="card-footer p-2">
                    <div class="btn-group-sm">
                        <a href="{{ route('account.private-message.reply.show', [$message]) }}" class="btn btn-primary float-right px-4">Reply</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection