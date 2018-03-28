@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="has-top-bar mt-5">
            <div class="card-deck my-4 text-center">
                <div class="card mb-4 box-shadow">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">Basic</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">£5 <small class="text-muted">/ mo</small></h1>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>3 Addresses included</li>
                            <li>Email support</li>
                            <li>Help center access</li>
                        </ul>
                    </div>
                </div>
                <div class="card mb-4 box-shadow">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">Business</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">£20 <small class="text-muted">/ mo</small></h1>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>15 Addresses included</li>
                            <li>Priority email support</li>
                            <li>Help center access</li>
                        </ul>
                    </div>
                </div>
                <div class="card mb-4 box-shadow">
                    <div class="card-header">
                        <h4 class="my-0 font-weight-normal">Enterprise</h4>
                    </div>
                    <div class="card-body">
                        <h1 class="card-title pricing-card-title">£70 <small class="text-muted">/ mo</small></h1>
                        <ul class="list-unstyled mt-3 mb-4">
                            <li>Unlimited Addresses</li>
                            <li>Phone and email support</li>
                            <li>Help center access</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection