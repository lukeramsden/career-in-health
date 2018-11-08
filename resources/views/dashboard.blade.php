@extends('layouts.app')
@section('content')
    @vueWhen(Auth::user()->isEmployee(), 'employee-dashboard')
    @vueWhen(Auth::user()->isValidCompany(), 'company-dashboard')
@endsection
@section('script')
    @usertype('employee')
        @mix('js/components/employee-dashboard.js')
    @elseusertype('company')
        @mix('js/components/company-dashboard.js')
    @endusertype
@endsection