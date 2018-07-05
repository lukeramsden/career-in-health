@extends('layouts.app')
@section('content')
    <div class="position-fixed" style="top: 1vw; right: 1vw">
        <a href="#message-thread-item-last">Jump to Latest Message</a>
    </div>
    <div class="container mt-lg-5 pb-5">
        <div class="card card-custom mx-lg-5 mb-4">
            <div class="card-header">
                @usertype('employee')
                    <b>Company:</b> <a href="{{ route('company.show', [$advert->company]) }}">{{ $advert->company->name }}</a>
                @elseusertype('company')
                    <b>Employee:</b> <a href="{{ route('employee.show', [$employee]) }}">{{ $employee->full_name }}</a>
                @endusertype
                <br>
                <b>Advert:</b> <a href="{{ route('advert.show', [$advert]) }}">{{ $advert->title }}</a>
                
                @usertype('employee')
                    @if(\App\AdvertApplication::hasApplied(Auth::user()->userable, $advert))
                        <hr>
                        <b>You have applied to this advert!</b>
                        <br>
                        <small class="text-muted">
                            {{ str_limit(
                                \App\AdvertApplication::getApplication(
                                    Auth::user()->userable,
                                    $advert
                                )->custom_cover_letter ?? 'No cover letter', 100) }}
                        </small>
                    @endif
                @endusertype
            </div>
        </div>
    
        @foreach($messages as $message)
            @set('isReceiver', $message->wasSentTo(Auth::user()))
            <div
            class="card card-custom mx-lg-5 mb-4 message-thread-item {{$isReceiver?'message-thread-item-sent':'message-thread-item-received'}}"
            @if ($loop->last)
                id="message-thread-item-last"
            @endif
            >
                @if($isReceiver)
                    @usertype('employee')
                        <div class="card-header"><b>From:</b> {{ $message->company->name }}</div>
                    @elseusertype('company')
                        <div class="card-header"><b>From:</b> {{ $message->employee->full_name }}</div>
                    @endusertype
                @else
                    <div class="card-header"><b>You said...</b></div>
                @endif
                <div class="card-body">{{ $message->body }}</div>
                <div class="card-footer">{{ $message->created_at->diffForHumans() }}</div>
            </div>
            @unset($isReceiver)
        @endforeach
        
        <div class="card card-custom mx-lg-5 mb-4" id="new-message">
            <div class="card-header">New Message</div>
            <div class="card-body">
                <form
                action="{{ route('account.private-message.store') }}#new-message"
                method="post">
                    {{ csrf_field() }}
    
                    <input type="hidden" name="advert_id" value="{{ $advert->id }}">
                    
                    @usertype('employee')
                        <input type="hidden" name="to_company_id" value="{{ $advert->company->id }}">
                    @elseusertype('company')
                        <input type="hidden" name="to_employee_id" value="{{ $employee->id }}">
                    @endusertype
                    
                    <div class="form-group">
                        <textarea
                        class="form-control {{ $errors->has('body') ? 'is-invalid' : '' }}"
                        name="body" id="inputBody" rows="10" maxlength="1000">{{ old('body') }}</textarea>
                        
                        @if($errors->has('body'))
                            <div class="invalid-feedback">{{ $errors->first('body') }}</div>
                        @endif
                    </div>
                    
                    <button type="submit" class="btn btn-primary px-4">Send</button>
                </form>
            </div>
        </div>
    </div>
@endsection