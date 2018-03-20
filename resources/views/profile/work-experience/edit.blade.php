@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="edit-work-experience-container form-container has-top-bar">
            <div class="row first-row">
                <div class="col-md-7 form-section">
                    <h1>Work Experience</h1>
                </div>
                <div class="col-md-5 help-section">
                    <h1>Help</h1>
                </div>
            </div>
            
            @foreach ($profile->work as $work)
                <form id="work-form{{$work->id}}" class="work-form" action="{{ route('profile.work.update', ['profileWorkExperience' => $work]) }}" method="post">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-7 form-section">
                            <hr>
                        </div>
                        <div class="col-md-5 help-section"></div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-7 form-section no-side-padding">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Job Title (<span class="text-action">*</span>)</label>
                                        <input type="text" class="form-control {{ $errors->has('job_title') ? 'is-invalid' : '' }}" name="job_title" placeholder="Job Title" disabled value="{{ $work->job_title }}">
                                        @if ($errors->has('job_title'))
                                            <div class="invalid-feedback">{{ $errors->first('job_title') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Company Name (<span class="text-action">*</span>)</label>
                                        <input type="text" class="form-control {{ $errors->has('company_name') ? 'is-invalid' : '' }}" name="company_name" placeholder="Company Name" disabled value="{{ $work->company_name }}">
                                        @if ($errors->has('company_name'))
                                            <div class="invalid-feedback">{{ $errors->first('company_name') }}</div>
                                        @endif
                                    </div>
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
                                        <label>Start Date (<span class="text-action">*</span>)</label>
                                        <input type="date" class="form-control" name="start_date" placeholder="Start Date" disabled value="{{ $work->start_date }}">
                                        @if ($errors->has('start_date'))
                                            <div class="invalid-feedback">{{ $errors->first('start_date') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input type="date" class="form-control" name="end_date" placeholder="End Date" disabled value="{{ $work->end_date }}">
                                        @if ($errors->has('end_date'))
                                            <div class="invalid-feedback">{{ $errors->first('end_date') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 help-section"></div>
                    </div>
                    
                    <div class='row'>
                        <div class='col-md-7 form-section no-side-padding'>
                            <div class="row">
                                <div class="col-md-12 work-edit-button">
                                    <button type="button" onclick="setActiveForm({{$work->id}})" class='btn btn-action btn-block'>Edit</button>
                                </div>
                                <div class="col-md-10 work-submit-button d-none">
                                    <button type="submit" class='btn btn-action btn-block'>Save</button>
                                </div>
                                <div class="col-md-2 work-submit-button d-none">
                                    <a href="{{ route('profile.work.destroy', ['profileWorkExperience' => $work]) }}" class='btn btn-danger btn-block'>Delete</a>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-5 help-section'></div>
                    </div>
                </form>
            @endforeach
            
            <script>
                function setFormClasses(form, active) {
                    // needed so you can pass elements from .each() without casting
                    // doesn't do anything if its already of correct type
                    form = $(form);

                    // call function with bracket notation because it allows for expressions
                    // get first child with class, then add or remove class d-none based on active bool
                    $(form.find('.work-edit-button')[0])[active ? 'addClass' : 'removeClass']('d-none');
                    $(form.find('.work-submit-button')[0])[active ? 'removeClass' : 'addClass']('d-none');
                    $(form.find('.work-submit-button')[1])[active ? 'removeClass' : 'addClass']('d-none');

                    form.find('.form-control').each(function(i, el) {
                        $(el).prop('disabled', !active);
                    });
                }

                function setActiveForm(id) {
                    // disable all forms
                    $('.work-form').each(function() {setFormClasses(this, false)});
                    // enable one form
                    setFormClasses($('#work-form' + id), true);
                }

                window.onload = function () {
                    setActiveForm();
                }
            </script>
            
            <form action="{{ route('profile.work.store') }}" method="post">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-7 form-section">
                        <hr>
                    </div>
                    <div class="col-md-5 help-section"></div>
                </div>
                <div class="row">
                    <div class="col-md-7 form-section no-side-padding">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Job Title (<span class="text-action">*</span>)</label>
                                    <input type="text" class="form-control {{ $errors->has('job_title') ? 'is-invalid' : '' }}" name="job_title" placeholder="Job Title" value="{{ old('job_title') }}">
                                    @if ($errors->has('job_title'))
                                        <div class="invalid-feedback">{{ $errors->first('job_title') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Company Name (<span class="text-action">*</span>)</label>
                                    <input type="text" class="form-control {{ $errors->has('company_name') ? 'is-invalid' : '' }}" name="company_name" placeholder="Company Name" value="{{ old('company_name') }}">
                                    @if ($errors->has('company_name'))
                                        <div class="invalid-feedback">{{ $errors->first('company_name') }}</div>
                                    @endif
                                </div>
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
                                    <label>Start Date (<span class="text-action">*</span>)</label>
                                    <input type="date" class="form-control" name="start_date" placeholder="Start Date" value="{{ old('start_date') }}">
                                    @if ($errors->has('start_date'))
                                        <div class="invalid-feedback">{{ $errors->first('start_date') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="date" class="form-control" name="end_date" placeholder="End Date" value="{{ old('end_date') }}">
                                    @if ($errors->has('end_date'))
                                        <div class="invalid-feedback">{{ $errors->first('end_date') }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 help-section">
                        <p>If you still work here, leave the end date blank.</p>
                    </div>
                </div>
                
                <div class='row'>
                    <div class='col-md-7 form-section'>
                        <button type="submit" class='btn btn-action btn-block'>Add</button>
                    </div>
                    <div class='col-md-5 help-section'></div>
                </div>
                <div class="row">
                    <div class="col-md-7 form-section">
                        <hr>
                        @if($is_cvbuilder)
                            <br>
                            <form method="post">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <button type="submit" class="btn btn-action btn-big">Done</button>
                                </div>
                            </form>
                        @endif
                    </div>
                    <div class="col-md-5 help-section"></div>
                </div>
            </form>
        </div>
    </div>
@endsection
