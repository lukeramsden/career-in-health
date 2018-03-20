@extends('layouts.account')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="card register-card has-top-bar">
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class='row'>
                            <div class='col-12'>
                                <h1>Create a FREE Account</h1>

                                <p class='sub-text'>With over 50 years of recruitment industry experience, we offer executive search, selection and contingency recruitment services for permanent, contract and temporary requirements.</p>
                            </div>

                            <div class='col-12'>
                                <div class="form-group">
                                    <label>I am (<span class='text-action'>*</span>)</label>
                                    <select class='form-control i-am {{ $errors->has('i_am') ? 'is-invalid' : '' }}' name='i_am'>
                                        <option value='' disabled selected>I am</option>
                                        <option {{ old('i_am') == 'Employee' ? 'selected' : '' }}>Employee</option>
                                        <option {{ old('i_am') == 'Employeer' ? 'selected' : '' }}>Employeer</option>
                                    </select>

                                    @if ($errors->has('i_am'))
                                        <div class="invalid-feedback">{{ $errors->first('i_am') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class='col-12 mt-3'>
                                <div class="form-group">
                                    <label>Email Address (<span class='text-action'>*</span>)</label>
                                    <input type="email" name='email' class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" 
                                        placeholder="Email Address" value='{{ old('email') }}'>

                                    @if ($errors->has('email'))
                                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class='col-md-6 mt-3'>
                                <div class="form-group">
                                    <label>First Name (<span class='text-action'>*</span>)</label>
                                    <input name="first_name" class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}" 
                                        placeholder="First Name" value='{{ old('first_name') }}'>

                                    @if ($errors->has('first_name'))
                                        <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class='col-md-6 mt-3'>
                                <div class="form-group">
                                    <label>Last Name (<span class='text-action'>*</span>)</label>
                                    <input name="last_name" class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}" 
                                        placeholder="Last Name" value='{{ old('last_name') }}'>

                                    @if ($errors->has('last_name'))
                                        <div class="invalid-feedback">{{ $errors->first('last_name') }}</div>
                                    @endif
                                </div>
                            </div>

                            <div class='col-md-6 mt-3'>
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

                            <div class='col-md-6 mt-3'>
                                <div class="form-group">
                                    <label>Confirm Password (<span class='text-action'>*</span>)</label>
                                    <input type='password' name="password_confirmation" class="form-control" placeholder="Password">
                                </div>
                            </div>

                            <div class='col-md-12 mt-3 company-name' style='display: none;'>
                                {{-- TODO:: Make this show up depending on old()
                                            this dissapears if one of the other inputs is wrong
                                --}}
                                <div class="form-group">
                                    <label>Company Name (<span class='text-action'>*</span>)</label>
                                    <input type='text' name="company_name" class="form-control" placeholder="Company Name">
                                </div>
                            </div>

                            <div class='col-md-12 mt-3'>
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input {{ $errors->has('terms') ? 'is-invalid' : '' }}" name='terms'>
                                    <label class="form-check-label" >By creating an account with career in health, you agree to our terms and conditions (<span class='text-action'>*</span>)</label>

                                    <div class='clear'></div>
                                </div>
                            </div>

                            <div class='col-md-12 mt-3'>
                                <div class="form-group">
                                    <button class='btn btn-action'>Start Recruiting, Create Your FREE Account</button>
                                </div>
                            </div>

                        </div>
                        
                    </form>
                </div>
            </div>
        
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $('.i-am').on('change', function() {
        if ($(this).val() == 'Employeer') {
            $('.company-name').show();
        } else {
            $('.company-name').hide();
        }
    });
</script>
@endsection
