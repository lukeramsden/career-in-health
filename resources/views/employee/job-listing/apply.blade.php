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
                        <h6>{{ $jobListing->setting_name }}</h6>
                        <div id="small-details">
                            <div>
                                <p><span
                                    class="badge badge-primary badge-pill p-2 px-3">{{ $jobListing->type_name }}</span>
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
                            <a class="link-unstyled" href="{{ route('company.show', $company) }}">
                                <div class="card card-custom scale-on-hover-2">
                                    <div class="card-body">
                                        <img src="{{ $company->picture() ?? '/images/generic.png' }}"
                                             alt="Profile picture" width="200" class="img-thumbnail mx-auto d-block">
                                        <div class="text-center">
                                            <h1
                                            class="mt-3">{{ $company->name }} {!!verified_badge($company)!!}</h1>
                                            <h5><b>{{ $company->location->name }}</b></h5>
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
                        <div id="vue-private-messages">
                            <private-messages></private-messages>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        let messages = {!! json_encode($messages) !!};
        messages.forEach(msg => window.store.commit('newPrivateMessage', msg));
        
        window.data = {
            privateMessages: {
                listing_id: {{ $jobListing->id }},
                company_id: {{ $company->id }},
                employee_id: {{ $employee->id }},
            },
        };
    </script>
    <script src="{{ mix('js/private-messages-component.js') }}"></script>
@endsection
@section('stylesheet')
@endsection