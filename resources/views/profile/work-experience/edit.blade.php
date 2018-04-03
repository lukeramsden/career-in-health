@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="edit-work-experience-container form-container has-top-bar">
            <div class="row first-row">
                <div class="col-md-7 form-section">
                    <h1>{{ $isCvBuilder ? 'Add' : 'Edit' }} Your Work Experience</h1>
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
                            @if(!$isCvBuilder)
                                <a href="{{ route('profile.me') }}" class="alert-link">View Your Profile</a>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-5 help-section"></div>
                </div>
            @endif
            
            @foreach ($profile->work as $work)
                <form>
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
                                        <label>Job Title</label>
                                        <input type="text" class="form-control" placeholder="Job Title" readonly value="{{ $work->job_title }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Company Name</label>
                                        <input type="text" class="form-control" placeholder="Company Name" readonly value="{{ $work->company_name }}">
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
                                        <label>Start Date</label>
                                        <input type="date" class="form-control" placeholder="Start Date" readonly value="{{ $work->start_date }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <input type="date" class="form-control" placeholder="End Date" readonly value="{{ $work->end_date }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 help-section"></div>
                    </div>
                    
                    <div class='row'>
                        <div class='col-md-7 form-section no-side-padding'>
                            <div class="row">
                                <div class="col-md-10">
                                    <a href="{{ route('profile.work.edit_single', ['work' => $work, 'isCvBuilder' => $isCvBuilder]) }}" class='btn btn-action btn-block'>Edit</a>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('profile.work.destroy', ['work' => $work]) }}" class="btn btn-danger btn-block">Delete</a>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-5 help-section'></div>
                    </div>
                </form>
            @endforeach
            
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
                                    <input type="date" class="form-control {{ $errors->has('start_date') ? 'is-invalid' : '' }}" name="start_date" placeholder="Start Date" value="{{ old('start_date') }}">
                                    @if ($errors->has('start_date'))
                                        <div class="invalid-feedback">{{ $errors->first('start_date') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>End Date</label>
                                    <input type="date" class="form-control {{ $errors->has('end_date') ? 'is-invalid' : '' }}" name="end_date" placeholder="End Date" value="{{ old('end_date') }}">
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
            </form>
            <div class="row">
                <div class="col-md-7 form-section">
                    <hr>
                    @if($isCvBuilder)
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
        </div>
    </div>
@endsection
