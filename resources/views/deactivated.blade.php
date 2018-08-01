@extends('layouts.app')
@section('base_content')
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-12">
                <div class="card card-custom mx-auto" style="max-width: 30rem;">
                    <img src="/images/cih-logo.svg" class="card-img-top p-5">
                    <div class="card-body">
                        <h4 class="card-title">Your account has been deactivated</h4>
                        <p>Please contact your company administrator to have your account reinstated.</p>
                    </div>
                    @auth
                        <div class="card-footer">
                            You are signed in as {{Auth::user()->userable->full_name}}, <a href="{{route('logout.get') }}">click here to log out.</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection