@extends('layouts.app')
@section('content')
    <div class="container mt-lg-5">
        <div class="card card-custom w-lg-50 mx-auto">
            <div class="card-body">
                <form action="{{ route('account.manage.email') }}" method="post">
                    {{ csrf_field() }}
                    
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input
                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        type="password"
                        name="password"
                        id="password"
                        value="{{ old('password') }}"
                        autocomplete="off"
                        required>
    
                        @if ($errors->has('password'))
                            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <label for="email">New Email</label>
                        <input
                            class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            type="email"
                            name="email"
                            id="email"
                            value="{{ old('email') }}"
                            autocomplete="off"
                            required>
                        
                        @if ($errors->has('email'))
                             <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                         @endif
                    </div>
    
                    <div class="form-group">
                        <label for="email_confirmation">Confirm Email</label>
                        <input
                        class="form-control {{ $errors->has('email_confirmation') ? 'is-invalid' : '' }}"
                        type="email"
                        name="email_confirmation"
                        id="email_confirmation"
                        value="{{ old('email_confirmation') }}"
                        autocomplete="off"
                        required>
    
                        @if ($errors->has('email_confirmation'))
                            <div class="invalid-feedback">{{ $errors->first('email_confirmation') }}</div>
                        @endif
                    </div>
                    
                    <button class="btn btn-action px-5 float-right">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection