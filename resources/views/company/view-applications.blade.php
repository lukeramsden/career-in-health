@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <table class="table table-striped table-hover" id="applications"
        data-order="[[ 4, &quot;desc&quot; ]]">
            <thead>
                <tr>
                    <th scope="col" data-searchable="false" data-orderable="false" data-visible="false">ID</th>
                    <th scope="col" data-searchable="true" data-orderable="true">Employee</th>
                    <th scope="col" data-searchable="true" data-orderable="true">Title</th>
                    <th scope="col" data-searchable="true" data-orderable="true">Position</th>
                    <th scope="col" data-searchable="true" data-orderable="true">Date Applied</th>
                    <th scope="col" data-searchable="true" data-orderable="true">Status</th>
                    <th scope="col" data-searchable="false" data-orderable="false" data-width="50px"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $application)
                    <tr>
                        <td></td>
                        <td data-search="{{ $application->employee->full_name }}">
                            <p><a href="{{ route('employee.show', [$application->employee]) }}">{{ $application->employee->full_name }}</a></p>
                        </td>
                        <td data-search="{{ $application->advert->title }}">
                            <p>{{ str_limit($application->advert->title, 80) }}</p>
                        </td>
                        <td data-search="{{ $application->advert->jobRole->name }}">
                            <p>{{ $application->advert->jobRole->name }}</p>
                        </td>
                        <td data-order="{{ $application->created_at->timestamp }}">
                            <p>{{ $application->created_at->toFormattedDateString() }}</p>
                        </td>
                        <td data-search="{{ \App\AdvertApplication::$statuses[$application->status ?? 0] }}">
                            <p>{{ \App\AdvertApplication::$statuses[$application->status ?? 0] }}</p>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('advert.show.applications', [$application->advert, 'highlight' => $application->id]) }}" class="btn btn-link">View</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('stylesheet')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.16/b-1.5.1/r-2.2.1/datatables.min.css"/>
@endsection
@section('script')
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/b-1.5.1/r-2.2.1/datatables.min.js"></script>
    
    <script>
        $(function() {
            $('#applications').DataTable({
                responsive: true,
                stateSave: true,
                pageLength: 15,
                lengthMenu: [15, 15 * 2, 15 * 3, 15 * 4, 15 * 5],
                stateDuration: 60 * 5, // 5 minutes
                language: {
                    paginate: {
                        previous: "&lt;",
                        next: "&gt;",
                    },
                },
            });
        });
    </script>
@endsection
