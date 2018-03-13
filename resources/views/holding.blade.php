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
        <meta name="description" content="Welcome to Career In Health. Innovative recruitment built exclusively for the UK's health sector">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        
        <link rel="shortcut icon" type="image/png" href="/i/favicon.png"/>
        <link rel="alternate" href="{{ env('APP_URL') }}" hreflang="en-gb" />
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>

    <body>
        <div id="app">
            <div class='content'><div class='container'>
                <div class='row'>

                    <div class='col-12'>

                        <div class='logo-account logo-holding'><a href='/'>
                            <img class='logo' src='images/cih-logo.svg'>
                        </a></div>

                    </div>

                    <div class='col-12'>
                        @if ($message != null)
                            <h1 class='holding-content'>{{ $message }}</h1>
                        @else
                            <h1 class='holding-content'>Innovative recruitment built exclusively for the UK's health sector</h1>
                        @endif
                    </div>

                    <div class='col-12 mt-5'>
                        <p class='holding-content'>Looking for your new career / employee in the health care sector? Thats what were are here for. Recruitment made simple with our easy to use Advert builder, CV builder and Generated Personel File.</p>
                    </div>

                </div>
            </div></div>

            <div class='content holding-blue'><div class='container'>
                <div class='row'>
                    <div class='col-12'>
                        <h2 class='holding-content'>Keep up-to-date by subscribing to our mailing list</h2>

                        <form method='post'>
                            {{ csrf_field() }}

                            <div class="input-group holding-sign-up">
                                <input type="text" class="form-control" placeholder="Email Address" name='email' value='{{ old('email') }}'>

                                <div class="input-group-append">
                                    <button class="btn btn-action" type="submit">Subscribe</button>
                                </div>
                            </div>

                            @if ($errors->has('email'))
                                <div class="invalid-feedback" style='display: block;'>{{ $errors->first('email') }}</div>
                            @endif
                        </form>

                    </div>
                </div>
            </div></div>

            <div class='content holding-dark-blue'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-12'>
                            <p>All Content © Career In Health {{ date('Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('js/app.js') }}"></script>

        @yield('script')
    </body>
</html>