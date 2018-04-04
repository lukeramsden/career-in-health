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
        <link rel="shortcut icon" type="image/png" href="/images/favicon.png"/>

        <link href="/open-iconic/font/css/open-iconic-bootstrap.css" rel="stylesheet">
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        @yield('stylesheet')
    </head>
    <body>
        <div id="navbar">
            <img class="logo" src="/images/cih-logo.svg" alt="logo">
            <div id="nav-inner">
                <nav class="nav flex-column">
                    @guest
                        <a class="nav-link {{ active_route('welcome') }}" href="{{ route('welcome') }}">Home</a>
                        <a class="nav-link {{ active_route('search') }}" href="{{ route('search') }}">Search</a>
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
                            <a class="nav-link" href="#">My Profile</a>
                            <a class="nav-link" href="#">Edit Profile</a>
                            <a class="nav-link" href="#">CV Builder</a>
                            <small class="text-muted">Find a job</small>
                            <a class="nav-link" href="#">Search</a>
                            <a class="nav-link" href="#">My Applications</a>
                            
                        @endif
                        
                    @endauth
                </nav>
            </div>
        </div>
    
        <div id="app">
            <main>
                @yield('content')
            </main>
        </div>

        <script src="{{ asset('js/app.js') }}"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        @yield('script')
    </body>
</html>
