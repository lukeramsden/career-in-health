@extends('layouts.mail')
@section('body')
    <div class="container">
        <div class="card card-custom mt-5 w-lg-50 mx-auto">
            <div class="card-header">Confirm Your Email Address</div>
            <div class="card-body">
                <p class="text-justify">Thanks for creating an account with Career In Health!<br>Please follow the link below to confirm your email address</p>
                <div class="text-center">
                    <a class="btn btn-action px-5" href="{{ route('confirm-email', ['confirmationCode' => $user->confirmation_code]) }}">Click Here</a>
                </div>
            </div>
            <div class="card-footer text-muted">
                <small>Or copy and paste this link in to your search bar:</small>
                <br>
                <small>{{ route('confirm-email', ['confirmationCode' => $user->confirmation_code]) }}</small>
            </div>
        </div>
    </div>

@endsection