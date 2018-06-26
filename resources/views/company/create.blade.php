@extends('layouts.app')
@section('content')
    <div class="container-fluid sleek-form-parent">
        <div class="card sleek-form">
            <div class="card-body">
                <div class="form-row">
                    <div class="col col-12 col-xl-6">
                        <form method="post" action="{{ route('company.store') }}">
                            {{ csrf_field() }}
                            
                            <div class="form-group">
                                <small class="text-muted">Name (<span class='text-action'>*</span>)</small>
                                <input
                                required
                                name="name"
                                class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                placeholder="First Name"
                                value="{{ old('name') }}">
                                
                                @if ($errors->has('name'))
                                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                                @endif
                            </div>
                            
                            <div class="form-group">
                                <small class="text-muted">Users To Invite</small>
                                <select
                                class="form-control {{ count($errors->get('usersToInvite.*')) ? 'is-invalid' : '' }}"
                                multiple="multiple"
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
                            
                            <div class="form-group text-center mt-5">
                                <button type="submit" class="btn btn-action">Create Company</button>
                            </div>
                        </form>
                    </div>
                    
                    <div class="col col-12 col-xl-6 order-first order-xl-last">
                    </div>
                </div>
            </div>
        </div>
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
        })
    </script>
@endsection
@section('stylesheet')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
@endsection