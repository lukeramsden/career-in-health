@extends('layouts.app')
@section('content')
    <div id="vue-job-listings-table">
        <job-listings-table></job-listings-table>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/job-listings-table-component.js') }}"></script>
@endsection