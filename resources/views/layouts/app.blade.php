@extends('layouts.base')
@section('base_content')
    {{-- side nav --}}
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
                    @onboarding
                        @foreach (Auth::user()->onboarding()->steps as $step)
                            <a
                            class="nav-link nav-link-onboarding {{ $step->linkClass ?: '' }} {{ Request::fullUrlIs($step->link) ? 'active' : ''  }}"
                            href="{{ $step->link }}"
                            {{ $step->complete() ? 'disabled=disabled' : '' }}>
                                @if($step->complete())
                                    <span class="oi oi-circle-check"></span>
                                @else
                                    <span class="oi oi-circle-x"></span>
                                @endif
                                {{ $loop->iteration }}. {{ $step->title }}
                            </a>
                        @endforeach
                    
                    @else
                        <a class="nav-link {{ active_route('dashboard') }}" href="{{ route('dashboard') }}">Dashboard</a>
                    
                        @if(Auth::user()->isValidCompany())
                            <small class="text-muted">Your Company</small>
                        
                            <li class="nav-item dropright {{ active_route(['company.show.me', 'company.edit']) }}">
                                <a class="nav-link dropdown-toggle"
                                   href="javascript:"
                                   id="navdropdown-Company"
                                   data-toggle="dropdown"
                                   aria-haspopup="true"
                                   aria-expanded="false">
                                    Company
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navdropdown-Company">
                                    <a class="dropdown-item {{active_route('company.show.me')}}" href="{{route('company.show.me')}}">View Your Company</a>
                                    <a class="dropdown-item {{active_route('company.edit')}}" href="{{route('company.edit')}}">Edit Your Company</a>
                                </div>
                            </li>
                        
                            <li class="nav-item dropright {{ active_route(['advert.*', 'company.show.applications']) }}">
                                <a class="nav-link dropdown-toggle"
                                   href="javascript:"
                                   id="navdropdown-Adverts"
                                   data-toggle="dropdown"
                                   aria-haspopup="true"
                                   aria-expanded="false">
                                    Adverts
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navdropdown-Adverts">
                                    <a class="dropdown-item dropdown-item-action {{ active_route('advert.create') }}" href="{{ route('advert.create') }}">Create New Advert</a>
                                    <a class="dropdown-item {{ active_route(['advert.index', 'advert.edit', 'advert.show.*']) }}" href="{{ route('advert.index') }}">View Adverts</a>
                                    <a class="dropdown-item {{ active_route('company.show.applications') }}" href="{{ route('company.show.applications') }}">View Applications</a>
                                </div>
                            </li>
                            
                            <li class="nav-item dropright {{ active_route(['address.create', 'address.index']) }}">
                                <a class="nav-link dropdown-toggle"
                                   href="javascript:"
                                   id="navdropdown-Addresses"
                                   data-toggle="dropdown"
                                   aria-haspopup="true"
                                   aria-expanded="false">
                                    Addresses
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navdropdown-Addresses">
                                    <a class="dropdown-item dropdown-item-action {{ active_route('address.create') }}" href="{{ route('address.create') }}">Create New Address</a>
                                    <a class="dropdown-item {{ active_route(['address.index', 'address.edit', 'address.show.*']) }}" href="{{ route('address.index') }}">My Addresses</a>
                                </div>
                            </li>
                            
                            <small class="text-muted">You</small>
                        
                            <li class="nav-item dropright {{ active_route('company-user.*') }}">
                                <a class="nav-link dropdown-toggle"
                                   href="javascript:"
                                   id="navdropdown-Profile"
                                   data-toggle="dropdown"
                                   aria-haspopup="true"
                                   aria-expanded="false">
                                    Profile
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navdropdown-Profile">
                                    <a class="dropdown-item {{ active_route('company-user.show.me') }}" href="{{ route('company-user.show.me') }}">View Your Profile</a>
                                    <a class="dropdown-item {{ active_route('company-user.edit') }}" href="{{ route('company-user.edit') }}">Edit Your Profile</a>
                                </div>
                            </li>
                            
                        @elseif(Auth::user()->isEmployee())
                            {{----}}
                        
                            <small class="text-muted">Get Hired</small>
                        
                            <a class="nav-link nav-link-action {{ active_route('search') }}" href="{{ route('search') }}">Search</a>
                        
                            <a class="nav-link {{ active_route('advert.application.*') }}" href="{{ route('advert.application.index') }}">My Applications</a>
                            
                            <small class="text-muted">You</small>
                        
                            <li class="nav-item dropright {{ active_route(['employee.*', 'cv.*']) }}">
                                <a class="nav-link dropdown-toggle"
                                   href="javascript:"
                                   id="navdropdown-Profile"
                                   data-toggle="dropdown"
                                   aria-haspopup="true"
                                   aria-expanded="false">
                                    Profile
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navdropdown-Profile">
                                    <a class="dropdown-item {{ active_route('employee.show.me') }}" href="{{ route('employee.show.me') }}">View Profile</a>
                                    <a class="dropdown-item {{ active_route('employee.edit') }}" href="{{ route('employee.edit') }}">Edit Profile</a>
                                    <a class="dropdown-item {{ active_route('cv.builder') }}" href="{{ route('cv.builder') }}">CV Builder</a>
                                </div>
                            </li>
                        
                        
                        @endif
                        
                        <a class="nav-link {{ active_route('account.private-message.*') }}"
                           href="{{ route('account.private-message.index') }}">
                            Messages
                            @if(Auth::user()->unreadMessages() > 0)
                                <span class="badge badge-danger p-1">{{ Auth::user()->unreadMessages() }}</span>
                            @endif
                        </a>
        
                        <li class="nav-item dropright {{ active_route('account.manage.*') }}">
                            <a class="nav-link dropdown-toggle"
                               href="javascript:"
                               id="navdropdown-Account"
                               data-toggle="dropdown"
                               aria-haspopup="true"
                               aria-expanded="false">
                                Account
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navdropdown-Account">
                                <a class="dropdown-item {{ active_route('account.manage.email') }}" href="{{ route('account.manage.email') }}">Change Email</a>
                                <a class="dropdown-item {{ active_route('account.manage.password') }}" href="{{ route('account.manage.password') }}">Change Password</a>
                                <a class="dropdown-item" href="{{ route('logout.get') }}">Log Out</a>
                            </div>
                        </li>
                    
                        @endonboarding
                    @endauth
            </nav>
        </div>
    </div>
    {{-- mobile nav --}}
    <nav class="navbar navbar-dark bg-primary d-block d-lg-none fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Career In Health</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
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
                    @onboarding
                        <li class="nav-item">
                            <a href="javascript:" class="nav-link">onboarding</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ active_route('dashboard') }}" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                    
                        @if(Auth::user()->isValidCompany())
                        
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
                                <a class="nav-link {{ active_route('employee.show.me') }}" href="{{ route('employee.show.me') }}">View Profile</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ active_route('employee.edit') }}" href="{{ route('employee.edit') }}">Edit Profile</a>
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
                                       href="javascript:">
                                        Messages
                                        <span class="badge badge-danger p-1">1</span>
                                    </a>
                                    <div class="nav-section-sub">
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
                                    <a class="nav-link nav-section-title" href="javascript:">Settings</a>
                                    <div class="nav-section-sub">
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
                    @endonboarding
                @endauth
            </ul>
        </div>
    </nav>
    {{--main app--}}
    <div id="app">
        @yield('content')
    </div>
@endsection
@section('base_stylesheet')
    <link href="/open-iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">
    @yield('stylesheet')
@endsection
@section('base_script')
    @yield('script')
@endsection