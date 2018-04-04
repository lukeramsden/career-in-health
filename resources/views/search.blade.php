@extends('layouts.app')
@section('content')
    <div class="container-fluid p-0 m-0">
        @isset($results)
            <div class="row" id="search-row">
                {{-- RESULTS --}}
                <div class="col-12 col-md-4 order-md-last" id="search-form-parent">
                    <div id="search-form">
                
                    </div>
                </div>
                <div class="col-12 col-md-8" id="search-results-parent">
                    <div id="search-results" style="">
                        {!! $results->appends(Request::capture()->except('page'))->render("vendor.pagination") !!}
                        @foreach($results as $advert)
                            <div class="card card-custom">
                                <div class="card-body">
                                    <a href="{{ route('company.show', ['company' => $advert->company]) }}" class="card-subtitle">
                                        {{$advert->company->name}}
                                    </a>
                                    <h4 class="card-title">
                                        {{$advert->jobType->name}}
                                    </h4>
                                </div>
                            </div>
                        @endforeach
                        {!! $results->appends(Request::capture()->except('page'))->render("vendor.pagination") !!}
                    </div>
                </div>
            </div>
            
        @else
            <div id="first-search-row" class="row justify-content-center">
                <div class="col-12 col-sm-10 col-md-8 col-lg-6">
                    <form method="get">
                        {{ csrf_field() }}
                        <h6 class="text-muted">I'm a</h6>
                        <div id="first-search-job-type">
                            <select id="first-search-job-type-control" name="job_types[]" class="custom-select" multiple size="1">
                                @foreach(\App\JobType::all() as $job)
                                    <option {{ collect(old('job_types', Request::get('job_types')))->contains($job->id) ? 'selected':'' }} value="{{ $job->id }}">{{ $job->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <h6 class="text-muted my-2">in</h6>
                        <div id="first-search-town">
                            <select id="first-search-town-control" name="town" class="custom-select" required>
                                <option {{ old('town', Request::get('town')) != null ? '' : 'selected' }} disabled></option>
                                @foreach (\App\Location::getAllLocations() as $loc)
                                    <option {{ $loc->id == old('town', Request::get('town')) ? 'selected' : '' }} value='{{ $loc->id }}'>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
    
                        <input type="hidden" name="radius" value="50">
                        <input type="hidden" name="min_salary" value="0">
                        <input type="hidden" name="max_salary" value="150000">
                        
                        <button class="btn btn-block btn-sm btn-action my-3" type="submit">Search</button>
                    </form>
                </div>
            </div>
        @endisset
    </div>
@endsection
@section('stylesheet')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#first-search-town-control').select2({});
            $('#first-search-job-type-control').select2({});
        });
    </script>
@endsection