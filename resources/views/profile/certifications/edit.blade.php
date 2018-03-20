@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Certifications
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
                        
                        @foreach ($profile->certifications as $certification)
                            <form id="certifications-form{{$certification->id}}" class="certifications-form" action="{{ route('profile.certifications.update', ['certification' => $certification]) }}" method="post">
                                {{ csrf_field() }}
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <input type="text" class="form-control" name="name" disabled="disabled" placeholder="Name" value="{{ $certification->name }}">
                                    </div>
                                </div>

                                <div class="certifications-form-buttons">
                                    <button type="button" class="certifications-form-edit btn btn-primary" onclick="setActiveForm({{$certification->id}})">Edit</button>
                                    <button type="submit" class="certifications-form-submit btn btn-primary d-none">Save</button>
                                    <a href="{{ route('profile.certifications.destroy', ['certification' => $certification]) }}" class="btn btn-danger">Delete</a>
                                    <a href="{{ $certification->url() }}" class="btn btn-outline-secondary">View</a>
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
                                $(form.find('.certifications-form-edit')[0])[active ? 'addClass' : 'removeClass']('d-none');
                                $(form.find('.certifications-form-submit')[0])[active ? 'removeClass' : 'addClass']('d-none');

                                form.find('.form-control').each(function(i, el) {
                                    $(el).prop('disabled', !active);
                                });
                            }

                            function setActiveForm(id) {
                                // disable all forms
                                $('.certifications-form').each(function() {setFormClasses(this, false)});
                                // enable one form
                                setFormClasses($('#certifications-form' + id), true);
                            }

                            window.onload = function () {
                                setActiveForm();
                            }
                        </script>

                        <br>
                        
                        <form action="{{ route('profile.certifications.store') }}" enctype="multipart/form-data" method="post">
                            {{ csrf_field() }}
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <input type="text" class="form-control" name="name" placeholder="Name" value="{{ old('name') }}">
                                </div>
                                <div class="form-group col-12">
                                    <input type="file" class="form-control-file" name="file">
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
