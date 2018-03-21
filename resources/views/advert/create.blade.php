@extends('layouts.app')
@section('content')

    <div class="container">
        <div class='create-advert-container form-container has-top-bar'>
            <form method='post'>
                {{ csrf_field() }}

                <div class='row first-row'>
                    <div class='col-md-7 form-section'>
                        <h1>Create A New Advert</h1>
                    </div>
                    <div class='col-md-5 help-section'>
                        <h1>Help</h1>
                    </div>
                </div>

                <div class='row'>
                    <div class='col-md-7 form-section'>
                        <div class="form-group">
                            <label>Address (<span class='text-action'>*</span>)</label>

                            <select name='address_id' class="custom-select location-search {{ $errors->has('address_id') ? 'is-invalid' : '' }}">
                                <option {{ old('address_id', $advert->address_id) != null ? '' : 'selected' }} disabled>Address</option>
                                @foreach (Auth::user()->company->addresses as $loc)
                                    <option {{ $loc->id == old('address_id', $advert->address_id) ? 'selected' : '' }} value='{{ $loc->id }}'>{{ $loc->name }} - {{ $loc->location->name }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('address_id'))
                                <div class="invalid-feedback">{{ $errors->first('address_id') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class='col-md-5 help-section'>
                        <p>This will be the address that you want to find staff for.<br />If you haven't created a address <a href='{{ route('address_create') }}'>click here</a> to creat one</p>
                    </div>
                </div>

                <div class='row'>
                    <div class='col-md-7 form-section'>
                        <div class="form-group">
                            <label>Title (<span class='text-action'>*</span>)</label>
                            <input type="text" name='title' class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" 
                                placeholder="Title" value='{{ old('title', $advert->title) }}'>

                            @if ($errors->has('title'))
                                <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class='col-md-5 help-section'>
                        <p>Use a well-known job title and avoid generic phrases like “Do you want to work in sales?” as people are unlikely to search using these terms. To reduce irrelevant applications you may also wish to use specific phrases</p>
                    </div>
                </div>

                <div class='row'>
                    <div class='col-md-7 form-section'>
                        <div class="form-group">
                            <label>Description (<span class='text-action'>*</span>)</label>
                            <textarea name='description' class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" 
                                placeholder="Description" rows='12'>{{ old('description', $advert->description) }}</textarea>

                            @if ($errors->has('description'))
                                <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class='col-md-5 help-section'>
                        <p>Your job advert should answer this question: “If I am your perfect applicant, why should I leave my current employer to join you rather than someone else?” Achieve this with concise, clear and persuasive communication.</p>
                    </div>
                </div>

                <div class='row'>
                    <div class='col-md-7 form-section'>
                        <div class="form-group">
                            <label>Role (<span class='text-action'>*</span>)</label>
                            <select class='custom-select {{ $errors->has('role') ? 'is-invalid' : '' }}' name='role'>
                                <option value='' selected disabled></option>
                                @foreach ($advert->getRoles() as $id => $role)
                                    <option value='{{ $id }}' {{ old('role', $advert->role) == $id ? 'selected' : '' }}>{{ $role }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('description'))
                                <div class="invalid-feedback">{{ $errors->first('description') }}</div>
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
                            <label>Role Setting (<span class='text-action'>*</span>)</label>
                            <select class='custom-select {{ $errors->has('setting') ? 'is-invalid' : '' }}' name='setting'>
                                <option value='' selected disabled></option>
                                @foreach ($advert->getSettings() as $id => $setting)
                                    <option value='{{ $id }}' {{ old('setting', $advert->setting) == $id ? 'selected' : '' }}>{{ $setting }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('setting'))
                                <div class="invalid-feedback">{{ $errors->first('setting') }}</div>
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
                            <label>Type (<span class='text-action'>*</span>)</label>
                            <select class='custom-select {{ $errors->has('type') ? 'is-invalid' : '' }}' name='type'>
                                <option value='' selected disabled></option>
                                @foreach ($advert->getTypes() as $id => $type)
                                    <option value='{{ $id }}' {{ old('type', $advert->type) == $id ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('type'))
                                <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class='col-md-5 help-section'>
                        <p></p>
                    </div>
                </div>

                <div class='row'>
                    <div class='col-md-7 form-section no-side-padding'>
                        <div class='row'>
                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label>Annual Salary Minimum</label>
                                    <input type="text" name='min_salary' class="form-control {{ $errors->has('min_salary') ? 'is-invalid' : '' }}" 
                                        placeholder="Minimum Salary" value='{{ old('min_salary', $advert->min_salary) }}'>
                                </div>
                            </div>

                            <div class='col-md-6'>
                                <div class="form-group">
                                    <label>Annual Salary Maximum</label>
                                    <input type="text" name='max_salary' class="form-control {{ $errors->has('max_salary') ? 'is-invalid' : '' }}" 
                                        placeholder="Maximum Salary" value='{{ old('max_salary', $advert->max_salary) }}'>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='col-md-5 help-section'>
                        <p>If you don't want to provide a salary just leave it blank.</p>
                    </div>
                </div>


                <div class='row'>
                    <div class='col-md-7 form-section'>
                        <button class='btn btn-action btn-big'>Create Advert</button>
                        <button class='btn btn-text btn-big' name='save_for_later' value='1'>Save As Draft</button>
                    </div>
                    <div class='col-md-5 help-section'>
                        <p></p>
                    </div>
                </div>

            </form>
        </div>
    </div>
    
@endsection

