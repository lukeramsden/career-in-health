@extends('layouts.app')
@section('content')

    <div class="container">
        <div class='create-advert-container has-top-bar'>

            <div class='row first-row'>
                <div class='col-md-12 form-section'>
                    <h1>Plans</h1>
                </div>
            </div>

            <div class='row' style='padding: 0px 0px 60px;'>
                <div class='col-md-12'><div class='row'>
                    @foreach ($plans as $plan)
                        <div class='col-md-3'>
                            <div class='plan-box'>
                                <div class='head'><div>
                                    <h2>{{ $plan->nickname }}</h2>
                                    <span>£{{ $plan->amount }} per month</span>
                                </div></div>
                                <div class='body'><div>
                                    <span>Only</span>
                                    <h2>£{{ $plan->amount }}</h2>
                                    <span>{{ $plan->location_limit == -1 ? 'unlimited' : $plan->location_limit }} {{ str_plural('location', $plan->location_limit) }}</span>

                                    @if ($current_plan->plan->stripe_plan_id == $plan->stripe_plan_id)
                                        <a href='#' disabled class='btn btn-action btn-big current-subscription'>Current</a>
                                    @else
                                        <a href='/account/payment/{{ $plan->stripe_plan_id }}' class='btn btn-action btn-big'>Purchase</a>
                                    @endif
                                </div></div>
                            </div>
                        </div>
                    @endforeach
                </div></div>
            </div>


            <div class='row' style='padding: 0px 0px 60px;'>
                <div class='col-md-12'><div class='row'>
                    <div class='col-md-6'>
                        <div class='why-choose-us'>
                            <h2>Why Choose Us?</h2>

                            <ul>
                                <li>No Credits<span>No 12 month limits, just a simple subscription.</span></li>
                                <li>Up to 120 Days Advert Length<span>Run your advert for as long as you want, as many times as you want.</span></li>
                                <li>Unlimited Adverts<span></span></li>
                                <li>Unlimited Candidates<span></span></li>
                                <li>Candidate Management System<span>Keep track of your candidates with our management system.</span></li>
                                <li>Email Notifications<span>We will keep you notified about new applicants, or suggestions.</span></li>
                                <li>Advert Analytics<span>See how many people have seen your advert, viewed and applied.</span></li>
                                <li>Advert Builder</li>
                                <li>Auto Generated Personnel File</li>
                            </ul>
                        </div>
                    </div>
                    <div class='col-md-6'>

                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection