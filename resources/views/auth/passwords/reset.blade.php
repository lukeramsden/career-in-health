@extends('layouts.app', ['title' => 'Reset Your Password'])
@section('content')
<div class="container mt-5">
    <div class="card card-custom w-lg-50 mx-auto">
        <div class="card-header">Reset Password</div>
        <div class="card-body">
            <form method="post" action="{{ route('password.request') }}">
                {{ csrf_field() }}

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        id="password"
                        name="password"
                        required>
                    @if ($errors->has('password'))
                        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="password-confirm">Confirm Password</label>
                    <input
                    type="password"
                    class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                    id="password-confirm"
                    name="password_confirmation"
                    required>
                    @if ($errors->has('password_confirmation'))
                        <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <button class='btn btn-action px-5 float-right'>Reset</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
