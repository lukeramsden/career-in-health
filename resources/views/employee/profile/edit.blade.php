@extends('layouts.app', ['title' => 'Edit Profile'])
@section('content')
    <div class="container my-lg-5">
        <form action="{{ route('employee.update') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
            
            <div class="card-columns smaller-card-columns">
                <div class="card card-custom">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-12 offset-lg-3 col-lg-6">
                                    <img src="{{ $employee->picture() ?? '/images/generic.png' }}" alt="Profile picture" class="img-thumbnail mx-auto d-block" style="width: 100%; max-width: 230px;">
                                </div>
                                <div class="col-12">
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
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card card-custom">
                    <div class="card-body">
                        <div class="form-group">
                            <label>First Name (<span class="text-action">*</span>)</label>
                            <input type="text" name="first_name" class="form-control {{ $errors->has('first_name') ? 'is-invalid' : '' }}"
                                   placeholder="First Name" value="{{ old('first_name', $employee->first_name) }}" required maxlength="40">
                            
                            @if ($errors->has('first_name'))
                                <div class="invalid-feedback">{{ $errors->first('first_name') }}</div>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control {{ $errors->has('last_name') ? 'is-invalid' : '' }}"
                                   placeholder="Last Name" value="{{ old('last_name', $employee->last_name) }}" maxlength="40">
                            
                            @if ($errors->has('last_name'))
                                <div class="invalid-feedback">{{ $errors->first('last_name') }}</div>
                            @endif
                        </div>
    
                        <div class="form-group">
                            <label>Location (<span class="text-action">*</span>)</label>
        
                            <select name="location_id" class="form-control location-control {{ $errors->has('location_id') ? 'is-invalid' : '' }}" title="Location" size="1">
                                <option {{ old('location_id', $employee->location_id) === null ? 'selected' : '' }} value="null" disabled>-</option>
                                @foreach(\App\Location::getAllLocations() as $location)
                                    <option {{ old('location_id', $employee->location_id) === $location->id ? 'selected' : '' }} value="{{ $location->id }}">{{ $location->name }}</option>
                                @endforeach
                            </select>
        
                            @if ($errors->has('location_id'))
                                <div class="invalid-feedback">{{ $errors->first('location_id') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="card card-custom">
                    <div class="card-body">
                        
                        <div class="form-group">
                            <label>About You</label>
                            <textarea name="about" class="form-control {{ $errors->has('about') ? 'is-invalid' : '' }}"
                                      placeholder="500 characters about yourself" rows="12" maxlength="500">{{ old('about', $employee->about) }}</textarea>
                            
                            @if ($errors->has('about'))
                                <div class="invalid-feedback">{{ $errors->first('about') }}</div>
                            @endif
                        </div>
                    
                    </div>
                </div>
    
                <div class="card card-custom">
                    <div class="card-body">
                        <button type="submit" class="btn btn-action btn-block">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('stylesheet')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    
    <script>
        $(function() {
            $('.location-control').select2({
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