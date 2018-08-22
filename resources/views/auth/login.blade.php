@extends('layouts.frontend')
@section('content')
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-12">
                <div class="card card-custom mx-auto" style="max-width:40rem">
                    <div class="card-body">
                        <form action="{{ route('login') }}" method="post">
                            {{ csrf_field() }}
                            @if($errors->has('email'))
                                <div class="form-group">
                                    <div class="alert alert-danger" role="alert">
                                        {{ $errors->first('email') }}
                                    </div>
                                </div>
                            @endif
                            
                            @if($errors->has('password'))
                                <div class="form-group">
                                    <div class="alert alert-danger" role="alert">
                                        {{ $errors->first('password') }}
                                    </div>
                                </div>
                            @endif
                            
                            <div class="form-group">
                                <label>Email Address (<span class='text-action'>*</span>)</label>
                                <input type="email" name='email'
                                       class="form-control {{ $errors->has('email') || $errors->has('password') ? 'is-invalid' : '' }}"
                                       placeholder="Email Address" value='{{ old('email') }}'>
                            </div>
                            
                            <div class="form-group">
                                <label>Password (<span class='text-action'>*</span>)</label>
                                <input type="password" name='password'
                                       class="form-control {{ $errors->has('email') || $errors->has('password') ? 'is-invalid' : '' }}"
                                       placeholder="Password">
                            </div>
                            
                            <div class="form-group">
                                <button class='btn btn-action px-5 float-right'>Login</button>
                                <a href="{{ route('password.request') }}" class='btn btn-sm btn-link float-right'>Forgot
                                    your
                                    password?</a>
                            </div>
                        
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection