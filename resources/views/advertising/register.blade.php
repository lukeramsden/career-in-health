@extends('layouts.app')
@section('base_content')
    <div id="app mx-0">
        <div class="container-fluid my-5">
            <div class="row">
                <div class="col-12 col-lg-4 offset-lg-2">
                    <div class="card card-custom">
                        <a class="p-5 mx-auto w-75" href="{{ route('home') }}">
                            <img class="card-img-top" src="/images/cih-logo.svg" alt="logo">
                        </a>
                        <div class="card-body">
                            <p class="my-3">You have been invited to become an advertiser at <b>Career In Health</b></p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card card-custom">
                        <div class="card-body">
                            <form action="{{ route('advertising.accept-invite', $invite) }}" method="post">
                                {{ csrf_field() }}
                                
                                <div class="form-group">
                                    <small class="text-muted">Email Address</small>
                                    <input class="form-control" disabled value='{{ $invite->email }}'>
                                </div>
                                
                                <div class="form-group">
                                    <small class="text-muted">Name (<span class='text-action'>*</span>)</small>
                                    <input
                                    required
                                    name="name"
                                    class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                    placeholder="Name"
                                    value="{{ old('name') }}">
                                
                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
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