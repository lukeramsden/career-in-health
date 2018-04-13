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
                        
                        {{----}}
                        
                        <small class="text-muted">Profile</small>
                        
                        <a class="nav-link" href="#">My Profile</a>
                        <a class="nav-link" href="#">Edit Profile</a>
                        
                        {{----}}
                        
                        <small class="text-muted">Adverts</small>
                        
                        <a class="nav-link nav-link-action" href="#">Create New Advert</a>
                        <a class="nav-link" href="#">My Adverts</a>
                    @else
                        
                        {{----}}
                        
                        <small class="text-muted">Profile</small>
                        
                        <a class="nav-link {{ active_route('profile.show.me') }}" href="{{ route('profile.show.me') }}">My Profile</a>
                       
                        
                        <div class="nav-section-parent">
                            @php($activeRoute = active_route([
                                                                'profile.edit',
                                                                'profile.work-experience.*',
                                                                'profile.references.*',
                                                                'profile.certifications.*'
                                                            ]))
                            <div class="nav-section {{ $activeRoute }}">
                                <a class="nav-link nav-section-title" onclick="navSectionClick(this)" href="javascript:">Edit Profile</a>
                                <div class="nav-section-sub" {{ empty($activeRoute) ? 'style=display:none' : '' }}>
                                    <a class="nav-link nav-link-sub {{ active_route('profile.edit') }}" href="{{ route('profile.edit') }}">Edit Personal Details</a>
                                    <a class="nav-link nav-link-sub {{ active_route('profile.work-experience.*') }}" href="{{ route('profile.work-experience.edit') }}">Edit Work Experience</a>
                                    <a class="nav-link nav-link-sub {{ active_route('profile.references.*') }}" href="{{ route('profile.references.edit') }}">Edit References</a>
                                    <a class="nav-link nav-link-sub {{ active_route('profile.certifications.*') }}" href="{{ route('profile.certifications.edit') }}">Edit Certifications</a>
                                </div>
                            </div>
                        </div>

                        <a class="nav-link {{ active_route('cv-builder.*') }}" href="{{ route('cv-builder.profile') }}">CV Builder</a>
                        
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