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
                    @if(\App\JobListingApplication::hasApplied($currentUser->userable, $jobListing))
                        <hr>
                        <b>You have applied to this job!</b>
                        <br>
                        <small class="text-muted">
                            {{ str_limit(
                                \App\JobListingApplication::getApplication(
                                    $currentUser->userable,
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
        <div class="mx-lg-5">
            @vue('private-messages')
        </div>
    </div>
@endsection
@section('script')
    <script>
        
        window.data = {
            privateMessages: {
                listing_id: {{ $jobListing->id }},
                company_id: {{ $company->id }},
                employee_id: {{ $employee->id }},
            },
        };
    </script>
    @mix('js/components/private-messages.js')
@endsection
@section('stylesheet')
@endsection