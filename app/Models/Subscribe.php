<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    protected $table = 'subscriptions';
    protected $with = ['plan'];

    public function plan()
    {
        return $this->hasOne('App\Models\SubscriptionPlan', 'stripe_plan_id', 'stripe_plan');
    }
}