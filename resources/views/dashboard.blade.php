@extends('layouts.app', ['title' => 'Dashboard'])
@section('content')
    @vueWhen($currentUser->isEmployee(), 'employee-dashboard')
    @vueWhen($currentUser->isValidCompany(), 'company-dashboard')
@endsection
@section('script')
    @usertype('employee')
        @mix('js/components/employee-dashboard.js')
    @elseusertype('company')
        @mix('js/components/company-dashboard.js')
    @endusertype
@endsection