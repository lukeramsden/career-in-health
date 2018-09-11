@extends('layouts.frontend')
@section('content')
    <div class="container-fluid sleek-form-parent mt-2 mt-lg-5">
        <div class="card sleek-form mx-auto">
            <div class="card-body">
                <div class="form-row">
                    <div class="col col-12 col-xl-6">
                        <form method="post" action="{{ route('register') }}">
                            {{ csrf_field() }}
                            
                            <div class="form-group mb-5" id="i_am">
                                <div class="row">
                                    <div class="col-auto">
                                        <label class="text-muted">I am a(n)</label>
                                    </div>
                                    <div class="col-auto">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" autocomplete="off" id="i_am-1" name="i_am" class="custom-control-input" value="{{ \App\Enum\UserType::COMPANY_USER }}" required {{ old('i_am') == \App\Enum\UserType::COMPANY_USER ? 'checked' : '' }}>
                                            <label class="custom-control-label text-sm" for="i_am-1">Company</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" autocomplete="off" id="i_am-2" name="i_am" class="custom-control-input" value="{{ \App\Enum\UserType::EMPLOYEE }}" required {{ old('i_am') == \App\Enum\UserType::EMPLOYEE ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="i_am-2">Employee</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <div class="form-group">
                                <small class="text-muted">Email Address (<span class='text-action'>*</span>)</small>
                                 <input required type="email" name='email' class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                     placeholder="Email Address" value='{{ old('email') }}'>
                                
                                @if ($errors->has('email'))
                                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                @endif
                            </div>
                            
                            <div class="form-group">
                                <small class="text-muted">First Name (<span class='text-action'>*</span>)</small>
                                <input
                                required
                                name="first_name"
                                class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                                placeholder="First Name"
                                value="{{ old('first_name') }}">
                            
                                @if ($errors->has('first_name'))
                                    <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                                @endif
                            </div>
                            
                            <div class="form-group">
                                <small class="text-muted">Password (<span class='text-action'>*</span>)</small>
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
                            
                            <div class="form-group" id="password-group">
                                <small class="text-muted">Confirm Password (<span class='text-action'>*</span>)</small>
                                <input
                                required
                                type="password"
                                name="password_confirmation"
                                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                placeholder="Password">
                               
                                @if ($errors->has('password'))
                                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                @endif
                            </div>
    
                            <div class="form-group">
                                <div class="form-check">
                                    <input
                                    class="form-check-input"
                                    type="checkbox"
                                    value="1"
                                    id="terms"
                                    name="terms"
                                    required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="javascript:" class="text-action">Terms and Conditions</a>
                                    </label>
                                    @if ($errors->has('terms'))
                                        <div class="invalid-feedback">{{ $errors->first('terms') }}</div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group text-center mt-5">
                                <button type="submit" class="btn btn-action">Sign Up</button>
                                <a href="{{ route('login') }}" class="btn btn-link btn-sm btn-block mt-3">Already Have An Account?</a>
                            </div>
                        </form>
                    </div>
                    <div class="col col-12 col-xl-6 order-first order-xl-last">
                        <div class="sleek-form-help-container">
                            <div class="sleek-form-help-inner">
                                <h2>Create a FREE Account</h2>
                                <p>With over 50 years of recruitment industry experience, we offer executive search, selection and contingency recruitment services for permanent, contract and temporary requirements.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection