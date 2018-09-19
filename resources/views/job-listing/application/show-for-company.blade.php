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
                        <div class="profile-picture">
                            <img
                            src="{{ $employee->picture() ?? '/images/generic.png'  }}"
                            alt="Profile picture">
                        </div>
                        <h1 class="text-center mt-3"><a
                            href="{{ action('EmployeeController@show', $employee) }}">{{ $employee->full_name  }}</a>
                        </h1>
                        <div class="mx-4">
                            <hr>
                            <div>
                                <label for="status">Status</label>
                                <select
                                class="custom-select"
                                id="select-status">
                                    <option {{ !isset($application->status) ? 'selected' : '' }} disabled>-</option>
                                    @foreach(App\JobListingApplication::$statuses as $id => $status)
                                        <option
                                        {{ $application->status == $id ? 'selected' : '' }} value="{{ $id }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <hr>
                        </div>
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
                                                <p class="my-1"><b>{{ $education->degree }}
                                                        in {{ $education->field_of_study }}</b></p>
                                                <p class="my-1">{{ $education->school_name }}
                                                    - {{ $education->location }}</p>
                                                @isset($education->end_date)
                                                    <p class="my-1">{{ $education->start_date->format('F Y') }}
                                                        to {{ $education->end_date->format('F Y') }}</p>
                                                @else
                                                    <p class="my-1">
                                                        Started {{ $education->start_date->format('F Y') }}</p>
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
                                                <p class="my-1"><b>{{ $workExperience->job_title }}</b> at
                                                    <b>{{ $workExperience->company_name }}</b></p>
                                                <p class="my-1">{{ $workExperience->location }}</p>
                                                @isset($workExperience->end_date)
                                                    <p class="my-1">{{ $workExperience->start_date->format('F Y') }}
                                                        to {{ $workExperience->end_date->format('F Y') }}</p>
                                                @else
                                                    <p class="my-1">
                                                        Started {{ $workExperience->start_date->format('F Y') }}</p>
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
                                                    <p class="my-1">
                                                        Awarded {{ $certification->start_date->format('F Y') }} -
                                                        Expires {{ $certification->end_date->format('F Y') }}</p>
                                                @else
                                                    <p class="my-1">
                                                        Awarded {{ $certification->start_date->format('F Y') }} (Doesn't
                                                        Expire)</p>
                                                @endisset
                                                @isset($certification->description)
                                                    <p class="my-1">{!! nl2br(e($certification->description)) !!}</p>
                                                @endisset
                                                <p class="mt-2 mb-1"><a class="btn btn-primary btn-sm btn-block"
                                                                        href="{{ $certification->url }}">View</a></p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        
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
        $(function () {
            $('#select-status').on('change', function (e) {
                $(e.target).prop('disabled', true);
                axios
                    .post('{{ route('job-listing.application.update', [$application]) }}', {
                        status: e.target.value,
                    })
                    .then(function (res) {
                        console.log(res);
                    })
                    .catch(function (err) {
                        console.log(err);
                        toastr.error('Could not update status.');
                    })
                    .then(function () {
                        $(e.target).prop('disabled', false);
                    });
            });
        });

        window.data = {
            privateMessages: {
                listing_id: {{ $jobListing->id }},
                company_id: {{ $company->id }},
                employee_id: {{ $employee->id }},
                messages: {!! json_encode($messages) !!},
                usertype:
                    @usertype('employee')
                'employee'
                    @elseusertype('company')
                'company'
                @elseusertype
                ''
                @endusertype
                ,
            },
        };
    </script>
    <script src="{{ mix('js/private-messages-component.js') }}"></script>
@endsection
@section('stylesheet')
@endsection