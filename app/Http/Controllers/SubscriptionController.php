<?php

namespace App\Http\Controllers;

use Auth;
use Stripe\Token;
use Stripe\Stripe;
use Illuminate\Http\Request;
use App\SubscriptionPlan;

class SubscriptionController extends Controller
{   

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function
    index()
    {
        return view('subscription.index')
            ->with([
                'plans' => SubscriptionPlan::orderBy('amount', 'ASC')
                    ->get(),
                'current_plan' => Auth::user()->getSubscriptionUser()->activeStripePlan()
            ]);
    }

    public function payment($plan)
    {
        $plan = SubscriptionPlan::where('stripe_plan_id', $plan)
            ->first();

        return view('subscription.payment')
            ->with([
                'plan' => $plan,
                'user' => Auth::user()->getSubscriptionUser()
            ]);
    }

    public function makePayment($plan, Request $request)
    {
        $rules = [];

        if ($request->select_card == 1) {
            $rules = [
                'number' => 'required',
                'exp_month' => 'required',
                'exp_year' => 'required',
                'ccv' => 'required',
            ];
        }

        $request->validate($rules);

        $user = Auth::user()->getSubscriptionUser();

        $plan = SubscriptionPlan::where('stripe_plan_id', $plan)
            ->first();

        Stripe::setApiKey(env('STRIPE_KEY'));

        if ($request->select_card == 1) {
            $token = Token::create([
                'card' => [
                    'number' => $request->number,
                    'exp_month' => $request->exp_month,
                    'exp_year' => $request->exp_year,
                    'cvc' => $request->ccv,
                    'currency' => 'GBP',
                ]
            ]);
        }

        if ($request->select_card == 1 && $user->stripe_id != null) {
            $user->updateCard($token->id);
        }

        // noProrate()
        
        if ($user->subscribed('Standard Packages')) {
            $user->subscription('Standard Packages')
                ->swap($plan->stripe_plan_id);
        } else {
            $user->newSubscription('Standard Packages', $plan->stripe_plan_id)
                ->create($token->id, [
                    'email' => $user->email
                ]);
        }
        
        return redirect('/home');
    }

}
