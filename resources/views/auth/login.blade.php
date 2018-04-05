@extends('layouts.app')
@section('content')
    <div class="container align-self-center">
        <form method="POST" action="{{ route('login') }}" class="m-5">
            {{ csrf_field() }}

             <div class='row'>
                <div class='col-12'>
                    <h1>Login to Career In Health</h1>
                </div>

                <div class='col-12 mt-5'>
                    <div class="form-group">
                        <label>Email Address (<span class='text-action'>*</span>)</label>
                        <input type="email" name='email' class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            placeholder="Email Address" value='{{ old('email') }}'>

                        @if ($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                </div>

                <div class='col-md-12 mt-3'>
                    <div class="form-group">
                        <label>Password (<span class='text-action'>*</span>)</label>
                        <input type="password" name='password'
                            class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            placeholder="Password">

                        @if ($errors->has('password'))
                            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                        @endif
                    </div>
                </div>

                <div class='col-md-12 mt-3'>
                    <div class="form-group">
                        <button class='btn btn-action'>Login</button>
                    </div>
                </div>
            </div>
        </form>
        
    </div>
@endsection