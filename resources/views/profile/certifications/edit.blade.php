@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="edit-work-experience-container form-container has-top-bar">
            <div class="row first-row">
                <div class="col-md-7 form-section">
                    <h1>{{ $isCvBuilder ? 'Add' : 'Edit' }} Your Certifications</h1>
                </div>
                <div class="col-md-5 help-section">
                    <h1>Help</h1>
                </div>
            </div>
            
            @foreach ($profile->certifications as $certification)
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Title (<span class="text-action">*</span>)</label>
                                        <input type="text" class="form-control" placeholder="Title" readonly value="{{ $certification->name }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 help-section"></div>
                    </div>
                    
                    <div class='row'>
                        <div class='col-md-7 form-section no-side-padding'>
                            <div class="row">
                                <div class="col-md-8">
                                    <a href="{{ route('profile.certifications.edit_single', ['certification' => $certification, 'isCvBuilder' => $isCvBuilder]) }}" class='btn btn-action btn-block'>Edit</a>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ $certification->url() }}" target="_blank" class="btn btn-outline-secondary btn-block">View</a>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ route('profile.certifications.destroy', ['certification' => $certification]) }}" class="btn btn-danger btn-block">Delete</a>
                                </div>
                            </div>
                        </div>
                        <div class='col-md-5 help-section'></div>
                    </div>
                </form>
            @endforeach
            
            <form action="{{ route('profile.certifications.store') }}" enctype="multipart/form-data" method="post">
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
                                    <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name" placeholder="Title" value="{{ old('name') }}">
                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input  {{ $errors->has('file') ? 'is-invalid' : '' }}" required name="file">
                                        <label class="custom-file-label" >Choose file...</label>
                                        @if ($errors->has('file'))
                                            <div class="invalid-feedback">{{ $errors->first('file') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
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
