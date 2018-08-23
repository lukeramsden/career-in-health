@extends('layouts.frontend')
@section('content')
    <div class="container mt-4">
        <div class="row">
            @if(null !== ($advert = App\Repositories\AdvertRepository::homepage()))
                <div class="col-12">
                    <a class="advert" href="{{ route('tracking.advert.homepage.click', [$advert]) }}" target="_blank">
                        <div class="advert-homepage">
                            <img src="{{ Storage::url($advert->image_path) }}">
                        </div>
                    </a>
                </div>
            @endif
            <div class="col-12">
                <form action="{{ route('search') }}">
                    <div class="form-row">
                        <div class="col-12 col-md-5">
                            <label for="what-input" class="h1">what</label>
                            <input id="what-input" name="what" class="form-control" list="what-list" required>
                            <datalist id="what-list">
                                @foreach(\App\JobRole::all() as $job)
                                    <option>{{ $job->name }}</option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="col-12 col-md-5">
                            <div class="form-group">
                                <label for="where-input" class="h1">where</label>
                                <select id="where-input" name="where" class="custom-select" required>
                                    <option selected disabled></option>
                                    @foreach (\App\Location::getAllLocations() as $loc)
                                        <option value='{{ $loc->id }}'>{{ $loc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-2 d-flex align-items-end">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Find Jobs</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12">
                <p class="text-center">
                    <span class="text-action">{{ App\JobListing::remember(60)->whereDate('created_at', '>=', Carbon\Carbon::today()->subDays(7))->count() }}</span>
                    new jobs in the last 7 days.
                </p>
            </div>
        </div>
    </div>
@endsection
@section('stylesheet')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.2/awesomplete.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" />
   
    <style>
        .awesomplete {
            display: block;
        }
    </style>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.2/awesomplete.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
    
    <script>
        $(function () {
            let $what = $('#what-input');
            const whatDropdown = new Awesomplete('#what-input');

            $what.on('awesomplete-selectcomplete', function (event) {
                $what[0].dispatchEvent(new Event('input', {'bubbles': true}));
                whatDropdown.close();
            });

            let $where = $('#where-input');
            $where.select2({
                dropdownAutoWidth: true,
                width: '100%'
            });
        });
    </script>
@endsection