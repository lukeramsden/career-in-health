<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-100">
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

        {{-- Stylesheets --}}
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        @yield('head')
        
        {{-- Script Preloads/prefetches --}}
    </head>
    <body>
        @yield('base_content')

        {{-- Scripts --}}
        @stack('pre-scripts')
        <script src="{{ mix('js/manifest.js') }}"></script>
        <script src="{{ mix('js/vendor.js') }}"></script>
        <script src="{{ mix('js/app.js') }}"></script>
        @include('toast::messages-jquery')
        @yield('body-end')
    </body>
</html>
