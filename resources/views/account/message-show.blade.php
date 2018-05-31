@extends('layouts.app')
@section('content')
    <div class="container-fluid mt-5">
        <div class="card card-custom w-lg-50 mx-auto mb-4">
            <div class="card-header">
                <b>From:</b> <a href="{{ route('company.show', [$message->advert->company]) }}">{{ $message->advert->company->name }}</a>
                <br>
                <b>Advert:</b> <a href="{{ route('advert.show', [$message->advert]) }}">{{ $message->advert->title }}</a>
            </div>
            <div class="card-body">
                <p class="my-0 mb-1">{{ $message->body }}</p>
            </div>
            <div class="card-footer p-2">
                <div class="btn-group-sm">
                    <a href="javascript:" class="btn btn-primary float-right">Reply</a>
                </div>
            </div>
        </div>
    </div>
@endsection