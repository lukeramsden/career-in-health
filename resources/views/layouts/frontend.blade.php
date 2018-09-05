@extends('layouts.base')
@section('base_content')
    <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img src="/images/cih-logo.svg" height="40" alt="Career In Health Logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item {{ active_route('home') }}">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item {{ active_route('pricing') }}">
                    <a class="nav-link" href="{{ route('pricing') }}">Pricing</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                @guest
                    <li class="nav-item {{ active_route('login') }}">
                        <a href="{{ route('login') }}" class="nav-link">Log In</a>
                    </li>
                    <li class="nav-item {{ active_route('register') }}">
                        <a href="{{ route('register') }}" class="nav-link">Register</a>
                    </li>
                @endguest
                @auth
                    <li class="nav-item {{ active_route('dashboard') }}">
                        <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>

    @yield('content')
@endsection
@section('base_stylesheet')
    @yield('stylesheet')
@endsection
@section('base_script')
    @routes
    @yield('script')
@endsection