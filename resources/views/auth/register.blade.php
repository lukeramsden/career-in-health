@extends('layouts.app')
@section('content')
    <div class="container-fluid" id="register-container-parent">
        <div class="card" id="register-container">
            <div class="card-body">
                <div class="form-row">
                    <div class="col col-12 col-xl-6">
                        <form method="post" action="{{ route('register') }}">
                            {{ csrf_field() }}
                            
                            <div class="form-group mb-5" id="i_am">
                                <div class="row">
                                    <div class="col-auto">
                                        <label class="text-muted">I am an</label>
                                    </div>
                                    <div class="col-auto">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" autocomplete="off" id="i_am-1" name="i_am" class="custom-control-input" value="Employer" required {{ old('i_am') == 'Employer' ? 'checked' : '' }}>
                                            <label class="custom-control-label text-sm" for="i_am-1">Employer</label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" autocomplete="off" id="i_am-2" name="i_am" class="custom-control-input" value="Employee" required {{ old('i_am') == 'Employee' ? 'checked' : '' }}>
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
                            
                            <div class="form-group" style="{{ old('i_am') == 'Employer' ? '' : 'display:none;' }}" id="company-name-group">
                                <small class="text-muted">Company Name (<span class='text-action'>*</span>)</small>
                                <input
                                type="text"
                                name="company_name"
                                class="form-control {{ $errors->has('company_name') ? 'is-invalid' : '' }}"
                                placeholder="Company Name"
                                value="{{ old('company_name') }}"
                                {{-- TODO: make this field required when its displayed --}}
                                >
                            
                                @if ($errors->has('company_name'))
                                    <div class="invalid-feedback">{{ $errors->first('company_name') }}</div>
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
                        <div id="register-form-help-container">
                            <div id="register-form-help-inner">
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
@section('script')
    <script>
        $( 'input[name="i_am"]:radio' ).change(function() {
            $('#company-name-group')[this.value === 'Employee' ? 'slideUp' : 'slideDown']("slow", "easeInOutExpo");
        });
    </script>
@endsection