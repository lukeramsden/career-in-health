@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Work Experience
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

                        @foreach ($profile->work as $work)
                            <form id="work-form{{$work->id}}" class="work-form" action="{{ route('profile.work.update', ['profileWorkExperience' => $work]) }}" method="post">
                                {{ csrf_field() }}
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="job_title" placeholder="Job Title" disabled="disabled" value="{{ $work->job_title }}">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="">at</span>
                                            </div>
                                            <input type="text" class="form-control" name="company_name" placeholder="Company Name" disabled="disabled" value="{{ $work->company_name }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <small class="form-text text-muted">Start Date</small>
                                        <input type="date" class="form-control" name="start_date" placeholder="Start Date" disabled="disabled" value="{{ $work->start_date }}">
                                    </div>
                                    <div class="form-group col-6">
                                        <small class="form-text text-muted">End Date</small>
                                        <input type="date" class="form-control" name="end_date" placeholder="End Date" disabled="disabled" value="{{ $work->end_date }}">
                                    </div>
                                </div>

                                <div class="work-form-buttons">
                                    <button type="button" class="work-form-edit btn btn-primary" onclick="setActiveForm({{$work->id}})">Edit</button>
                                    <button type="submit" class="work-form-submit btn btn-primary d-none">Save</button>
                                    <a href="{{ route('profile.work.destroy', ['work' => $work]) }}" class="btn btn-danger">Delete</a>
                                </div>
                            </form>
                            <hr>
                        @endforeach

                        <script>
                            function setFormClasses(form, active) {
                                // needed so you can pass elements from .each() without casting
                                // doesn't do anything if its already of correct type
                                form = $(form);

                                // call function with bracket notation because it allows for expressions
                                // get first child with class, then add or remove class d-none based on active bool
                                $(form.find('.work-form-edit')[0])[active ? 'addClass' : 'removeClass']('d-none');
                                $(form.find('.work-form-submit')[0])[active ? 'removeClass' : 'addClass']('d-none');

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

                        <br>

                        <form action="{{ route('profile.work.store') }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="job_title" placeholder="Job Title" value="{{ old('job_title') }}">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="">at</span>
                                        </div>
                                        <input type="text" class="form-control" name="company_name" placeholder="Company Name" value="{{ old('company_name') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-6">
                                    <small class="form-text text-muted">Start Date</small>
                                    <input type="date" class="form-control" name="start_date" placeholder="Start Date" value="{{ old('start_date') }}">
                                </div>
                                <div class="form-group col-6">
                                    <small class="form-text text-muted">End Date</small>
                                    <input type="date" class="form-control" name="end_date" placeholder="End Date" value="{{ old('end_date') }}">
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
                        
                        @if($is_cvbuilder)
                            <br>
                            <form method="post">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Done</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
