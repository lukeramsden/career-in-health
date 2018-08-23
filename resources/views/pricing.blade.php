@extends('layouts.frontend')
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card-deck">
                    <div class="card card-custom">
                        <div class="card-header text-center"><h2>Silver</h2></div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><b>Unlimited</b> Adverts</li>
                            <li class="list-group-item"><span class="text-muted">No Search Statistics</span></li>
                            <li class="list-group-item"><span class="text-muted">No Listing Boosts</span></li>
                            <li class="list-group-item"><b>1</b> Address</li>
                        </ul>
                        <div class="card-body"></div>
                        @guest
                            <div class="card-footer p-0">
                                <div class="btn-group btn-group-vertical btn-group-full btn-group-square"
                                     role="group">
                                    <a href="{{ route('register') }}"
                                       class="btn btn-primary">Sign Up</a>
                                </div>
                            </div>
                        @endguest
                    </div>
                    
                    <div class="card card-custom card-custom-action">
                        <div class="card-header text-center"><h2>Gold</h2></div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><b class="text-action">Unlimited</b> Adverts</li>
                            <li class="list-group-item"><b class="text-action">Detailed</b> Search Statistics</li>
                            <li class="list-group-item"><b class="text-action">1</b> Listing Boost</li>
                            <li class="list-group-item"><b class="text-action">3</b> Addresses</li>
                        </ul>
                        <div class="card-body"></div>
                        @guest
                            <div class="card-footer p-0">
                                <div class="btn-group btn-group-vertical btn-group-full btn-group-square"
                                     role="group">
                                    <a href="{{ route('register') }}"
                                       class="btn btn-action">Sign Up</a>
                                </div>
                            </div>
                        @endguest
                    </div>
                    
                    <div class="card card-custom">
                        <div class="card-header text-center"><h2>Platinum</h2></div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><b>Unlimited</b> Adverts</li>
                            <li class="list-group-item"><b>Detailed</b> Search Statistics</li>
                            <li class="list-group-item"><b>3</b> Listing Boosts</li>
                            <li class="list-group-item"><b>5</b> Addresses</li>
                        </ul>
                        <div class="card-body"></div>
                        @guest
                            <div class="card-footer p-0">
                                <div class="btn-group btn-group-vertical btn-group-full btn-group-square"
                                     role="group">
                                    <a href="{{ route('register') }}"
                                       class="btn btn-primary">Sign Up</a>
                                </div>
                            </div>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
