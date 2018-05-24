@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <div class="card card-custom w-lg-50 mx-auto">
            <div class="card-body">
                <div class="text-center">
                    <h2>Please Confirm Your Email</h2>
                    <p>You will receive an email shortly with your confirmation link.</p>
                </div>
            </div>
            @if(Session::has('user_id'))
                <div class="card-footer">
                    <div class="text-center">
                        <a href="{{ route('confirm-email-resend', ['user' => Session::get('user_id')]) }}" class="btn btn-link">Resend Confirmation Email</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection