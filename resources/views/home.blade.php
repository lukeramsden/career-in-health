@extends('layouts.frontend')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 mt-5">
                <form>
                    <div class="form-row">
                        <div class="col-12 col-md-5">
                            <label for="what-input" class="h1">what</label>
                            <input id="what-input" class="form-control" list="what-list">
                            <datalist id="what-list">
                                @foreach(\App\JobRole::all() as $job)
                                    <option>{{ $job->name }}</option>
                                @endforeach
                            </datalist>
                        </div>
                        <div class="col-12 col-md-5">
                            <div class="form-group">
                                <label for="where-input" class="h1">where</label>
                                <input id="where-input" class="form-control" list="where-list">
                                <datalist id="where-list">
                                    @foreach (\App\Location::getAllLocations() as $loc)
                                        <option>{{ $loc->name }}</option>
                                    @endforeach
                                </datalist>
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
    <style>
        .awesomplete {
            display: block;
        }
    </style>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/awesomplete/1.1.2/awesomplete.min.js"></script>
    
    <script>
        $(function () {
            let $what = $('#what-input');
            const whatDropdown = new Awesomplete('#what-input');

            $what.on('awesomplete-selectcomplete', function (event) {
                $what[0].dispatchEvent(new Event('input', {'bubbles': true}));
                whatDropdown.close();
            });

            let $where = $('#where-input');
            const whereDropdown = new Awesomplete('#where-input');

            $where.on('awesomplete-selectcomplete', function (event) {
                $where[0].dispatchEvent(new Event('input', {'bubbles': true}));
                whereDropdown.close();
            });
        });
    </script>
@endsection