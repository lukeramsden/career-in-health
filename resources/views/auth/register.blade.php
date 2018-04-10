@extends('layouts.app')
@section('content')
    <div class="container-fluid" id="register-container-parent">
        <div class="card" id="register-container">
            <div class="card-body">
                <div class="form-row">
                    <div class="col col-12 col-xl-6">
                        <form method="post" action="{{ route('register') }}">
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
                                <label>First Name (<span class='text-action'>*</span>)</label>
                                <input name="first_name" class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                                    placeholder="First Name" value='{{ old('first_name') }}'>
                            
                                @if ($errors->has('first_name'))
                                    <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
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
                                <label>Confirm Password (<span class='text-action'>*</span>)</label>
                                <input type='password' name="password_confirmation" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Password">
                               
                                @if ($errors->has('password'))
                                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                @endif
                            </div>
                            
                            <div class="form-group text-center mt-5">
                                <button type="submit" class="btn btn-action">Sign Up</button>
                            </div>
                        </form>
                    </div>
                    <div class="col col-12 col-xl-6 order-sm-first order-xl-last">
                        <h2>Create a FREE Account</h2>
                        <p>With over 50 years of recruitment industry experience, we offer executive search, selection and contingency recruitment services for permanent, contract and temporary requirements.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection