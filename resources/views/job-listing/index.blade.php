@extends('layouts.app')
@section('content')
    @vue('job-listings-table')
@endsection
@section('script')
    @mix('js/components/job-listings-table.js')
@endsection