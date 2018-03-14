@extends('layouts.app')
@section('content')

    <div class="container">
        <div class='create-advert-container has-top-bar'>
            <form method='post'>
                {{ csrf_field() }}

                <div class='row first-row'>
                    <div class='col-md-7 form-section'>
                        <h1>Create Address</h1>
                    </div>
                    <div class='col-md-5 help-section'>
                        <h1>Help</h1>
                    </div>
                </div>

                <div class='row'>
                    <div class='col-md-7 form-section'>
                        <div class="form-group">
                            <label>Name (<span class='text-action'>*</span>)</label>
                            <input type="text" name='name' class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" 
                                placeholder="Name" value='{{ old('name', $address->name) }}'>

                            @if ($errors->has('name'))
                                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class='col-md-5 help-section'>
                        <p></p>
                    </div>
                </div>

                <div class='row'>
                    <div class='col-md-7 form-section'>
                        <div class="form-group">
                            <label>Address Line 1</label>
                            <input type="text" name='address_line_1' class="form-control {{ $errors->has('address_line_1') ? 'is-invalid' : '' }}" 
                                placeholder="Address Line 1" value='{{ old('address_line_1', $address->address_line_1) }}'>

                            @if ($errors->has('address_line_1'))
                                <div class="invalid-feedback">{{ $errors->first('address_line_1') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class='col-md-5 help-section'>
                        <p></p>
                    </div>
                </div>

                <div class='row'>
                    <div class='col-md-7 form-section'>
                        <div class="form-group">
                            <label>Address Line 2</label>
                            <input type="text" name='address_line_2' class="form-control {{ $errors->has('address_line_2') ? 'is-invalid' : '' }}" 
                                placeholder="Address Line 2" value='{{ old('address_line_2', $address->address_line_2) }}'>

                            @if ($errors->has('address_line_2'))
                                <div class="invalid-feedback">{{ $errors->first('address_line_2') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class='col-md-5 help-section'>
                        <p></p>
                    </div>
                </div>

                <div class='row'>
                    <div class='col-md-7 form-section'>
                        <div class="form-group">
                            <label>Town</label>

                            <select name='town' class="form-control location-search {{ $errors->has('location_id') ? 'is-invalid' : '' }}">
                                <option {{ old('town', $address->town) != null ? '' : 'selected' }} disabled>Town</option>
                                @foreach ($address->getAllLocations() as $loc)
                                    <option {{ $loc->id == old('town', $address->town) ? 'selected' : '' }} value='{{ $loc->id }}'>{{ $loc->name }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('town'))
                                <div class="invalid-feedback">{{ $errors->first('town') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class='col-md-5 help-section'>
                        <p></p>
                    </div>
                </div>

                <div class='row'>
                    <div class='col-md-7 form-section'>
                        <div class="form-group">
                            <label>County</label>
                            <input type="text" name='county' class="form-control {{ $errors->has('county') ? 'is-invalid' : '' }}" 
                                placeholder="County" value='{{ old('county', $address->county) }}'>

                            @if ($errors->has('county'))
                                <div class="invalid-feedback">{{ $errors->first('county') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class='col-md-5 help-section'>
                        <p></p>
                    </div>
                </div>

                <div class='row'>
                    <div class='col-md-7 form-section'>
                        <div class="form-group">
                            <label>Postcode</label>
                            <input type="text" name='postcode' class="form-control {{ $errors->has('postcode') ? 'is-invalid' : '' }}" 
                                placeholder="Postcode" value='{{ old('postcode', $address->postcode) }}'>

                            @if ($errors->has('postcode'))
                                <div class="invalid-feedback">{{ $errors->first('postcode') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class='col-md-5 help-section'>
                        <p></p>
                    </div>
                </div>

                <div class='row'>
                    <div class='col-md-7 form-section'>
                        <button class='btn btn-action btn-big'>Create Address</button>
                    </div>
                    <div class='col-md-5 help-section'>
                        <p></p>
                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection
@section('script')
    <script>
        $(".location-search").select2({});
    </script>
@endsection