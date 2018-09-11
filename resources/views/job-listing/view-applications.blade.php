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
                                <p><span class="badge badge-primary badge-pill p-2 px-3">{{ $jobListing->getType() }}</span></p>
                            </div>
                            <div>
                                <p><span class="oi oi-map-marker mr-3"></span>{{ $jobListing->address->location->name }}</p>
                            </div>
                            <div>
                                <p>
                                    @money($jobListing->min_salary * 100, 'GBP') - @money($jobListing->max_salary * 100, 'GBP')
                                </p>
                            </div>
                            <div>
                                <p><span class="oi oi-calendar"></span> <span class="text-muted">Last Updated</span> {{ $jobListing->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12">
                <div class="card-columns m-0">
                    @foreach($jobListing->applications as $application)
                        <div class="card card-custom" id="application{{$application->id}}">
                            <div class="card-body">
                                <p>
                                    <b>From:</b> 
                                    <a href="{{ route('employee.show', $application->employee) }}">{{ $application->employee->full_name }}</a>
                                </p>
                                <p>
                                    @if($application->custom_cover_letter)
                                        {{ $application->custom_cover_letter }}
                                    @else
                                        <span class="text-muted font-italic">No cover letter</span>
                                    @endif
                                </p>
    
                                <hr>
                                
                                <form action="{{ route('job-listing.application.update', [$application]) }}" method="post">
                                    {{ csrf_field() }}
                                    
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="custom-select" name="status" id="status">
                                            <option {{ !isset($application->status) ? 'selected' : '' }} disabled>-</option>
                                            @foreach(App\JobListingApplication::$statuses as $id => $status)
                                                <option {{ $application->status == $id ? 'selected' : '' }} value="{{ $id }}">{{ $status }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="notes">Notes</label>
                                        <textarea name="notes" id="notes" cols="30" rows="8" class="form-control">{{ old('notes', $application->notes) }}</textarea>
                                        <small class="text-muted">Not shown to applicant.</small>
                                    </div>
                                    
                                    <button class="btn btn-action px-4">Save</button>
                                </form>
                            </div>
                            <div class="card-footer">
                                {{ $application->updated_at->diffForHumans() }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(function() {
            @if(Request::query('highlight'))
                var $card = $('#application{{ Request::query('highlight')  }}');
                $card
                    .addClass('highlight')
                    .addClass('rounded');
                
                $([document.documentElement, document.body]).animate({
                    scrollTop: $card.offset().top
                }, 500);
            @endif
        });
    </script>
@endsection