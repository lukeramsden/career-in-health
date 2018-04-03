@extends('layouts.app')

@section('content')
    <div class="container search-container-parent p-0 mb-5">
        <div class="row no-side-padding">
            <div class="col-md-4">
                <div class="search-container has-top-bar">
                    <div class="row first-row">
                        <div class="col-md-12 form-section">
                            <h1>Find Your Dream Job</h1>
                        </div>
                    </div>
        
                    <div class="row">
                        <div class="col-md-12 form-section">
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
        
                                    <div class="col-md-12 mb-5">
                                        <div class="form-group">
                                            <label>Minimum Salary</label>
                                            <div id="salary-slider"></div>
                                            <input type="hidden" name="min_salary" id="min-salary-input" value="{{ old('min_salary', Request::get('min_salary', 0)) }}">
                                            <input type="hidden" name="max_salary" id="max-salary-input" value="{{ old('max_salary', Request::get('max_salary', 150000)) }}">
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
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                @isset($results)
                    @foreach($results as $advert)
                        <div class="form-container has-top-bar mb-0
                            @if (!$loop->first)
                        mt-4
                            @endif">
                            <div class="row first-row">
                                <div class="col-12 mt-4 px-4">
                                    <div class="media">
                                        <div class="media-body">
                                            <h5 class="py-0 my-0 pb-1"><a href="{{ route('company', ['company' => $advert->company]) }}">{{ $advert->company->name }}</a></h5>
                                            <h3 class="py-0 my-0 pb-1">{{ $advert->jobType->name }}</h3>
                                            <h5 class="py-0 my-0 pb-1">{{ $advert->getSetting() }}</h5>
                                            <h6 class="py-0 my-0 pb-1">{{ $advert->title }}</h6>
                                            <div class="d-block my-3">
                                                <div class="d-inline-block">
                                                    <p><span class="badge badge-secondary badge-pill p-2 px-3">{{ $advert->getType() }}</span></p>
                                                </div>
                                                <div class="d-inline-block ml-4">
                                                    <p><span class="oi oi-map-marker mr-3"></span>{{ $advert->address->location->name }}</p>
                                                </div>
                                                <div class="d-inline-block ml-4">
                                                    <p>
                                                        @money($advert->min_salary * 100, 'GBP') - @money($advert->max_salary * 100, 'GBP')
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            @if($advert->company->picture() != null)
                                                <img height="100" src="{{ $advert->company->picture() }}" alt="Company profile picture">
                                            @endif
                                            <h5><a href="{{ route('advert.show', ['advert' => $advert]) }}" class="btn btn-link p-3 px-5 mt-2">View</a></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="mt-4">
                        {{-- TODO: customize this to match search form --}}
                        {!! $results->appends(Request::capture()->except('page'))->render("pagination::search") !!}
                    </div>
                @endisset
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
                {{ old('min_salary', Request::get('min_salary', 0)) }},
                {{ old('max_salary', Request::get('max_salary', 150000)) }}
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
            $('#min-salary-input').val(moneyFormatter.from(values[0]));
            $('#max-salary-input').val(moneyFormatter.from(values[1]));
        });
        
        $('#radius-slider').find('div.noUi-value:nth-child(47)').html('50+');
        $('#salary-slider').find('div.noUi-value:nth-child(30)').html('£150,000+');
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