@extends('layouts.app', ['title' => 'Not Authorized'])
@section('base_content')
    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-12">
                <div class="card card-custom card-custom-dark-primary mx-auto" style="max-width: 30rem;">
                    {{--<img src="/images/cih-logo.svg" class="card-img-top p-5">--}}
                    <div class="card-body">
                        <h4 class="card-title">Access denied</h4>
                        <p>You are not authorized to perform that action.</p>
                    </div>
                    <div class="card-footer p-0">
                        <div class="btn-group btn-group-sm btn-group-full" role="group">
                            @set('referer', Request::header('referer'))
                            @empty($referer)
                                <a href="{{ route('dashboard') }}"
                                   class="btn btn-primary">Dashboard</a>
                            @else
                                <a href="{{ url()->previous() }}"
                                   class="btn btn-primary">Go Back</a>
                                <a href="{{ route('dashboard') }}"
                                   class="btn btn-dark-primary">Dashboard</a>
                            @endempty
                            @unset($referer)
                            <a href="{{ route('logout.get') }}"
                               class="btn btn-dark-primary">Log Out</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection