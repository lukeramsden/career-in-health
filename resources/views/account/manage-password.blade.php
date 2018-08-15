@extends('layouts.app')
@section('content')
    <div class="container mt-lg-5">
        <div class="card card-custom w-lg-50 mx-auto">
            <div class="card-body">
                <form action="{{ route('account.manage.password') }}" method="post">
                    {{ csrf_field() }}
                    
                    <div class="form-group">
                        <label for="password">Current Password</label>
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
                        <label for="new_password">New Password</label>
                        <input
                            class="form-control {{ $errors->has('new_password') ? 'is-invalid' : '' }}"
                            type="password"
                            name="new_password"
                            id="new_password"
                            value="{{ old('new_password') }}"
                            autocomplete="off"
                            required>
                        
                        @if ($errors->has('new_password'))
                             <div class="invalid-feedback">{{ $errors->first('new_password') }}</div>
                         @endif
                    </div>
    
                    <div class="form-group">
                        <label for="new_password_confirmation">Confirm New Password</label>
                        <input
                        class="form-control {{ $errors->has('new_password_confirmation') ? 'is-invalid' : '' }}"
                        type="password"
                        name="new_password_confirmation"
                        id="new_password_confirmation"
                        value="{{ old('new_password_confirmation') }}"
                        autocomplete="off"
                        required>
                        
                        @if ($errors->has('new_password_confirmation'))
                             <div class="invalid-feedback">{{ $errors->first('new_password_confirmation') }}</div>
                         @endif
                    </div>
                    
                    <button class="btn btn-action px-5 float-right">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection