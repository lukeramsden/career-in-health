@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <table class="table table-striped table-hover" id="jobListings"
               data-order="[[ 1, &quot;desc&quot; ]]">
            <thead>
            <tr>
                <th scope="col" data-searchable="false" data-orderable="false" data-visible="false">ID</th>
                <th scope="col" data-searchable="false" data-orderable="true">Last Updated</th>
                <th scope="col" data-searchable="true" data-orderable="true">Title</th>
                <th scope="col" data-searchable="true" data-orderable="true">Address</th>
                <th scope="col" data-searchable="true" data-orderable="true">Status</th>
                <th scope="col" data-searchable="false" data-orderable="false" data-width="100px"></th>
            </tr>
            </thead>
            <tbody>
            @foreach($jobListings as $jobListing)
                <tr>
                    <td>
                        <p>{{ $jobListing->id }}</p>
                    </td>
                    <td data-order="{{ $jobListing->last_edited->timestamp }}">
                        <p>{{ $jobListing->last_edited->diffForHumans() }}</p>
                    </td>
                    <td data-search="{{ $jobListing->title }}">
                        <p>{{ $jobListing->title }}</p>
                    </td>
                    <td data-search="{{ optional($jobListing->address)->name }}">
                        <p>{{ optional($jobListing->address)->name }}</p>
                    </td>
                    @set('status',
                    $jobListing->isOpen() ?
                    $jobListing->isPublished() ? 'Published' : 'Draft'
                    : 'Closed')
                    <td data-search="{{ $status }}" data-order="{{ $jobListing->status }}">
                        @if($status === 'Closed')
                            <p class="w-100 badge badge-danger p-2 px-3">
                                <span class="oi oi-ban"></span>
                                Closed
                            </p>
                        @elseif($status === 'Published')
                            <p class="w-100 badge badge-primary p-2 px-3">
                                <span class="oi oi-check"></span>
                                Published
                            </p>
                        @elseif($status === 'Draft')
                            <p class="w-100 badge badge-secondary p-2 px-3">
                                <span class="oi oi-bookmark"></span>
                                Draft
                            </p>
                        @else
                            <p>{{ $status }}</p>
                        @endif
                    </td>
                    @unset($status)
                    <td>
                        @if($jobListing->isPublished())
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('job-listing.show', [$jobListing]) }}" class="btn btn-link">View</a>
                                <a href="{{ route('job-listing.edit', [$jobListing]) }}" class="btn btn-link">Edit</a>
                            </div>
                        @else
                            <a href="{{ route('job-listing.edit', [$jobListing]) }}"
                               class="btn btn-sm btn-block btn-link">Edit</a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('stylesheet')
    <link rel="stylesheet" type="text/css"
          href="https://cdn.datatables.net/v/dt/dt-1.10.16/b-1.5.1/r-2.2.1/datatables.min.css"/>
@endsection
@section('script')
    <script type="text/javascript"
            src="https://cdn.datatables.net/v/dt/dt-1.10.16/b-1.5.1/r-2.2.1/datatables.min.js"></script>
    
    <script>
        $(function () {
            $('#jobListings').DataTable({
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
