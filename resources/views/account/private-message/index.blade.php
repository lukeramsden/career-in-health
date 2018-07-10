@extends('layouts.app')
@section('content')
    <div class="container mt-lg-5">
        {{--@foreach($threads as $advert)--}}
            {{--<div class="card card-custom mb-3">--}}
                {{--<div class="card-header">--}}
                    {{--<b>To:</b> <a href="{{ route('company.show', [$advert->company]) }}">{{ $advert->company->name }}</a>--}}
                    {{--<br>--}}
                    {{--<b>Advert:</b> <a href="{{ route('advert.show', [$advert]) }}">{{ $advert->title }}</a>--}}
                {{--</div>--}}
                {{--<div class="card-body">--}}
                    {{--<p>--}}
                        {{--<b>{{ $threadMessageCount = $advert->threadMessageCount() }}</b> {{ str_plural('message', $threadMessageCount) }}--}}
                        {{--<br>--}}
                        {{--<b>{{ $threadReceivedUnreadMessagesCount = $advert->threadReceivedUnreadMessagesCount() }}</b> new unread {{ str_plural('message', $threadReceivedUnreadMessagesCount) }}--}}
                    {{--</p>--}}
                    {{--<small>Latest Received Message:</small>--}}
                    {{--<br>--}}
                    {{--<small class="text-muted">--}}
                        {{--{{ str_limit($advert->threadLatestReceivedMessage()->body) }}--}}
                    {{--</small>--}}
                {{--</div>--}}
                {{--<div class="card-footer">--}}
                    {{--<a href="{{ route('account.private-message.show', [$advert]) }}">View Messages</a>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--@endforeach--}}
        @usertype('employee')
            @foreach($messages as $message)
            <div class="card card-custom mb-3">
                <div class="card-header">
                    <b>To:</b> <a href="{{ route('company.show', [$message->company]) }}">{{ $message->company->name }}</a>
                    <br>
                    <b>Advert:</b> <a href="{{ route('advert.show', [$message->advert]) }}">{{ $message->advert->title }}</a>
                </div>
                <div class="card-body">
                    <p>
                        {{--<b>{{ $threadMessageCount = $message->threadMessageCount() }}</b> {{ str_plural('message', $threadMessageCount) }}--}}
                        {{--<br>--}}
                        {{--<b>{{ $threadReceivedUnreadMessagesCount = $message->threadReceivedUnreadMessagesCount() }}</b> new unread {{ str_plural('message', $threadReceivedUnreadMessagesCount) }}--}}
                    </p>
                    {{--<small>Latest Received Message:</small>--}}
                    {{--<br>--}}
                    {{--<small class="text-muted">--}}
                        {{--{{ str_limit($message->threadLatestReceivedMessage()->body) }}--}}
                    {{--</small>--}}
                </div>
                <div class="card-footer">
                    <a href="{{ route('account.private-message.show-employee', [$message->advert]) }}">View Messages</a>
                </div>
            </div>
            @endforeach
        @elseusertype('company')
        
        @endusertype
    </div>
@endsection