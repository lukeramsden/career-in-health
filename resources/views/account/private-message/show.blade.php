@extends('layouts.app')
@section('content')
    <div class="container mt-lg-5 pb-5">
        <div class="card card-custom mx-lg-5 mb-4">
            <div class="card-header">
                @usertype('employee')
                    <b>Company:</b> <a href="{{ route('company.show', [$jobListing->company]) }}">{{ $jobListing->company->name }} {!!verified_badge($jobListing->company)!!}</a>
                @elseusertype('company')
                    <b>Employee:</b> <a href="{{ route('employee.show', [$employee]) }}">{{ $employee->full_name }}</a>
                @endusertype
                <br>
                <b>Listing:</b> <a href="{{ route('job-listing.show', [$jobListing]) }}">{{ $jobListing->title }}</a>
                
                @usertype('employee')
                    @if(\App\JobListingApplication::hasApplied(Auth::user()->userable, $jobListing))
                        <hr>
                        <b>You have applied to this job!</b>
                        <br>
                        <small class="text-muted">
                            {{ str_limit(
                                \App\JobListingApplication::getApplication(
                                    Auth::user()->userable,
                                    $jobListing
                                )->custom_cover_letter ?? 'No cover letter', 100) }}
                        </small>
                    @endif
                @elseusertype('company')
                    @if(\App\JobListingApplication::hasApplied($employee, $jobListing))
                        <hr>
                        <b>They have applied to this job!</b>
                        <br>
                        <small class="text-muted">
                            {{ str_limit(
                                \App\JobListingApplication::getApplication(
                                    $employee,
                                    $jobListing
                                )->custom_cover_letter ?? 'No cover letter', 100) }}
                        </small>
                    @endif
                @endusertype
            </div>
        </div>
        <div id="vue-private-messages" class="mx-lg-5">
            <private-messages></private-messages>
        </div>
    </div>
@endsection
@section('script')
    <script>
        let messages = {!! json_encode($messages) !!};
        messages.forEach(msg => window.store.commit('newPrivateMessage', msg));
        
        window.data.privateMessages = {
            listing_id: {{ $jobListing->id }},
            company_id: {{ $company->id }},
            employee_id: {{ $employee->id }},
        };
    </script>
    <script src="{{ mix('js/private-messages-component.js') }}"></script>
@endsection
@section('stylesheet')
@endsection