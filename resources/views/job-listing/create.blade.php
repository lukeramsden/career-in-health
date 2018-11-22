@extends('layouts.app', ['title' => ($edit?'Edit':'Create').' a Listing'])
@section('content')
    <create-job-listing :listing-id="{{ $edit ? $listing->id : 'null' }}" />
@endsection
