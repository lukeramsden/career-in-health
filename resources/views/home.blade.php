@extends('layouts.app')
@section('base_content')
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-12">
                <div class="card card-custom mx-auto" style="max-width: 30rem;">
                    <img src="/images/cih-logo.svg" class="card-img-top p-5">
                    <div class="card-footer p-0">
                        <div class="btn-group btn-group-lg btn-group-vertical btn-group-full btn-group-square" role="group">
                            @auth
                                <a href="{{ route('dashboard') }}"
                                   class="btn scale-on-hover-2 btn-action">Dashboard</a>
                            
                                <a href="{{ route('search') }}"
                                   class="btn scale-on-hover-2 btn-primary">Search</a>
                                
                                <a href="{{ route('logout.get') }}"
                                   class="btn scale-on-hover-2 btn-dark-primary">Log Out</a>
                            @endauth
                            @guest
                                <a href="{{ route('login') }}"
                                   class="btn scale-on-hover-2 btn-action">Log In</a>
                                
                                <a href="{{ route('pricing') }}"
                                   class="btn scale-on-hover-2 btn-primary">Pricing</a>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
