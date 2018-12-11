@extends('layouts.base')
@section('base_content')
    @includeWhen(!Auth::check(), 'partials.top-navbar')
    
    {{--main app--}}
    <div id="app" class="{{Auth::check()?'side-navbar':'top-navbar'}}">
        @auth
            <latest-notifications />
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