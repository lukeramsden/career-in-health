@extends('layouts.app', ['title' => 'Reset Your Password'])

@section('content')
    <div class="container mt-lg-5">
        <div class="card card-custom w-lg-50 mx-auto">
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
                
                <form method="post" action="{{ route('password.email') }}">
                    {{ csrf_field() }}
                    
                    <div class="form-group">
                        <label for="email">E-Mail Address</label>
                        
                        <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        value="{{ old('email') }}"
                        placeholder="joebloggs@example.com"
                        required>
                        
                        @if ($errors->has('email'))
                            <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                        @endif
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-action float-right">Send Password Reset Link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
