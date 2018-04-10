@extends('layouts.app')
@section('content')
    <div class="container-fluid mt-5">
        <table class="table table-striped table-hover" id="applications">
            <thead>
                <tr>
                    <th scope="col">Company</th>
                    <th scope="col">Position</th>
                    <th scope="col">Date Applied</th>
                    <th scope="col">Status</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $application)
                    <tr>
                        <td>
                            <p><a href="{{ route('company.show', [$application->advert->company]) }}">{{ $application->advert->company->name }}</a></p>
                        </td>
                        <td>
                            <p>{{ $application->advert->jobType->name }}</p>
                        </td>
                        <td>
                            <p>{{ $application->created_at->toFormattedDateString() }}</p>
                        </td>
                        <td>
                            <p>{{ \App\AdvertApplication::$statuses[$application->status or 0] }}</p>
                        </td>
                        <td>
                            <p><a href="{{ route('advert.show', [$application->advert]) }}">View Advert</a></p>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {!! $applications->appends(Request::capture()->except('page'))->render('vendor.pagination') !!}
    </div>
@endsection