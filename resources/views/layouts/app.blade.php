@extends('layouts.base')
@section('b_content')
    <div id="navbar" class="d-none d-lg-block">
        <a href="{{ route(Auth::check() ? 'dashboard' : 'home') }}">
            <img class="logo" src="/images/cih-logo.svg" alt="logo">
        </a>
        <div id="nav-inner">
            <nav class="nav flex-column">
                @guest
                    <a class="nav-link {{ active_route('home') }}" href="{{ route('home') }}">Home</a>
                    <a class="nav-link nav-link-action {{ active_route('search') }}" href="{{ route('search') }}">Search</a>
                    <a class="nav-link {{ active_route('login') }}" href="{{ route('login') }}">Log In</a>
                    <a class="nav-link {{ active_route('register') }}" href="{{ route('register') }}">Register</a>
                @endguest
                
                @auth
                    <a class="nav-link {{ active_route('dashboard') }}" href="{{ route('dashboard') }}">Dashboard</a>
                    
                    @if(Auth::user()->isCompany())
                        
                        {{----}}
                        
                        <small class="text-muted">Profile</small>
                        
                        {{--<a class="nav-link {{ active_route('company.show.me') }}" href="{{ route('company.show.me') }}">My Profile</a>--}}
                        {{--<a class="nav-link {{ active_route('company.edit') }}" href="{{ route('company.edit') }}">Edit Profile</a>--}}
                        
                        {{----}}
                        
                        <small class="text-muted">Adverts</small>
                        
                        <a class="nav-link nav-link-action {{ active_route('advert.create') }}" href="{{ route('advert.create') }}">Create New Advert</a>
                        <a class="nav-link {{ active_route(['advert.index', 'advert.edit', 'advert.show.*']) }}" href="{{ route('advert.index') }}">My Adverts</a>
                        
                        {{----}}
                        
                        <small class="text-muted">Addresses</small>
                        
                        <a class="nav-link {{ active_route('address.create') }}" href="{{ route('address.create') }}">Create New Address</a>
                        <a class="nav-link {{ active_route(['address.index', 'address.edit', 'address.show.*']) }}" href="{{ route('address.index') }}">My Addresses</a>
                        
                        {{----}}
                    
                    @elseif(Auth::user()->isEmployee())
                        
                        {{----}}
                        
                        <small class="text-muted">Profile</small>
                        
                        {{--<a class="nav-link {{ active_route('profile.show.me') }}" href="{{ route('profile.show.me') }}">View Profile</a>--}}
                        {{--<a class="nav-link {{ active_route('profile.edit') }}" href="{{ route('profile.edit') }}">Edit Profile</a>--}}
                        <a class="nav-link {{ active_route('cv.builder') }}" href="{{ route('cv.builder') }}">CV Builder</a>
                        
                        {{----}}
                        
                        <small class="text-muted">Get Hired</small>
                        
                        <a class="nav-link nav-link-action {{ active_route('search') }}" href="{{ route('search') }}">Search</a>
                        
                        <a class="nav-link {{ active_route('advert.application.*') }}" href="{{ route('advert.application.index') }}">My Applications</a>
                    @endif
                    
                    {{----}}
                    
                    <small class="text-muted">Account</small>
                    
                    <div class="nav-section-parent">
                        @set('activeRoute', active_route('account.private-message.*'))
                        <div class="nav-section {{ $activeRoute }}">
                            <a class="nav-link nav-section-title"
                               onclick="navSectionClick(this)"
                               href="javascript:">
                                Messages
                                <span class="badge badge-danger p-1">1</span>
                            </a>
                            <div class="nav-section-sub" {{ empty($activeRoute) ? 'style=display:none' : '' }}>
                                <a class="nav-link nav-link-sub {{ active_route('account.private-message.index') }}" href="{{ route('account.private-message.index') }}">Inbox</a>
{{--                                <a class="nav-link nav-link-sub {{ active_route('account.private-message.index.sent') }}" href="{{ route('account.private-message.index.sent') }}">Sent Messages</a>--}}
                            </div>
                        </div>
                        @unset($activeRoute)
                    </div>
                    
                    <div class="nav-section-parent">
                        @set('activeRoute', active_route('account.manage.*'))
                        <div class="nav-section {{ $activeRoute }}">
                            <a class="nav-link nav-section-title" onclick="navSectionClick(this)" href="javascript:">Settings</a>
                            <div class="nav-section-sub" {{ empty($activeRoute) ? 'style=display:none' : '' }}>
                                <a class="nav-link nav-link-sub {{ active_route('account.manage.email') }}" href="{{ route('account.manage.email') }}">Change Email</a>
                                <a class="nav-link nav-link-sub {{ active_route('account.manage.password') }}" href="{{ route('account.manage.password') }}">Change Password</a>
                            </div>
                        </div>
                        @unset($activeRoute)
                    </div>
                    <a class="nav-link" href="{{ route('logout.get') }}">Log Out</a>
                    
                    {{----}}
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
                        <li class="nav-item {{ active_route('home') }}">
                            <a class="nav-link" href="{{ route('home') }}">Home</a>
                        </li>
                        
                        <li class="nav-item {{ active_route('search') }}">
                            <a class="nav-link" href="{{ route('search') }}">Search</a>
                        </li>
                        
                        <li class="nav-item {{ active_route('login') }}">
                            <a class="nav-link" href="{{ route('login') }}">Log In</a>
                        </li>
                        
                        <li class="nav-item {{ active_route('register') }}">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endguest
                    
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ active_route('dashboard') }}" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        
                        @if(Auth::user()->isCompany())
                            
                            {{----}}
                            
                            <small class="text-light">Profile</small>
                            
                            <li class="nav-item">
{{--                                <a class="nav-link {{ active_route('company.show.me') }}" href="{{ route('company.show.me') }}">My Profile</a>--}}
                            </li>
                            
                            <li class="nav-item">
                                {{--<a class="nav-link {{ active_route('company.edit') }}" href="{{ route('company.edit') }}">Edit Profile</a>--}}
                            </li>
                            
                            {{----}}
                            
                            <small class="text-light">Adverts</small>
                            
                            <li class="nav-item">
                                <a class="nav-link nav-link-action {{ active_route('advert.create') }}" href="{{ route('advert.create') }}">Create New Advert</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ active_route(['advert.index', 'advert.edit', 'advert.show.*']) }}" href="{{ route('advert.index') }}">My Adverts</a>
                            </li>
                            
                            {{----}}
                            
                            <small class="text-light">Addresses</small>
                            
                            <li class="nav-item">
                                <a class="nav-link {{ active_route('address.create') }}" href="{{ route('address.create') }}">Create New Address</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ active_route(['address.index', 'address.edit', 'address.show.*']) }}" href="{{ route('address.index') }}">My Addresses</a>
                            </li>
                            
                            {{----}}
                        
                        @elseif(Auth::user()->isEmployee())
                            
                            {{----}}
                            
                            <small class="text-light">Profile</small>
                            
                            <li class="nav-item">
{{--                                <a class="nav-link {{ active_route('profile.show.me') }}" href="{{ route('profile.show.me') }}">View Profile</a>--}}
                            </li>
                            <li class="nav-item">
{{--                                <a class="nav-link {{ active_route('profile.edit') }}" href="{{ route('profile.edit') }}">Edit Profile</a>--}}
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ active_route('cv.builder') }}" href="{{ route('cv.builder') }}">CV Builder</a>
                            </li>
                            
                            {{----}}
                            
                            <small class="text-light">Get Hired</small>
                            
                            <li class="nav-item">
                                <a class="nav-link {{ active_route('search') }}" href="{{ route('search') }}">Search</a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link {{ active_route('advert.application.*') }}" href="{{ route('advert.application.index') }}">My Applications</a>
                            </li>
                        @endif
                        
                        {{----}}
                        
                        <small class="text-light">Account</small>
                        
                        <li class="nav-item">
                            <div class="nav-section-parent">
                                @set('activeRoute', active_route('account.private-message.*'))
                                <div class="nav-section {{ $activeRoute }}">
                                    <a class="nav-link nav-section-title"
                                       onclick="navSectionClick(this)"
                                       href="javascript:">
                                        Messages
                                            <span class="badge badge-danger p-1">1</span>
                                    </a>
                                    <div class="nav-section-sub" {{ empty($activeRoute) ? 'style=display:none' : '' }}>
                                        <a class="nav-link nav-link-sub {{ active_route('account.private-message.index') }}" href="{{ route('account.private-message.index') }}">Inbox</a>
{{--                                        <a class="nav-link nav-link-sub {{ active_route('account.private-message.index.sent') }}" href="{{ route('account.private-message.index.sent') }}">Sent Messages</a>--}}
                                    </div>
                                </div>
                                @unset($activeRoute)
                            </div>
                        </li>
                        
                        <li class="nav-item">
                            <div class="nav-section-parent">
                                @set('activeRoute', active_route('account.manage.*'))
                                <div class="nav-section {{ $activeRoute }}">
                                    <a class="nav-link nav-section-title" onclick="navSectionClick(this)" href="javascript:">Settings</a>
                                    <div class="nav-section-sub" {{ empty($activeRoute) ? 'style=display:none' : '' }}>
                                        <a class="nav-link nav-link-sub {{ active_route('account.manage.email') }}" href="{{ route('account.manage.email') }}">Change Email</a>
                                        <a class="nav-link nav-link-sub {{ active_route('account.manage.password') }}" href="{{ route('account.manage.password') }}">Change Password</a>
                                    </div>
                                </div>
                                @unset('activeRoute')
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout.get') }}">Log Out</a>
                        </li>
                        
                        {{----}}
                    @endauth
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
            $(t).next().slideToggle("fast", "easeInOutExpo");
        }
    </script>
    @yield('script')
@endsection