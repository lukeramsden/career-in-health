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

        <title>
            @isset($title)
                {{ $title }}
                -
            @endisset
            {{ config('app.name', 'Laravel') }}
        </title>
        <link rel="shortcut icon" type="image/png" href="/images/favicon.png"/>

        {{-- Stylesheets --}}
        @mix('css/main.css')
        @yield('head')
    </head>
    <body>
        @yield('base_content')

        {{-- Scripts --}}
        <script>
            window.isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
            window.currentUser = {!! Auth::check() ? json_encode(Auth::user()) : 'null' !!};
            window.isOnboarding = {{ Auth::check() && Auth::user()->onboarding()->inProgress() ? 'true' : 'false' }}
            window.nextOnboardingStep = {!! Auth::check() && Auth::user()->onboarding()->inProgress() ? "'".Auth::user()->onboarding()->nextUnfinishedStep()->link."'" : 'null' !!}
        </script>
        
        @routes
        @mix('js/manifest.js')
        @mix('js/vendor.js')
        @mix('js/app.js')
        @include('toast::messages-jquery')
        @yield('body-end')
    </body>
</html>
