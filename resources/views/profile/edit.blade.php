@extends('layouts.app')

@section('content')
    <div class="container">
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
@endsection