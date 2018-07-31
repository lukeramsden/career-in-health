@extends('layouts.app')
@section('content')
    <div class="container my-5">
        <h2 class="mb-3"><em>Owner</em></h2>
        @set('user', $company->owner())
        <div class="card card-custom">
            <div class="card-body">
                <div class="media">
                    <a href="{{ route('company-user.show', $user) }}">
                        <img class="mr-3 img-thumbnail" width="80" src="{{ $user->picture() ?? '/images/generic.png' }}">
                    </a>
                    <div class="media-body">
                        <h4 class="my-0">
                            <a href="{{ route('company-user.show', $user) }}">
                                {{ $user->full_name }}
                            </a>
                            @if(Auth::user()->userable_id == $user->id)
                                <small class="text-muted">You</small>
                            @endif
                        </h4>
                        
                        <h5 class="mt-0">{{ $user->job_title ?? 'Unknown' }}</h5>
                    </div>
                </div>
            </div>
        </div>
        @unset($user)
        <h2 class="my-3"><em>Managers</em></h2>
        <div class="card-columns smaller-card-columns mt-2">
            @foreach($company->managers() as $user)
                <div class="card card-custom">
                    <div class="card-body">
                        <div class="media">
                            <a href="{{ route('company-user.show', $user) }}">
                                <img class="mr-3 img-thumbnail" width="80" src="{{ $user->picture() ?? '/images/generic.png' }}">
                            </a>
                            <div class="media-body">
                                <h4 class="my-0">
                                    <a href="{{ route('company-user.show', $user) }}">
                                        {{ $user->full_name }}
                                    </a>
                                    @if(Auth::user()->userable_id == $user->id)
                                        <small class="text-muted">You</small>
                                    @endif
                                </h4>
                                
                                <h5 class="mt-0">{{ $user->job_title ?? 'Unknown' }}</h5>
                                
                                @if(Auth::user()->userable_id != $user->id)
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if(Auth::user()->userable->hasPermsOver($user))
                                            <a href="{{
                                                route('company.manage-users.update-permission-level', [
                                                    $user,
                                                    'new_permission_level' => 'standard',
                                                ]) }}"
                                               class="btn btn-danger">Demote</a>
        
                                            <a href="javascript:" class="btn btn-golden">
                                                <span class="oi oi-star"></span>
                                                Make Owner
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <h2 class="my-3"><em>Standard Users</em></h2>
        <div class="card-columns smaller-card-columns mt-2">
            @foreach($company->standardUsers() as $user)
                <div class="card card-custom">
                    <div class="card-body">
                        <div class="media">
                            <a href="{{ route('company-user.show', $user) }}">
                                <img class="mr-3 img-thumbnail" width="80" src="{{ $user->picture() ?? '/images/generic.png' }}">
                            </a>
                            <div class="media-body">
                                <h4 class="my-0">
                                    <a href="{{ route('company-user.show', $user) }}">
                                        {{ $user->full_name }}
                                    </a>
                                    @if(Auth::user()->userable_id == $user->id)
                                        <small class="text-muted">You</small>
                                    @endif
                                </h4>
                                
                                <h5 class="mt-0">{{ $user->job_title ?? 'Unknown' }}</h5>
                                
                                @if(Auth::user()->userable_id != $user->id)
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{
                                            route('company.manage-users.update-permission-level', [
                                                $user,
                                                'new_permission_level' => 'manager',
                                            ]) }}"
                                           class="btn btn-primary">Promote</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <h2 class="my-3"><em>Invites</em></h2>
        <div class="card-columns mt-2">
            @foreach($company->invites as $invite)
                <div class="card card-custom">
                    <div class="card-header">Invited by <a href="{{ route('company-user.show', $invite->invitedBy) }}">{{$invite->invitedBy->full_name}}</a></div>
                    <div class="card-body">
                        <form action="{{ route('company.manage-users.invite.cancel', [$invite]) }}" method="post">
                            {{ csrf_field() }}
                            
                            <div class="form-group">
                                <label>Email (<span class="text-action">*</span>)</label>
                                <input
                                    type="email"
                                    class="form-control"
                                    value="{{ $invite->email }}"
                                    disabled>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-danger btn-sm btn-block">Cancel Invite</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
            
            <div class="card card-custom">
                <div class="card-header">Invite a new user</div>
                <div class="card-body">
                    <form action="{{ route('company.manage-users.invite') }}" method="post">
                        {{ csrf_field() }}
                        
                        <div class="form-group">
                            <label>Email (<span class="text-action">*</span>)</label>
                            <input
                                type="email"
                                name="email"
                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                placeholder="Email"
                                value="{{ old('email') }}"
                                required>
                            
                            @if ($errors->has('email'))
                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-action btn-sm btn-block">Invite</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection