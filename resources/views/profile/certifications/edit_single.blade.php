@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="edit-work-experience-container form-container has-top-bar">
            <div class="row first-row">
                <div class="col-md-7 form-section">
                    <h1>Certifications</h1>
                </div>
                <div class="col-md-5 help-section">
                    <h1>Help</h1>
                </div>
            </div>
            
            <form action="{{ route('profile.certifications.update', ['certification' => $certification, 'isCvBuilder' => app('request')->input('isCvBuilder')]) }}" method="post">
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
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Title (<span class="text-action">*</span>)</label>
                                    <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name" placeholder="Title" value="{{ $certification->name }}">
                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                            </div>
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
