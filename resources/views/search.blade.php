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
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Town</label>
        
                                    <select name='town' class="custom-select location-search {{ $errors->has('location_id') ? 'is-invalid' : '' }}" required>
                                        <option {{ old('town', Request::get('town')) != null ? '' : 'selected' }} disabled>Town</option>
                                        @php($address = new \App\Models\Address())
                                        @foreach ($address->getAllLocations() as $loc)
                                            <option {{ $loc->id == old('town', Request::get('town')) ? 'selected' : '' }} value='{{ $loc->id }}'>{{ $loc->name }}</option>
                                        @endforeach
                                    </select>
        
                                    @if ($errors->has('town'))
                                        <div class="invalid-feedback">{{ $errors->first('town') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Radius (miles)</label>
                                    <div id="radius-slider"></div>
                                    <input type="hidden" name="radius" id="radius-input" value="{{ old('radius', Request::get('radius', 50)) }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Jobs</label>
                                    
                                    <select name="job_types[]" class="form-control {{ $errors->has('job_types') ? 'is-invalid' : '' }}" multiple title="Jobs" size="15">
                                        @foreach(\App\Models\JobType::all() as $job)
                                            <option {{ collect(old('job_types', Request::get('job_types')))->contains($job->id) ? 'selected':'' }} value="{{ $job->id }}">{{ $job->name }}</option>
                                        @endforeach
                                    </select>
        
                                    @if ($errors->has('job_types'))
                                        <div class="invalid-feedback">{{ $errors->first('job_types') }}</div>
                                    @endif
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
                                    <p class="card-text">{{ str_limit($advert->description, 60) }}</p>
                                    <a href="#" class="card-link">View</a>
                                    <a href="#" class="card-link">See On Map</a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/11.0.3/nouislider.min.js" integrity="sha256-oj880/QiddQHkKfC9iOmsu+Hu5V4KCHfS3RY3RaZdZc=" crossorigin="anonymous"></script>
    
    <script>
        $(".location-search").select2({});
        var radiusSlider = document.getElementById('radius-slider');
        
        noUiSlider.create(radiusSlider, {
            start: {{ old('radius', Request::get('radius', 50)) }},
            step: 5,
            tooltips: true,
            range: {
                'min': 10,
                'max': 500
            }
        });
        
        radiusSlider.noUiSlider.on('change', function() {
        	$('#radius-input').val(parseInt(radiusSlider.noUiSlider.get()));
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