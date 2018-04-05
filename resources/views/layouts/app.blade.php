@extends('layouts.base')
@section('b_content')
    <div id="navbar">
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
                        <small class="text-muted">Profile</small>
                        <a class="nav-link" href="#">My Profile</a>
                        <a class="nav-link" href="#">Edit Profile</a>
                        <small class="text-muted">Adverts</small>
                        <a class="nav-link nav-link-action" href="#">Create New Advert</a>
                        <a class="nav-link" href="#">My Adverts</a>
                    @else
                        <small class="text-muted">Profile</small>
                        
                        <a class="nav-link {{ active_route('profile.show.me') }}" href="{{ route('profile.show.me') }}">My Profile</a>
                       
                        <a class="nav-link {{ active_route([
                            'profile.edit',
                            'profile.work-experience.*',
                            'profile.references.*',
                            'profile.certifications.*'
                        ]) }}" href="{{ route('profile.edit') }}">Edit Profile</a>
                        
                        <a class="nav-link {{ active_route('cv-builder.*') }}" href="{{ route('cv-builder.profile') }}">CV Builder</a>
                        
                        <small class="text-muted">Find a Job</small>
                        
                        <a class="nav-link {{ active_route('search') }}" href="{{ route('search') }}">Search</a>
                        <a class="nav-link {{ active_route('advert.application.*') }}" href="{{ route('advert.application.index') }}">My Applications</a>
                    @endif
                  
                    <small class="text-muted">Account</small>
                    <a class="nav-link" href="{{ route('logout.get') }}">Log Out</a>
                    
                @endauth
            </nav>
        </div>
    </div>

    <div id="app">
        <main>
            @yield('content')
        </main>
    </div>
@endsection
@section('b_stylesheet')
    <link href="/open-iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">
    @yield('stylesheet')
@endsection
@section('b_script')
    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    @yield('script')
@endsection