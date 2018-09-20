@extends('layouts.base')
@section('base_content')
    {{-- side nav --}}
    <div id="navbar">
        @auth
            @usertype('admin')
                <img class="logo svg-inline svg-logo-danger" src="/images/cih-logo.svg" alt="logo">
                <p class="logo-after-text text-danger">Admin</p>
            @endusertype
            @usertype('advertiser')
                <img class="logo svg-inline svg-logo-success" src="/images/cih-logo.svg" alt="logo">
                <p class="logo-after-text text-success">Advertiser</p>
            @endusertype
            @usertype('employee,company')
                <img class="logo" src="/images/cih-logo.svg" alt="logo">
            @endusertype
        @endauth
        @guest
            <img class="logo" src="/images/cih-logo.svg" alt="logo">
        @endguest
        <div id="navbar-inner">
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
                    
                        @usertype('company')
                            <small class="text-muted">Your Company</small>
                        
                            <li class="nav-item dropright {{ active_route(['company.show.me', 'company.edit', 'company.manage-users.show']) }}">
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
                                    @if(Auth::user()->userable->hasUserManagePerms())
                                        <a class="dropdown-item {{active_route('company.manage-users.show')}}" href="{{route('company.manage-users.show')}}">Manage Users</a>
                                    @endif
                                </div>
                            </li>
                        
                            <li class="nav-item dropright {{ active_route(['job-listing.*', 'company.application.index']) }}">
                                <a class="nav-link dropdown-toggle"
                                   href="javascript:"
                                   id="navdropdown-JobListings"
                                   data-toggle="dropdown"
                                   aria-haspopup="true"
                                   aria-expanded="false">
                                    Job Listings
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navdropdown-JobListings">
                                    <a class="dropdown-item dropdown-item-action {{ active_route('job-listing.create') }}" href="{{ route('job-listing.create') }}">Create New Listing</a>
                                    <a class="dropdown-item {{ active_route(['job-listing.index', 'job-listing.edit', 'job-listing.show.*']) }}" href="{{ route('job-listing.index') }}">View Listings</a>
                                    <a class="dropdown-item {{ active_route('company.application.index') }}" href="{{ route('company.application.index') }}">View Applications</a>
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
                            
                        @endusertype
                        @usertype('employee')
                            {{----}}
                        
                            <small class="text-muted">Get Hired</small>
                        
                            <a class="nav-link nav-link-action {{ active_route('search') }}" href="{{ route('search') }}">Search</a>
                        
                            <a class="nav-link {{ active_route('job-listing.application.*') }}" href="{{ route('job-listing.application.index') }}">My Applications</a>
        
                            <a class="nav-link {{ active_route('employee.saved-job-listings') }}" href="{{ route('employee.saved-job-listings') }}">Saved Job Listings</a>
                    
                            <small class="text-muted">You</small>
                        
                            <li class="nav-item dropright {{ active_route(['employee.show.*', 'employee.edit', 'cv.*']) }}">
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
                        
                        @endusertype
                    
                        @usertype('advertiser')
                            {{----}}
                            <li class="nav-item dropright {{ active_route(['advertising.*']) }}">
                                <a class="nav-link dropdown-toggle"
                                   href="javascript:"
                                   id="navdropdown-Addresses"
                                   data-toggle="dropdown"
                                   aria-haspopup="true"
                                   aria-expanded="false">
                                    Adverts
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navdropdown-Addresses">
                                    <a class="dropdown-item dropdown-item-action {{ active_route('advertising.create') }}" href="{{ route('advertising.create') }}">Create New</a>
                                    <a class="dropdown-item {{ active_route('advertising.index') }}" href="{{ route('advertising.index') }}">Manage Adverts</a>
                                </div>
                            </li>
                        @endusertype
                    
                        @usertype('admin')
                            {{----}}
                            
                            <a class="nav-link {{ active_route('admin.manage-advertisers.*') }}" href="{{ route('admin.manage-advertisers.show') }}">Manage Advertisers</a>
                        @endusertype
                        
                        @can('sendMessages', App\PrivateMessage::class)
                            <a class="nav-link {{ active_route('account.private-message.*') }}"
                               href="{{ route('account.private-message.index') }}">
                                Messages
                                @if(Auth::user()->unreadMessages() > 0)
                                    <span class="badge badge-danger p-1 unread-messages-count-badge">{{ Auth::user()->unreadMessages() }}</span>
                                @endif
                            </a>
                        @endcan
        
                        <a href="javascript:toggleNotificationDrawer()"
                           class="nav-link"
                           id="navbar-notification-toggle">Notifications
                            @if(($unread_notif_count = Auth::user()->unreadNotifications()->count()) > 0)
                                <span class="badge badge-danger" id="navbar-notification-unread-badge">{{ $unread_notif_count }}</span>
                            @endif
                        </a>
                    
                        <li class="nav-item dropright {{ active_route('account.manage.*', 'account.manage.notification-preferences') }}">
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
                                <a class="dropdown-item {{ active_route('account.manage.notification-preferences') }}" href="{{ route('account.manage.notification-preferences') }}">Notification Preferences</a>
                                <a class="dropdown-item" href="{{ route('logout.get') }}">Log Out</a>
                            </div>
                        </li>
                    
                        @endonboarding
                    @endauth
            </nav>
        </div>
        @auth
            <div id="navbar-notification-panel" class="">
                <div class="notification notification-actions">
                    <a class="view-all-notifications" href="{{ route('notifications.index') }}">View All</a>
                    <button class="mark-as-read">Mark All As Read</button>
                </div>
                @foreach(Auth::user()->notifications()->orderByRaw('-read_at ASC')->take(10)->get() as $notif)
                    @switch($notif->type)
                        @case(\App\Notifications\ReceivedPrivateMessage::class)
                            <a href="{{ action('NotificationController@clickThrough', ['notification' => $notif]) }}" class="link-unstyled">
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
                            <a href="{{ action('NotificationController@clickThrough', ['notification' => $notif]) }}" class="link-unstyled">
                                <div class="notification {{$notif->unread()?'unread':''}} notification-application">
                                    <div class="notification-inner">
                                        <p>Application from <b>{{ $notif->data['sender_name'] }}</b></p>
                                        @if($notif->data['body'])
                                            <p>{{ str_limit($notif->data['body']) }}</p>
                                        @else
                                            <p><span class="text-muted font-italic">No cover letter</span></p>
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
                        
                            <small class="text-light">JobListings</small>
                        
                            <li class="nav-item">
                                <a class="nav-link nav-link-action {{ active_route('job-listing.create') }}" href="{{ route('job-listing.create') }}">Create New JobListing</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ active_route(['job-listing.index', 'job_listing.edit', 'job_listing.show.*']) }}" href="{{ route('job-listing.index') }}">My JobListings</a>
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
                                <a class="nav-link {{ active_route('job-listing.application.*') }}" href="{{ route('job-listing.application.index') }}">My Applications</a>
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
                                        @if(Auth::user()->unreadMessages() > 0)
                                            <span class="badge badge-danger p-1 unread-messages-count-badge">{{ Auth::user()->unreadMessages() }}</span>
                                        @endif
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
                                @unset($activeRoute)
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
@section('head')
    <link href="/open-iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">
    @yield('stylesheet')
@endsection
@section('body-end')
    @routes
    @auth
        <script>
            function toggleNotificationDrawer()
            {
                $('#navbar-notification-panel').toggleClass('open');
                $('#navbar-notification-toggle').toggleClass('active');
            }
            
            $(function() {
               $('#navbar-notification-panel .mark-as-read').click(function() {
                   var self = $(this);
                   self.prop('disabled', true);
    
                   axios
                       .post('{{ route('notifications.mark-all-as-read') }}')
                       .then(function (resp) {
                           if (resp.data.success)
                           {
                               $('.notification').removeClass('unread');
                               $('#navbar-notification-unread-badge').remove();
                           }
                       })
                       .catch(function (e) {
                           console.log(e);
                           toastr.error('Could not mark messages as read');
                       })
                       .then(function () {
                           self.prop('disabled', false);
                       })
               })
            });
            
            window.store.commit('updateUserType',
                @usertype('employee')
                    'employee'
                @elseusertype('company')
                    'company'
                @elseusertype
                    ''
                @endusertype
            );
        </script>
    @endauth
    @yield('script')
@endsection