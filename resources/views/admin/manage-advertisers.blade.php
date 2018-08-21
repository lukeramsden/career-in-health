@extends('layouts.app')
@section('content')
    <div class="container my-5">
        <h2 class="my-3"><em>Advertisers</em></h2>
        <div class="card-columns mt-2">
            @foreach($advertisers as $advertiser)
                @if($advertiser->isActive())
                    <div class="card card-custom">
                        <form action="{{ route('admin.manage-advertisers.deactivate-user', [$advertiser]) }}"
                              method="post">
                @else
                    <div class="card card-custom card-custom-secondary">
                        <form action="{{ route('admin.manage-advertisers.activate-user', [$advertiser]) }}"
                              method="post">
                @endif
                        {{ csrf_field() }}
                        
                        <div class="card-body">
                            <div class="form-group">
                                <label>Name</label>
                                <input
                                type="text"
                                class="form-control"
                                value="{{ $advertiser->name }}"
                                disabled>
                            </div>
                            
                            <div class="form-group">
                                <label>Email</label>
                                <input
                                type="text"
                                class="form-control"
                                value="{{ $advertiser->user->email }}"
                                disabled>
                            </div>
                            
                            <div class="form-group">
                                <label>Adverts</label>
                                <input
                                type="text"
                                class="form-control"
                                value="{{ $advertiser->adverts()->count() }}"
                                disabled>
                            </div>
                        </div>
                        
                        <div class="card-footer p-0">
                            <div class="btn-group btn-group-sm btn-group-full" role="group">
                            @if($advertiser->isActive())
                                <button type="submit" class="btn btn-danger">Deactivate User</button>
                            @else
                                <button type="submit" class="btn btn-secondary">Activate User</button>
                            @endif
                            </div>
                        </div>
                    </form>
                </div>
            @endforeach
        </div>
        <h2 class="my-3"><em>Invites</em></h2>
        <div class="card-columns mt-2">
            @foreach($invites as $invite)
                <div class="card card-custom">
                    <form action="{{ route('admin.manage-advertisers.invite.cancel', [$invite]) }}" method="post">
                        <div class="card-body">
                            {{ csrf_field() }}
                            
                            <div class="form-group">
                                <label>Email</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    value="{{ $invite->email }}"
                                    disabled>
                            </div>
                            
                            @isset($invite->last_reminded_at)
                                <div class="form-group">
                                    <label>Last Reminded</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        value="{{ $invite->last_reminded_at->diffForHumans() }}"
                                        disabled>
                                </div>
                            @endisset
                        
                        </div>
                        <div class="card-footer p-0">
                            <div class="btn-group btn-group-sm btn-group-full" role="group">
                                <button type="submit" class="btn btn-danger">Cancel Invite</button>
                                <a href="{{ route('admin.manage-advertisers.invite.remind', $invite) }}"
                                   class="btn btn-primary">Remind</a>
                            </div>
                        </div>
                    </form>
                </div>
            @endforeach
            
            <div class="card card-custom">
                <div class="card-header">Invite a new user</div>
                <div class="card-body">
                    <form action="{{ route('admin.manage-advertisers.invite') }}" method="post">
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