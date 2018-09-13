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
                                    @money($jobListing->min_salary * 100, 'GBP') - @money($jobListing->max_salary * 100,
                                    'GBP')
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
                        <div>
                            <div class="card card-custom">
                                <form action="{{ route('job-listing.application.store', [$jobListing]) }}"
                                      method="post">
                                    <div class="card-body">
                                        {{ csrf_field() }}
                                        <label>Custom Cover Letter</label>
                                        <textarea
                                        class="form-control {{ $errors->has('custom_cover_letter') ? 'is-invalid' : '' }}"
                                        maxlength="3000" placeholder="Cover Letter (max 3000 characters)"
                                        name="custom_cover_letter" rows="5">{{ old('custom_cover_letter') }}</textarea>
                                        <small class="form-text text-muted"></small>
                                        @if ($errors->has('custom_cover_letter'))
                                            <div
                                            class="invalid-feedback">{{ $errors->first('custom_cover_letter') }}</div>
                                        @endif
                                    </div>
                                    <div class="card-footer p-0">
                                        <button type="submit" class="btn btn-action btn-block btn-square">Submit</button>
                                    </div>
                                </form>
                            </div>
                            
                            <hr class="w-25">
                            
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 order-lg-0">
                        <div class="about-company">
                            <a class="link-unstyled" href="{{ route('company.show', $address->company) }}">
                                <div class="card card-custom scale-on-hover-2">
                                    <div class="card-body">
                                        <img src="{{ $address->company->picture() ?? '/images/generic.png' }}"
                                             alt="Profile picture" width="200" class="img-thumbnail mx-auto d-block">
                                        <div class="text-center">
                                            <h1
                                            class="mt-3">{{ $address->company->name }} {!!verified_badge($address->company)!!}</h1>
                                            <h5><b>{{ $address->company->location->name }}</b></h5>
                                            <p class="mx-5 text-justify">{!! nl2br(e($company->about)) !!}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                            <hr class="w-25">
                            <a href="{{ route('address.show', $address) }}" class="link-unstyled">
                                <div class="card card-custom mb-4 scale-on-hover-2">
                                    <div class="card-body">
                                        <h2 class="card-title my-0">{{$address->name}}</h2>
                                        <h3>{{ $address->location->name }}</h3>
                                        
                                        <hr>
                                        
                                        @isset($address->about)
                                            <h5><em>About</em></h5>
                                            <p>{!! nl2br(e($address->about)) !!}</p>
                                        @endisset
                                        
                                        <hr>
                                        
                                        @isset($address->phone)
                                            <h5><span class="oi oi-phone text-muted"></span> <span
                                                class="text-muted">Phone:</span> <span>{{ $address->phone }}</span></h5>
                                        @endisset
                                        
                                        @isset($address->email)
                                            <h5><span class="oi oi-envelope-closed text-muted"></span> <span
                                                class="text-muted">Email:</span> <span>{{ $address->email }}</span></h5>
                                        @endisset
                                    </div>
                                </div>
                            </a>
                            
                            <hr class="w-25">
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 order-lg-2">
                        <div class="your-correspondence">
                            <h2 class="text-center"><em>Your Correspondence</em></h2>
                            
                            <div class="card card-custom mb-4" id="new-message">
                                <form
                                action="{{ route('account.private-message.store') }}#new-message"
                                method="post">
                                    <div class="card-body">
                                        {{ csrf_field() }}
                                        
                                        <input type="hidden" name="job_listing_id" value="{{ $jobListing->id }}">
                                        
                                        @usertype('employee')
                                        <input type="hidden" name="to_company_id"
                                               value="{{ $jobListing->company->id }}">
                                        @elseusertype('company')
                                        <input type="hidden" name="to_employee_id" value="{{ $employee->id }}">
                                        @endusertype
                                        
                                        <textarea
                                        class="form-control {{ $errors->has('body') ? 'is-invalid' : '' }}"
                                        name="body" id="inputBody" rows="3"
                                        maxlength="1000">{{ old('body') }}</textarea>
                                        
                                        @if($errors->has('body'))
                                            <div class="invalid-feedback">{{ $errors->first('body') }}</div>
                                        @endif
                                    
                                    </div>
                                    <div class="card-footer p-0">
                                        <button type="submit" class="btn btn-primary btn-block">Send</button>
                                    </div>
                                </form>
                            </div>
                            
                            @foreach($messages as $message)
                                @set('isReceiver', $message->wasSentTo(Auth::user()))
                                <div
                                class="card card-custom message-thread-item mb-4 {{$isReceiver?'message-thread-item-sent':'message-thread-item-received'}}"
                                @if ($loop->last)
                                id="message-thread-item-last"
                                @endif
                                >
                                    @if($isReceiver)
                                        @usertype('employee')
                                        <div class="card-header">
                                            <b>From:</b> {{ $message->company->name }} {!!verified_badge($message->company)!!}
                                        </div>
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
{{--@extends('layouts.app')--}}
{{--@section('content')--}}
{{--<div class="container mt-lg-5">--}}
{{--<div class="card card-custom">--}}
{{--<div class="card-body">--}}
{{--<form action="{{ route('job-listing.application.store', [$jobListing]) }}" method="post">--}}
{{--{{ csrf_field() }}--}}
{{----}}
{{--<div class="form-group">--}}
{{--<label>Custom Cover Letter</label>--}}
{{--<textarea class="form-control {{ $errors->has('custom_cover_letter') ? 'is-invalid' : '' }}" maxlength="3000" placeholder="Cover Letter (max 3000 characters)" name="custom_cover_letter" rows="5">{{ old('custom_cover_letter') }}</textarea>--}}
{{--<small class="form-text text-muted"></small>--}}
{{--@if ($errors->has('custom_cover_letter'))--}}
{{--<div class="invalid-feedback">{{ $errors->first('custom_cover_letter') }}</div>--}}
{{--@endif--}}
{{--</div>--}}
{{----}}
{{--<div class="form-group">--}}
{{--<button class="btn btn-action btn-big px-5">Apply</button>--}}
{{--</div>--}}
{{--</form>--}}
{{--</div>--}}
{{--</div>--}}
{{--</div>--}}
{{--@endsection--}}