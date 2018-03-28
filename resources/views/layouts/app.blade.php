<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        @if (env('APP_ENV') != 'local')
            <script async src="https://www.googletagmanager.com/gtag/js?id=UA-109407615-2"></script>
            <script>
              window.dataLayer = window.dataLayer || [];
              function gtag(){dataLayer.push(arguments);}
              gtag('js', new Date());

              gtag('config', 'UA-109407615-2');
            </script>
        @endif
        
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link rel="shortcut icon" type="image/png" href="/i/favicon.png"/>

        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app">

            <div class='heading'>
                <div class="container">

                    <div class='row'>
                        <div class='col-md-6'>
                            <div class='logo-box'><a href='/'>
                                <img class='logo' src='/images/cih-logo.svg'>
                            </a></div>
                        </div>

                        <div class='col-md-6'>

                        </div>
                    </div>

                </div>
            </div>

            <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
                <div class="container">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav mr-auto">
                            @if (Auth::check())
                                <li><a class="nav-link {{ isActive('home') ? 'active' : '' }}" href='/home'>Dashboard</a></li>

                                @if (Auth::user()->isCompany())
                                    <li><a class="nav-link {{ isActive('*/advert/*') || isActive('*/advert') ? 'active' : '' }}" href='{{ route('advert.index') }}'>Adverts</a></li>
                                    <li><a class="nav-link {{ isActive('*/plans') ? 'active' : '' }}" href='{{ route('plans') }}'>Plans</a></li>
                                @endif
                            @else
                                <li><a class="nav-link {{ isActive('/') ? 'active' : '' }}" href='/'>Home</a></li>
                                <li><a class="nav-link" href='#'>Employer</a></li>
                                <li><a class="nav-link" href='#'>Employee</a></li>
                                <li><a class="nav-link" href='#'>Pricing</a></li>
                                <li><a class="nav-link" href='#'>Reviews</a></li>
                                <li><a class="nav-link  {{ isActive('search') ? 'active' : '' }}" href='{{ route('search') }}'>Search</a></li>
                            @endif
                        </ul>

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            @guest
                                <li><a class="nav-link btn-action" href="{{ route('register') }}">Create FREE account</a></li>
                                <li><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                            @else
                                @if (Auth::user()->isCompany())
                                    <li><a class="nav-link btn-action {{ isActive('*/advert') ? 'active' : '' }}" href='{{ route('advert.create') }}'>Create New Advert</a></li>
                                @endif

                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ Auth::user()->profile->fullName() }} <span class="caret"></span>
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        @if (!Auth::user()->isCompany())
                                            <a class="dropdown-item" href='{{ route('profile.me') }}'>My Profile</a>
                                            <a class="dropdown-item" href='{{ route('cv-builder.profile') }}'>CV Builder</a>
                                        @endif
                                        <a class="dropdown-item" href='#'>Subscription</a>
                                        <a class="dropdown-item" href='#'>My Addresses</a>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="">
                @yield('content')
            </main>
        </div>

        <script src="{{ asset('js/app.js') }}"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
        @yield('script')
    </body>
</html>
