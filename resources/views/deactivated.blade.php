@extends('layouts.app', ['title' => 'Your Account Is Deactivated'])
@section('base_content')
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-12">
                <div class="card card-custom mx-auto" style="max-width: 30rem;">
                    <img src="/images/cih-logo.svg" class="card-img-top p-5">
                    <div class="card-body">
                        <h4 class="card-title">Your account has been deactivated</h4>
                        @usertype('company')
                            <p>Please contact your company administrator to have your account reinstated.</p>
                        @endusertype
                        @usertype('advertiser')
                            <p>Please contact support to have your account reinstated.</p>
                        @endusertype
                    </div>
                    @auth
                        <div class="card-footer">
                            @usertype('company')
                            You are signed in as {{$currentUser->userable->full_name}}, <a href="{{route('logout.get') }}">click here to log out.</a>
                            @endusertype
                            @usertype('advertiser')
                            You are signed in as {{$currentUser->userable->name}}, <a href="{{route('logout.get') }}">click here to log out.</a>
                            @endusertype
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection