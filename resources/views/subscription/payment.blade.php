@extends('layouts.app')
@section('content')

    <div class="container">
        <div class='create-advert-container has-top-bar'>
            <form method='post'>
                {{ csrf_field() }}

                <div class='row first-row'>
                    <div class='col-md-7 form-section'>
                        <h1>Make Your Payment</h1>

                        <div class="form-group" style='margin-top: 50px;'>
                            <label>Card Number (<span class='text-action'>*</span>)</label>
                            <input type="text" name='number' class="form-control {{ $errors->has('number') ? 'is-invalid' : '' }}" 
                                placeholder="Card Number" value='{{ old('number') }}'>

                            @if ($errors->has('number'))
                                <div class="invalid-feedback">{{ $errors->first('number') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>Expiry Date (<span class='text-action'>*</span>)</label>

                            <div class='form-inline'>
                                <select name='exp_month' class="form-control {{ $errors->has('exp_month') ? 'is-invalid' : '' }}">
                                    <option value='01'>January</option>
                                    <option value='02'>Febuary</option>
                                    <option value='03'>March</option>
                                    <option value='04'>April</option>
                                    <option value='05'>May</option>
                                    <option value='06'>June</option>
                                    <option value='07'>July</option>
                                    <option value='08'>August</option>
                                    <option value='09'>September</option>
                                    <option value='10'>October</option>
                                    <option value='11'>November</option>
                                    <option value='12'>December</option>
                                </select>

                                <span class='exp-spacer'>/</span>

                                <select name='exp_year' class="form-control {{ $errors->has('exp_year') ? 'is-invalid' : '' }}">
                                    @for ($i = date('Y'); $i <= date('Y')+20; $i++)
                                        <option>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            @if ($errors->has('exp_month'))
                                <div class="invalid-feedback">{{ $errors->first('exp_month') }}</div>
                            @endif
                        </div>

                        <div class='row align-minus'>
                            <div class='col-md-8'>
                                <div class="form-group">
                                    <label>Name On Card (<span class='text-action'>*</span>)</label>
                                    <input type="text" name='name' class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" 
                                        placeholder="Name On Card" value='{{ old('name') }}'>

                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class='col-md-4 col-sm-4 col-xs-6'>
                                <div class="form-group">
                                    <label>CCV (<span class='text-action'>*</span>)</label>
                                    <input type="text" name='ccv' class="form-control {{ $errors->has('ccv') ? 'is-invalid' : '' }}" 
                                        placeholder="CCV" value='{{ old('ccv') }}'>

                                    @if ($errors->has('ccv'))
                                        <div class="invalid-feedback">{{ $errors->first('ccv') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style='margin-top: 30px;'>
                            <button class='btn btn-action btn-big'>Make Payment</button>
                        </div>

                    </div>
                    <div class='col-md-5 help-section'>
                        <h3>Summary</h3>



                    </div>
                </div>

            </form>
        </div>
    </div>

@endsection