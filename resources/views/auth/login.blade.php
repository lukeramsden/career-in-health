@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row align-items-center justify-content-center">
            <div class="col-4" id="login-container">
                <div class="card card-custom">
                    <div class="card-body">
                        <form action="{{ route('login') }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>Email Address (<span class='text-action'>*</span>)</label>
                                <input type="email" name='email' class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                    placeholder="Email Address" value='{{ old('email') }}'>

                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <label>Password (<span class='text-action'>*</span>)</label>
                                <input type="password" name='password'
                                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                    placeholder="Password">

                                @if ($errors->has('password'))
                                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                @endif
                            </div>

                            <div class="form-group">
                                <button class='btn btn-action px-5 float-right'>Login</button>
                                <a href="{{ route('register') }}" class='btn btn-sm btn-link float-right'>Sign Up</a>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection