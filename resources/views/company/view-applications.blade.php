@extends('layouts.app')
@section('content')
    @vue('company-view-applications-table')
@endsection
@section('script')
    @mix('js/components/company-view-applications-table.js')
@endsection