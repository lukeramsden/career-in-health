@extends('layouts.app')

@section('content')
    <div class="container d-none">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Profile Editor
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
	                    <form
                        @isset($action)
	                        action="{{ $action }}"
		                @endisset
                        method="post" enctype="multipart/form-data">
		                    {{ csrf_field() }}
                            <div class="form-row">
                                <div class="form-group col-md-auto">
                                    <img src="{{ $profile->picture() }}" class="img-thumbnail mx-auto d-block" width="200" alt="User's profile picture">
                                </div>
                                <div class="form-group col-md-8">
                                    <small class="form-text text-muted">Avatar</small>
                                    <input type="file" class="form-control-file" name="avatar">
                                </div>
                                <div class="form-group col-md-6">
                                    <small class="form-text text-muted">First Name</small>
                                    <input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" value="{{ $profile->first_name }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <small class="form-text text-muted">Last Name</small>
                                    <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" value="{{ $profile->last_name }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <small class="form-text text-muted">Headline</small>
                                    <input type="text" class="form-control" name="headline" id="headline" placeholder="Headline" value="{{ $profile->headline }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <small class="form-text text-muted">Location</small>
                                    <input type="text" class="form-control" name="location" id="location" placeholder="Location" value="{{ $profile->location }}">
                                </div>
                                <div class="form-group col-12">
                                    <small class="form-text text-muted">Description</small>
                                    <textarea class="form-control" name="description" placeholder="Description" id="description" cols="30">{{ $profile->description }}</textarea>
                                </div>
                                <div class="form-group col-12">
                                    <small class="form-text text-muted">Jobs</small>
                                    <select name="job_types[]" class="form-control" multiple title="Jobs" size="15">
                                        @foreach(\App\Models\JobType::all() as $job)
                                            <option {{ $profile->jobTypes->contains($job) ? 'selected' : '' }} value="{{ $job->id }}">{{ $job->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="edit-profile-container form-container has-top-bar">
            <form
            @isset($action)
            action="{{ $action }}"
            @endisset
            method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row first-row">
                    <div class="col-md-7 form-section">
                        <h1>Edit Your Profile</h1>
                    </div>
                    <div class="col-md-5 help-section">
                        <h1>Help</h1>
                    </div>
                </div>
    
                <div class="row">
                    <div class="col-md-7 form-section">
                        <div class="form-group">
                            <label>Avatar (<span class="text-action">*</span>)</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="input-avatar">
                                <label class="custom-file-label" for="input-avatar">Choose file...</label>
                                @if ($errors->has('avatar'))
                                    <div class="invalid-feedback">{{ $errors->first('avatar') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 help-section"></div>
                </div>
                <div class="row">
                    <div class="col-md-7 form-section no-side-padding">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>First Name (<span class="text-action">*</span>)</label>
                                    <input type="text" name="first_name" class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                                        placeholder="First Name" value="{{ old('first_name', $profile->first_name) }}" required>
                                    
                                    @if ($errors->has('first_name'))
                                        <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input type="text" name="last_name" class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                                        placeholder="Last Name" value="{{ old('last_name', $profile->last_name) }}">
                                
                                    @if ($errors->has('last_name'))
                                        <div class="invalid-feedback">{{ $errors->first('last_name') }}</div>
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
                                placeholder="Location" value="{{ old('location', $profile->location) }}">
                        
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
                                placeholder="Headline" value="{{ old('headline', $profile->headline) }}">
                        
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
                                placeholder="Description" rows="12">{{ old('description', $profile->description) }}</textarea>

                            @if ($errors->has('description'))
                                <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-5 help-section"></div>
                </div>
                <div class="row">
                    <div class="col-md-7 form-section">
                        <div class="form-group">
                            <label>Jobs</label>
                            
                            <select name="job_types[]" class="form-control {{ $errors->has('job_types') ? 'is-invalid' : '' }}'" multiple title="Jobs" size="15">
                                @foreach(\App\Models\JobType::all() as $job)
                                    <option {{ $profile->jobTypes->contains($job) ? 'selected' : '' }} value="{{ $job->id }}">{{ $job->name }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('job_types'))
                                <div class="invalid-feedback">{{ $errors->first('job_types') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-5 help-section"></div>
                </div>
                <div class='row'>
                    <div class='col-md-7 form-section'>
                        <button class='btn btn-action btn-big'>Save</button>
                    </div>
                    <div class='col-md-5 help-section'></div>
                </div>
            </form>
        </div>
    </div>
@endsection