@extends('layouts.base')
@section('base_content')
    @includeWhen(!Auth::check(), 'partials.top-navbar')
    
    {{--main app--}}
    <div id="app" class="{{Auth::check()?'side-navbar':'top-navbar'}}">
        @auth
            <div id="notification-panel" class="">
                <div class="notification notification-actions">
                    <a class="view-all-notifications" href="{{ route('notifications.index') }}">View
                        All</a>
                    <button class="mark-as-read">Mark All As Read</button>
                </div>
                @foreach($currentUser->notifications()->orderByRaw('-read_at ASC')->take(10)->get() as $notif)
                    @switch($notif->type)
                        @case(\App\Notifications\ReceivedPrivateMessage::class)
                        <a href="{{ action('NotificationController@clickThrough', ['notification' => $notif]) }}"
                           class="link-unstyled">
                            <div class="notification {{$notif->unread()?'unread':''}} notification-private-message">
                                <div class="notification-inner">
                                    <p>Message from <b>{{ $notif->data['sender_name'] }}</b></p>
                                    <p>{{ str_limit($notif->data['body']) }}</p>
                                    <hr>
                                    <p>{{ $notif->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </a>
                        @break
                        @case(\App\Notifications\CompanyReceivedListingApplication::class)
                        <a href="{{ action('NotificationController@clickThrough', ['notification' => $notif]) }}"
                           class="link-unstyled">
                            <div class="notification {{$notif->unread()?'unread':''}} notification-application">
                                <div class="notification-inner">
                                    <p>Application from <b>{{ $notif->data['sender_name'] }}</b></p>
                                    @if($notif->data['body'])
                                        <p>{{ str_limit($notif->data['body']) }}</p>
                                    @else
                                        <p><span class="text-muted font-italic">No cover letter</span>
                                        </p>
                                    @endif
                                    <hr>
                                    <p>{{ $notif->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </a>
                        @break
                    @endswitch
                @endforeach
            </div>
        @endauth
        @yield('content')
    </div>
    
    @includeWhen(Auth::check(), 'partials.side-navbar')
@endsection
@section('head')
    <link href="/open-iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">
    @yield('stylesheet')
@endsection
@section('body-end')
    @auth
        <script>
        window.store.commit( 'updateUserType', '{{ $userType }}' );
        </script>
    @endauth
    @yield('pre-script')
    @yield('script')
@endsection