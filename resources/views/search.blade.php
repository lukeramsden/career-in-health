@extends('layouts.app')
@section('content')
    @if(isset($results) || $isAdvanced)
        <div class="container-fluid p-0 m-0">
            <div class="row" id="search-row">
                <div class="col-12 col-md-5 col-lg-4 order-md-last" id="search-form-parent">
                    <div id="search-form">
                        <form method="get">
                            <div class="form-group">
                                <label for="search-town-control">Location</label>
                                <select id="search-town-control" name="town" class="custom-select town-control"
                                        required>
                                    <option
                                    {{ old('town', Request::get('town')) != null ? '' : 'selected' }} disabled></option>
                                    @foreach (\App\Location::getAllLocations() as $loc)
                                        <option
                                        {{ $loc->id == old('town', Request::get('town')) ? 'selected' : '' }} value='{{ $loc->id }}'>{{ $loc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="search-job-role-control">Job Roles</label>
                                <select id="search-job-role-control" name="job_roles[]"
                                        class="custom-select job-role-control" multiple size="1">
                                    @foreach(\App\JobRole::all() as $job)
                                        <option
                                        {{ collect(old('job_roles', Request::get('job_roles')))->contains($job->id) ? 'selected':'' }} value="{{ $job->id }}">{{ $job->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="form-group form-group-dropdown">
                                <label>Radius (miles)</label>
                                <div id="radius-slider"></div>
                                <input type="hidden" name="radius" id="radius-input"
                                       value="{{ old('radius', Request::get('radius', 50)) }}">
                            </div>
                            
                            <div class="form-group form-group-dropdown">
                                <label>Minimum/Maximum Salary</label>
                                <div id="salary-slider"></div>
                                <input type="hidden" name="min_salary" id="min-salary-input"
                                       value="{{ old('min_salary', Request::get('min_salary', 0)) }}">
                                <input type="hidden" name="max_salary" id="max-salary-input"
                                       value="{{ old('max_salary', Request::get('max_salary', 150000)) }}">
                            </div>
                            
                            <div class="form-group">
                                <label>Settings</label>
                                
                                @foreach(\App\JobListing::$settings as $id => $setting)
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input"
                                               {{ collect(old('setting_filter', Request::get('setting_filter')))->contains($id) ? 'checked':'' }} name="setting_filter[]"
                                               value="{{ $id }}" id="setting-check{{ $id }}">
                                        <label class="custom-control-label"
                                               for="setting-check{{ $id }}">{{ $setting }}</label>
                                    </div>
                                @endforeach
                            
                            </div>
                            
                            <div class="form-group">
                                <label>Types</label>
                                
                                @foreach(\App\JobListing::$types as $id => $type)
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input"
                                               {{ collect(old('type_filter', Request::get('type_filter')))->contains($id) ? 'checked':'' }} name="type_filter[]"
                                               value="{{ $id }}" id="type-check{{ $id }}">
                                        <label class="custom-control-label"
                                               for="type-check{{ $id }}">{{ $type }}</label>
                                    </div>
                                @endforeach
                            
                            </div>
                            
                            <button type="submit" class="btn btn-block btn-action">Search</button>
                        </form>
                    </div>
                </div>
                {{-- RESULTS --}}
                <div class="col-12 col-md-7 col-lg-8" id="search-results-parent">
                    <div id="search-results" style="">
                        @isset($results)
                            {!! $results->appends(Request::capture()->except('page'))->render("vendor.pagination") !!}
                            <div class="text-center">
                                <p class="font-italic">Found {{ $results->total() }}
                                    matching {{ str_plural('job listing', $results->total()) }}</p>
                            </div>
                            @foreach($results as $jobListing)
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
                                                <p><span
                                                    class="oi oi-map-marker mr-3"></span>{{ $jobListing->address->location->name }}
                                                    (<b>{{ number_format((float)$jobListing->getDistanceToLocation($town), 0, '.', '') }}</b>
                                                    miles away)</p>
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
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            {!! $results->appends(Request::capture()->except('page'))->render('vendor.pagination') !!}
                        @endisset
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="container h-100">
            <div class="row h-100 justify-content-center align-items-center">
                <div class="col-12">
                    <div class="card card-custom mx-auto" style="max-width:45rem;">
                        <div class="card-body">
                            <form method="get">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <h6 class="text-muted">I'm a</h6>
                                    <div id="first-search-job-role">
                                        <select id="first-search-job-role-control" name="job_roles[]"
                                                class="custom-select town-control" multiple size="1">
                                            @foreach(\App\JobRole::all() as $job)
                                                <option
                                                {{ collect(old('job_roles', Request::get('job_roles')))->contains($job->id) ? 'selected':'' }} value="{{ $job->id }}">{{ $job->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <h6 class="text-muted my-2">in</h6>
                                    <div id="first-search-town">
                                        <select id="first-search-town-control" name="town"
                                                class="custom-select job-role-control" required>
                                            <option
                                            {{ old('town', Request::get('town')) != null ? '' : 'selected' }} disabled></option>
                                            @foreach (\App\Location::getAllLocations() as $loc)
                                                <option
                                                {{ $loc->id == old('town', Request::get('town')) ? 'selected' : '' }} value='{{ $loc->id }}'>{{ $loc->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                <button class="btn btn-block btn-action my-3" type="submit">Search</button>
                                <a href="{{ route('search', ['advanced' => true]) }}"
                                   class="btn btn-block btn sm btn-link my-2">Advanced</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('stylesheet')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet"/>
    
    <style>
        .noUi-tooltip {
            display: none;
        }
        
        .noUi-active .noUi-tooltip {
            display: block;
            
        }
        
        .noUi-handle {
            outline: none;
            border-radius: 0;
        }
        
        .noUi-value-sub {
            color: #999;
            line-height: 1.8;
        }
    </style>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wnumb/1.1.0/wNumb.min.js"
            integrity="sha256-HT7c4lBipI1Hkl/uvUrU1HQx4WF3oQnSafPjgR9Cn8A=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/11.0.3/nouislider.min.js"
            integrity="sha256-oj880/QiddQHkKfC9iOmsu+Hu5V4KCHfS3RY3RaZdZc=" crossorigin="anonymous"></script>
    
    <script>
        $(function () {
            $('.town-control').select2({
                dropdownAutoWidth: true,
                width: '100%'
            });
            $('.job-role-control').select2({
                dropdownAutoWidth: true,
                width: '100%'
            });

            var radiusSlider = document.getElementById('radius-slider');

            noUiSlider.create(radiusSlider, {
                start: [{{ old('radius', Request::get('radius', 50)) }}],
                step: 5,
                tooltips: true,
                range: {
                    'min': 5,
                    'max': 50
                },
                pips: {
                    mode: 'steps',
                    density: 3
                },
                format: wNumb({
                    decimals: 0
                })
            });

            radiusSlider.noUiSlider.on('change', function () {
                $('#radius-input').val(parseInt(radiusSlider.noUiSlider.get()));
            });

            var salarySlider = document.getElementById('salary-slider');
            var moneyFormatter = wNumb({
                decimals: 0,
                thousand: ',',
                prefix: '£'
            });

            noUiSlider.create(salarySlider, {
                start: [
                    {{ old('min_salary', Request::get('min_salary', 0)) }},
                    {{ old('max_salary', Request::get('max_salary', 150000)) }}
                ],
                step: 500,
                tooltips: true,
                margin: 2000,
                range: {
                    'min': 0,
                    'max': 150000
                },
                format: wNumb({
                    decimals: 0,
                    thousand: ',',
                    prefix: '£'
                }),
                pips: {
                    mode: 'positions',
                    values: [0, 25, 50, 75, 100],
                    density: 4,
                    format: moneyFormatter
                }
            });

            salarySlider.noUiSlider.on('change', function (values, handle) {
                $('#min-salary-input').val(moneyFormatter.from(values[0]));
                $('#max-salary-input').val(moneyFormatter.from(values[1]));
            });

            $('#radius-slider').find('div.noUi-value:nth-child(47)').html('50+');
            $('#salary-slider').find('div.noUi-value:nth-child(30)').html('£150,000+');
        });
    </script>
@endsection
