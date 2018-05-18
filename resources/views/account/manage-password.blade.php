@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <div class="card card-custom w-50 mx-auto">
            <div class="card-body">
                <form action="{{ route('account.manage.password') }}" method="post">
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
                        <label for="password_confirmation">Confirm Password</label>
                        <input
                        class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                        type="password"
                        name="password_confirmation"
                        id="password_confirmation"
                        value="{{ old('password_confirmation') }}"
                        autocomplete="off"
                        required>
                        
                        @if ($errors->has('password_confirmation'))
                             <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
                         @endif
                    </div>
                    
                    <button class="btn btn-action px-5 float-right">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection