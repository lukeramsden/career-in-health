@extends('layouts.app')

@section('content')
    <div class="container search-container-parent">
        <div class="search-container has-top-bar">
            <div class="row first-row">
                <div class="col-md-4 form-section">
                    <h1>Find Your Dream Job</h1>
                </div>
                <div class="col-md-8 results-section">
                    <h1>Results</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 form-section">
                    <form method="get">
                        {{-- TODO: ERROR HANDLING --}}
                        {{-- TODO: Turn multiple select in to box of checkboxes --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Town</label>
        
                                    <select name='town' class="custom-select location-search" required>
                                        <option {{ old('town', Request::get('town')) != null ? '' : 'selected' }} disabled>Town</option>
                                        @foreach (\App\Models\Location::getAllLocations() as $loc)
                                            <option {{ $loc->id == old('town', Request::get('town')) ? 'selected' : '' }} value='{{ $loc->id }}'>{{ $loc->name }}</option>
                                        @endforeach
                                    </select>
        
                                </div>
                            </div>

                            {{-- 
                                TODO
                                Please change Radius slider to be 5 - 50 in steps of 5.
                                    also on 50 if you could get the label to say 50+ that would be good

                                This is to make it easier to both design and use.
                            --}}

                            <div class="col-md-12 mb-5">
                                <div class="form-group">
                                    <label>Radius (miles)</label>
                                    <div id="radius-slider"></div>
                                    <input type="hidden" name="radius" id="radius-input" value="{{ old('radius', Request::get('radius', 50)) }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Job Types</label>
                                    
                                    <select name="job_types[]" id="job_types" class="form-control" multiple title="Jobs" size="15">
                                        @foreach(\App\Models\JobType::all() as $job)
                                            <option {{ collect(old('job_types', Request::get('job_types')))->contains($job->id) ? 'selected':'' }} value="{{ $job->id }}">{{ $job->name }}</option>
                                        @endforeach
                                    </select>
        
                                    <button type="button" style="height: auto;" onclick="$('#job_types').val([]);" class="btn btn-block btn-danger">Clear Selected Items</button>
                                </div>
                            </div>

                            {{-- 
                                TODO
                                need a max salary
                                that should be always greater than min
                                default should be the max vale of the max salary slider
                                the last value might want to be $150,000+?
                            --}}
                            <div class="col-md-12 mb-5">
                                <div class="form-group">
                                    <label>Minimum Salary</label>
                                    <div id="salary-slider"></div>
                                    <input type="hidden" name="min_salary" id="min-salary-input" value="{{ old('min_salary', Request::get('min_salary', 0)) }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Settings</label>
                                    
                                    @foreach(\App\Models\Advert::$settings as $id => $setting)
                                        {{-- TODO: Make backgrounds darker when unchecked--}}
                                        <div class="custom-control custom-checkbox">
                                          <input type="checkbox" class="custom-control-input" {{ collect(old('setting_filter', Request::get('setting_filter')))->contains($id) ? 'checked':'' }} name="setting_filter[]" value="{{ $id }}" id="setting-check{{ $id }}">
                                          <label class="custom-control-label" for="setting-check{{ $id }}">{{ $setting }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Types</label>
                                    
                                    @foreach(\App\Models\Advert::$types as $id => $type)
                                        {{-- TODO: Make backgrounds darker when unchecked--}}
                                        <div class="custom-control custom-checkbox">
                                          <input type="checkbox" class="custom-control-input" {{ collect(old('type_filter', Request::get('type_filter')))->contains($id) ? 'checked':'' }} name="type_filter[]" value="{{ $id }}" id="type-check{{ $id }}">
                                          <label class="custom-control-label" for="type-check{{ $id }}">{{ $type }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class='btn btn-action btn-block'>Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-8 results-section no-side-padding">
                    @isset($results)
                        {!! $results->appends(Request::capture()->except('page'))->render("pagination::bootstrap-4") !!}
                        @foreach($results as $advert)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h4 class="card-title">{{ $advert->title }}</h4>
                                    <h5 class="card-subtitle mb-2 text-muted">{{ $advert->jobType->name }} at {{ $advert->company->name }}</h5>
                                    <h6 class="card-subtitle mb-2"><b>{{ number_format((float)$advert->getDistanceToLocation($town), 0, '.', '') }}</b> miles away</h6>
                                    <h6 class="card-subtitle mb-2 text-muted">{{ $advert->address->location->name }}</h6>
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        @money($advert->min_salary * 100, 'GBP') - @money($advert->max_salary * 100, 'GBP')
                                    </h6>
                                    <h6 class="card-subtitle mb-2 text-muted">{{ $advert->getSetting() }}</h6>
                                    <h6 class="card-subtitle mb-2 text-muted">{{ $advert->getType() }}</h6>
                                    <p class="card-text">{{ str_limit($advert->description, 60) }}</p>
                                    <a href="{{ route('advert.show', ['advert' => $advert]) }}" class="card-link">View</a>
                                    <a href="{{ $advert->address->location->mapUrl() }}" target="_blank" class="card-link">See On Map</a>
                                </div>
                            </div>
                        @endforeach
                        {!! $results->appends(Request::capture()->except('page'))->render("pagination::bootstrap-4") !!}
                    @endisset
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wnumb/1.1.0/wNumb.min.js" integrity="sha256-HT7c4lBipI1Hkl/uvUrU1HQx4WF3oQnSafPjgR9Cn8A=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/11.0.3/nouislider.min.js" integrity="sha256-oj880/QiddQHkKfC9iOmsu+Hu5V4KCHfS3RY3RaZdZc=" crossorigin="anonymous"></script>
    
    <script>
        $(".location-search").select2({});
        
        var radiusSlider = document.getElementById('radius-slider');
        
        noUiSlider.create(radiusSlider, {
            start: [{{ old('radius', Request::get('radius', 50)) }}],
            step: 5,
            tooltips: true,
            range: {
                'min': 10,
                'max': 500
            },
            pips: {
           		mode: 'values',
           		values: [10,100,200,300,400,500],
           		density: 4
           	},
            format: wNumb({
           		decimals: 0
           	})
        });
        
        radiusSlider.noUiSlider.on('change', function() {
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
                {{ old('min_salary', Request::get('min_salary', 0)) }}
            ],
            step: 500,
            tooltips: true,
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
                values: [0,25,50,75,100],
                density: 4,
                format: moneyFormatter
            }
        });
        
        salarySlider.noUiSlider.on('change', function(values, handle) {
            $('#min-salary-input').val(moneyFormatter.from(values[handle]));
        });
    </script>

    <style>
        .noUi-tooltip {
            display: none;
        }
        
        .noUi-active .noUi-tooltip {
            display: block;
    
        }
        
        .noUi-handle {
            outline: none;
        }
    </style>
@endsection