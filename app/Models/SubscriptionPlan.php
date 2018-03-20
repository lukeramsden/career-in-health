<?php

namespace App\Models;

use Stripe\Plan;
use Stripe\Stripe;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    private $vat = 0;

    public function getPlansFromStripe()
    {
        SubscriptionPlan::where('id', '>', 0)->delete();

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $plans = Plan::all();

        foreach ($plans->data as $plan) {
            $sp = new SubscriptionPlan();
            $sp->stripe_plan_id = $plan->id;
            $sp->nickname = $plan->nickname;
            $sp->amount = $plan->amount / 100;
            $sp->interval = $plan->interval;
            $sp->interval_count = $plan->interval_count;
            $sp->interval_count = $plan->interval_count;

            switch ($sp->stripe_plan_id) {
                case 'standard':
                    $sp->location_limit = 1;
                break;
                case 'professional':
                    $sp->location_limit = 5;
                break;
                case 'enterprise':
                    $sp->location_limit = 10;
                break;
                case 'unlimited':
                    $sp->location_limit = -1;
                break;
            }

            $sp->save();
        }
    }

    public function amount()
    {
        return number_format($this->amount, 2);
    }

    public function vat()
    {
        $this->vat = number_format(($this->amount / 100) * 20, 2);
        
        return $this->vat;
    }

    public function total()
    {
        return number_format($this->amount + $this->vat, 2);
    }

}
