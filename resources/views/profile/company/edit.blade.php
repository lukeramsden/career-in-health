@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="edit-profile-container form-container has-top-bar">
            <div class="row first-row">
                <div class="col-md-7 form-section">
                    <h1>Edit Your Company Profile</h1>
                </div>
                <div class="col-md-5 help-section">
                    <h1>Help</h1>
                </div>
            </div>

            @if (session('status'))
                <div class="row">
                    <div class="col-md-7 form-section">
                        <div class="alert alert-success alert-updated">
                            {{ session('status') }}
                            <a href="{{ route('company.me') }}" class="alert-link">View Your Profile</a>
                        </div>
                    </div>
                    <div class="col-md-5 help-section"></div>
                </div>
            @endif
            
            <form action="{{ route('company.update') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
    
                <div class="row">
                    <div class="col-md-7 form-section">
                        <div class="form-group">
                            <div class="media col-12">
                                <div class="py-2">
                                    <img class="mr-3 company-picture" src="{{ $company->picture() }}" alt="Profile picture">
                                </div>
                                <div class="media-body">
                                    <label>Avatar</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="input-avatar" name="avatar">
                                        <label class="custom-file-label" for="input-avatar">Choose file...</label>
                                        @if ($errors->has('avatar'))
                                            <div class="invalid-feedback">{{ $errors->first('avatar') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 help-section"></div>
                </div>
                <div class="row">
                    <div class="col-md-7 form-section no-side-padding">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Name (<span class="text-action">*</span>)</label>
                                    <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                        placeholder="Name" value="{{ old('name', $company->name) }}" required>
                                    
                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 help-section"></div>
                </div>
                <div class="row">
                    <div class="col-md-7 form-section">
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="location" class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}"
                                placeholder="Location" value="{{ old('location', $company->location) }}">
                        
                            @if ($errors->has('location'))
                                <div class="invalid-feedback">{{ $errors->first('location') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-5 help-section"></div>
                </div>
                <div class="row">
                    <div class="col-md-7 form-section">
                        <div class="form-group">
                            <label>Headline</label>
                            <input type="text" name="headline" class="form-control {{ $errors->has('headline') ? 'is-invalid' : '' }}"
                                placeholder="Headline" value="{{ old('headline', $company->headline) }}">
                        
                            @if ($errors->has('headline'))
                                <div class="invalid-feedback">{{ $errors->first('headline') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-5 help-section"></div>
                </div>
                <div class="row">
                    <div class="col-md-7 form-section">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                placeholder="Description" rows="12">{{ old('description', $company->description) }}</textarea>

                            @if ($errors->has('description'))
                                <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-5 help-section"></div>
                </div>
                <div class='row'>
                    <div class='col-md-7 form-section'>
                        <button type="submit" class='btn btn-action btn-big'>Save</button>
                    </div>
                    <div class='col-md-5 help-section'></div>
                </div>
            </form>
        </div>
    </div>
@endsection