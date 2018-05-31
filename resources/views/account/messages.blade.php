@extends('layouts.app')
@section('content')
    <div class="container-fluid mt-4">
        @foreach($messages as $msg)
            <div class="card card-custom w-lg-50 mx-auto mb-4">
                <div class="card-header">
                    <b>From:</b> <a href="{{ route('company.show', [$msg->advert->company]) }}">{{ $msg->advert->company->name }}</a>
                    <br>
                    <b>Advert:</b> <a href="{{ route('advert.show', [$msg->advert]) }}">{{ $msg->advert->title }}</a>
                </div>
                <div class="card-body">
                    <p class="my-0 mb-1">{{ str_limit($msg->body, 130) }}</p>
                    @unless($msg->read)
                        <p class="my-0"><span class="badge badge-info p-2">Unread</span></p>
                    @else
                        <p class="my-0"><span class="badge badge-secondary p-2">Read</span></p>
                    @endif
                </div>
                <div class="card-footer p-2">
                    <div class="btn-group-sm">
                        <a href="{{ route('account.private-message.show', [$msg]) }}" class="btn btn-primary">View</a>
                        @unless($msg->read)
                            <a href="{{ route('account.private-message.mark-as-read', [$msg]) }}" class="btn btn-link">Mark As Read</a>
                        @else
                            <a href="{{ route('account.private-message.mark-as-unread', [$msg]) }}" class="btn btn-link">Mark As Unread</a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection