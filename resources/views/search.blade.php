@extends('layouts.app', ['title' => 'Search'])
@section('content')
    @vue('search')
@endsection
@section('script')
    @mix('js/components/search.js')
@endsection