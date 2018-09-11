@extends('layouts.app')
@section('content')
    <div class="container mt-lg-5">
        @isset($messages)
            {!! $messages->appends(Request::capture()->except('page'))->render('vendor.pagination') !!}

            @usertype('employee')
                @foreach($messages as $message)
                    <div class="card card-custom mb-3">
                        <div class="card-header">
                            <b>With:</b> <a href="{{ route('company.show', [$message->company]) }}">{{ $message->company->name }} {!!verified_badge($message->company)!!}</a>
                            <br>
                            <b>JobListing:</b> <a href="{{ route('job-listing.show', [$message->job_listing]) }}">{{ $message->job_listing->title }}</a>
                        </div>
                        <div class="card-body">
                            <p>Latest Message:</p>
                            <p class="text-muted">
                                {{ str_limit($message->body) }}
                            </p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('account.private-message.show-employee', [$message->job_listing]) }}">View Messages</a>
                        </div>
                    </div>
                @endforeach
            @elseusertype('company')
                @foreach($messages as $message)
                    <div class="card card-custom mb-3">
                        <div class="card-header">
                            <b>With:</b> <a href="{{ route('employee.show', [$message->employee]) }}">{{ $message->employee->full_name }}</a>
                            <br>
                            <b>JobListing:</b> <a href="{{ route('job-listing.show', [$message->job_listing]) }}">{{ $message->job_listing->title }}</a>
                        </div>
                        <div class="card-body">
                            <h6>Latest:</h6>
                            <p class="mb-0">
                                <b>{{ $message->wasSentTo(Auth::user()) ? 'They' : 'You' }} Said:</b>
                                {{ str_limit($message->body) }}
                            </p>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('account.private-message.show-company', [$message->job_listing, $message->employee]) }}">View Messages</a>
                        </div>
                    </div>
                @endforeach
            @endusertype
        @endisset
        @empty($messages)
            <div class="text-center">
                <p class="font-italic">You have no messages</p>
            </div>
        @endempty
    </div>
@endsection