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
                                <div class="card-body">
                                    <textarea
                                    class="form-control"
                                    maxlength="3000"
                                    id="custom_cover_letter"
                                    placeholder="Cover Letter (max 3000 characters)"
                                    rows="5">{{ $application->custom_cover_letter }}</textarea>
                                </div>
                                <div class="card-footer p-0">
                                    <button onclick="saveCoverLetter(this)" class="btn btn-action btn-block btn-square">
                                        Save
                                    </button>
                                </div>
                            </div>
                            
                            <hr class="w-25">
                            
                            @if($application->jobListing->isOpen())
                                <div class="card card-custom">
                                    <div class="card-header">Status</div>
                                    <div class="card-body">
                                        <select
                                        class="custom-select"
                                        disabled>
                                            <option>{{ $application->status_name }}</option>
                                        </select>
                                    </div>
                                </div>
                            @else
                                <div class="card card-custom">
                                    <div class="card-body">
                                        <p class="mb-0"><b>This listing has been closed.</b></p>
                                        <p class="mb-1 font-italic">{{ $application->jobListing->close_reason ?? 'No reason was provided.' }}</p>
                                    </div>
                                </div>
                            @endif
                            
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
        function saveCoverLetter(self) {
            var $self = $(self);
            $self.prop('disabled', true);
            axios
                .post('{{ route('job-listing.application.update', [$application]) }}', {
                    custom_cover_letter: $('#custom_cover_letter').val(),
                })
                .then(function (res) {
                    console.log(res);
                })
                .catch(function (err) {
                    console.log(err);
                    toastr.error('Could not update cover letter.');
                })
                .then(function () {
                    $self.prop('disabled', false);
                });
        }

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