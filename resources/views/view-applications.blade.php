@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="has-top-bar form-container mt-5">
            <div class="row first-row">
                <div class="col-md-12 form-section">
                    <h1>Your Applications</h1>
                </div>
            </div>
    
            <div class='row'>
                <div class='col-md-12 form-section'>
                    <table id="table" class='table table-striped' data-responsive="true" data-order-multi="true">
                        <thead class="thead-dark">
                            <tr>
                                <th>Company</th>
                                <th>Job</th>
                                <th>Date Applied</th>
                                <th>Job Status</th>
                                <th>Cover Letter</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($applications as $application)
                                <tr>
                                    <td>
                                        <p><a href="{{ route('company', ['company' => $application->advert->company]) }}">{{ $application->advert->company->name }}</a></p>
                                    </td>
                                    <td>
                                        <p>{{ $application->advert->jobType->name }} at {{ $application->advert->company->name }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $application->created_at->toFormattedDateString() }}</p>
                                    </td>
                                    <td>
                                        <p>{{ \App\Models\AdvertApplication::$statuses[$application->status or 0] }}</p>
                                    </td>
                                    <td>
                                        <p>{{ $application->custom_cover_letter or 'No cover letter' }}</p>
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
    <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.8.4/moment.min.js"></script>
    <script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.2.1/js/dataTables.responsive.min.js"></script>
    <script src="//cdn.datatables.net/responsive/2.2.1/js/responsive.bootstrap4.min.js"></script>
    <script src="//cdn.datatables.net/plug-ins/1.10.16/sorting/datetime-moment.js"></script>
    <script>
        $.fn.dataTable.moment('ll');

        $('#table').DataTable({
            responsive: true,
            orderMulti: true,
            order: [[ 2, "desc" ], [ 3, "desc" ]]
        });
    </script>
@endsection
