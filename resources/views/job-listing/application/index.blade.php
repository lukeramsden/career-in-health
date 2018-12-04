@extends('layouts.app', ['title' => 'View Applications'])
@section('content')
    <show-applications-for-job-listing :listing-id="{{ $listing->id }}" />
@endsection