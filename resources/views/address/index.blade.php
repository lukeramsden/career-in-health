@extends('layouts.app', ['title' => 'Your Addresses'])
@section('content')
    <div class="container-fluid">
        <table class="table table-striped table-hover" id="addresses"
               data-order="[[ 0, &quot;asc&quot; ]]">
            <thead>
                <tr>
                    <th scope="col" data-searchable="false" data-orderable="false" data-visible="false">ID</th>
                    <th scope="col" data-searchable="false" data-orderable="true">Name</th>

                    <th scope="col" data-searchable="false" data-orderable="false" data-width="100px"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($addresses as $address)
                    <tr>
                        <td>
                            <p>{{ $address->id }}</p>
                        </td>
                        <td data-search="{{ $address->name }}">
                            <a href="{{ route('address.show', $address) }}">
                                <p>{{ $address->name }}</p>
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('address.edit', [$address]) }}" class="btn btn-sm btn-block btn-link">Edit</a>
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
            $('#addresses').DataTable({
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
