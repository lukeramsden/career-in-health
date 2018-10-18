@extends('layouts.app')
@section('content')
    <div id="vue-company-view-applications-table">
        <company-view-applications-table></company-view-applications-table>
    </div>
@endsection
@section('script')
    <script src="{{ mix('js/company-view-applications-table-component.js') }}"></script>
@endsection