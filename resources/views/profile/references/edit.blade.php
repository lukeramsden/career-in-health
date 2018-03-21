@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        References
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

                        @foreach ($profile->references as $reference)
                            <form id="references-form{{$reference->id}}" class="references-form" action="{{ route('profile.references.update', ['reference' => $reference]) }}" method="post">
                                {{ csrf_field() }}
                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="person_name" disabled="disabled" placeholder="Name" value="{{ $reference->person_name }}">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="">at</span>
                                            </div>
                                            <input type="text" class="form-control" name="person_company" disabled="disabled" placeholder="Company" value="{{ $reference->person_company }}">
                                        </div>
                                    </div>
                                    <div class="form-group col-12">
                                        <input type="text" class="form-control" name="person_relation" disabled="disabled" placeholder="Relation" value="{{ $reference->person_relation }}">
                                    </div>
                                    <div class="form-group col-12">
                                        <input type="text" class="form-control" name="person_contact" disabled="disabled" placeholder="Contact Info" value="{{ $reference->person_contact }}">
                                    </div>
                                    <div class="form-group col-12">
                                        <label for="work_id">Associated Job</label>
                                        <select name="work_id" id="work_id" class="form-control" disabled="disabled">
                                            <option value>-</option>
                                            @foreach($profile->work as $work)
                                                <option {{ $reference->work_id === $work->id ? 'selected' : '' }} value="{{ $work->id }}"><em>{{ $work->job_title }}</em> at <em>{{ $work->company_name }}</em></option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="references-form-buttons">
                                    <button type="button" class="references-form-edit btn btn-primary" onclick="setActiveForm({{$reference->id}})">Edit</button>
                                    <button type="submit" class="references-form-submit btn btn-primary d-none">Save</button>
                                    <a href="{{ route('profile.references.destroy', ['reference' => $reference]) }}" class="btn btn-danger">Delete</a>
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
                                $(form.find('.references-form-edit')[0])[active ? 'addClass' : 'removeClass']('d-none');
                                $(form.find('.references-form-submit')[0])[active ? 'removeClass' : 'addClass']('d-none');

                                form.find('.form-control').each(function(i, el) {
                                    $(el).prop('disabled', !active);
                                });
                            }

                            function setActiveForm(id) {
                                // disable all forms
                                $('.references-form').each(function() {setFormClasses(this, false)});
                                // enable one form
                                setFormClasses($('#references-form' + id), true);
                            }

                            window.onload = function () {
                                setActiveForm();
                            }
                        </script>

                        <br>

                        <form action="{{ route('profile.references.store') }}" method="post">
                            {{ csrf_field() }}
                            <div class="form-row">
                                <div class="form-group col-12">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="person_name" placeholder="Name" value="{{ old('person_name') }}">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="">at</span>
                                        </div>
                                        <input type="text" class="form-control" name="person_company" placeholder="Company" value="{{ old('person_company') }}">
                                    </div>
                                </div>
                                <div class="form-group col-12">
                                    <input type="text" class="form-control" name="person_relation" placeholder="Relation" value="{{ old('person_relation') }}">
                                </div>
                                <div class="form-group col-12">
                                    <input type="text" class="form-control" name="person_contact" placeholder="Contact Info" value="{{ old('person_contact') }}">
                                </div>
                                <div class="form-group col-12">
                                    <label for="work_id">Associated Job</label>
                                    <select name="work_id" id="work_id" class="form-control">
                                        <option value>-</option>
                                        @foreach($profile->work as $work)
                                            <option value="{{ $work->id }}"><em>{{ $work->job_title }}</em> at <em>{{ $work->company_name }}</em></option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Add</button>
                        </form>
    
                        @if($isCvBuilder)
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
