@extends('layouts.base')
@section('b_content')
    <div id="navbar" class="d-none d-lg-block">
        <img class="logo" src="/images/cih-logo.svg" alt="logo">
        <div id="nav-inner">
            <nav class="nav flex-column">
                @guest
                    <a class="nav-link {{ active_route('home') }}" href="{{ route('home') }}">Home</a>
                    <a class="nav-link {{ active_route('search') }}" href="{{ route('search') }}">Search</a>
                    <a class="nav-link {{ active_route('login') }}" href="{{ route('login') }}">Log In</a>
                    <a class="nav-link {{ active_route('register') }}" href="{{ route('register') }}">Register</a>
                @endguest
                
                @auth
                    <a class="nav-link {{ active_route('dashboard') }}" href="{{ route('dashboard') }}">Dashboard</a>
                
                    @if(Auth::user()->isCompany())
                        
                        {{----}}
                        
                        <small class="text-muted">Profile</small>
                        
                        <a class="nav-link {{ active_route('company.show.me') }}" href="{{ route('company.show.me') }}">My Profile</a>
                        <a class="nav-link {{ active_route('company.edit') }}" href="{{ route('company.edit') }}">Edit Profile</a>
                        
                        {{----}}
                        
                        <small class="text-muted">Adverts</small>
                        
                        <a class="nav-link nav-link-action {{ active_route('advert.create') }}" href="{{ route('advert.create') }}">Create New Advert</a>
                        <a class="nav-link {{ active_route(['advert.index', 'advert.edit']) }}" href="{{ route('advert.index') }}">My Adverts</a>
                    @else
                        
                        {{----}}
                        
                        <small class="text-muted">Profile</small>
                        
                        <a class="nav-link {{ active_route('profile.show.me') }}" href="{{ route('profile.show.me') }}">View Profile</a>
                        <a class="nav-link {{ active_route('profile.edit') }}" href="{{ route('profile.edit') }}">Edit Profile</a>
                        <a class="nav-link {{ active_route('cv.builder') }}" href="{{ route('cv.builder') }}">CV Builder</a>
                       
                        {{-- EXAMPLE SUBMENU --}}
                        {{--<div class="nav-section-parent">--}}
                            {{--@php($activeRoute = active_route([--}}
                                                                {{--'profile.edit',--}}
                                                                {{--'profile.work-experience.*',--}}
                                                                {{--'profile.references.*',--}}
                                                                {{--'profile.certifications.*'--}}
                                                            {{--]))--}}
                            {{--<div class="nav-section {{ $activeRoute }}">--}}
                                {{--<a class="nav-link nav-section-title" onclick="navSectionClick(this)" href="javascript:">Edit Profile</a>--}}
                                {{--<div class="nav-section-sub" {{ empty($activeRoute) ? 'style=display:none' : '' }}>--}}
                                    {{--<a class="nav-link nav-link-sub {{ active_route('profile.edit') }}" href="{{ route('profile.edit') }}">Edit Personal Details</a>--}}
                                    {{--<a class="nav-link nav-link-sub {{ active_route('profile.work-experience.*') }}" href="{{ route('profile.work-experience.edit') }}">Edit Work Experience</a>--}}
                                    {{--<a class="nav-link nav-link-sub {{ active_route('profile.references.*') }}" href="{{ route('profile.references.edit') }}">Edit References</a>--}}
                                    {{--<a class="nav-link nav-link-sub {{ active_route('profile.certifications.*') }}" href="{{ route('profile.certifications.edit') }}">Edit Certifications</a>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}

                        {{--<a class="nav-link {{ active_route('cv-builder.*') }}" href="{{ route('cv-builder.profile') }}">CV Builder</a>--}}
                        
                        {{----}}
                        
                        <small class="text-muted">Get Hired</small>
                        
                        <a class="nav-link {{ active_route('search') }}" href="{{ route('search') }}">Search</a>
                        
                        <a class="nav-link {{ active_route('advert.application.*') }}" href="{{ route('advert.application.index') }}">My Applications</a>
                    @endif
                  
                    {{----}}
                    
                    <small class="text-muted">Account</small>
                    <a class="nav-link" href="{{ route('logout.get') }}">Log Out</a>
                    
                @endauth
            </nav>
        </div>
    </div>
    
    <nav class="navbar navbar-dark bg-primary d-block d-lg-none fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">Career In Health</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
    
            <div id="navbarCollapse" class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto"></ul>
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item {{ active_route('home') }}"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                        <li class="nav-item {{ active_route('search') }}"><a class="nav-link" href="{{ route('search') }}">Search</a></li>
                        <li class="nav-item {{ active_route('login') }}"><a class="nav-link" href="{{ route('login') }}">Log In</a></li>
                        <li class="nav-item {{ active_route('register') }}"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @endguest
                    
                    {{-- TODO: flesh out mobile navbar --}}
                </ul>
            </div>
        </div>
    </nav>

    <div id="app">
        @yield('content')
    </div>
@endsection
@section('b_stylesheet')
    <link href="/open-iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">
    @yield('stylesheet')
@endsection
@section('b_script')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
    <script>
        function navSectionClick(t) {
            $(t).next().slideToggle("slow", "easeInOutExpo");
        }
    </script>
    @yield('script')
@endsection