@extends('layouts.app', ['title' => 'Create a Company'])
@section('content')
    <div class="container my-lg-5">
        <form
        action="{{ route($edit ? 'company.update' : 'company.store') }}"
        method="post"
        enctype="multipart/form-data">
            {{ csrf_field() }}
            
            <div class="card-columns smaller-card-columns">
                <div class="card card-custom">
                    <div class="card-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-12 offset-lg-3 col-lg-6">
                                    <img src="{{ optional($company)->picture() ?? '/images/generic.png' }}" alt="Profile picture"
                                         class="img-thumbnail mx-auto d-block" style="width: 100%; max-width: 230px;">
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col">
                                            <label>Avatar</label>
                                            <div class="custom-file">
                                                <input type="file"
                                                       class="custom-file-input {{ $errors->has('avatar') ? 'is-invalid' : '' }}"
                                                       id="inputAvatar" name="avatar" accept="image/png,image/jpeg">
                                                <label class="custom-file-label" for="inputAvatar">Choose
                                                    file...</label>
                                                <small class="text-muted">(.png, .jpg, .jpeg)</small>
                                                @if ($errors->has('avatar'))
                                                    <div class="invalid-feedback">{{ $errors->first('avatar') }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto align-self-center">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="inputRemoveAvatar" value="1"
                                                       name="remove_avatar">
                                                <label class="custom-control-label" for="inputRemoveAvatar">Remove
                                                    avatar?</label>
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
                            <label for="inputName">Name (<span class='text-action'>*</span>)</label>
                            <input
                            required
                            name="name"
                            id="inputName"
                            class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                            placeholder="Name"
                            value="{{ old('name', $company->name) }}">
                            
                            @if ($errors->has('name'))
                                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Location (<span class='text-action'>*</span>)</label>
                            
                            <select name="location_id"
                                    class="form-control location-control {{ $errors->has('location_id') ? 'is-invalid' : '' }}"
                                    title="Location" size="1">
                                <option {{ old('location_id', optional($company->location)->id) === null ? 'selected' : '' }} value="null" disabled>-</option>
                                @foreach(\App\Location::getAllLocations() as $location)
                                    <option {{ old('location_id', optional($company->location)->id) === $location->id ? 'selected' : '' }} value="{{ $location->id }}">{{ $location->name }}</option>
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
                        @if(!$edit)
                            <div class="form-group">
                                <label for="usersInviteSelect">Users To Invite</label>
                                <select
                                class="form-control {{ count($errors->get('usersToInvite.*')) ? 'is-invalid' : '' }}"
                                multiple="multiple"
                                size="1"
                                name="usersToInvite[]"
                                id="usersInviteSelect">
                                    @if (is_array(old('usersToInvite')))
                                        @foreach (old('usersToInvite') as $email)
                                            <option value="{{ $email }}" selected="selected">{{ $email }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if (count($errors->get('usersToInvite.*')))
                                    <div class="invalid-feedback">
                                        @foreach(array_values($errors->get('usersToInvite.*')) as $error)
                                            {{ $error[0] }} {{-- TODO: Make this error prettier--}}
                                            <br>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif
                        
                        <div class="form-group">
                            <label>About</label>
                            <textarea name="about" class="form-control {{ $errors->has('about') ? 'is-invalid' : '' }}"
                                      placeholder="500 characters about the company" rows="12"
                                      maxlength="500">{{ old('about', $company->about) }}</textarea>
                            
                            @if ($errors->has('about'))
                                <div class="invalid-feedback">{{ $errors->first('about') }}</div>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <label>Contact Details</label>
                            <input
                            type="tel"
                            class="form-control my-1 {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                            placeholder="Phone Number"
                            name="phone"
                            value="{{ old('phone', $company->phone) }}">
                            
                            @if ($errors->has('phone'))
                                <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                            @endif
                            <input
                            type="email"
                            class="form-control my-1 {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            placeholder="Email Address"
                            name="email"
                            value="{{ old('email', $company->email) }}">
                            
                            @if ($errors->has('email'))
                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="card card-custom">
                    <div class="card-body">
                        <div class="form-group">
                            <button type="submit" class="btn btn-action btn-block">
                                {{ $edit ? 'Save' : 'Create Company' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
    <script>
        $(function () {
            function validateEmail(email) {
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(String(email).toLowerCase());
            }
            $('#usersInviteSelect').select2({
                tags: true,
                placeholder: 'Enter a list of emails (comma separated)',
                tokenSeparators: [',', ' '],
                createTag: function (params) {
                    const term = $.trim(params.term);

                    if (term === '')
                        return null;

                    if (!validateEmail(term))
                        return null;

                    return {
                        id: term,
                        text: term,
                        newTag: true,
                    }
                },
                language: {
                    noResults: function (params) {
                        return 'Invalid email.';
                    },
                },
                containerCssClass: 'select2-sleek-input',
            });
        
            $('.location-control').select2({
                dropdownAutoWidth : true,
                width: '100%'
            });

            $('input[name="avatar"]').change(function(){
                const path = $(this).val();
                $('label[for="inputAvatar"]').text(path.substr(path.lastIndexOf('\\') + 1));
            });
        })
    </script>
@endsection
@section('stylesheet')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet"/>
    <style>
        .custom-checkbox .custom-control-label::before {
            border: 1px solid #495057;
        }
    </style>
@endsection