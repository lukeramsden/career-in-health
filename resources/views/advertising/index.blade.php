@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <table class="table table-striped table-hover" id="adverts"
               data-order="[[ 1, &quot;desc&quot; ]]">
            <thead>
                <tr>
                    <th scope="col" data-searchable="false" data-orderable="false" data-visible="false">ID</th>
                    <th scope="col" data-searchable="true"  data-orderable="true">Title</th>
                    <th scope="col" data-searchable="true"  data-orderable="true">Status</th>
                    <th scope="col" data-searchable="false" data-orderable="false" data-width="100px"></th>
                </tr>
            </thead>
            <tbody>
            {{-- TODO: more fields --}}
                @foreach($adverts as $advert)
                    <tr>
                        <td>
                            <p>{{ $advert->id }}</p>
                        </td>
                        <td data-search="{{ $advert->title }}">
                            <p>{{ $advert->title }}</p>
                        </td>
                        <td data-search="{{ $advert->active ? 'Active' : 'Inactive' }}" data-order="{{ $advert->status }}">
                            <p>{{ $advert->active ? 'Active' : 'Inactive' }}</p>
                        </td>
                        <td>
                            <a href="{{ route('advertising.edit', [$advert]) }}" class="btn btn-sm btn-block btn-link">Edit</a>
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
