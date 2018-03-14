<?php

namespace App\Models;

use Stripe\Plan;
use Stripe\Stripe;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    
    public function getPlansFromStripe()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $plans = Plan::all();

        foreach ($plans->data as $plan) {
            $sp = new SubscriptionPlan();
            $sp->stripe_plan_id = $plan->id;
            $sp->amount = $plan->amount / 100;
            $sp->interval = $plan->interval;
            $sp->interval_count = $plan->interval_count;
            $sp->interval_count = $plan->interval_count;
            $sp->save();
        }
    }

}
