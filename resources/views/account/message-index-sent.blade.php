@extends('layouts.app')
@section('content')
    <div class="d-none d-lg-block text-center" id="pagination-lg">
        <div class="card card-custom" id="message-index-filter">
            <div class="card-body">
                {!! $messages->appends(Request::capture()->except('page'))->render('vendor.pagination') !!}
                <p class="font-italic">{{ $messages->total() }} {{ str_plural('message', $messages->total()) }}</p>
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="unreadCheck" {{ Request::query('filterRead') ? 'checked' : '' }} disabled>
                  <label class="custom-control-label" for="unreadCheck">Only show unread</label>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-4">
        @foreach($messages as $message)
            <div class="card card-custom w-lg-50 mx-auto mb-4">
                <div class="card-header">
                    <b>From:</b> <a href="{{ route('company.show', [$message->advert->company]) }}">{{ $message->advert->company->name }}</a>
                    <br>
                    <b>Advert:</b> <a href="{{ route('advert.show', [$message->advert]) }}">{{ $message->advert->title }}</a>
                </div>
                <div class="card-body">
                    <p class="my-0 mb-1">{{ str_limit($message->body, 90) }}</p>
                    @unless($message->read)
                        <p class="my-0"><span class="badge badge-info p-2">Unread</span></p>
                    @else
                        <p class="my-0"><span class="badge badge-secondary p-2">Read</span></p>
                    @endif
                </div>
                <div class="card-footer p-2">
                    <div class="btn-group-sm">
                        <a href="{{ route('account.private-message.show', [$message]) }}" class="btn btn-primary">View</a>
                        @unless($message->read)
                            <a href="{{ route('account.private-message.mark-as-read', [$message]) }}" class="btn btn-link">Mark As Read</a>
                        @else
                            <a href="{{ route('account.private-message.mark-as-unread', [$message]) }}" class="btn btn-link">Mark As Unread</a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/URI.js/1.19.1/URI.min.js"></script>
    <script>
        $(function() {
            let $unreadCheck = $('#unreadCheck');
            $unreadCheck.prop("disabled", false);
            $unreadCheck.change(function() {
                window.location.href = URI(window.location.href)
                    .search({
                        page: 1,
                        filterRead: this.checked ? 1 : 0
                    })
                    .toString();
                
            });
        });
    </script>
@endsection
@section('stylesheet')
    <style>
        #pagination-lg {
            position: fixed;
            z-index: 9999;
            top: 1vw;
            right: 1vw;
        }
    </style>
@endsection