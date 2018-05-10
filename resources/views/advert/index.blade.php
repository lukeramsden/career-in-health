@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        {{-- TODO: add more fields to this --}}
        <table class="table table-striped table-hover" id="adverts"
               data-order="[[ 1, &quot;desc&quot; ]]">
            <thead>
                <tr>
                    <th scope="col" data-searchable="false" data-orderable="false" data-visible="false">ID</th>
                    <th scope="col" data-searchable="false" data-orderable="true">Last Updated</th>
                    <th scope="col" data-searchable="true"  data-orderable="true">Title</th>
                    <th scope="col" data-searchable="true"  data-orderable="true">Address</th>
                    <th scope="col" data-searchable="true"  data-orderable="true">Status</th>
                    <th scope="col" data-searchable="false" data-orderable="false" data-width="100px"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($adverts as $advert)
                    <tr>
                        <td>
                            <p>{{ $advert->id }}</p>
                        </td>
                        <td data-order="{{ $advert->updated_at->timestamp }}">
                            <p>{{ $advert->updated_at->diffForHumans() }}</p>
                        </td>
                        <td data-search="{{ $advert->title }}">
                            <p>{{ $advert->title }}</p>
                        </td>
                        <td data-search="{{ optional($advert->address)->name }}">
                            <p>{{ optional($advert->address)->name }}</p>
                        </td>
                        <td data-search="{{ $advert->isPublished() ? 'Published' : 'Draft' }}" data-order="{{ $advert->status }}">
                            <p>{{ $advert->isPublished() ? 'Published' : 'Draft' }}</p>
                        </td>
                        <td>
                            @if($advert->isPublished())
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('advert.show', [$advert]) }}" class="btn btn-link">View</a>
                                    <a href="{{ route('advert.edit', [$advert]) }}" class="btn btn-link">Edit</a>
                                </div>
                            @else
                                <a href="{{ route('advert.edit', [$advert]) }}" class="btn btn-sm btn-block btn-link">Edit</a>
                            @endif
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
            $('#adverts').DataTable({
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
