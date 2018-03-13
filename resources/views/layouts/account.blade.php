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

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>
        <div id="app">
            <div class='content'>
                <div class='row'>
                    <div class='col-12'>

                        <div class='logo-account'><a href='/'>
                            <img class='logo' src='images/cih-logo.svg'>
                        </a></div>

                    </div>
                </div>
            </div>

            @yield('content')
        </div>

        <script src="{{ asset('js/app.js') }}"></script>

        @yield('script')
    </body>
</html>