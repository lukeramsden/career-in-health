@extends('layouts.app')
@section('base_content')
    <div id="app mx-0">
        <div class="container-fluid my-5">
            <div class="row">
                <div class="col-12 col-lg-4 offset-lg-2">
                    <div class="card card-custom">
                        <a class="p-5 mx-auto w-75" href="{{ route(Auth::check() ? 'dashboard' : 'home') }}">
                            <img class="card-img-top" src="/images/cih-logo.svg" alt="logo">
                        </a>
                        <div class="card-body">
                            <p>You have been invited to join...</p>
                            
                            <div class="media">
                                <img class="align-self-start mr-3 img-thumbnail"
                                     width="130"
                                     src="{{ $invite->company->picture() ?? '/images/generic.png' }}"
                                     alt="Profile picture">
                                <div class="media-body">
                                    <a href="{{ route('company.show', $invite->company) }}">
                                        <h5 class="mt-0">{{ $invite->company->name }}</h5>
                                    </a>
                                    @isset($invite->company->about)
                                        <p>{!! str_limit(nl2br(e($invite->company->about)), 100) !!}</p>
                                    @endisset
                                    @isset($invite->company->phone)
                                        <h6><span class="oi oi-phone text-muted"></span> <span class="text-muted">Phone:</span> <span>{{ $invite->company->phone }}</span></h6>
                                    @endisset
                                    @isset($invite->company->email)
                                        <h6><span class="oi oi-envelope-closed text-muted"></span> <span class="text-muted">Email:</span> <span>{{ $invite->company->email }}</span></h6>
                                    @endisset
                                    <p class="mb-0"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card card-custom">
                        <div class="card-body">
                            <form action="{{ route('accept-invite.accept', $invite) }}" method="post">
                                {{ csrf_field() }}
                                
                                <div class="form-group">
                                    <small class="text-muted">Email Address</small>
                                    <input class="form-control" disabled value='{{ $invite->email }}'>
                                </div>
                                
                                <div class="form-group">
                                    <small class="text-muted">First Name (<span class='text-action'>*</span>)</small>
                                    <input
                                    required
                                    name="first_name"
                                    class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                                    placeholder="First Name"
                                    value="{{ old('first_name') }}">
                                
                                    @if ($errors->has('first_name'))
                                        <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                                    @endif
                                </div>
                                
                                <div class="form-group">
                                    <small class="text-muted">Password (<span class='text-action'>*</span>)</small>
                                    <input
                                    required
                                    type="password"
                                    name="password"
                                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                    placeholder="Password">
                                    
                                    @if ($errors->has('password'))
                                        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <small class="text-muted">Confirm Password (<span class='text-action'>*</span>)</small>
                                    <input
                                    required
                                    type="password"
                                    name="password_confirmation"
                                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                    placeholder="Password">
                                   
                                    @if ($errors->has('password'))
                                        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                    @endif
                                </div>
        
                                <div class="form-group">
                                    <div class="form-check">
                                        <input
                                        class="form-check-input"
                                        type="checkbox"
                                        value="1"
                                        id="terms"
                                        name="terms"
                                        required>
                                        <label class="form-check-label" for="terms">
                                            I agree to the <a href="javascript:" class="text-action">Terms and Conditions</a>
                                        </label>
                                        @if ($errors->has('terms'))
                                            <div class="invalid-feedback">{{ $errors->first('terms') }}</div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group text-center">
                                    <button type="submit" class="btn btn-action px-5">Sign Up</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection