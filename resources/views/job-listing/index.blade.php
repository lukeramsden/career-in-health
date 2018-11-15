@extends('layouts.app', ['title' => 'View Listings'])
@section('content')
    @vue('job-listings-table')
@endsection
@section('script')
    @mix('js/components/job-listings-table.js')
@endsection