@extends('layouts.app')
@section('content')

    <div class="container-fluid">
        <div class='create-advert-container form-container has-top-bar my-5'>
            <div class='row first-row my-3'>
                <div class='col-md-12 form-section'>
                    <h1>All Adverts</h1>
                </div>
            </div>

            <div class='row'>
                <div class='col-md-12 form-section'>
                    <table id="table" class='table' data-responsive="true" data-order-multi="true">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Applications</th>
                                <th data-orderable="false">Buttons</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($adverts as $advert)
                                <tr>
                                    <td>
                                        <p>{{ $advert->title }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $advert->applications()->count() }}</p>
                                    </td>
                                    <td>
                                        <a href='{{ route('advert.show.internal', ['advert' => $advert]) }}' class='btn btn-outline-primary btn-sm'>View</a>
                                        <a href='{{ $advert->linkEdit() }}' class='btn btn-action btn-sm'>Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>

@endsection
@section('stylesheet')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.1/css/responsive.bootstrap4.min.css">
@endsection
@section('script')
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.1/js/responsive.bootstrap4.min.js"></script>
    <script>
        $('#table').DataTable({
            responsive: true,
            orderMulti: true,
        });
    </script>
@endsection
