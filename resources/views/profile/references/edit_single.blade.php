@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="edit-work-experience-container form-container has-top-bar">
            <div class="row first-row">
                <div class="col-md-7 form-section">
                    <h1>References</h1>
                </div>
                <div class="col-md-5 help-section">
                    <h1>Help</h1>
                </div>
            </div>
            
            <form action="{{ route('profile.references.update', ['reference' => $reference, 'isCvBuilder' => app('request')->input('isCvBuilder')]) }}" method="post">
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
                                    <label>Person Name (<span class="text-action">*</span>)</label>
                                    <input type="text" class="form-control {{ $errors->has('person_name') ? 'is-invalid' : '' }}" name="person_name" placeholder="Person Name" value="{{ $reference->person_name }}">
                                    @if ($errors->has('person_name'))
                                        <div class="invalid-feedback">{{ $errors->first('person_name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Company Name (<span class="text-action">*</span>)</label>
                                    <input type="text" class="form-control {{ $errors->has('person_company') ? 'is-invalid' : '' }}" name="person_company" placeholder="Company Name" value="{{ $reference->person_company }}">
                                    @if ($errors->has('person_company'))
                                        <div class="invalid-feedback">{{ $errors->first('person_company') }}</div>
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
                                    <label>Relation (<span class="text-action">*</span>)</label>
                                    <input type="text" class="form-control" name="person_relation" placeholder="Relation" value="{{ $reference->person_relation }}">
                                    @if ($errors->has('person_relation'))
                                        <div class="invalid-feedback">{{ $errors->first('person_relation') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contact Information (<span class="text-action">*</span>)</label>
                                    <input type="text" class="form-control" name="person_contact" placeholder="Contact Information" value="{{ $reference->person_contact }}">
                                    @if ($errors->has('person_contact'))
                                        <div class="invalid-feedback">{{ $errors->first('person_contact') }}</div>
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
                            <label>Associated Job</label>
                            <select name="work_id" title="Associated Job" class="form-control">
                                <option value>-</option>
                                @foreach(Auth::user()->profile->work as $work)
                                    <option {{ $reference->work_id === $work->id ? 'selected' : '' }} value="{{ $work->id }}">{{ $work->job_title }} at {{ $work->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5 help-section"></div>
                </div>
                
                <div class='row'>
                    <div class='col-md-7 form-section'>
                        <button type="submit" class='btn btn-action btn-block'>Save</button>
                    </div>
                    <div class='col-md-5 help-section'></div>
                </div>
                <div class="row">
                    <div class="col-md-7 form-section">
                        <hr>
                    </div>
                    <div class="col-md-5 help-section"></div>
                </div>
            </form>
        </div>
    </div>
@endsection
