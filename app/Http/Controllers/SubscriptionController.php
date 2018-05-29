<?php

namespace App\Http\Controllers;

use Auth;
use Stripe\Token;
use Stripe\Stripe;
use Illuminate\Http\Request;
use App\SubscriptionPlan;

class SubscriptionController extends Controller
{   
    protected $request;

    public function __construct(Request $request)
    {
        $this->request;

        $this->middleware('auth');
        $this->middleware('only.employer');
    }

    protected function rules()
    {
        if ($this->request->select_card == 1) {
            return [
                'number'    => 'required',
                'exp_month' => 'required',
                'exp_year'  => 'required',
                'ccv'       => 'required',
            ];
        }

        return [];
    }

    public function index()
    {
        return view('subscription.index')
            ->with([
                'plans' =>
                    SubscriptionPlan
                        ::orderBy('amount', 'ASC')
                        ->get(),
                'current_plan' =>
                    Auth
                        ::user()
                        ->getSubscriptionUser()
                        ->activeStripePlan()
            ]);
    }

    public function payment(SubscriptionPlan $plan)
    {
        $plan = SubscriptionPlan
            ::whereStripePlanId($plan)
            ->first();

        return view('subscription.payment')
            ->with([
                'plan' => $plan,
                'user' => Auth::user()->getSubscriptionUser()
            ]);
    }

    public function makePayment(SubscriptionPlan $plan)
    {
        $this->request->validate(self::rules());

        $user = Auth::user()->getSubscriptionUser();
        $plan = SubscriptionPlan::whereStripePlanId($plan)->first();

        Stripe::setApiKey(env('STRIPE_KEY'));

        if ($this->request->select_card == 1) {
            $token = Token::create([
                'card' => [
                    'number'    => $this->request->number,
                    'exp_month' => $this->request->exp_month,
                    'exp_year'  => $this->request->exp_year,
                    'cvc'       => $this->request->ccv,
                    'currency'  => 'GBP',
                ]
            ]);
        }

        if ($this->request->select_card == 1 && $user->stripe_id != null)
            $user->updateCard($token->id);

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
        
        return redirect(route('dashboard'));
    }

}
