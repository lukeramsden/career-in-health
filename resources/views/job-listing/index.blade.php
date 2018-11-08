@extends('layouts.app')
@section('content')
    @vue('job-listings-table')
@endsection
@section('script')
    @mix('js/job-listings-table-component.js')
@endsection