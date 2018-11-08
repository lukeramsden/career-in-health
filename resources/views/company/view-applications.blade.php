@extends('layouts.app')
@section('content')
    @vue('company-view-applications-table')
@endsection
@section('script')
    @mix('js/company-view-applications-table-component.js')
@endsection