@extends('layouts.app')
@section('content')
    <div class="container my-5">
        <div class="card card-custom">
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12 col-lg-10">
                                        <div class="row">
                                            <div class="col">
                                                <label>Avatar</label>
                                                 <div class="custom-file">
                                                     <input type="file" class="custom-file-input {{ $errors->has('avatar') ? 'is-invalid' : '' }}" id="inputAvatar" name="avatar" accept="image/png,image/jpeg">
                                                     <label class="custom-file-label" for="inputAvatar">Choose file...</label>
                                                     <small class="text-muted">(.png, .jpg, .jpeg)</small>
                                                     @if ($errors->has('avatar'))
                                                         <div class="invalid-feedback">{{ $errors->first('avatar') }}</div>
                                                     @endif
                                                 </div>
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="custom-control custom-checkbox">
                                                  <input type="checkbox" class="custom-control-input" id="inputRemoveAvatar" value="1" name="remove_avatar">
                                                  <label class="custom-control-label" for="inputRemoveAvatar">Remove avatar?</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 order-first order-lg-last col-lg-2">
                                        <img src="{{ $profile->picture() ?? '/images/generic.png' }}" alt="Profile picture" class="img-thumbnail mx-auto d-block" style="width: 100%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label>First Name (<span class="text-action">*</span>)</label>
                                <input type="text" name="first_name" class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                                       placeholder="First Name" value="{{ old('first_name', $profile->first_name) }}" required maxlength="40">
                                    
                                @if ($errors->has('first_name'))
                                    <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                
                                <label>Last Name</label>
                                <input type="text" name="last_name" class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                                       placeholder="Last Name" value="{{ old('last_name', $profile->last_name) }}" maxlength="40">
                                
                                @if ($errors->has('last_name'))
                                    <div class="invalid-feedback">{{ $errors->first('last_name') }}</div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label>Location</label>
                                <input type="text" name="location" class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}"
                                       placeholder="Location" value="{{ old('location', $profile->location) }}" maxlength="80">
                            
                                @if ($errors->has('location'))
                                    <div class="invalid-feedback">{{ $errors->first('location') }}</div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="tel" name="phone" class="form-control {{ $errors->has('phone') ? 'is-invalid' : ''}}"
                                       placeholder="Phone Number" value="{{ old('phone', $profile->phone) }}" maxlength="40">
                                
                                @if ($errors->has('phone'))
                                    <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label>Headline</label>
                                <input type="text" name="headline" class="form-control {{ $errors->has('headline') ? 'is-invalid' : '' }}"
                                    placeholder="Headline" value="{{ old('headline', $profile->headline) }}" maxlength="80">
                            
                                @if ($errors->has('headline'))
                                    <div class="invalid-feedback">{{ $errors->first('headline') }}</div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                    placeholder="Description" rows="12" maxlength="1000">{{ old('description', $profile->description) }}</textarea>
    
                                @if ($errors->has('description'))
                                    <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <label>Job Roles</label>
                                
                                <select name="job_types[]" class="form-control job-type-control {{ $errors->has('job_types') ? 'is-invalid' : '' }}" multiple title="Jobs" size="1">
                                    @foreach(\App\JobType::all() as $job)
                                        <option {{ $profile->jobTypes->contains($job) ? 'selected' : '' }} value="{{ $job->id }}">{{ $job->name }}</option>
                                    @endforeach
                                </select>
    
                                @if ($errors->has('job_types'))
                                    <div class="invalid-feedback">{{ $errors->first('job_types') }}</div>
                                @endif
                            </div>
                        </div>
                        
                    </div>
                    
                    <button type="submit" class="btn btn-action px-5 float-right">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('stylesheet')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    
    <style>
        .custom-checkbox .custom-control-label::before {
            border: 1px solid #495057;
        }
    </style>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    
    <script>
        $(function() {
            $('.job-type-control').select2({
                dropdownAutoWidth : true,
                width: '100%'
            });
            
            $('input[name="avatar"]').change(function(){
                const path = $(this).val();
                $('label[for="inputAvatar"]').text(path.substr(path.lastIndexOf('\\') + 1));
            });
        });
    </script>
@endsection