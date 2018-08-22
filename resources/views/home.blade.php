@extends('layouts.frontend')
@section('content')
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12">
                <form action="{{ route('search') }}">
                    <div class="form-row">
                        <div class="col">
                            <label for="what-input" class="h1">what</label>
                            <input id="what-input" class="form-control">
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="where-input" class="h1">where</label>
                                <input id="where-input" class="form-control">
                            </div>
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-primary rounded">Find Jobs</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-12">
                <p
                class="text-center">{{ App\JobListing::remember(60)->whereDate('created_at', '>=', Carbon\Carbon::today()->subDays(7))->count() }}
                    new jobs in the last 7 days.</p>
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
            const whatDropdown = new Awesomplete('#what-input', {
                list: [
                    @foreach(\App\JobRole::all() as $job)
                        ['{{ $job->name }}', {{ $job->id }}],
                    @endforeach
                ],
            });

            $what.on('awesomplete-selectcomplete', function (event) {
                $what[0].dispatchEvent(new Event('input', {'bubbles': true}));
                whatDropdown.close();
            });

            let $where = $('#where-input');
            const whereDropdown = new Awesomplete('#where-input', {
                list: [
                    @foreach (\App\Location::getAllLocations() as $loc)
                        ['{{ $loc->name }}', {{ $loc->id }}],
                    @endforeach
                ],
            });

            $where.on('awesomplete-selectcomplete', function (event) {
                $where[0].dispatchEvent(new Event('input', {'bubbles': true}));
                whereDropdown.close();
            });
        });
    </script>
@endsection