@extends('layouts.app')

@section('content')
    <div class="container-fluid">
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
                            {{--<div class="col-md-12">--}}
                                {{--<div class="form-group">--}}
                                    {{--<label>Title</label>--}}
                                    {{--<input type="text" name="title" placeholder="Title" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"--}}
                                           {{--value="{{ old('title', Request::get('title')) }}">--}}
                                       {{----}}
                                    {{--@if ($errors->has('title'))--}}
                                        {{--<div class="invalid-feedback">{{ $errors->first('title') }}</div>--}}
                                    {{--@endif--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Town</label>
        
                                    <select name='town' class="custom-select location-search {{ $errors->has('location_id') ? 'is-invalid' : '' }}">
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
                            <div class="col-12">
                                <button type="submit" class='btn btn-action btn-block'>Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-8 results-section">
                    @foreach($results as $advert)
                        <p><b>{{ $advert->address->location->name }}</b></p>
                        <p>{{ $advert->getDistanceToLocation($town) }}</p>
                        <hr>
                    @endforeach
                    
{{--                    {{ $results->render("pagination::bootstrap-4") }}--}}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(".location-search").select2({});
    </script>
@endsection