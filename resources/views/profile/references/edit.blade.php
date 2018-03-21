@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="edit-work-experience-container form-container has-top-bar">
            <div class="row first-row">
                <div class="col-md-7 form-section">
                    <h1>{{ $isCvBuilder ? 'Add' : 'Edit Your' }} References</h1>
                </div>
                <div class="col-md-5 help-section">
                    <h1>Help</h1>
                </div>
            </div>
            
            @foreach ($profile->references as $reference)
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
                                        <label>Person Name</label>
                                        <input type="text" class="form-control" placeholder="Person Name" readonly value="{{ $reference->person_name }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Company Name</label>
                                        <input type="text" class="form-control" placeholder="Company Name" readonly value="{{ $reference->person_company }}">
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
                                        <label>Relation</label>
                                        <input type="text" class="form-control" placeholder="Relation" readonly value="{{ $reference->person_relation }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Information</label>
                                        <input type="text" class="form-control" placeholder="Contact Information" readonly value="{{ $reference->person_contact }}">
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
                                <select name="work_id" title="Associated Job" class="custom-select" disabled readonly>
                                    <option selected value="{{ $reference->work->id }}">{{ $reference->work->job_title }} at {{ $reference->work->company_name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5 help-section"></div>
                    </div>
                    
                    <div class='row'>
                        <div class='col-md-7 form-section no-side-padding'>
                            <div class="row">
                                <div class="col-md-10">
                                    <a href="{{ route('profile.references.edit_single', ['reference' => $reference, 'isCvBuilder' => $isCvBuilder]) }}" class='btn btn-action btn-block'>Edit</a>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('profile.references.destroy', ['reference' => $reference]) }}" class="btn btn-danger btn-block">Delete</a>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-5 help-section'></div>
                    </div>
                </form>
            @endforeach
            
            <form action="{{ route('profile.references.store') }}" method="post">
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
                                    <input type="text" class="form-control {{ $errors->has('person_name') ? 'is-invalid' : '' }}" name="person_name" placeholder="Person Name" required value="{{ old('person_name') }}">
                                    @if ($errors->has('person_name'))
                                        <div class="invalid-feedback">{{ $errors->first('person_name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Company Name (<span class="text-action">*</span>)</label>
                                    <input type="text" class="form-control {{ $errors->has('person_company') ? 'is-invalid' : '' }}" name="person_company" placeholder="Company Name" required value="{{ old('person_company') }}">
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
                                    <input type="text" class="form-control {{ $errors->has('person_relation') ? 'is-invalid' : '' }}" name="person_relation" placeholder="Relation" required value="{{ old('person_relation') }}">
                                    @if ($errors->has('person_relation'))
                                        <div class="invalid-feedback">{{ $errors->first('person_relation') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contact Information (<span class="text-action">*</span>)</label>
                                    <input type="text" class="form-control {{ $errors->has('person_contact') ? 'is-invalid' : '' }}" name="person_contact" placeholder="Contact Information" required value="{{ old('person_contact') }}">
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
                            <select name="work_id" title="Associated Job" class="custom-select  {{ $errors->has('work_id') ? 'is-invalid' : '' }}">
                                <option value>-</option>
                                @foreach(Auth::user()->profile->work as $work)
                                    <option {{ old('work_id') == $work->id ? 'selected' : '' }} value="{{ $work->id }}">{{ $work->job_title }} at {{ $work->company_name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('work_id'))
                                <div class="invalid-feedback">{{ $errors->first('work_id') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-5 help-section"></div>
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
