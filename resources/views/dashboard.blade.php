@extends('layouts.app', ['title' => 'Dashboard'])
@section('content')
    @usertype('employee')
        <employee-dashboard />
    @elseusertype('company')
        <company-dashboard />
    @endusertype
@endsection