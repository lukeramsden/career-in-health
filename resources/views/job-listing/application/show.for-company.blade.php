@extends('layouts.app')
@section('content')
    <div class="container-fluid p-0 m-0">
        <div class="row m-0 p-4" id="job_listing-show-row">
            <div class="col-12">
                <div class="card card-custom card-job_listing">
                    <div class="card-body">
                        <a href="{{ route('company.show', [$jobListing->company]) }}" class="card-subtitle">
                            {{$jobListing->company->name}} {!!verified_badge($jobListing->company)!!}
                        </a>
                        <h4 class="card-title">{{$jobListing->jobRole->name}}</h4>
                        <h5>{{ $jobListing->title }}</h5>
                        <h6>{{ $jobListing->getSetting() }}</h6>
                        <div id="small-details">
                            <div>
                                <p><span
                                    class="badge badge-primary badge-pill p-2 px-3">{{ $jobListing->getType() }}</span>
                                </p>
                            </div>
                            <div>
                                <p><span class="oi oi-map-marker mr-3"></span>{{ $jobListing->address->location->name }}
                                </p>
                            </div>
                            <div>
                                <p>
                                    @money($jobListing->min_salary * 100, 'GBP') - @money($jobListing->max_salary * 100, 'GBP')
                                </p>
                            </div>
                            <div>
                                <p><span class="oi oi-calendar"></span> <span
                                    class="text-muted">Last Updated</span> {{ $jobListing->updated_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12" id="application">
                <div class="row">
                    <div class="col-12 col-lg-4 order-lg-1">
                        <div class="profile-picture">
                            <img
                                src="{{ $employee->picture() ?? '/images/generic.png'  }}"
                                alt="Profile picture">
                        </div>
                        <h1 class="text-center mt-3"><a href="{{ action('EmployeeController@show', $employee) }}">{{ $employee->full_name  }}</a></h1>
                        <hr class="mx-4">
                        <p class="mx-5 text-justify">
                            @isset($application->custom_cover_letter)
                                {!! nl2br(e($application->custom_cover_letter)) !!}
                            @else
                                <span class="text-muted font-italic">No cover letter</span></p>
                            @endisset
                        </p>
                    </div>
                    <div class="col-12 col-lg-4 order-lg-0">
                        <div class="about-employee">
                            <h2 class="text-center"><em>About {{ $employee->full_name }}</em></h2>
                            <p class="mx-5 text-justify">{!! nl2br(e($employee->about)) !!}</p>
                            @if($cv->education->count() > 0)
                                <div class="card card-custom mb-4">
                                    <div class="card-body">
                                        <h4 class="mb-4"><em>Education</em></h4>
                                        
                                        @foreach($cv->education as $education)
                                            <div>
                                                <p class="my-1"><b>{{ $education->degree }} in {{ $education->field_of_study }}</b></p>
                                                <p class="my-1">{{ $education->school_name }} - {{ $education->location }}</p>
                                                @isset($education->end_date)
                                                    <p class="my-1">{{ $education->start_date->format('F Y') }} to {{ $education->end_date->format('F Y') }}</p>
                                                @else
                                                    <p class="my-1">Started {{ $education->start_date->format('F Y') }}</p>
                                                @endisset
                                            </div>
                                            @if(!$loop->last)
                                                <hr class="my-4">
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if($cv->workExperience->count() > 0)
                                <div class="card card-custom mb-4">
                                    <div class="card-body">
                                        <h4 class="mb-4"><em>Work Experience</em></h4>
                                
                                        @foreach($cv->workExperience as $workExperience)
                                            <div>
                                                <p class="my-1"><b>{{ $workExperience->job_title }}</b> at <b>{{ $workExperience->company_name }}</b></p>
                                                <p class="my-1">{{ $workExperience->location }}</p>
                                                @isset($workExperience->end_date)
                                                    <p class="my-1">{{ $workExperience->start_date->format('F Y') }} to {{ $workExperience->end_date->format('F Y') }}</p>
                                                @else
                                                    <p class="my-1">Started {{ $workExperience->start_date->format('F Y') }}</p>
                                                @endisset
                                                @isset($workExperience->description)
                                                    <p class="my-1">{!! nl2br(e($workExperience->description)) !!}</p>
                                                @endisset
                                            </div>
                                            @if(!$loop->last)
                                                <hr class="my-4">
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if($cv->certifications->count() > 0)
                                <div class="card card-custom mb-4">
                                    <div class="card-body">
                                        <h4 class="mb-4"><em>Certifications/Licenses</em></h4>
                                    
                                        @foreach($cv->certifications as $certification)
                                            <div>
                                                <p class="my-1"><b>{{ $certification->title }}</b></p>
                                                @isset($certification->end_date)
                                                    <p class="my-1">Awarded {{ $certification->start_date->format('F Y') }} - Expires {{ $certification->end_date->format('F Y') }}</p>
                                                @else
                                                    <p class="my-1">Awarded {{ $certification->start_date->format('F Y') }} (Doesn't Expire)</p>
                                                @endisset
                                                @isset($certification->description)
                                                    <p class="my-1">{!! nl2br(e($certification->description)) !!}</p>
                                                @endisset
                                                <p class="mt-2 mb-1"><a class="btn btn-primary btn-sm btn-block" href="{{ $certification->url }}">View</a></p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 order-lg-2">
                        @foreach($messages as $message)
                            @set('isReceiver', $message->wasSentTo(Auth::user()))
                            <div
                            class="card card-custom message-thread-item {{$isReceiver?'message-thread-item-sent':'message-thread-item-received'}}"
                            @if ($loop->last)
                                id="message-thread-item-last"
                            @endif
                            >
                                @if($isReceiver)
                                    @usertype('employee')
                                        <div class="card-header"><b>From:</b> {{ $message->company->name }} {!!verified_badge($message->company)!!}</div>
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
                        {!! $messages->appends(Request::capture()->except('page'))->render('vendor.pagination') !!}
                        
                        <div class="card card-custom" id="new-message">
                            <div class="card-header">New Message</div>
                            <div class="card-body">
                                <form
                                action="{{ route('account.private-message.store') }}#new-message"
                                method="post">
                                    {{ csrf_field() }}
                    
                                    <input type="hidden" name="job_listing_id" value="{{ $jobListing->id }}">
                                    
                                    @usertype('employee')
                                        <input type="hidden" name="to_company_id" value="{{ $jobListing->company->id }}">
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
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
@section('stylesheet')
@endsection