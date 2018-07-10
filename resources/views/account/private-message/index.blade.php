@extends('layouts.app')
@section('content')
    <div class="container mt-lg-5">
        {{-- TODO: improve UX for this --}}
        @usertype('employee')
            @forelse($messages as $message)
                <div class="card card-custom mb-3">
                    <div class="card-header">
                        <b>With:</b> <a href="{{ route('company.show', [$message->company]) }}">{{ $message->company->name }}</a>
                        <br>
                        <b>Advert:</b> <a href="{{ route('advert.show', [$message->advert]) }}">{{ $message->advert->title }}</a>
                    </div>
                    <div class="card-body">
                        <p>Latest Message:</p>
                        <p class="text-muted">
                            {{ str_limit($message->body) }}
                        </p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('account.private-message.show-employee', [$message->advert]) }}">View Messages</a>
                    </div>
                </div>
            @empty
                <div class="text-center">
                    <p class="font-italic">You have no messages</p>
                </div>
            @endforelse
        @elseusertype('company')
            @forelse($messages as $message)
                <div class="card card-custom mb-3">
                    <div class="card-header">
                        <b>With:</b> <a href="{{ route('employee.show', [$message->employee]) }}">{{ $message->employee->full_name }}</a>
                        <br>
                        <b>Advert:</b> <a href="{{ route('advert.show', [$message->advert]) }}">{{ $message->advert->title }}</a>
                    </div>
                    <div class="card-body">
                        <h6>Latest:</h6>
                        <p class="mb-0">
                            <b>{{ $message->wasSentTo(Auth::user()) ? 'They' : 'You' }} Said:</b>
                            {{ str_limit($message->body) }}
                        </p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('account.private-message.show-company', [$message->advert, $message->employee]) }}">View Messages</a>
                    </div>
                </div>
                
            @empty
                <div class="text-center">
                    <p class="font-italic">You have no messages</p>
                </div>
            @endforelse
        @endusertype
    </div>
@endsection