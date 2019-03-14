@extends('layouts.app', ['title' => 'Log In'])
@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <div class="card card-custom mx-auto" style="max-width:40rem">
                    <div class="card-body">
                        <form action="{{ route('login') }}" method="post">
                            {{ csrf_field() }}
                            
                            <div class="form-group">
                                <label>Email Address (<span class='text-action'>*</span>)</label>
                                <input required type="email" name='email' class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                       placeholder="Email Address" value='{{ old('email') }}'>
                                
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                            
                            <div class="form-group">
                                <label>Password (<span class='text-action'>*</span>)</label>
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
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           autocomplete="off"
                                           id="remember"
                                           name="remember"
                                           class="custom-control-input">
                                    <label class="custom-control-label text-sm" for="remember">Stay logged in.</label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="btn-group float-right">
                                    <a href="{{ route('password.request') }}" class="btn btn-sm btn-link">
                                        Forgot your password?
                                    </a>
                                    <a href="{{ route('register') }}" class="btn btn-secondary">Register</a>
                                    <button class="btn btn-action">Login</button>
                                </div>
                            </div>
                        
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection