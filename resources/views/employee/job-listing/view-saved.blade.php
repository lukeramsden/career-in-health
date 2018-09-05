@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        @isset($jobListings)
            {!! $jobListings->appends(Request::capture()->except('page'))->render('vendor.pagination') !!}
            @foreach($jobListings as $savedJobListing)
                @set('jobListing', $savedJobListing->jobListing)
                <div class="card card-custom card-job_listing">
                    <div class="card-body">
                        <a href="{{ route('company.show', [$jobListing->company]) }}"
                           class="card-subtitle">
                            {{$jobListing->company->name}}
                        </a>
                        <h4 class="card-title">{{$jobListing->jobRole->name}}</h4>
                        <h5><a
                            href="{{ route('job-listing.show', [$jobListing]) }}">{{ $jobListing->title }}</a>
                        </h5>
                        <h6>{{ $jobListing->getSetting() }}</h6>
                        <div id="small-details">
                            <div>
                                <p><span
                                    class="badge badge-secondary badge-pill p-2 px-3">{{ $jobListing->getType() }}</span>
                                </p>
                            </div>
                            <div>
                                <p>
                                    @money($jobListing->min_salary * 100, 'GBP') -
                                    @money($jobListing->max_salary * 100, 'GBP')
                                </p>
                            </div>
                            <div>
                                <a
                                href="{{ route('tracking.job-listing.search.click', [$jobListing]) }}"
                                class="btn btn-outline-primary">View</a>
                            </div>
                            <div>
                                <button
                                    class="btn btn-golden"
                                    id="save-toggle-button"
                                    state="saved"
                                    job-listing="{{ $jobListing->id }}"><span class="oi oi-star"></span> Remove</button>
                            </div>
                        </div>
                    </div>
                </div>
                @unset($jobListing)
            @endforeach
            {!! $jobListings->appends(Request::capture()->except('page'))->render('vendor.pagination') !!}
        @endisset
    </div>
@endsection
@section('script')
    <script>
        $(function() {
            $('#save-toggle-button').click(function(e) {
                var self = $(this);
                self.prop('disabled', true );
                switch(self.attr('state'))
                {
                    case 'saved':
                    {
                        axios
                            .post(route('employee.unsave-job-listing', self.attr('job-listing')).toString())
                            .then(function(response) {
                                if(response.data.success)
                                    toastr.success('Listing removed');
                            })
                            .catch(function(error) {
                                console.error(error);
                                toastr.error('Error');
                            })
                            .then(function() {
                                self.prop('disabled', false);
                                self.attr('state', 'unsaved');
                                self.removeClass('btn-golden');
                                self.addClass('btn-secondary');
                                self.html('<span class="oi oi-star"></span> Save')
                            });
                        break;
                    }
                    case 'unsaved':
                    {
                        axios
                            .post(route('employee.save-job-listing', self.attr('job-listing')).toString())
                            .then(function(response) {
                                if(!response.data.success)
                                    toastr.error('Error');
                            })
                            .catch(function(error) {
                                console.error(error);
                                toastr.error('Error');
                            })
                            .then(function() {
                                self.prop('disabled', false);
                                self.attr('state', 'saved');
                                self.removeClass('btn-secondary');
                                self.addClass('btn-golden');
                                self.html('<span class="oi oi-star"></span> Remove')
                            });
                        break;
                    }
                    default:
                        console.log('Unrecognized state: ' + self.attr('state'));
                }
            });
        });
    </script>
@endsection