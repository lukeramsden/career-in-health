@extends('layouts.app')
@section('content')
    @vue('search')
@endsection
@section('script')
    @mix('js/search-component.js')
@endsection